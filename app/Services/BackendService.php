<?php
namespace App\Services;

use App\Models\Applicant;
use App\Models\ApplicantsGroup;
use App\Models\Batch;
use App\Models\Camp;
use App\Models\CampOrg;
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
        for ($i = 0; $i < $batch->num_groups; $i++) {
            if ($this->checkBatchCanAddMoreGroup($batch)) {
                $group = ApplicantsGroup::firstOrCreate([
                    'batch_id' => $batch->id,
                    'alias' => "第" . __($i) . "組",
                ]);
            }
            else {
                $group = ApplicantsGroup::where([
                    'batch_id' => $batch->id,
                    'alias' => "第" . __($i) . "組",
                ])->firstOrFail();
            }
            // todo: 待營隊職務轉移後，此處應該要改成用 $batch->id
            $campOrg = CampOrg::firstOrCreate([
                'camp_id' => $batch->camp_id,
                'section' => '關懷大組',
                'position' => '關懷小組' . $group->alias . '小組長',
            ]);
            $campOrg2 = CampOrg::firstOrCreate([
                'camp_id' => $batch->camp_id,
                'section' => '關懷大組',
                'position' => '關懷小組' . $group->alias . '副小組長',
            ]);
            $campOrg3 = CampOrg::firstOrCreate([
                'camp_id' => $batch->camp_id,
                'section' => '關懷大組',
                'position' => '關懷小組' . $group->alias . '關懷員',
            ]);
            $campOrg4 = CampOrg::firstOrCreate([
                'camp_id' => $batch->camp_id,
                'section' => '關懷大組',
                'position' => '關懷小組' . $group->alias . '組員',
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

    public function processGroup(Applicant $applicant, string $group = null): ApplicantsGroup
    {
        if ($applicant->batch->num_groups && $this->checkBatchCanAddMoreGroup($applicant->batch)) {
            $group = ApplicantsGroup::firstOrCreate([
                'batch_id' => $applicant->batch_id,
                'alias' => $group,
            ]);
            if ($group) {
                return $group;
            }
        } elseif ($applicant->batch->num_groups && !$this->checkBatchCanAddMoreGroup($applicant->batch)) {
            $group = ApplicantsGroup::firstOrFail([
                'batch_id' => $applicant->batch_id,
                'alias' => $group,
            ]);
        }
        elseif (!$applicant->batch->num_groups) {
            return ApplicantsGroup::firstOrCreate([
                'batch_id' => $applicant->batch_id,
                'alias' => $group,
            ]);
        }

        throw new \Exception('組別處理發生異常狀況');
    }

    public function setGroup(Applicant $applicant, string $group = null): Applicant
    {
        $group = $this->processGroup($applicant, $group);
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

    public function setGroupOrg(array $applicantsOrUsers, string $groupId): array
    {
        $groupOrg = CampOrg::findOrFail($groupId);
        $succeedList = [];
        foreach ($applicantsOrUsers as $entity) {
            if (is_numeric($entity["uses_user_id"])) {
                $user = \App\Models\User::findOrFail($entity["uses_user_id"]);
                (new OrgUser([
                    'org_id' => $groupOrg->id,
                    'user_id' => $user->id,
                    'user_type' => 'App\Models\User',
                ]))->save();
                if ($entity["type"] == "applicant") {
                    (new UserApplicantXref([
                        'user_id' => $user->id,
                        'applicant_id' => $entity["id"],
                    ]))->save();
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
            }
            elseif ($entity["uses_user_id"] == "generation_needed") {
                $applicant = Applicant::findOrFail($entity["id"]);
                $applicant->is_admitted = 1;
                $applicant->save();
                $user = $this->generateUser($applicant);
                $succeedList[] = [
                    'applicant' => $applicant,
                    'connected_to_user' => $user,
                    'user_is_generated' => true,
                    'org' => $groupOrg,
                ];
            }
            else {
                throw new \Exception("異常");
//                $applicant = Applicant::findOrFail($entity);
//                if (!$applicant->is_admitted) {
//                    $this->setAdmitted($applicant, true);
//                }
//                $applicant->groupOrgRelation()->associate($groupOrg);
//                $applicant->save();
//                $applicant->refresh();
            }
        }
        return $succeedList;
    }

    public function generateUser(Applicant $applicant): User
    {
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
        $takenby = User::find($contactlog->takenby_id);
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

    public function getAvailableModels()
    {
        $models = collect(File::allFiles(app_path('Models')))
            ->map(function ($item) {
                $path = $item->getRelativePathName();
                $class = sprintf('\%s%s',
                    'App\Models\\',
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));
                $model = new $class;
                return collect([
                    'class' => $class,
                    'name' => $model->resourceNameInMandarin
                ]);
            });
        return $models;
    }

    public function getCampOrganizations(Camp $camp): Collection | null
    {
        return $camp->organizations ?? null;
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
                            }
                            else {
                                if ($index == 0) {
                                    $queryStr .= "(";
                                }
                                $queryStr .= $key . "=" . $parameter . ")";
                            }
                        }
                    }
                    elseif ($key == "group_id" && ($parameter == "na" || $parameter == "NONE")) {
                        $queryStr .= "(group_id = '' or group_id is null)";
                    }
                    elseif ($key == "age") {
                        $parameter = str_replace("age", "timestampdiff(year, concat(birthyear, '-01-01'), curdate())", $parameter);
                        $queryStr .= $parameter;
                    }
                    elseif (is_string($parameter) && str_contains($key, 'name')) {
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
                        }
                        elseif ($key == 'applicants_name') {
                            $queryStr .= "applicants.name like '%" . $parameter . "%'";
                        }
                        else {
                            $queryStr .= $key . " like '%" . $parameter . "%'";
                        }
                        if (!($skip ?? false)) {
                            $need_to_close = 1;
                        }
                    }
                    elseif (is_string($parameter)) {
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
                            }
                            else {
                                $queryStr .= " or ";
                            }
                        }
                        elseif ($key != 'applicants.name'){
                            $queryStr .= ") ";
                        }
                    }
                    else {
                        $queryStr .= ") ";
                    }
                    $parameter_count++;
                }
                $count++;
            }
            if ($count < count($payload)) {
                if ($request->ceocamp_sets_learner) {
                    if ($key == 'age') {
                    }
                    elseif (($parameter != '' || $parameter != null) && $parameter_count <= count($parameters)) {
                    }
                }
                elseif($directly_skipped_this_parameter) {
                    $queryStr .= " ";
                    $directly_skipped_this_parameter = 0;
                }
                else {
                }
            }
        }
        return $queryStr;
    }
}
