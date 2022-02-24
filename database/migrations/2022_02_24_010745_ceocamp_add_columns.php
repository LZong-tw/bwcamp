<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CeocampAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ceocamp', function (Blueprint $table) {
            //
            $table->string('industry_other', 30)->nullable()->after('industry');
            $table->string('job_property_other', 30)->nullable()->after('job_property');
            $table->string('org_type_other', 30)->nullable()->after('organization_type');
            $table->renameColumn('organization_type', 'org_type');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ceocamp', function (Blueprint $table) {
            //
            $table->dropColumn('industry_other');
            $table->dropColumn('job_property_other');
            $table->dropColumn('org_type_other');
            $table->renameColumn('org_type', 'organization_type');            
        });
    }
}
