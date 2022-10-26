<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtcampTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utcamp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->string('title')->nullable();
            $table->string('position')->nullable();
            $table->string('position_complement')->nullable();
            $table->string('unit')->nullable();
            $table->string('unit_county')->nullable();
            $table->string('department')->nullable();  
            $table->string('info_source')->nullable();
            $table->string('info_source_other')->nullable();
            $table->boolean('is_blisswisdom')->nullable();   
            $table->string('blisswisdom_type')->nullable();   
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
        Schema::dropIfExists('utcamp');
    }
}
