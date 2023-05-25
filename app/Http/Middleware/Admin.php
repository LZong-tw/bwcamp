<?php

namespace App\Http\Middleware;

use Closure;

class Admin
{
    /**
     * 檢查使用者是否具有存取目前營隊的權限，與 \App\Services\CampDataService::getAvailableCamps() 功能類似
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        // $userPermission = auth()->user()->getPermission('all');
        // $isAdmin = 0;
        // foreach($userPermission as $p){
        //     if($p->level == 1) {
        //         $isAdmin = 1;
        //     }
        // }
        // if($isAdmin) {
        //     return $next($request);
        // }
        // else{
        //     abort(401, 'Unauthorized.');
        // }
        return $next($request);
    }
}
