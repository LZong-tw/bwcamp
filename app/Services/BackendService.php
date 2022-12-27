<?php
namespace App\Services;

use App\Models\Applicant;
use App\Models\ApplicantsGroup;
use App\Models\Batch;
use App\Models\Camp;
use App\Models\CampOrg;
use Carbon\Carbon;
use App\Models\ContactLog;
use App\Models\GroupNumber;
use App\User;
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

    public function setGroupOrgNew(array $applicants, string $groupId): bool
    {
        foreach ($applicants as $applicant) {
            $applicant = Applicant::findOrFail($applicant);
            $groupOrg = CampOrg::findOrFail($groupId);
            if (!$applicant->is_admitted) {
                $this->setAdmitted($applicant, true);
            }
            $applicant->groupOrgRelation()->associate($groupOrg);
            $applicant->save();
            $applicant->refresh();
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
}
