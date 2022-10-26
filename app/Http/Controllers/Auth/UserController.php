<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showDashboard()
    {
        return view('dashboard', ['name' => auth()->user()->name]);
    }

    public function updateProfile()
    {
        $this->validate(request(), [
            'name' => 'required',
            'password' => 'required|confirmed'
        ]);

        auth()->user()->update([
            'name' => request('name'),
            'password' => bcrypt(request('password'))
        ]);

        return redirect()->back()->with('status', 'Profile updated!');
    }
}
