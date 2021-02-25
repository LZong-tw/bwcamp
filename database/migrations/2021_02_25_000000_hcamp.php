<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Hcamp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // https://docs.google.com/forms/d/1c_QhNl8QplunsWnoHU9jMPf0-DVojo161wMtYPAjDWw/edit
        Schema::create('hcamp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->string('education', 10)->nullable();  
            $table->text('special_condition')->nullable();  
            $table->string('traffic', 50)->nullable();  
            $table->string('branch_or_classroom_belongs_to', 20)->nullable();
            $table->string('class_type', 20)->nullable();
            $table->string('parent_lamrim_class', 20)->nullable();
            $table->boolean('is_recommended_by_reading_class')->default(false);
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
        Schema::dropIfExists('hcamp');
    }
}
