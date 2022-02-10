<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCeocampTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ceocamp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->string('unit', 40)->nullable();                     //公司名稱
            $table->string('title', 40)->nullable();
            $table->string('job_property', 30)->nullable();
            $table->integer('employees')->nullable();                   //員工總數
            $table->integer('direct_managed_employees')->nullable();    //直屬員工人數
            $table->integer('capital')->nullable();                     //資本額
            $table->string('industry', 30)->nullable();                 //產業別
            $table->string('organization_type', 30)->nullable();        //公司性質
            $table->string('years_operation', 16)->nullable();          //公司年限
            $table->string('contact_time', 60)->nullable();             //適合聯絡時間
            $table->string('marital_status', 20)->nullable();           //婚姻狀態
            $table->text('exceptional_conditions')->nullable();         //特殊狀況
            $table->string('participation_mode', 16)->nullable();       //參與模式：實體/線上
            $table->string('reasons_online', 60)->nullable();           //選線上的原因
            $table->text('reasons_recommend')->nullable();              //推薦原因
            $table->string('substitute_name', 40)->nullable();
            $table->string('substitute_phone', 25)->nullable();
            $table->string('substitute_email',100)->nullable();
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
        Schema::dropIfExists('ceocamp');
    }
}
