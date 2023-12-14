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
        Schema::table('dynamic_stats', function (Blueprint $table) {
            //
            $table->string('urltable_type')->after('applicant_id');    //camp, batch, or applicant
            $table->string('purpose')->nullable()->after('urltable_type');  //用途：如動態調查、問卷等等
            $table->string('spreadsheet_id')->nullable()->after('google_sheet_url');  //optional
            $table->string('sheet_name')->nullable()->after('spreadsheet_id');  //optional
            $table->renameColumn('applicant_id','urltable_id'); //camp_id, batch_id, or applicant_id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dynamic_stats', function (Blueprint $table) {
            //
            $table->dropColumn('urltable_type');
            $table->dropColumn('purpose');
            $table->dropColumn('spreadsheet_id');
            $table->dropColumn('sheet_name');
            $table->renameColumn('urltable_id','applicant_id');
        });
    }
};
