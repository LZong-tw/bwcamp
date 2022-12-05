<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camp;
use App\Models\CampOrg;
use App\Models\Batch;
use App\Models\Role;
use Carbon\Carbon;

class AdminController extends BackendController {
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
        \Session::flash('message', $campName . " 修改成功。");
        return redirect()->route("campManagement");
    }

    public function showModifyCamp($camp_id){
        $camp = Camp::find($camp_id);
        $camp_orgs = $camp->organizations;
        return view('backend.camp.campForm', ["action" => "修改", "actionURL" => route("modifyCamp", $camp->id), "camp" => $camp]);
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
        return view('backend.camp.batchList', compact('camp', 'batches'));
    }

    public function modifyBatch(Request $request, $camp_id, $batch_id){
        $formData = $request->toArray();
        $batch = Batch::find($batch_id);
        $batch->update($formData);
        $campName = Camp::find($camp_id)->abbreviation;
        \Session::flash('message', $campName . " " . $batch->name . " 修改成功。");
        return redirect()->route("showBatch", $camp_id);
    }

    public function showModifyBatch($camp_id, $batch_id){
        $camp = Camp::find($camp_id);
        $batch = Batch::find($batch_id);
        return view('backend.camp.modifyBatch', compact("camp", "batch"));
    }

    public function addOrgs(Request $request, $camp_id){
        //dd($request);
        $formData = $request->toArray();
        $camp = Camp::find($camp_id);
        $orgs = $camp->organizations;   //existing orgs
        $newSet = array();
        $is_exist = false;

        $sections = count($formData['section']);
        foreach($formData as $key => $field) {
            if ($key == 'section') {
                $j = 0;
                for($i = 0; $i < $sections; $i++) {
                    while(!isset($field[$j])) {
                        $j = $j+1;  //skip non-exist idx
                    }
                    $sec_tg = $field[$j];
                    $newSet[$j][0]['camp_id'] = $camp_id; 
                    $newSet[$j][0]['section'] = $sec_tg;
                    $newSet[$j][0]['position'] = 'root';
                    $is_exist = false;  //init
                    foreach($orgs as $org) {
                        if ($org->section == $sec_tg) {
                            $is_exist = true;   //once find match, break
                            break;
                        }
                    }
                    if ($is_exist == false) {
                        CampOrg::create($newSet[$j][0]);
                    }
                    $j = $j+1;
                }
            }
        }
        foreach($formData as $key => $field) {
            if ($key == 'position') {
                $j = 0;
                for($i = 0; $i < $sections; $i++) {
                    while(!isset($field[$j])) {
                        $j = $j+1;  //skip non-exist idx
                    }
                    $positions = count($field[$j]);
                    for($k = 0; $k < $positions; $k++) {    
                        $sec_tg = $newSet[$j][0]['section'];              
                        $newSet[$j][$k+1]['camp_id'] = $camp_id; 
                        $newSet[$j][$k+1]['section'] = $sec_tg;
                        $newSet[$j][$k+1]['position'] = $field[$j][$k];
                        $is_exist = false;
                        foreach($orgs as $org) {
                            if (($org->section == $sec_tg) && ($org->position == $field[$j][$k])) {
                                $is_exist = true;   //once find match, break
                                break;
                            }
                        }
                        if ($is_exist == false) {
                            CampOrg::create($newSet[$j][$k+1]);
                        }
                    }
                    $j = $j+1;
                }
            }
        }
        \Session::flash('message', " 組織新增成功。");
        return redirect()->route("showOrgs", $camp_id);
    }
    
    public function showAddOrgs($camp_id, $org_id){
        $camp = Camp::find($camp_id);
        $orgs = $camp->organizations;
        $orgs = $orgs->sortByDesc('section');
        if ($org_id == 0) {
            $sec_tg = "null";
        }
        else {
            $org_tg = CampOrg::find($org_id);   
            $sec_tg = $org_tg->section; //找到要新增的sec
        }
        return view('backend.camp.addOrgs', compact("camp", "orgs", "sec_tg"));
    }

    public function copyOrgs(Request $request, $camp_id){
        $formData = $request->toArray();
        $newSet = array();
        $camp2copy_id = $formData['camp2copy'];
        $orgs2copy = CampOrg::where('camp_id', $camp2copy_id)->get();
        //dd($orgs2copy);
        foreach ($orgs2copy as $org) {
            $newOrg = $org->replicate();
            $newOrg->camp_id = $camp_id;
            $newOrg->created_at = Carbon::now();
            $newOrg->save();
        }
        \Session::flash('message', "組織複製成功。");
        return redirect()->route("showOrgs", $camp_id);
    }

    public function modifyOrg(Request $request, $camp_id, $org_id){
        $formData = $request->toArray();
        $camp = Camp::find($camp_id);
        $org_tg = CampOrg::find($org_id);   //找到要被修改的org
        $sec_tg = $org_tg->section;     //修改前大組名稱
        $is_root = ($formData['position'] == 'root')? true:false;    //是否修改大組名稱

        if ($is_root) {
            $orgs = $camp->organizations;   //找到所有orgs
            foreach ($orgs as $org) {
                if ($org->section == $sec_tg) { //如果大組名稱=要被修改的大組名稱
                    $formData['position'] = $org->position;
                    $org->update($formData);
                }
            }
        } else {
            $org_tg->update($formData);     //修改職務only
        }
        \Session::flash('message', $camp->abbreviation . " 組織修改成功。");
        return redirect()->route("showOrgs", $camp_id);
    }

    public function showModifyOrg($camp_id, $org_id){
        $camp = Camp::find($camp_id);
        $org = CampOrg::find($org_id);
        return view('backend.camp.modifyOrg', compact("camp", "org"));
    }

    public function showOrgs($camp_id){
        $camp = Camp::find($camp_id);
        $orgs = $camp->organizations;
        $orgs = $orgs->sortByDesc('section');
        //permission??
        $permission = auth()->user()->getPermission('all');
        $camp_list = Camp::where('table', $camp->table)->get();
        //dd($camp_list);
        return view('backend.camp.orgList', compact('camp', 'orgs', 'camp_list'));
    }

    public function removeOrg(Request $request){
        //刪除大組：找到（同camp_id）&（同section）的全刪掉
        if ($request->org_position == 'root') {
            $result = \App\Models\CampOrg::where('camp_id', $request->camp_id)->where('section', $request->org_section)->delete();
        } else {    //刪除職務：刪除此org_id就好
            $result = \App\Models\CampOrg::find($request->org_id)->delete();
        }
        if($result){
            \Session::flash('message', "職務刪除成功。");
            return back();
        }
        else{
            \Session::flash('error', "職務刪除失敗。");
            return back();
        }
    }

}
