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
        // todo: 營隊學員組別檢查，如果組別未達設定，即自動補足
        if ($applicant->batch->num_groups && ($applicant->batch->groups->count() < $applicant->batch->num_groups))
        {

        }

        $group = ApplicantsGroup::firstOrCreate([
            'batch_id' => $applicant->batch_id,
            'alias' => $group,
        ]);
        return $group;
    }

    public function setGroup(Applicant $applicant, string $group = null): Applicant
    {
        $group = $this->processGroup($applicant, $group);
        $applicant->group = $group;
        return $applicant;
    }

    public function processNumber(Applicant $applicant, string $number = null): GroupNumber
    {
        $number = GroupNumber::firstOrCreate([
            'group_id' => $applicant->group->id,
            'applicant_id' => $applicant->id,
            'number' => $number,
        ]);
        return $number;
    }

    public function setNumber(Applicant $applicant, string $number = null): Applicant
    {
        $number = $this->processNumber($applicant, $number);
        $applicant->number = $number;
        return $applicant;
    }

    public function setTakenByName(ContactLog $contactlog): ContactLog
    {
        $takenby = User::find($contactlog->takenby_id);
        $contactlog->takenby_name = $takenby->name;
        return $contactlog;
    }
}
