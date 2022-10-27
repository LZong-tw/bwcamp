<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use App\Models\Camp;
use App\Models\Applicant;
use App\Models\Batch;
use App\Models\CheckIn;
use App\Models\Traffic;
use Carbon\Carbon;
use View;
use App\Traits\EmailConfiguration;
use App\Models\SignInSignOut;

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
