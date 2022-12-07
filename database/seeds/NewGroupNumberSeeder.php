<?php

namespace Database\Seeders;

use App\Models\Applicant;
use Illuminate\Database\Seeder;
use App\Services\BackendService;
use Illuminate\Support\Facades\DB;

class NewGroupNumberSeeder extends Seeder
{
    public function __construct(public BackendService $backendService)
    {
    }
    public function run()
    {
        Applicant::where('group_legacy', '!=', null)->chunk(20, function ($applicants) {
            DB::transaction(function () use ($applicants) {
                foreach ($applicants as $applicant) {
                    $this->backendService->setGroup($applicant, $applicant->group_legacy);
                    $this->backendService->setNumber($applicant, $applicant->number_legacy);
                    $applicant->save();
                }
            });
        });
    }
}
