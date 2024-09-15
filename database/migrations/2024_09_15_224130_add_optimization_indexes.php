<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOptimizationIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_applicant_xrefs', function (Blueprint $table) {
            $table->index(['user_id', 'applicant_id']);
        });

        Schema::table('org_user', function (Blueprint $table) {
            $table->index(['user_id', 'org_id', 'user_type']);
        });

        Schema::table('camp_org', function (Blueprint $table) {
            $table->index('camp_id');
        });

        Schema::table('applicants', function (Blueprint $table) {
            $table->index(['batch_id', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_applicant_xrefs', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'applicant_id']);
        });

        Schema::table('org_user', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'org_id', 'user_type']);
        });

        Schema::table('camp_org', function (Blueprint $table) {
            $table->dropIndex(['camp_id']);
        });

        Schema::table('applicants', function (Blueprint $table) {
            $table->dropIndex(['batch_id', 'deleted_at']);
        });
    }
}
