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
        Schema::create('group_numbers', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id');
            $table->integer('applicant_id');
            $table->integer('number');
            $table->timestamps();

            $table->unique(['group_id', 'applicant_id', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_numbers');
    }
};
