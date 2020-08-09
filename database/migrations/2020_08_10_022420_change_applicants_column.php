<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeApplicantsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('applicants', function (Blueprint $table) {
            $table->boolean('is_admitted')->default(0)->change();
            $table->boolean('is_attend')->default(0)->change();
            $table->string('group', 10)->nullable()->change();
            $table->string('number', 10)->nullable()->change();
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
        Schema::table('applicants', function (Blueprint $table) {
            $table->boolean('is_admitted')->change();
            $table->boolean('is_attend')->change();
            $table->string('group', 10)->change();
            $table->string('number', 10)->change();
        });
    }
}
