<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Camp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('camp', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->string('table', 10);            // 個別營隊報名表客製化欄位表格
            $table->date('registration_start');	    // 報名開始日期
            $table->date('registration_end');	    // 報名截止日期
            $table->date('admission_announcing_date');	    // 公佈錄取日期
            $table->date('admission_confirming_end');	    // 錄確認截止日期
            $table->date('camp_start');	    // 營隊開始日
            $table->date('camp_end');	    // 營隊結束日
            $table->timestamps();
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
        Schema::dropIfExists('camp');
    }
}
