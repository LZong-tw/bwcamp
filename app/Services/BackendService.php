<?php
namespace App\Services;

use App\Models\Applicant;
use App\Models\ApplicantsGroup;
use Carbon\Carbon;
use App\Models\ContactLog;
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

    public function processGroup(Applicant $applicant, string $group): Applicant
    {
        if($applicant->batch->groups->count() < $applicant->batch->num_groups) {

        }
        return $applicant;
    }

    public function setGroup(Applicant $applicant, string $group): Applicant
    {
        $applicant->group = $group;
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
}
