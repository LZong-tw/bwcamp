<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('camps', function (Blueprint $table) {
            //營隊結束後，研習證明提供下載日期
            $table->date('certificate_available_date')->nullable()->after('rejection_showing_date');
            //becasue admission_confirming_end is nullable, needed_to_reply_attend default should be false.
            $table->boolean('needed_to_reply_attend')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('camps', function (Blueprint $table) {
            //
            $table->dropColumn('certificate_available_date');
            $table->boolean('needed_to_reply_attend')->default(true)->change();
        });
    }
};
