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
        //
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_p6')->nullable();
            $table->boolean('is_p7')->nullable();
            $table->boolean('is_p9')->nullable();
            $table->boolean('is_p10')->nullable();
            $table->boolean('is_p14')->nullable();
            $table->boolean('is_p15')->nullable();
            $table->boolean('is_p16')->nullable();
            $table->boolean('is_p18')->nullable();
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
    }
};
