<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BackendController;

class PermissionController extends BackendController {
    public function showPermissionScope()
    {
        return view('backend.camp.permissionScopes', ['applicant' => null]);
    }

    public function showRoles()
    {
        return view('backend.camp.roles', ['applicant' => null]);
    }
}
