<?php

namespace App\Http\Middleware;

use Closure;

class RestrictLocal
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
        $host = parse_url(request()->headers->get('referer'), PHP_URL_HOST);
        $ip = $request->ip();
        // $allowed_origin = ["bw.camp"];
        // $allowed_ip = ["127.0.0.1"];
        if($host == "bwcamp.bwfoce.org") {
            return $next($request);
        }
        else if($host == "bw.camp" && $ip == "127.0.0.1") {
            return $next($request);
        }
        else{
            return abort(404);
        }
    }
}
