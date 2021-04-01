<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AcampCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // https://docs.google.com/forms/d/1c_QhNl8QplunsWnoHU9jMPf0-DVojo161wMtYPAjDWw/edit
        Schema::create('acamp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->string('unit', 40)->nullable();  
            $table->string('unit_county', 8)->nullable();  
            $table->string('unit_district', 8)->nullable();  
            $table->string('industry', 16)->nullable();
            $table->string('title', 20)->nullable();  
            $table->string('education', 10)->nullable();  
            $table->string('job_property', 16)->nullable();  
            $table->boolean('is_manager')->nullable(); 
            $table->boolean('is_cadre')->nullable(); 
            $table->boolean('is_technicalstaff')->nullable(); 
            $table->string('class_location',8)->nullable(); 
            $table->string('way', 10)->nullable();
            $table->string('belief', 20)->nullable();
            $table->string('motivation', 20)->nullable();
            $table->text('blisswisdom_type')->nullable();
            $table->boolean('is_inperson');   
            $table->string('agent_name', 10)->nullable();   
            $table->string('agent_phone', 16)->nullable();   
            $table->string('agent_relationship', 9)->nullable();   
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
        Schema::dropIfExists('acamp');
    }
}
