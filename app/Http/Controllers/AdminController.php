<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camp;
use App\Models\Batch;
use App\Models\Role;

class AdminController extends BackendController{
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
        $camps = Camp::all();
        return view('backend.camp.list', compact('camps'));
    }

    public function showAddCamp(){
        return view('backend.camp.campForm', ["action" => "建立", "actionURL" => route("addCamp")]);
    }

    public function showModifyCamp($camp_id){
        $camp = Camp::find($camp_id);
        return view('backend.camp.campForm', ["action" => "修改", "actionURL" => null, "camp" => $camp]);
    }

    public function addCamp(Request $request){
        $formData = $request->toArray();
        $camp = Camp::create($formData);
        \Session::flash('message', $camp->name . " 新增成功。");
        return redirect()->route("campManagement");
    }

    public function modifyCamp(Request $request, $camp_id){
        $formData = $request->toArray();
        $camp = Camp::find($camp_id);
        $camp->update($formData);
        $campName = $formData["abbreviation"];
        \Session::flash('message', $campName . " 修改成功。");
        return redirect()->route("campManagement");
    }

    public function showBatch($camp_id){
        $camp = Camp::find($camp_id);
        $batches = $camp->batchs;
        return view('backend.camp.batchList', compact('camp', 'batches'));
    }

    public function showAddBatch($camp_id){
        $camp = Camp::find($camp_id);
        return view('backend.camp.addBatch', ["camp" => $camp]);
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

    public function showModifyBatch($camp_id, $batch_id){
        $camp = Camp::find($camp_id);
        $batch = Batch::find($batch_id);
        return view('backend.camp.modifyBatch', compact("camp", "batch"));
    }

    public function modifyBatch(Request $request, $camp_id, $batch_id){
        $formData = $request->toArray();
        $batch = Batch::find($batch_id);
        $batch->update($formData);
        $campName = Camp::find($camp_id)->abbreviation;
        \Session::flash('message', $campName . " " . $batch->name . " 修改成功。");
        return redirect()->route("showBatch", $camp_id);
    }
}
