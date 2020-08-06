<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tcamp extends Migration
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
        Schema::create('tcamp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->boolean('is_educating');  
            $table->boolean('has_license');
            $table->integer('years_teached')->nullable();
            $table->string('education', 10)->nullable();  
            $table->string('school_or_course', 40)->nullable();  
            $table->string('subject_teaches', 40)->nullable();  
            $table->string('title', 20)->nullable();
            $table->string('unit', 40)->nullable();
            $table->string('unit_county', 8)->nullable();
            $table->string('unit_district', 8)->nullable();
            $table->boolean('is_blisswisdom');   
            $table->text('blisswisdom_type', 20)->nullable();   
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
        Schema::dropIfExists('tcamp');
    }
}
