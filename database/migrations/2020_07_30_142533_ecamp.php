<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Ecamp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 各欄位說明
        // https://docs.google.com/spreadsheets/d/1UXCVFgP8OXzr2fD_aiCnSbRW_zoQ_0Vu8MakmMOYuYc/
        Schema::create('ecamp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->string('belief', 20)->nullable();
            $table->string('education', 10)->nullable();
            $table->string('unit', 40)->nullable();
            $table->string('unit_location', 8)->nullable();
            $table->string('title', 20)->nullable();
            $table->string('job_property', 16)->nullable();
            $table->text('experience')->nullable();   
            $table->integer('employees')->nullable();   
            $table->integer('direct_managed_employees')->nullable();   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('ecamp');
    }
}
