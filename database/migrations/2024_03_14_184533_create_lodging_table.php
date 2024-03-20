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
        if (!Schema::hasTable('lodging')) {
            Schema::create('lodging', function (Blueprint $table) {
                $table->id();
                $table->foreignId('applicant_id')->constrained('applicants');
                $table->string('room_type');
                $table->integer('nights')->default(0);
                $table->integer('fare')->default(0);
                $table->integer('deposit')->default(0);
                $table->integer('cash')->default(0);
                $table->integer('sum')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lodging');
    }
};
