<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CeovcampModifyColumns extends Migration
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
            $table->text('volunteer_experiences')->nullable()->after('lrclass');   //義工護持記錄
            $table->text('cadre_experiences')->nullable()->after('lrclass');       //班級護持記錄
            $table->dropColumn('industry_other');
            $table->dropColumn('job_property_other');
            $table->dropColumn('org_type_other');
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
            $table->dropColumn('volunteer_experiences');
            $table->dropColumn('cadre_experiences');
            $table->string('industry_other', 30)->nullable()->after('industry');            //產業別:自填
            $table->string('job_property_other', 30)->nullable()->after('job_property');    //工作性質:自填
            $table->string('org_type_other', 30)->nullable()->after('org_type');            //公司性質:自填
        });
    }
}
