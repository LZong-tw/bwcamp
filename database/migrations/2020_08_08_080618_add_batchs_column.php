<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('batchs', function (Blueprint $table) {
            $table->date('batch_start')->nullable()->after('name');           // 梯次開始日期
            $table->date('batch_end')->nullable()->after('batch_start');      // 梯次開始日期
        });
        Schema::table('camps', function (Blueprint $table) {
            $table->dropColumn('camp_start');
            $table->dropColumn('camp_end');
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
        Schema::table('batchs', function (Blueprint $table) {
            $table->dropColumn('batch_start');
            $table->dropColumn('batch_end');
        });
        Schema::table('camps', function (Blueprint $table) {
            $table->date('camp_start')->nullable()->after('admission_confirming_end');
            $table->date('camp_end')->nullable()->after('camp_start');	  
        });
    }
}
