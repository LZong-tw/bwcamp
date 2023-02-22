<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectPath()
    {
        if (auth()->user()->roles()->filter(static fn ($r) => $r->camp->year == now()->year)->count() == 1) {
            foreach (auth()->user()->roles as $role) {
                if ($role->camp->year == now()->year && str_contains($role->position, "關懷小組") && str_contains($role->position, "組員")) {
                    return route("showLearners", $role->camp->id);
                }
            }
        }
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}
