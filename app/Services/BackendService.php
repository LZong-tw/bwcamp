<?php
namespace App\Services;

use App\Models\Applicant;
use App\Models\ApplicantsGroup;
use App\Models\Batch;
use App\Models\Camp;
use App\Models\CampOrg;
use App\Models\OrgUser;
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

    public function setGroupOrg(array $applicantsOrUsers, string $groupId): bool
    {
        $groupOrg = CampOrg::findOrFail($groupId);
        foreach ($applicantsOrUsers as $entity) {
            if (str_contains($entity, "U")) {
                $user = \App\Models\User::findOrFail(str_replace("U", "", $entity));
                (new OrgUser([
                    'org_id' => $groupOrg->id,
                    'user_id' => $user->id,
                    'user_type' => 'App\Models\User',
                ]))->save();
            }
            else {
                $applicant = Applicant::findOrFail($entity);
                if (!$applicant->is_admitted) {
                    $this->setAdmitted($applicant, true);
                }
                $applicant->groupOrgRelation()->associate($groupOrg);
                $applicant->save();
                $applicant->refresh();
            }
        }
        return true;
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
        $next_need_to_add_and = 0;
        $directly_skipped_this_parameter = 0;
        foreach ($payload as $key => $parameters) {
            if (is_array($parameters)) {
                foreach ($parameters as $index => $parameter) {
                    if (($parameter == '' || $parameter == null) && !$next_need_to_add_and) {
                        $next_need_to_add_and = 1;
                        continue;
                    }
                    elseif ($index == 0) {
                        if ($next_need_to_add_and && ($parameter != '' || $parameter != null)) {
                            $queryStr .= " AND ";
                            $next_need_to_add_and = 0;
                        }
                        else {
                            $directly_skipped_this_parameter = 1;
                        }
                        $queryStr .= " (";
                    }
                    if (is_numeric($parameter) && $key != 'name') {
                        if ($key == 'age' && !$request->ceocamp_sets_learner) {
                            $year = now()->subYears($parameter)->format('Y');
                            $queryStr .= "birthyear = " . $year;
                        } else {
                            $queryStr .= $key . "=" . $parameter;
                        }
                    }
                    elseif ($key == "group_id" && $parameter == "na") {
                        $queryStr .= "group_id = '' or group_id is null";
                    }
                    elseif ($key == "age") {
                        $parameter = str_replace("age", "timestampdiff(year, concat(birthyear, '-01-01'), curdate())", $parameter);
                        $queryStr .= $parameter;
                    }
                    elseif (is_string($parameter) && $key == 'name') {
                        if (!$request->ceocamp_sets_learner) {
                            $key = 'applicants.name';
                            $queryStr .= $key . " like '%" . $parameter . "%'";
                        }
                        elseif ($parameter) {
                            $queryStr .= $index . " like '%" . $parameter . "%'";
                        }
                    }
                    elseif (is_string($parameter)) {
                        $queryStr .= $key . " like '%" . $parameter . "%'";
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
                        else{
                            $queryStr .= ") ";
                        }
                    }
                }
                $count++;
            }
            if ($count <= count($payload) - 1) {
                if ($request->ceocamp_sets_learner) {
                    if (
                        (isset($payload["name"]) && ($payload["name"]['applicants.name'] == '' || $payload["name"]['applicants.name'] == null)) &&
                        (isset($payload["name"]) && ($payload["name"]['introducer_name'] == '' || $payload["name"]['introducer_name'] == null))
                    ) {
                        $queryStr .= "";
                    }
                    elseif ($key != 'name' || $key != 'age') {
                        $queryStr .= " and ";
                    }
                    else {
                        $queryStr .= ") and ";
                    }
                }
                elseif($directly_skipped_this_parameter) {
                    $queryStr .= "";
                    $directly_skipped_this_parameter = 0;
                }
                else {
                    $queryStr .= " and ";
                }
            }
        }
        return $queryStr;
    }
}
