<?php

namespace Database\Factories;

use App\Models\Camp;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Camp::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $counter = 1;
        return [
            'fullName' => 'Test Camp ' . $counter++,
            'table' => 'ycamp',
            'year' => 2024,
            'registration_start' => now()->subDays(30),
            'registration_end' => now()->addDays(30),
            'admission_announcing_date' => now()->addDays(35),
            'admission_confirming_end' => now()->addDays(40),
            'payment_deadline' => now()->addDays(45),
            'fee' => 1000,
        ];
    }
}
