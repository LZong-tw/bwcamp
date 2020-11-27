<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBatchSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batchs', function (Blueprint $table) {
            //
            $table->tinyInteger('is_late_registration_end')->default(0)->after('batch_end');
            $table->date('late_registration_end')->nullable()->after('is_late_registration_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batchs', function (Blueprint $table) {
            //
            $table->dropColumn('is_late_registration_end');
            $table->dropColumn('late_registration_end');
        });
    }
}
