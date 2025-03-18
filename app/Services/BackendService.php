<?php

namespace App\Services;

use App\Models\Applicant;
use App\Models\ApplicantsGroup;
use App\Models\Batch;
use App\Models\Camp;
use App\Models\CampOrg;
use App\Models\CarerApplicantXref;
use App\Models\OrgUser;
use App\Models\UserApplicantXref;
use Carbon\Carbon;
use App\Models\ContactLog;
use App\Models\GroupNumber;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class BackendService
{
    public function setAdmitted(Applicant $applicant, bool $admitted): Applicant
    {
        $applicant->is_admitted = $admitted;
        $applicant->save();
        $applicant->refresh();
        return $applicant;
    }

    public function groupsCreation(Batch $batch): bool
    {
        if (!$batch->num_groups) {
            return true;
        }
        for ($i = 1; $i <= $batch->num_groups; $i++) {
            if ($this->checkBatchCanAddMoreGroup($batch)) {
                $group = ApplicantsGroup::firstOrCreate([
                    'batch_id' => $batch->id,
                    'alias' => "第" . $i . "組",
                ]);
            } else {
                $group = ApplicantsGroup::where([
                    'batch_id' => $batch->id,
                    'alias' => "第" . $i . "組",
                ])->firstOrFail();
            }
            $campOrg = CampOrg::firstOrCreate([
                'camp_id' => $batch->camp_id,
                'batch_id' => $group->batch_id,
                'section' => '關懷大組',
                'position' => '關懷小組' . $group->alias . '小組長',
                'group_id' => $group->id,
            ]);
            $campOrg2 = CampOrg::firstOrCreate([
                'camp_id' => $batch->camp_id,
                'batch_id' => $group->batch_id,
                'section' => '關懷大組',
                'position' => '關懷小組' . $group->alias . '副小組長',
                'group_id' => $group->id,
            ]);
            $campOrg3 = CampOrg::firstOrCreate([
                'camp_id' => $batch->camp_id,
                'batch_id' => $group->batch_id,
                'section' => '關懷大組',
                'position' => '關懷小組' . $group->alias . '組員',
                'group_id' => $group->id,
            ]);
        }
        return true;
    }

    public function checkBatchCanAddMoreGroup(Batch $batch): bool
    {
        if (!$batch->num_groups) {
            throw new \Exception("梯次沒有設定組數");
        }
        return ($batch->groups?->count() ?? 0) < $batch->num_groups;
    }

    public function processGroup(Applicant $applicant, string $group = null): ApplicantsGroup | null | string
    {
        $flag = 0;
        if ($applicant->batch->num_groups && $this->checkBatchCanAddMoreGroup($applicant->batch)) {
            $group = ApplicantsGroup::query()->firstOrCreate([
                'batch_id' => $applicant->batch_id,
                'alias' => $group,
            ]);
            if ($group) {
                return $group;
            } else {
                $flag = 1;
            }
        } elseif ($applicant->batch->num_groups && !$this->checkBatchCanAddMoreGroup($applicant->batch)) {
            $group = ApplicantsGroup::query()->where([
                'batch_id' => $applicant->batch_id,
                'alias' => $group,
            ])->first();
            if ($group) {
                return $group;
            } else {
                return null;
            }
        } elseif (!$applicant->batch->num_groups) {
            return ApplicantsGroup::query()->firstOrCreate([
                'batch_id' => $applicant->batch_id,
                'alias' => $group,
            ]);
        }
        if (app()->bound('sentry')) {
            \Sentry\captureMessage('組別處理發生異常狀況，營隊編號：' . $applicant->batch->camp_id . '，梯次編號：' . $applicant->batch_id . '，報名序號：' . $applicant->id . '，組別：' . $group . '，標記：' . $flag);
        }
        logger('組別處理發生異常狀況，營隊編號：' . $applicant->batch->camp_id . '，梯次編號：' . $applicant->batch_id . '，報名序號：' . $applicant->id . '，組別：' . $group . '，標記：' . $flag);
        return '<h2>組別處理發生異常狀況，請聯絡系統管理員</h2>';
    }

    public function setGroup(Applicant $applicant, string $group = null): Applicant | false
    {
        $group = $this->processGroup($applicant, $group);
        if (!$group) {
            return false;
        }
        if (!$applicant->is_admitted) {
            $this->setAdmitted($applicant, true);
        }
        $applicant->groupRelation()->associate($group);
        $applicant->save();
        $applicant->refresh();
        return $applicant;
    }

    public function setGroupNew(array $applicants, string $groupId): bool
    {
        foreach ($applicants as $applicant) {
            if (str_contains($applicant, "A")) {
                $applicant = str_replace("A", '', $applicant);
            }
            $applicant = Applicant::findOrFail($applicant);
            $group = ApplicantsGroup::findOrFail($groupId);
            if (!$applicant->is_admitted) {
                $this->setAdmitted($applicant, true);
            }
            $applicant->groupRelation()->associate($group);
            $applicant->save();
            $applicant->refresh();
        }
        return true;
    }

    public function setCarer(array $applicants, string $carerId): bool
    {
        foreach ($applicants as $applicant) {
            if (str_contains($applicant, "A")) {
                $applicant = str_replace("A", '', $applicant);
            }
            $applicant = Applicant::findOrFail($applicant);
            $carer = \App\Models\User::findOrFail($carerId);
            if (!$applicant->is_admitted) {
                $this->setAdmitted($applicant, true);
            }
            (new CarerApplicantXref([
                'user_id' => $carer->id,
                'applicant_id' => $applicant->id
            ]))->save();
        }
        return true;
    }

    public function setGroupOrg(array $applicantsOrUsers, string $groupId): array|string
    {
        $groupOrg = CampOrg::findOrFail($groupId);
        $succeedList = [];
        foreach ($applicantsOrUsers as $entity) {
            if (is_numeric($entity["uses_user_id"])) {
                $user = \App\Models\User::findOrFail($entity["uses_user_id"]);
                $record = UserApplicantXref::where('applicant_id', $entity["id"])->first();
                if ($record && $record->user_id != $user->id) {
                    $applicant = Applicant::find($entity["id"]);
                    \Sentry::captureMessage($applicant->name . " 已有連結的使用者，無法再連結其他使用者。");
                    return "<h1>" . $applicant->name . " 已有連結的使用者，無法再連結其他使用者。</h1>";
                }
                OrgUser::firstOrCreate([
                    'org_id' => $groupOrg->id,
                    'user_id' => $user->id,
                    'user_type' => 'App\Models\User',
                ]);
                if ($entity["type"] == "applicant") {
                    UserApplicantXref::firstOrCreate([
                        'user_id' => $user->id,
                        'applicant_id' => $entity["id"],
                    ]);
                    $applicant = Applicant::findOrFail($entity["id"]);
                    $applicant->is_admitted = 1;
                    $applicant->save();
                }
                $succeedList[] = [
                    'applicant' => $applicant ?? $user,
                    'connected_to_user' => $user,
                    'user_is_generated' => false,
                    'org' => $groupOrg,
                ];
            } elseif ($entity["uses_user_id"] == "generation_needed") {
                $user_is_generated = false;
                $applicant = Applicant::findOrFail($entity["id"]);
                $applicant->is_admitted = 1;
                $applicant->save();
                //$user = \App\Models\User::where('email', $applicant->email)->first();
                //use fuzzy search instead
                //if eamil missing, compare name only, otherwise name+email
                if ($applicant->email == null || $applicant->email == "") {
                    $user = \App\Models\User::where('name', 'like', "%". $applicant->name . "%")
                    //->orWhere('mobile', 'like', "%". $applicant->mobile . "%")
                    ->orderByDesc('id')->first();
                } else {
                    $user = \App\Models\User::where('name', 'like', "%". $applicant->name . "%")
                    ->orWhere('email', 'like', "%". $applicant->email . "%")
                    //->orWhere('mobile', 'like', "%". $applicant->mobile . "%")
                    ->orderByDesc('id')->first();
                }
                if ($user) {
                    \Sentry::captureMessage("Email " . $applicant->email . " 已註冊。");
                    //return "<h1>" . $applicant->name . "的 Email " . $applicant->email . " 已註冊。</h1>
                    //<h4>為什麼發生這個狀況？</h4>
                    //<h3>您可能一次開了多個分頁做同樣的操作，或是對同一位義工指派新職務後，又按下上一頁，才會遇到這個狀況。</h3>
                    //";
                } else {
                    $user = $this->generateUser($applicant);
                    $user_is_generated = true;
                }
                $record = UserApplicantXref::where('applicant_id', $entity["id"])->first();
                if ($record && $record->user_id != $user->id) {
                    $applicant = Applicant::find($entity["id"]);
                    \Sentry::captureMessage($applicant->name . " 已有連結的使用者，無法再連結其他使用者。");
                    return "<h1>" . $applicant->name . " 已有連結的使用者，無法再連結其他使用者。</h1>";
                }
                OrgUser::firstOrCreate([
                    'org_id' => $groupOrg->id,
                    'user_id' => $user->id,
                    'user_type' => 'App\Models\User',
                ]);
                UserApplicantXref::firstOrCreate([
                    'user_id' => $user->id,
                    'applicant_id' => $entity["id"],
                ]);
                $succeedList[] = [
                    'applicant' => $applicant,
                    'connected_to_user' => $user,
                    'user_is_generated' => $user_is_generated,
                    'org' => $groupOrg,
                ];
            } elseif ($entity["uses_user_id"] == "generation_needed_custom") {
                if (\App\Models\User::where('email', 'like', $entity["email"])->first()) {
                    \Sentry::captureMessage("Email " . $entity["email"] . " 已註冊。");
                    return "<h1>Email " . $entity["email"] . " 已註冊。</h1>";
                }
                $applicant = Applicant::findOrFail($entity["id"]);
                $applicant->is_admitted = 1;
                $applicant->save();
                $applicantTmp = clone $applicant;
                $name = $applicantTmp->name;
                $applicant = new Applicant();
                $applicant->name = $name;
                $applicant->email = $entity["email"];
                $applicant->mobile = $entity["password"];
                $user = $this->generateUser($applicant);
                $record = UserApplicantXref::where('applicant_id', $entity["id"])->first();
                if ($record && $record->user_id != $user->id) {
                    $applicant = Applicant::find($entity["id"]);
                    \Sentry::captureMessage($applicant->name . " 已有連結的使用者，無法再連結其他使用者。");
                    return "<h1>" . $applicant->name . " 已有連結的使用者，無法再連結其他使用者。</h1>";
                }
                OrgUser::firstOrCreate([
                    'org_id' => $groupOrg->id,
                    'user_id' => $user->id,
                    'user_type' => 'App\Models\User',
                ]);
                if ($entity["type"] == "applicant") {
                    UserApplicantXref::firstOrCreate([
                        'user_id' => $user->id,
                        'applicant_id' => $entity["id"],
                    ]);
                }
                $succeedList[] = [
                    'applicant' => $applicant,
                    'connected_to_user' => $user,
                    'user_is_generated' => true,
                    'org' => $groupOrg,
                ];
            } else {
                \Sentry::captureMessage("異常，請回上一頁檢查輸入資料是否齊全。");
                return "<h1>異常，請回上一頁檢查輸入資料是否齊全。</h1>";
            }
        }
        return $succeedList;
    }

    public function generateUser(Applicant $applicant): User
    {
        //to deal with empty email and mobile
        $milliseconds = (int) floor(microtime(true) * 1000);
        if($applicant->email == null || $applicant->email == "") {
            $applicant->email = "dummy" . $milliseconds. "@blisswisdom.org";
        }
        if($applicant->mobile == null || $applicant->mobile == "") {
            $applicant->mobile = "0000000000";
        }
        $user = new User([
            'name' => $applicant->name,
            'email' => $applicant->email,
            'password' => \Hash::make($applicant->mobile),
        ]);
        $user->save();
        $user->refresh();
        return $user;
    }

    public function processNumber(Applicant $applicant, string $number = null): GroupNumber
    {
        $number = GroupNumber::firstOrCreate([
            'group_id' => $applicant->group_id,
            'applicant_id' => $applicant->id,
            'number' => $number,
        ]);
        return $number;
    }

    public function setNumber(Applicant $applicant, string $number = null): Applicant
    {
        $number = $this->processNumber($applicant, $number);
        $applicant->numberRelation()->associate($number);
        $applicant->save();
        $applicant->refresh();
        return $applicant;
    }

    public function setTakenByName(ContactLog $contactlog): ContactLog
    {
        $takenby = User::find($contactlog->user_id);
        $contactlog->takenby_name = $takenby->name;
        return $contactlog;
    }

    public function removeAdmittedNumber(Applicant $applicant): Applicant
    {
        $applicant->groupRelation()->dissociate();
        $applicant->numberRelation()->delete();
        $applicant->numberRelation()->dissociate();
        $applicant->save();
        $applicant->refresh();
        return $applicant;
    }

    public function getBatchGroups(Camp $camp): Collection | null
    {
        return $camp->batchs()->with('groups')->get() ?? null;
    }

    public static function getAvailableModels()
    {
        $models = collect(File::allFiles(app_path('Models')))
            ->map(function ($item) {
                $path = $item->getRelativePathName();
                $class = sprintf(
                    '\%s%s',
                    'App\Models\\',
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\')
                );
                $model = new $class();
                if (str_contains($model->resourceNameInMandarin, "廢棄") || str_contains($model->resourceNameInMandarin, "未使用")) {
                    $name = "";
                }
                if ($model->resourceNameInMandarin == "") {
                    $name = "";
                }
                return collect([
                    'class' => $class,
                    'name' => $name ?? $model->resourceNameInMandarin,
                    'description' => $model->resourceDescriptionInMandarin,
                ]);
            });
        return $models;
    }

    public function getCampOrganizations(Camp $camp): Collection | null
    {
        //MCH: $camp->organizations will be empty, but not null.
        return $camp->organizations->isEmpty() ? null : $camp->organizations;
    }

    public function queryStringParser(array $payload, Request $request): string | null
    {
        $queryStr = null;
        $count = 0;
        $directly_skipped_this_parameter = 0;
        foreach ($payload as $key => $parameters) {
            if (is_array($parameters)) {
                $parameter_count = 0;
                foreach ($parameters as $index => $parameter) {
                    if (!$parameter) {
                        $directly_skipped_this_parameter = 1;
                        continue;
                    }
                    if (($parameter == '' || $parameter == null)) {
                        $directly_skipped_this_parameter = 1;
                        continue;
                    }
                    if ($index == 0 || $parameter_count == 0) {
                        if (($parameter != '' || $parameter != null) && $count != 0 && $queryStr) {
                            $queryStr .= " and ";
                        }
                        $queryStr .= " (";
                    }
                    // name: 避免有人用純數字查詢名字
                    // zipcode: 郵遞區號是以字串格式存於資料庫中
                    if (is_numeric($parameter) && $key != 'name' && $key != 'zipcode') {
                        if ($key == 'age' && !$request->ceocamp_sets_learner) {
                            $year = now()->subYears($parameter)->format('Y');
                            $queryStr .= "birthyear = " . $year;
                        } else {
                            if ($request->ceocamp_sets_learner) {
                                $queryStr .= "(" . $key . "=" . $parameter . ")";
                            } else {
                                if ($index == 0) {
                                    $queryStr .= "(";
                                }
                                $queryStr .= $key . "=" . $parameter . ")";
                            }
                        }
                    } elseif ($key == "group_id" && ($parameter == "na" || $parameter == "NONE")) {
                        $queryStr .= "(group_id = '' or group_id is null)";
                    } elseif ($key == "age") {
                        $parameter = str_replace("age", "timestampdiff(year, concat(birthyear, '-01-01'), curdate())", $parameter);
                        $queryStr .= $parameter;
                    } elseif (is_string($parameter) && str_contains($key, 'name')) {
                        // ceocamp: 菁英營
                        if (!$request->ceocamp_sets_learner) {
                            // 菁英營以外的營隊
                            if ($index == 0) {
                                $queryStr .= "(";
                            }
                            $queryStr .= "applicants.name like '%" . $parameter . "%'";
                            if ($index == count($parameters) - 1) {
                                $queryStr .= ")";
                                $need_to_close = 0;
                                $skip = 1;
                            }
                        } elseif ($key == 'applicants_name') {
                            $queryStr .= "applicants.name like '%" . $parameter . "%'";
                        } else {
                            $queryStr .= $key . " like '%" . $parameter . "%'";
                        }
                        if (!($skip ?? false)) {
                            $need_to_close = 1;
                        }
                    } elseif (is_string($parameter)) {
                        if (($index == 0 || $parameter_count == 0) && !$request->ceocamp_sets_learner) {
                            $queryStr .= " (";
                        }
                        $queryStr .= $key . " like '%" . $parameter . "%'";
                        $need_to_close = 1;
                    }

                    if (($need_to_close ?? false) && !$request->ceocamp_sets_learner) {
                        $queryStr .= ") ";
                    }

                    if (!is_string($index)) {
                        if ($index != count($parameters) - 1) {
                            if ($key != "age" && !$request->ceocamp_sets_learner) {
                                $queryStr .= " or (";
                            } else {
                                $queryStr .= " or ";
                            }
                        } elseif ($key != 'applicants.name') {
                            $queryStr .= ") ";
                        }
                    } else {
                        $queryStr .= ") ";
                    }
                    $parameter_count++;
                }
                $count++;
            }
            if ($count < count($payload)) {
                if ($request->ceocamp_sets_learner) {
                    if ($key == 'age') {
                    } elseif (($parameter != '' || $parameter != null) && $parameter_count <= count($parameters)) {
                    }
                } elseif($directly_skipped_this_parameter) {
                    $queryStr .= " ";
                    $directly_skipped_this_parameter = 0;
                } else {
                }
            }
        }
        return $queryStr;
    }

    public function volunteerQueryStringParser(array $payload, Request $request, Camp $camp): array
    {
        $queryStr = null;
        $queryRoles = null;
        $targetVolunteers = null;
        foreach ($payload as $key => &$value) {
            if ($key == "batch_id" || $key == "is_setting") {
                unset($payload[$key]);
                continue;
            }
            if ($key == "roles") {
                $do = true;
                $column = "id";
            } elseif ($key == "sections") {
                $do = true;
                $column = "section";
            }
            if ($do ?? false) {
                if (!is_array($value)) {
                    $value = [$value];
                }
                if (!$queryRoles) {
                    $queryRoles = CampOrg::whereIn($column, $value)->get();
                    $queryRoles = $queryRoles->filter(fn ($role) => $role->camp_id == $camp->id);
                    $targetVolunteers = OrgUser::whereIn('org_id', $value)->get()->pluck('user_id');
                    $targetVolunteers = \App\Models\User::whereIn('id', $targetVolunteers)->get();
                    $targetVolunteers->load('application_log');
                    $targetVolunteers = $targetVolunteers->filter(fn ($volunteer) => $volunteer->application_log->filter(function ($log) use ($camp) {
                        return $log->camp->id == $camp->vcamp->id;
                    })->count() > 0)->pluck('id');
                }
                unset($payload[$key]);
            }
            if (!is_array($value)) {
                unset($payload[$key]);
            }
        }
        $count = 0;
        foreach ($payload as $key => $parameters) {
            if (is_array($parameters)) {
                foreach ($parameters as $index => $parameter) {
                    if ($index == 0) {
                        $queryStr .= " (";
                    }
                    if (is_numeric($parameter)) {
                        if ($key == 'age') {
                            $year = now()->subYears($parameter)->format('Y');
                            $queryStr .= "birthyear = " . $year;
                        } else {
                            $queryStr .= $key . "=" . $parameter;
                        }
                    } elseif ($key == "group_id") {
                        $queryStr .= "1 = 1";
                        $showNoJob = true;
                    } elseif (is_string($parameter)) {
                        if ($key == 'name') {
                            $key = 'applicants.name';
                        }
                        if ($key == 'industry') {
                            $key = $camp->vcamp->table . '.industry';
                        }
                        $queryStr .= $key . " like '%" . $parameter . "%'";
                    }
                    if ($index != count($parameters) - 1) {
                        $queryStr .= " or ";
                    } else {
                        $queryStr .= ") ";
                    }
                }
                $count++;
            }
            if ($count <= count($payload) - 1) {
                $queryStr .= " and ";
            }
        }
        return [$queryStr, $queryRoles ?? collect([]), $showNoJob ?? null];
    }

    public function volunteerQueryStringParserRefactoredByChatGPT(array $payload, Request $request, Camp $camp): array
    {
        $queryStr = '';
        $queryRoles = collect([]);
        $showNoJob = null;
        foreach ($payload as $key => &$value) {
            if ($key == 'roles') {
                $queryRoles = CampOrg::whereIn('id', $value)
                    ->where('camp_id', $camp->id)
                    ->get()
                    ->pluck('id');

                $targetVolunteers = OrgUser::whereIn('org_id', $value)
                    ->get()
                    ->pluck('user_id');

                $targetVolunteers = \App\Models\User::whereIn('id', $targetVolunteers)
                    ->with('application_log')
                    ->get()
                    ->filter(fn ($volunteer) => $volunteer->application_log
                        ->filter(fn ($log) => $log->camp->id == $camp->vcamp->id)
                        ->isNotEmpty())
                    ->pluck('id');

                unset($payload[$key]);
            } elseif (!is_array($value)) {
                unset($payload[$key]);
            }
        }

        foreach ($payload as $key => $parameters) {
            if (is_array($parameters)) {
                $queryStr .= '(';

                foreach ($parameters as $index => $parameter) {
                    if ($index !== 0) {
                        $queryStr .= ' OR ';
                    }

                    if (is_numeric($parameter)) {
                        if ($key === 'age') {
                            $year = now()->subYears($parameter)->format('Y');
                            $queryStr .= 'birthyear = ' . $year;
                        } else {
                            $queryStr .= $key . ' = ' . $parameter;
                        }
                    } elseif ($key === 'group_id') {
                        $queryStr .= '1 = 1';
                        $showNoJob = true;
                    } elseif (is_string($parameter)) {
                        if ($key === 'name') {
                            $key = 'applicants.name';
                        }

                        if ($key === 'industry') {
                            $key = $camp->vcamp->table . '.industry';
                        }

                        $queryStr .= $key . " LIKE '%" . $parameter . "%'";
                    }
                }

                $queryStr .= ') AND ';
            }
        }

        $queryStr = rtrim($queryStr, ' AND ');

        return [$queryStr, $queryRoles, $showNoJob];
    }

    public function permissionTableProcessor($request, $roleID, $camp, $totalPermissions = [], $rolesModel = "\App\Models\CampOrg", $permissionModel = "\App\Models\Permission")
    {
        if ($request->resources && $request->range) {
            foreach ($request->resources as $resource => $actions) {
                foreach ($actions as $key => $action) {
                    $role = $rolesModel::findOrFail($roleID);
                    if (!isset($request->range[$resource])) {
                        return back()->withErrors(['range' => '您先前只選擇了' . $resource . '，未選擇範圍。']);
                    }
                    $permission = $permissionModel::firstOrCreate([
                        'name' => $resource . '.' . $action,
                        'display_name' => $camp->abbreviation . '-' . $action . ' ' . $request->resources_name[$resource],
                        'description' => $camp->abbreviation . '-' . $action . ' ' . $request->resources_name[$resource],
                        'resource' => $resource,
                        'action' => $action,
                        'range' => $request->range[$resource],
                        'camp_id' => $role->camp_id,
                        'batch_id' => $role->batch_id,
                    ]);
                    $totalPermissions[] = $permission->id;
                }
            }
            if (count($request->resources) != count($request->range)) {
                return back()->withErrors(['range' => '動作及範圍對應的資源數量不同。']);
            }
            return $totalPermissions;
        } elseif ($request->range && !$request->resources) {
            return back()->withErrors(['resources' => '您先前只選擇了範圍，未選擇動作。']);
        } elseif (!$request->range && $request->resources) {
            return back()->withErrors(['range' => '您先前只選擇了動作，未選擇範圍。']);
        } elseif (!$request->range && !$request->resources) {
            return [];
        }
        return "<h1>異常。</h1>";
    }
}
