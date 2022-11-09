<?php
namespace App\Services;

use App\Models\Applicant;
use App\Models\ApplicantsGroup;
use Carbon\Carbon;

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
        $applicant->group = $group;
        $applicant->save();
        $applicant->refresh();
        return $applicant;
    }

    public function setGroup(Applicant $applicant, string $group): Applicant
    {
        $applicant->group = $group;
        $applicant->save();
        $applicant->refresh();
        return $applicant;
    }
}
