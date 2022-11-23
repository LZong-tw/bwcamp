<?php
namespace App\Services;

use App\Models\Applicant;
use App\Models\ApplicantsGroup;
use Carbon\Carbon;
use App\Models\ContactLog;
use App\Models\GroupNumber;
use App\User;

class BackendService
{
    public function setAdmitted(Applicant $applicant, bool $admitted): Applicant
    {
        $applicant->is_admitted = $admitted;
        $applicant->save();
        $applicant->refresh();
        return $applicant;
    }

    public function processGroup(Applicant $applicant, string $group = null): ApplicantsGroup
    {
        if ($applicant->batch->num_groups && ($applicant->batch->groups->count() <= $applicant->batch->num_groups))
        {
            $group = ApplicantsGroup::first([
                'batch_id' => $applicant->batch_id,
                'alias' => $group,
            ]);
            if ($group) {
                return $group;
            } elseif ($applicant->batch->groups->count() + 1 > $applicant->batch->num_groups) {
                throw new \Exception('組別已滿');
            } else {
                $group = new ApplicantsGroup();
                $group->batch_id = $applicant->batch_id;
                $group->alias = $group;
                $group->save();
                return $group;
            }
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
        $applicant->groupRelation()->associate($group);
        $applicant->save();
        $applicant->refresh();
        return $applicant;
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
}
