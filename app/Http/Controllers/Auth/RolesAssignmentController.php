<?php

namespace App\Http\Controllers\Auth;

use Laratrust\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\BackendController;
use App\Services\CampDataService;
use App\Services\ApplicantService;
use App\Services\BackendService;
use App\Services\GSheetService;

class RolesAssignmentController extends BackendController
{
    protected $rolesModel;
    protected $permissionModel;
    protected $assignPermissions;
    protected $campDataService;
    protected $applicantService;
    protected $backendService;
    protected $user;

    public function __construct(CampDataService $campDataService,
                                ApplicantService $applicantService,
                                BackendService $backendService,
                                protected GSheetService $gSheetService,
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
            $gSheetService,
            $request
        );
        $this->rolesModel = Config::get('laratrust.models.role');
        $this->permissionModel = Config::get('laratrust.models.permission');
        $this->assignPermissions = Config::get('laratrust.panel.assign_permissions_to_user');
        $availableResources = \App\Services\BackendService::getAvailableModels();
        view()->share('availableResources', $availableResources);
        $this->checkPermission($request);
    }

    public function checkPermission($request) {
        $that = $this;
        $this->middleware(function ($request, $next) use (&$that) {
            $that->user = \App\Models\User::with("roles.permissions")->find(auth()->user()->id);
            $canDoPermissions = $that->user->roles->pluck("permissions")->flatten()
                                ->where('camp_id', $this->campFullData->id)
                                ->filter(function ($item) {
                                    return (str_contains($item->resource, "Permission") &&
                                        str_contains($item->action, "assign")) ||
                                        (str_contains($item->resource, "Permission") &&
                                            str_contains($item->action, "create"));
                                })->count();
            $canDoRoles = $that->user->roles->pluck("permissions")->flatten()
                                ->where('camp_id', $this->campFullData->id)
                                ->filter(function ($item) {
                                    return (str_contains($item->resource, "CampOrg") &&
                                        str_contains($item->action, "assign")) ||
                                        (str_contains($item->resource, "CampOrg") &&
                                            str_contains($item->action, "create"));
                                })->count();
            if (!($canDoPermissions && $canDoRoles) && $that->user->id > 2) {
                return response("<h1>權限不足</h1>");
            }
            return $next($request);
        });
    }

    public function index(Request $request, $camp_id)
    {
        $modelsKeys = array_keys(Config::get('laratrust.user_models'));
        $modelKey = $request->get('model') ?? $modelsKeys[0] ?? null;
        $userModel = Config::get('laratrust.user_models')[$modelKey] ?? null;

        if (!$userModel) {
            abort(404);
        }

        return view('vendor.laratrust.panel.roles-assignment.index', [
            'models' => $modelsKeys,
            'modelKey' => $modelKey,
            'users' => $userModel::query()
                ->withCount(['roles', 'permissions'])
//                ->whereHas('roles', function ($query) use ($camp_id) {
//                    $query->where('camp_id', $camp_id);
//                })
                ->orderBy('id', 'desc')
                ->simplePaginate(10),
        ]);
    }

    public function edit(Request $request, $camp_id, $modelId)
    {
        $modelKey = $request->get('model');
        $userModel = Config::get('laratrust.user_models')[$modelKey] ?? null;

        if (!$userModel) {
            Session::flash('laratrust-error', 'Model was not specified in the request');
            return redirect(route('laratrustCustom.roles-assignment.index', $this->camp_id));
        }

        $user = $userModel::query()
            ->with(['roles', 'permissions'])
            ->findOrFail($modelId);

        $roles = $this->rolesModel::where('position', 'not like', '%root%')
            ->orderBy('id')->get()
            ->map(function ($role) use ($user) {
                $role->assigned = $user->roles
                ->pluck('id')
                    ->contains($role->id);
                $role->isRemovable = Helper::roleIsRemovable($role);

                return $role;
            });

        if ($this->assignPermissions) {
            $permissions = $this->permissionModel::orderBy('name')->get()
                ->map(function ($permission) use ($user) {
                    $permission->assigned = $user->permissions
                        ->pluck('id')
                        ->contains($permission->id);

                    return $permission;
                });
        }

        return View::make('vendor.laratrust.panel.roles-assignment.edit', [
            'modelKey' => $modelKey,
            'roles' => $roles,
            'permissions' => $this->assignPermissions ? $permissions : null,
            'user' => $user,
            'userModel' => $userModel,
        ]);
    }

    public function update(Request $request, $camp_id, $modelId)
    {
        $modelKey = $request->get('model');
        $userModel = Config::get('laratrust.user_models')[$modelKey] ?? null;

        if (!$userModel) {
            Session::flash('laratrust-error', 'Model was not specified in the request');
            return redirect()->back();
        }

        $user = $userModel::findOrFail($modelId);

        $user->syncRoles($request->get('roles') ?? []);
        if ($this->assignPermissions) {
            $user->syncPermissions($request->get('permissions') ?? []);
        }

        Session::flash('laratrust-success', 'Roles and permissions assigned successfully');
        return redirect(route('laratrustCustom.roles-assignment.index', ['model' => $modelKey, 'camp_id' => $this->camp_id]));
    }
}
