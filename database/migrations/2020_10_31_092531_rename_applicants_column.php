<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameApplicantsColumn2 extends Migration
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
            $table->renameColumn('postal_code', 'zipcode');
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
            $table->renameColumn('zipcode', 'postal_code');
        });
    }
}
