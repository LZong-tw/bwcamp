<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyApplicantsColumn extends Migration
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
            $table->string("nationality", 30)->nullable()->change();  
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
            $table->string("nationality", 30)->change();  
        });
    }
}
