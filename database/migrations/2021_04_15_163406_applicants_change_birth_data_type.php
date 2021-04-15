<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApplicantsChangeBirthDataType extends Migration
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
            $table->integer('birthyear')->nullable()->change();
            $table->integer('birthmonth')->nullable()->change();
            $table->integer('birthday')->nullable()->change();
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
            $table->string('birthyear', 4)->change();
            $table->string('birthmonth', 2)->nullable()->change();
            $table->string('birthday', 2)->nullable()->change();
        });
    }
}
