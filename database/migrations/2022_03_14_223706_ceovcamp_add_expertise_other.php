<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CeovcampAddExpertiseOther extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ceovcamp', function (Blueprint $table) {
            //
            $table->string('expertise_other', 30)->nullable()->after('expertise');  //專長：自填
            $table->dropColumn('group_priority_other');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ceovcamp', function (Blueprint $table) {
            //
            $table->dropColumn('expertise_other');
            $table->string('group_priority_other', 60)->nullable()->after('group_priority3');
        });
    }
}
