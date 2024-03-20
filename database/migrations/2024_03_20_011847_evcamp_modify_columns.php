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
        Schema::table('evcamp', function (Blueprint $table) {
            //
            $table->string('group_priority1', 255)->change();
            $table->string('group_priority2', 255)->change();
            $table->string('group_priority3', 255)->change();
            $table->string('lrclass', 255)->change();
            $table->string('transport', 255)->change();
            $table->string('transport_other', 255)->change();
            $table->string('expertise', 255)->change();
            $table->string('expertise_other', 255)->change();
            $table->string('language', 255)->change();
            $table->string('language_other', 255)->change();
            $table->string('unit', 255)->change();
            $table->string('industry', 255)->change();
            $table->string('title', 255)->change();
            $table->string('job_property', 255)->change();
            $table->string('org_type', 255)->change();
            $table->string('years_operation', 255)->change();
            $table->string('recruit_channel')->nullable()->after('group_priority3');
            $table->string('stay_dates')->nullable()->after('participation_dates');
            $table->boolean('is_preliminaries')->nullable()->after('stay_dates');
            $table->boolean('is_cleanup')->nullable()->after('is_preliminaries');
            $table->string('depart_from')->nullable()->after('is_cleanup');
            $table->string('depart_from_location')->nullable()->after('depart_from');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evcamp', function (Blueprint $table) {
            //
            $table->dropColumn('recruit_channel');
            $table->dropColumn('stay_dates');
            $table->dropColumn('is_preliminaries');
            $table->dropColumn('is_cleanup');
            $table->dropColumn('depart_from');
            $table->dropColumn('depart_from_location');
        });
    }
};
