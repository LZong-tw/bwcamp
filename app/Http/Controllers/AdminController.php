<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Batch;
use App\Models\BatchVbatchXref;
use App\Models\Camp;
use App\Models\CampVcampXref;
use App\Models\CampOrg;
use App\Models\DynamicStat;
use App\Models\Permission;
use App\Models\Region;
use App\Models\Role;
use App\Models\Vcamp;
use Carbon\Carbon;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use App\Services\ApplicantService;
use App\Services\BackendService;
use App\Services\CampDataService;
use App\Services\GSheetService;
use App\Services\CampOrgService;

class AdminController extends BackendController {

    protected $campOrgService;

    public function __construct(
        ApplicantService $applicantService,
        BackendService $backendService,
        CampDataService $campDataService,
        CampOrgService $campOrgService,
        GSheetService $gsheetService,
        Request $request
    ) {
        parent::__construct(
            $campDataService,
            $applicantService,
            $backendService,
            $gsheetService,
            $request
        );
        $this->campOrgService = $campOrgService;
    }

    public function userlist(){
        return view('backend.user.list', ['users' => \App\User::all()]);
    }

    public function userAddRole($user_id){
        $user = \App\User::find($user_id);
        return view('backend.user.userAddRole',
        ['user' => $user,
        'roles_available' => \App\Models\Role::whereNotIn('id', $user->role_relations->pluck('role_id'))->get()]);
    }

    public function removeRole(Request $request){
        $result = \App\Models\RoleUser::where('user_id', $request->user_id)->where('role_id', $request->role_id)->delete();
        if($result){
            \Session::flash('message', "權限刪除成功。");
            return back();
        }
        else{
            \Session::flash('error', "權限刪除失敗。");
            return back();
        }
    }

    public function addRole(Request $request){
        $result = new \App\Models\RoleUser;
        $result->user_id = $request->user_id;
        $result->role_id = $request->role_id;
        $result->save();
        if($result){
            \Session::flash('message', "權限新增成功。");
            return back();
        }
        else{
            \Session::flash('error', "權限新增失敗。");
            return back();
        }
    }

    public function rolelist(){
        return view('backend.user.rolelist', ['roles' => \App\Models\Role::all()]);
    }

    public function listRemoveRole(Request $request){
        $result = \App\Models\Role::find($request->role_id)->delete();
        if($result){
            \Session::flash('message', "角色刪除成功。");
            return back();
        }
        else{
            \Session::flash('error', "角色刪除失敗。");
            return back();
        }
    }

    public function listAddRole(Request $request){
        if ($request->isMethod('GET')) {
            return view('backend.user.roleForm', ['camps' => Camp::all()]);
        }
        if ($request->isMethod('POST')) {
            $result = new \App\Models\Role;
            $result->name = $request->name;
            $result->level = $request->level;
            $result->camp_id = $request->camp_id;
            $result->region = $request->region;
            $result->save();
            if($result){
                \Session::flash('message', "角色新增成功。");
                return redirect()->route('rolelist');
            }
            else{
                \Session::flash('error', "角色新增失敗。");
                return redirect()->route('rolelist');
            }
        }
    }

    public function editRole($role_id, Request $request){
        if ($request->isMethod('GET')) {
            $role = Role::find($role_id);
            return view('backend.user.roleForm', ['camps' => Camp::all(), 'role' => $role]);
        }
        if ($request->isMethod('POST')) {
            $role = Role::find($role_id);
            $role->name = $request->name;
            $role->level = $request->level;
            $role->camp_id = $request->camp_id;
            $role->region = $request->region;
            $role->save();
            if($role){
                \Session::flash('message', "角色修改成功。");
                return redirect()->route('rolelist');
            }
            else{
                \Session::flash('error', "角色修改失敗。");
                return redirect()->route('rolelist');
            }
        }
    }

    public function showJobs(){
        $jobs = \DB::table('jobs')->get();
        $failedJobs = \DB::table('failed_jobs')->get();
        $jobs = json_decode($jobs, true);
        $failedJobs = json_decode($failedJobs, true);
        return view('backend.jobs', compact('jobs', 'failedJobs'));
    }

    public function failedJobsClear(){
        return \DB::table('failed_jobs')->truncate();
    }

    public function campManagement(){
        $camps = Camp::orderBy('id', 'desc')->get();
        return view('backend.camp.list', compact('camps'));
    }

    public function addCamp(Request $request){
        $formData = $request->toArray();
        $camp = Camp::create($formData);
        $campName = $formData["abbreviation"];
        foreach($request->regions ?? [] as $region){
            $camp->regions()->attach($region);
        }
        \Session::flash('message', $campName . " 新增成功。");
        return redirect()->route("campManagement");
    }

    public function showAddCamp(){
        return view('backend.camp.campForm', ["action" => "建立", "actionURL" => route("addCamp")]);
    }

    public function modifyCamp(Request $request, $camp_id){
        $formData = $request->toArray();
        $camp = Camp::find($camp_id);
        $camp->update($formData);
        $campName = $formData["abbreviation"];
        $camp->regions()->detach();
        foreach($request->regions ?? [] as $region){
            $camp->regions()->attach($region);
        }
        if ($request->vcamp_id) {
            CampVcampXref::updateOrCreate(["camp_id" => $camp_id], ["vcamp_id" => $request->vcamp_id]);
        }
        \Session::flash('message', $campName . " 修改成功。");
        return redirect()->route("campManagement");
    }

    public function showModifyCamp($camp_id){
        $camp = Camp::find($camp_id);
        $camp_orgs = $camp->organizations;
        $vcamps = Camp::where('registration_end', '>', now()->year . "-01-01")->where('table', 'like', '%vcamp%')->get();
        return view('backend.camp.campForm', ["action" => "修改", "actionURL" => route("modifyCamp", $camp->id), "camp" => $camp, "vcamps" => $vcamps]);
    }

    public function addBatches(Request $request, $camp_id){
        $formData = $request->toArray();
        $newSet = array();
        $batches = count($formData['name']);
        for($i = 0; $i < $batches; $i++){
            foreach($formData as $key => $field){
                if($key == 'is_late_registration_end' && $field[$i] == ''){
                    continue;
                }
                $newSet[$i][$key] = $field[$i];
            }
            $newSet[$i]['camp_id'] = $camp_id;
            Batch::create($newSet[$i]);
        }
        \Session::flash('message', " 梯次新增成功。");
        return redirect()->route("showBatch", $camp_id);
    }

    public function copyBatch(Request $request, $camp_id){
        $formData = $request->toArray();
        //$newSet = array();
        $batch = Batch::find($formData['batch_id']);
        $newBatch = $batch->replicate();
        $newBatch->created_at = Carbon::now();
        $newBatch->save();
        \Session::flash('message', " 梯次複製成功。");
        return redirect()->route("showBatch", $camp_id);
    }

    public function showAddBatch($camp_id){
        $camp = Camp::find($camp_id);
        return view('backend.camp.addBatch', ["camp" => $camp]);
    }

    public function showBatch($camp_id){
        $camp = Camp::find($camp_id);
        $batches = $camp->batchs;
        $num_applicants = array();
        foreach($batches as $batch) {
            $num_applicants[$batch->id] = \DB::table('applicants')->where('batch_id',$batch->id)->count();
        }
        return view('backend.camp.batchList', compact('camp', 'batches','num_applicants'));
    }

    public function modifyBatch(Request $request, $camp_id, $batch_id){
        $formData = $request->toArray();
        $batch = Batch::find($batch_id);
        $batch->update($formData);
        $campName = Camp::find($camp_id)->abbreviation;
        if ($request->vbatch_id) {
            BatchVbatchXref::updateOrCreate(["batch_id" => $batch_id], ["vbatch_id" => $request->vbatch_id]);
        }
        \Session::flash('message', $campName . " " . $batch->name . " 修改成功。");
        return redirect()->route("showBatch", $camp_id);
    }

    public function showModifyBatch($camp_id, $batch_id){
        $camp = Camp::find($camp_id);
        $batch = Batch::find($batch_id);
        $vbatches = null;
        if (!$batch->is_vbatch()) {
            $vbatches = $camp->vcamp?->batchs ?? null;
        }
        return view('backend.camp.modifyBatch', compact("camp", "batch", "vbatches"));
    }

    public function removeBatch(Request $request){
        $result = \App\Models\Batch::find($request->batch_id)->delete();
        if($result){
            \Session::flash('message', "梯次刪除成功。");
            return back();
        }
        else{
            \Session::flash('error', "梯次刪除失敗。");
            return back();
        }
    }

    public function addOrgs(Request $request, $camp_id){
        $formData = $request->toArray();
        $camp = Camp::find($camp_id);
        $orgs = $camp->organizations;   //existing orgs
        $newSet = array();
        $is_exist = false;
        $existed_org = null;
        //dd($formData);
        $positions = count($formData['position']);
        //i: position index
        //j: valid position index
        foreach($formData as $key => $field) {
            if ($key == 'position') {
                $j = 0;
                for($i = 0; $i < $positions; $i++) {
                    while(!isset($field[$j])) {
                        $j = $j+1;  //skip non-exist idx
                    }
                    $pos_tg = $field[$j];
                    $newSet[$j]['camp_id'] = $camp_id;
                    $newSet[$j]['batch_id'] = $formData['batch_id'][$j];
                    $newSet[$j]['region_id'] = $formData['region_id'][$j];
                    $newSet[$j]['section'] = $formData['section'][$j];
                    $newSet[$j]['position'] = $field[$j];
                    $newSet[$j]['prev_id'] = $formData['prev_id'][$j];
                    $newSet[$j]['is_node'] = 0;
                    $newSet[$j]['order'] = $formData['order'][$j] ?? 0;
                    $is_exist = false;  //init
                    foreach($orgs as $org) {
                        if ($org->position == $pos_tg &&
                            $org->prev_id == $formData['prev_id'][$j] &&
                            $org->batch_id == $formData['batch_id'][$j] &&
                            $org->region_id == $formData['region_id'][$j]) {
                            $is_exist = true;   //once find match, break
                            $existed_org = $org;
                            break;
                        }
                    }
                    if ($is_exist == false) {
                        CampOrg::create($newSet[$j]);
                        //set parent is_node = 1
                        if ($newSet[$j]['prev_id']>0) {
                            $org_parent = CampOrg::find($newSet[$j]['prev_id']);
                            $org_parent->is_node = 1;
                            $org_parent->save();
                        }
                        if($org_parent ?? false){
                            \Session::flash('message', "職務上層修改成功。");
                        } else {
                            \Session::flash('error', "職務上層修改失敗。");
                        }
                    }
                    $j = $j+1;
                }
            }
        }
        if (!$is_exist) {
            \Session::flash('message', " 組織新增成功。");
            return redirect()->route("showOrgs", $camp_id);
        }
        else {
            return redirect()->route("showOrgs", $camp_id)->withErrors(['職務已存在，ID：' . $existed_org->id]);
        }
    }

    public function showAddOrgs($camp_id, $org_id){
        $camp = Camp::find($camp_id);
        //$orgs = $this->backendService->getCampOrganizations($this->campFullData);
        //$orgs = $orgs->sortByDesc('section');
        $orgs = $camp->organizations->sortByDesc('section');
        $batches = $camp->batchs;
        $regions = $camp->regions;
        $org_tg = null;
        $sec_tg = null;
        $batch_tg = null;
        $region_tg = null;
        if ($org_id == 0) { //無上層
            //create 大會
            if ($orgs->isEmpty()) {
                $org_tg = new CampOrg();
                $org_tg->camp_id = $camp_id;
                //$org_tg->batch_id = null;   //all batches
                $org_tg->section = '大會';
                $org_tg->position = '大會';
                $org_tg->is_node = '0';
                $org_tg->prev_id = '0';
                $org_tg->order = '0';
                $org_tg->save();
                //$camp = Camp::find($camp_id);
                //$orgs = $camp->organizations->sortByDesc('section');
                $orgs->put(0,$org_tg);
            }
            $batch_tg = new Batch();
            $batch_tg->id = 0;
            $batch_tg->name = '不限';
            $region_tg = new Region();
            $region_tg->id = 0;
            $region_tg->name = '不限';
        } else {  //有上層
            $org_tg = CampOrg::find($org_id);

            $sec_tg = $org_tg->section; //找到要新增的sec
            if ($org_tg->batch_id==0 || $org_tg->batch_id==null) {
                $batch_tg = new Batch();
                $batch_tg->id = 0;
                $batch_tg->name = '不限';
            } else {
                $batch_tg = Batch::find($org_tg->batch_id);
            }
            if ($org_tg->region_id==0 || $org_tg->region_id==null) {
                $region_tg = new Region();
                $region_tg->id = 0;
                $region_tg->name = '不限';
            } else {
                $region_tg = Region::find($org_tg->region_id);
            }
        }
        return view('backend.camp.addOrgs', compact("orgs", "batches", "regions", "org_tg", "sec_tg", "batch_tg", "region_tg"))->with('camp', $this->campFullData);
    }

    public function copyOrgs(Request $request, $camp_id){
        //複製整個營隊組織
        $formData = $request->toArray();
        $campDst_id = $camp_id;
        $campDst = Camp::find($campDst_id);
        $batchesDst = $campDst->batchs;

        $campSrc_id = $formData['camp2copy'];
        $campSrc = Camp::find($campSrc_id);
        $batchesSrc = $campSrc->batchs;
        $orgsSrc = $campSrc->organizations;
        //dd($orgsSrc);
        $doCopyPermissions = $formData['do_copy_permissions'];

        //match batches
        //check if number of batches is the same
        if ($batchesDst->count() != $batchesSrc->count()) {
            \Session::flash('error', "梯次數量不同，無法複製");
            return back();
        }
        //match by batches' names
        $batchIdMatchList = array("0" => 0);
        foreach($batchesSrc as $batchSrc) {
            $batchDst = $batchesDst->where('name',$batchSrc->name)->first();
            //batch
            $batchIdMatchList[$batchSrc->id] = $batchDst?->id ?? null;
            //vbatch
            if (!is_null($batchSrc->vbatch)) {
                $batchIdMatchList[$batchSrc->vbatch->id] = $batchDst?->vbatch?->id ?? null;
            }
        }
        //dd($batchIdMatchList);

        //update section before copying
        $this->campOrgService->updateSection($orgsSrc);
        //dd($orgsSrc);

        foreach ($orgsSrc as $org) {
            $orgDst = $org->replicate();
            $orgDst->camp_id = $campDst_id;
            if ( !is_null($orgDst->batch_id) ) {
                $orgDst->batch_id = $batchIdMatchList[$org->batch_id];
            }
            $orgDst->created_at = Carbon::now();
            $orgDst->save();
            if($doCopyPermissions) {
                $this->campOrgService->copyPermissions($campDst, $campSrc, $orgDst, $org, $batchIdMatchList);
            }
        }
        $orgsDst = CampOrg::where('camp_id', $campDst_id)->get();
        $this->campOrgService->updatePrevId($orgsDst);

        \Session::flash('message', "組織複製成功。");
        return redirect()->route("showOrgs", $camp_id);
    }

    public function duplicateOrg($camp_id, $org_id){
        //複製單一組織＋權限
        //$formData = $request->toArray();
        $orgSrc = CampOrg::find($org_id);   //找到要被複製的org
        $orgDst = $orgSrc->replicate();
        $orgDst->position = $orgSrc->position . "copy";
        $orgDst->is_node = 0;               //新增是leaf
        $orgDst->created_at = Carbon::now();
        $orgDst->save();
        $this->campOrgService->duplicatePermissions($orgDst, $orgSrc);
        \Session::flash('message', $orgSrc->section . $orgSrc->position ." 複製成功。");
        return redirect()->route("showOrgs", $camp_id);
    }

    public function modifyOrg(Request $request, $camp_id, $org_id){
        $formData = $request->toArray();
        $camp = Camp::find($camp_id);
        $org_tg = CampOrg::find($org_id);   //找到要被修改的org
        $pos_tg = $org_tg->position;        //修改前職務名稱
        //$sec_tg = $org_tg->section;     //修改前大組名稱
        //$is_root = ($formData['position'] == 'root')? true:false;    //是否修改大組名稱

        /*if ($is_root) {
            $orgs = $camp->organizations;   //找到所有orgs
            foreach ($orgs as $org) {
                if ($org->section == $sec_tg) { //如果大組名稱=要被修改的大組名稱
                    $formData['position'] = $org->position;
                    $org->update($formData);
                }
            }
        } else {*/
            $totalPermissions = $this->backendService->permissionTableProcessor($request, $org_tg->id, $camp);
            if (!is_array($totalPermissions)) {
                return $totalPermissions;
            }
            $org_tg->update($formData);     //修改職務only
            $org_tg->syncPermissions($totalPermissions);
            $org_tg->save();
            //如果修改position名稱, 底下children都需更新
            if ($org_tg->position != $pos_tg) {
                $orgs = $camp->organizations;
                $this->campOrgService->updateSectionChildren($orgs, $org_tg);
                foreach ($orgs as $org) {
                    $org->save();
                }
            }
        //}
        \Session::flash('message', $camp->abbreviation . " 組織職務：" . $org_tg->batch?->name . $org_tg->section . "-" . $org_tg->position . " 修改成功。");
        return redirect()->route("showOrgs", $camp_id);
    }

    public function showModifyOrg($camp_id, $org_id){
        $camp = Camp::find($camp_id);
        $org = CampOrg::find($org_id);
        $availableResources = \App\Services\BackendService::getAvailableModels();
        view()->share('availableResources', $availableResources);
        return view('backend.camp.modifyOrg', compact("camp", "org"))
                    ->with('complete_permissions', $org->permissions);
    }

    public function showOrgs($camp_id){
        $camp = Camp::find($camp_id);
        if (isset($camp->vcamp)) {
            $vcamp = Camp::find($camp->vcamp->id);
            $batches = $camp->batchs->merge($vcamp->batchs);
        } else {
            $vcamp = null;
            $batches = $camp->batchs;
        }
        $regions = $camp->regions;
        $orgs = $camp->organizations->sortBy('order');
        //$orgs = $camp->organizations->sortBy('batch_id');

        $num_users = array();
        foreach($orgs as $org) {
            if($org->position == 'root') {
                //count number of orgs with the same camp_id and section
                //minus one to exclude 'root' itself
                $num_users[$org->id] = (\DB::table('camp_org')
                    ->where('camp_id',$org->camp_id)
                    ->where('section',$org->section)
                    ->count())
                    -1;
            } else {
                $num_users[$org->id] = \DB::table('org_user')->where('org_id',$org->id)->count();
            }
        }
        //permission??
        $permission = auth()->user()->getPermission('all');
        $camp_list = Camp::where('table', $camp->table)->get();
        //dd($camp_list);
        $models = $this->backendService->getAvailableModels();
        return view('backend.camp.orgList', compact('camp', 'batches', 'orgs', 'camp_list', 'models','num_users'));
    }

    public function removeOrg(Request $request){
        //=====>目前只允許is_node=0可被刪
        //刪除大組：找到（同camp_id）&（同section）的全刪掉
        //if ($request->org_position == 'root') {
        //    $result = \App\Models\CampOrg::where('camp_id', $request->camp_id)->where('section', $request->org_section)->delete();
        //} else {    //刪除職務：刪除此org_id就好
            //$result = \App\Models\CampOrg::find($request->org_id)?->delete();
            $org_tg = CampOrg::find($request->org_id);
            $parent_id = $org_tg->prev_id;  //先記錄下來
            $result = $org_tg->delete();

            if ($result) {
                //檢查parent是否還有children
                $org_siblings = CampOrg::where('prev_id',$parent_id);
                if ($org_siblings->count()==0) {
                    $org_parent = CampOrg::find($parent_id);
                    $org_parent->is_node = 0;
                    $result = $org_parent->save();
                }
            }
        //}
        if($result){
            \Session::flash('message', "職務刪除成功。");
            return back();
        }
        else{
            \Session::flash('error', "職務刪除失敗。");
            return back();
        }
    }

    public function addDSLink(Request $request, $camp_id){
        $formData = $request->toArray();
        $is_this_camp = false;
        if ($formData['urltable_type'] == 'Camp') {
            $is_this_camp = ($formData['urltable_id'] == $camp_id)? true:false;
        }
        else if ($formData['urltable_type'] == 'Batch') {
            $batch = Batch::find($formData['urltable_id']);
            if ($batch) {
                $is_this_camp = ($batch->camp->id == $camp_id)? true:false;
            } else {
                \Session::flash('error', " 找不到梯次，DSLink新增失敗。");
                return redirect()->route("showAddDSLink", $camp_id);
            }
        }
        else if ($formData['urltable_type'] == 'CampOrg') {
            $org = CampOrg::find($formData['urltable_id']);
            if ($org) {
                $is_this_camp = ($org->camp_id == $camp_id)? true:false;
            } else {
                \Session::flash('error', " 找不到職務，DSLink新增失敗。");
                return redirect()->route("showAddDSLink", $camp_id);
            }
        }
        else {  //$formData['urltable_type'] == 'Applicant'
            $applicant = Applicant::find($formData['urltable_id']);
            if ($applicant) {
                $is_this_camp = ($applicant->camp->id == $camp_id)? true:false;
            } else {
                \Session::flash('error', " 找不到報名者，DSLink新增失敗。");
                return redirect()->route("showAddDSLink", $camp_id);
            }
        }
        if ($is_this_camp) {
            $formData['urltable_type'] = 'App\\Models\\' . $formData['urltable_type'];
            DynamicStat::create($formData);
            \Session::flash('message', " DSLink新增成功。");
            return redirect()->route("showAddDSLink", $camp_id);
        } else {
            \Session::flash('error', " 非屬此營隊，DSLink新增失敗。");
            return redirect()->route("showAddDSLink", $camp_id);
        }
    }
    public function queryDSLink(Request $request, $camp_id){
        $formData = $request->toArray();
        $is_this_camp = false;
        if($formData['urltable_type'] == 'Camp') {
            $is_this_camp = ($formData['urltable_id'] == $camp_id)? true:false;
        }
        else if ($formData['urltable_type'] == 'Batch') {
            $batch = Batch::find($formData['urltable_id']);
            $is_this_camp = ($batch->camp->id == $camp_id)? true:false;
        }
        else if ($formData['urltable_type'] == 'CampOrg') {
            $org = CampOrg::find($formData['urltable_id']);
            $is_this_camp = ($org->camp_id == $camp_id)? true:false;
        }
        else {  //$formData['urltable_type'] == 'Applicant'
            $applicant = Applicant::find($formData['urltable_id']);
            $is_this_camp = ($applicant->camp->id == $camp_id)? true:false;
        }
        if ($is_this_camp) {
            $urltable_type = 'App\\Models\\'.$request->urltable_type;
            $urltable_id = $request->urltable_id;
            $ds = DynamicStat::select('dynamic_stats.*')
            ->where('urltable_id', $urltable_id)
            ->where('urltable_type', $urltable_type)
            ->first();
            if($ds==null) {
                \Session::flash('message', " 查無資料。");
                return redirect()->route("showAddDSLink", $camp_id);
            } else {
                $is_show = 1;
                //replace App\Models\XXX with XXX
                $ds->urltable_type = $request->urltable_type;
                return view('backend.other.addDSLink', compact("camp_id", "ds", "is_show"));
            }
        } else {
            \Session::flash('message', " 非屬此營隊，DSLink查詢失敗。");
            return redirect()->route("showAddDSLink", $camp_id);
        }
    }
    public function showAddDSLink($camp_id){
        return view('backend.other.addDSLink', compact("camp_id"));
    }
    public function modifyDSLink(Request $request, $camp_id){
        $formData = $request->toArray();
        $is_this_camp = false;
        if($formData['urltable_type'] == 'Camp') {
            $is_this_camp = ($formData['urltable_id'] == $camp_id)? true:false;
        }
        else if ($formData['urltable_type'] == 'Batch') {
            $batch = Batch::find($formData['urltable_id']);
            $is_this_camp = ($batch->camp->id == $camp_id)? true:false;
        }
        else if ($formData['urltable_type'] == 'CampOrg') {
            $org = CampOrg::find($formData['urltable_id']);
            $is_this_camp = ($org->camp_id == $camp_id)? true:false;
        }
        else {  //$formData['urltable_type'] == 'Applicant'
            $applicant = Applicant::find($formData['urltable_id']);
            $is_this_camp = ($applicant->camp->id == $camp_id)? true:false;
        }
        if ($is_this_camp) {
            $formData['urltable_type'] = 'App\\Models\\' . $formData['urltable_type'];
            $ds = DynamicStat::find($formData['ds_id']);
            $ds->update($formData);
            \Session::flash('message', " DSLink修改成功。");
            return redirect()->route("showAddDSLink", $camp_id);
        } else {
            \Session::flash('message', " 非屬此營隊，DSLink修改失敗。");
            return redirect()->route("showAddDSLink", $camp_id);
        }
    }
}
