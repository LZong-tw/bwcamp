<?php

namespace App\Http\Middleware;

use App\Models\OrgUser;
use Closure;

class Permitted
{
    /**
     * 檢查使用者是否具有存取目前營隊的權限，與 \App\Services\CampDataService::getAvailableCamps() 功能類似
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        /**
         * todo: Request resolver: 檢查目前使用者是否可以取得目標資源
         */
        $userPermission = auth()->user()->getPermission('all');
        foreach($userPermission as $p){
            if($p->level == 1) {
                return $next($request);
            }
            else if($p->level >= 2 && $p->level <= 6) {
                if(\Str::contains($p->camp_id, $request->route()->parameter('camp_id'))){
                    return $next($request);
                }
                else if(\Str::contains($p->camp_id, $request->camp_id)){
                    return $next($request);
                }
            }
        }
        $newPermissions = OrgUser::with('camp')->where('user_id', \Auth::user()->id)->get()->pluck('camp.id')->toArray();
        $newPermissions = array_filter($newPermissions, fn ($value) => !is_null($value));
        if(in_array($request->camp_id, $newPermissions)){
//            $camp = \App\Models\Camp::find($request->camp_id);
//            $currentUser = \App\Models\User::find(auth()->user()->id);
//            $currentUser->permissionParser($camp);
//            \View::share('currentUser', $currentUser);
//            dd(1);
            return $next($request);
        }
        if ($request->is('checkin*')) {
            $camp = \App\Models\Camp::find($request->camp_id);
            return response()->view('errors.401',  ['message' => '目前報到營隊為' . $camp->fullName . '，非您可存取'], 401);
        }
        abort(401, 'Unauthorized.');
    }
}
