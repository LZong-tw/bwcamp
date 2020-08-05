<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Applicants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 各欄位說明
        // https://docs.google.com/spreadsheets/d/1UXCVFgP8OXzr2fD_aiCnSbRW_zoQ_0Vu8MakmMOYuYc/
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batch');
            $table->string('name', 40);
            $table->boolean('is_admitted');
            $table->string('group', 10);
            $table->boolean('is_attend');
            $table->string('gender', 2);
            $table->string('birthyear', 4);
            $table->string('birthmonth', 2)->nullable();
            $table->string('birthday', 2)->nullable();
            $table->string('nationality', 30);
            $table->string('idno', 20)->nullable();
            $table->boolean('is_foreigner')->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('phone_home', 15)->nullable();
            $table->string('phone_work', 15)->nullable();
            $table->string('fax', 15)->nullable();
            $table->string('line', 40)->nullable();
            $table->string('wechat', 40)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('postal_code', 6)->nullable();
            $table->string('address', 60)->nullable();
            $table->string('emergency_name', 40);
            $table->string('emergency_relationship', 8);
            $table->string('emergency_mobile', 20)->nullable();
            $table->string('emergency_phone_home', 15)->nullable();
            $table->string('emergency_phone_work', 15)->nullable();
            $table->string('emergency_fax', 15)->nullable();
            $table->string('introducer_name', 40)->nullable();
            $table->string('introducer_relationship', 8)->nullable();
            $table->string('introducer_phone', 20)->nullable();
            $table->text('participated')->nullable();
            $table->boolean('portrait_agree');
            $table->boolean('profile_agree');
            $table->text('expectation')->nullable();
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
        Schema::dropIfExists('applicants');
    }
}
