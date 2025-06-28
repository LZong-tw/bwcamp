<?php

namespace Database\Factories;

use App\Models\Applicant;
use App\Models\Batch;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicantFactory extends Factory
{
    protected $model = Applicant::class;

    public function definition()
    {
        static $counter = 1;
        return [
            'batch_id' => Batch::factory(),
            'name' => 'Test User ' . $counter++,
            'gender' => 'M',
            'mobile' => '0912345678',
            'email' => 'test@example.com',
            'is_admitted' => 1,
            'birthyear' => 1990,
            'birthmonth' => 1,
            'birthday' => 1,
            'nationality' => '中華民國',
            'portrait_agree' => 1,
            'profile_agree' => 1,
        ];
    }
}