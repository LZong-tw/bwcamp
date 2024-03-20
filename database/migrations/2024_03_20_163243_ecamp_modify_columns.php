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
        Schema::table('ecamp', function (Blueprint $table) {
            //
            $table->string('belief', 255)->change();
            $table->string('education', 255)->change();
            $table->string('unit', 255)->change();
            $table->string('unit_location', 255)->change();
            $table->string('title', 255)->change();
            $table->string('level', 255)->change();
            $table->string('job_property', 255)->change();
            $table->string('employees', 255)->change();
            $table->string('direct_managed_employees', 255)->change();
            $table->string('industry', 255)->change();
            $table->string('after_camp_available_day', 255)->change();
            $table->string('favored_event', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecamp', function (Blueprint $table) {
            //
        });
    }
};
