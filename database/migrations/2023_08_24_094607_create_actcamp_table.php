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
        Schema::create('actcamp', function (Blueprint $table) {

            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->string('category')->nullable();         //身份別
            $table->string('transportation')->nullable();   //交通方式            
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
        Schema::dropIfExists('actcamp');
    }
};
