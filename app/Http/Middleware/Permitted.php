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
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            return abort(404);
        }
        $newRoles = OrgUser::with('camp')->where('user_id', \Auth::user()->id)->get()->pluck('camp.id')->toArray();
        $newRoles = array_filter($newRoles, fn ($value) => !is_null($value));
        if(in_array($request->camp_id, $newRoles)) {
            return $next($request);
        }
        if ($request->is('checkin*') && !$request->is('checkin/selectCamp')) {
            $camp = \App\Models\Camp::find($request->camp_id);
            return response()->view('errors.401', ['message' => '目前報到營隊為' . $camp->fullName . '，非您可存取'], 401);
        }
        return $next($request);
    }
}
