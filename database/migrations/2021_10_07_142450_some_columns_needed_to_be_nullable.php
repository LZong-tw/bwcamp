<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SomeColumnsNeededToBeNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            //
            $table->string('emergency_name', 40)->nullable()->change();
            $table->string('emergency_relationship', 8)->nullable()->change();
        });
        Schema::table('tcamp', function (Blueprint $table) {
            //
            $table->boolean('is_educating')->nullable()->change(); 
            $table->boolean('has_license')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            //
            $table->string('emergency_name', 40)->change();
            $table->string('emergency_relationship', 8)->change();
        });
        Schema::table('tcamp', function (Blueprint $table) {
            //
            $table->boolean('is_educating')->change(); 
            $table->boolean('has_license')->change();
        });
    }
}
