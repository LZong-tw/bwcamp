<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $regions = \App\Models\Region::all();
        \DB::transaction(function () use ($regions) {
            \App\Models\Applicant::query()->whereNull('region_id')->get()->each(function ($applicant) use ($regions) {
                $applicant->region_id = $regions->where('name', $applicant->region)->first()?->id ?? null;
                $applicant->save();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
