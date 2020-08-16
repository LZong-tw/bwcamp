<?php

namespace App\Http\Middleware;

use Closure;

class Permitted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->user()->permission == "ALL"){
            return $next($request);
        }
        else{
            abort(401, 'Unauthorized.');
        }
    }
}
