<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackendController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permitted');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function backendIndex()
    {
        return view('backend.index');
    }
}
