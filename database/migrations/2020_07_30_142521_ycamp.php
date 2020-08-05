<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Ycamp extends Migration
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
        Schema::create('ycamp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');           
            $table->string('school', 40)->nullable();
            $table->string('school_location', 10)->nullable();
            $table->string('region', 8)->nullable();
            $table->string('day_night', 8)->nullable();
            $table->string('system', 8)->nullable();
            $table->string('department', 40)->nullable();
            $table->string('grade', 10)->nullable();   
            $table->string('way', 10)->nullable();   
            $table->boolean('is_blisswisdom');   
            $table->string('blisswisdom_type', 20)->nullable();   
            // 
            // 
            // 
            // father_name
            // father_lamrim
            // father_phone
            // mother_name
            // mother_lamrim
            // mother_phone

            // is_inperson
            // agent_name
            // agent_phone

            // habbit
            // club
            // goal
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
        Schema::dropIfExists('ycamp');
    }
}
