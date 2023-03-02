<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath($request));
    }

    public function redirectPath($request)
    {
        if ($request->camp_id) {
            return "/backend/" . $request->camp_id . "/IOI/learner";
        }
        if (\App\Models\User::find(auth()->user()->id)->roles->filter(static fn ($r) => $r->camp->year == now()->year)->count() == 1) {
            foreach (\App\Models\User::find(auth()->user()->id)->roles as $role) {
                if ($role->camp->year == now()->year && str_contains($role->section, "關懷大組")) {
                    return "/backend/" . $role->camp->id . "/IOI/learner";
                }
            }
        }
        if (str_contains($request->headers->get('referer'), 'login')) {
            return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
        }
        return redirect()->back();
    }
}
