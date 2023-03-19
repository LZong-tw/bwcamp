<?php

namespace App\Http\Controllers\Auth;

use Laratrust\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\BackendController;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use App\Services\BackendService;
use Termwind\Components\Dd;

class RolesController extends BackendController
{
    protected $rolesModel;
    protected $permissionModel;
    protected $campDataService;
    protected $applicantService;
    protected $backendService;
    protected $user;

    public function __construct(CampDataService $campDataService,
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
        $this->rolesModel = Config::get('laratrust.models.role');
        $this->permissionModel = Config::get('laratrust.models.permission');
        $availableResources = \App\Services\BackendService::getAvailableModels();
        view()->share('availableResources', $availableResources);
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
        return view('vendor.laratrust.panel.roles.index', [
            'roles' => $this->rolesModel::where('camp_id', $camp_id)->withCount('permissions')
                ->orderBy('section')->orderBy('position')->simplePaginate(10),
        ]);
    }

    public function create($camp_id)
    {
        return View::make('vendor.laratrust.panel.edit', [
            'model' => null,
            'permissions' => $this->permissionModel::all(['id', 'name']),
            'type' => 'role',
            'typeInMandarin' => '職務',
        ]);
    }

    public function show(Request $request, $camp_id, $id)
    {
        $role = $this->rolesModel::query()
            ->with('permissions')
            ->findOrFail($id);

        return View::make('vendor.laratrust.panel.roles.show', ['role' => $role]);
    }

    public function store(Request $request, $camp_id)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role = $this->rolesModel::create($data);
        $role->syncPermissions($request->get('permissions') ?? []);

        Session::flash('laratrust-success', 'Role created successfully');
        return redirect(route('laratrustCustom.roles.index', $this->camp_id));
    }

    public function edit($camp_id, $id)
    {
        $role = $this->rolesModel::query()
            ->with('permissions')
            ->findOrFail($id);

        if (!Helper::roleIsEditable($role)) {
            Session::flash('laratrust-error', 'The role is not editable');
            return redirect()->back();
        }

        $permissions = $this->permissionModel::all()
            ->map(function ($permission) use ($role) {
                $permission->assigned = $role->permissions
                    ->pluck('id')
                    ->contains($permission->id);

                return $permission;
            });

        $role2 = $this->rolesModel::query()->with('permissions')->findOrFail($id);

        return View::make('vendor.laratrust.panel.edit', [
            'model' => $role,
            'complete_permissions' => $role2->permissions,
            'permissions' => $permissions,
            'type' => 'role',
            'typeInMandarin' => '職務',
        ]);
    }

    public function update(Request $request, $camp_id, $id)
    {
        $totalPermissions = [];
        $role = $this->rolesModel::findOrFail($id);
        $totalPermissions = $this->backendService->permissionTableProcessor($request, $id, $this->campFullData, $totalPermissions, $this->rolesModel, $this->permissionModel);
        if (!is_array($totalPermissions)) {
            return $totalPermissions;
        }

        if (!Helper::roleIsEditable($role)) {
            Session::flash('laratrust-error', 'The role is not editable');
            return redirect()->back();
        }

        $data = $request->validate([
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $totalPermissions = array_merge($totalPermissions, $request->get('permissions') ?? []);
        $role->update($data);
        $role->syncPermissions($totalPermissions);

        Session::flash('laratrust-success', 'Role updated successfully');
        return redirect(route('laratrustCustom.roles.index', $this->camp_id));
    }

    public function destroy($camp_id, $id)
    {
        $usersAssignedToRole = DB::table(Config::get('laratrust.tables.role_user'))
            ->where(Config::get('laratrust.foreign_keys.role'), $id)
            ->count();
        $role = $this->rolesModel::findOrFail($id);

        if (!Helper::roleIsDeletable($role)) {
            Session::flash('laratrust-error', 'The role is not deletable');
            return redirect()->back();
        }

        if ($usersAssignedToRole > 0) {
            Session::flash('laratrust-warning', 'Role is attached to one or more users. It can not be deleted');
        } else {
            Session::flash('laratrust-success', 'Role deleted successfully');
            $this->rolesModel::destroy($id);
        }

        return redirect(route('laratrustCustom.roles.index', $this->camp_id));
    }
}
