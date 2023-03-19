<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\BackendController;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use App\Services\BackendService;

class LaratrustPermissionsController extends BackendController
{
    protected $permissionModel;
    protected $campDataService;
    protected $applicantService;
    protected $backendService;
    protected $user;

    public function __construct(
        CampDataService $campDataService,
        ApplicantService $applicantService,
        BackendService $backendService,
        Request $request
    )
    {
        $this->backendService = $backendService;
        $this->campDataService = $campDataService;
        $this->applicantService = $applicantService;
        parent::__construct(
            $campDataService,
            $applicantService,
            $backendService,
            $request
        );
        $this->permissionModel = Config::get('laratrust.models.permission');
        $this->checkPermission($request);
    }

    public function checkPermission($request) {
        $that = $this;
        $this->middleware(function ($request, $next) use (&$that) {
            $that->user = \App\Models\User::with("roles.permissions")->find(auth()->user()->id);
            
            $canDoPermissions = $that->user->roles->pluck("roles.permissions")->where('camp_id', $request->camp_id)
                    ->where(function($query) {
                        $query->where([["resource", "like", "%Permission%"], ["action", "like", "%assign%"]])
                            ->orWhere([["resource", "like", "%Permission%"], ["action", "like", "%create%"]]);
                    })->count();
            $canDoRoles = $that->user->roles->pluck("roles.permissions")->where('camp_id', $request->camp_id)
                ->where(function($query) {
                    $query->where([["resource", "like", "%CampOrg%"], ["action", "like", "%assign%"]])
                        ->orWhere([["resource", "like", "%CampOrg%"], ["action", "like", "%create%"]]);
                })->count();
            if (!($canDoPermissions && $canDoRoles) && $that->user->id != 1) {
                return response("<h1>權限不足</h1>");
            }
            return $next($request);
        });
    }

    public function index($camp_id)
    {
        $models = $this->backendService->getAvailableModels();
        return View::make('vendor.laratrust.panel.permissions.index', [
            'permissions' => $this->permissionModel::orderBy('display_name')->simplePaginate(10),
            'models' => $models,
        ]);
    }

    public function create($camp_id)
    {
        return View::make('vendor.laratrust.panel.edit', [
            'model' => null,
            'permissions' => null,
            'type' => 'permission',
            'typeInMandarin' => '權限',
            'modelsAvailable' => $this->backendService->getAvailableModels()
        ]);
    }

    public function store(Request $request, $camp_id)
    {
        $data = $request->validate([
            'resource'     => 'required|string',
            'action'       => 'required|string',
            'display_name' => 'required|string',
            'description'  => 'nullable|string',
            'range' => 'required|string',
        ]);

        $data['name'] = $data['resource'] . "." . $data['action'];

        $permission = $this->permissionModel::create($data);


        Session::flash('laratrust-success', 'Permission created successfully');
        return redirect(route('laratrustCustom.permissions.index', $this->camp_id));
    }

    public function edit($camp_id, $id)
    {
        $permission = $this->permissionModel::findOrFail($id);

        return View::make('vendor.laratrust.panel.edit', [
            'model' => $permission,
            'type' => 'permission',
            'typeInMandarin' => '權限',
            'modelsAvailable' => $this->backendService->getAvailableModels()
        ]);
    }

    public function update(Request $request, $camp_id, $id)
    {
        $permission = $this->permissionModel::findOrFail($id);

        $data = $request->validate([
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
            'resource' => 'required|string',
            'action' => 'required|string',
            'range' => 'required|string',
        ]);

        $permission->update($data);

        Session::flash('laratrust-success', 'Permission updated successfully');
        return redirect(route('laratrustCustom.permissions.index', $this->camp_id));
    }
}
