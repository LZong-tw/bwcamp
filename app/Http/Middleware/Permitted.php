<?php

namespace App\Http\Middleware;

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
        if(auth()->user()->getPermission()->role_id == 1) {
            return $next($request);
        }
        else{
            abort(401, 'Unauthorized.');
        }
    }
}
