<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Camp;
use Illuminate\Database\Eloquent\Factories\Factory;

class BatchFactory extends Factory
{
    protected $model = Batch::class;

    public function definition()
    {
        return [
            'camp_id' => Camp::factory(),
            'name' => 'A梯',
            'batch_start' => now()->addDays(10)->toDateString(),
            'batch_end' => now()->addDays(15)->toDateString(),
            'is_appliable' => true,
            'locationName' => 'Test Location',
        ];
    }
}