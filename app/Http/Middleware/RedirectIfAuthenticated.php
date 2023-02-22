<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (auth()->user()->roles()->filter(static fn ($r) => $r->camp->year == now()->year)->count() == 1) {
                foreach (auth()->user()->roles as $role) {
                    if ($role->camp->year == now()->year && str_contains($role->position, "關懷小組") && str_contains($role->position, "組員")) {
                        $this->redirectTo = route("showLearners", $role->camp->id);
                    }
                }
            }
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
