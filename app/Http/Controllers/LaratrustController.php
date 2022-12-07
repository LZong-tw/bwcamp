<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Http\Controllers\BackendController;

class LaratrustController extends BackendController
{
    public function index()
    {
        $permissions = Permission::paginate(50);
        return view('vendor.laratrust.panel.permissions.index', compact('permissions'));
    }

    public function roleIndex()
    {
        $permissions = Permission::paginate(50);
        return view('vendor.laratrust.panel.permissions.index', compact('permissions'));
    }
}
