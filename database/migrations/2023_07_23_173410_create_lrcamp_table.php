<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lrcamp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->string('belief')->nullable();
            $table->string('education')->nullable();
            $table->string('unit')->nullable();
            $table->string('unit_location')->nullable();
            $table->string('title')->nullable();
            $table->string('level')->nullable();
            $table->string('job_property')->nullable();
            $table->text('experience')->nullable();   
            $table->string('employees')->nullable();   
            $table->string('direct_managed_employees')->nullable();   
            $table->string('industry')->nullable();   
            $table->string('after_camp_available_day')->nullable();   
            $table->string('favored_event')->nullable();   
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
        Schema::dropIfExists('lrcamp');
    }
};
