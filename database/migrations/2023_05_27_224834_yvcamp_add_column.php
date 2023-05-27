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
        Schema::table('yvcamp', function (Blueprint $table) {
            //
            $table->foreignId('applicant_id')->constrained('applicants')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yvcamp', function (Blueprint $table) {
            //
            $table->dropColumn('applicant_id');
        });
    }
};
