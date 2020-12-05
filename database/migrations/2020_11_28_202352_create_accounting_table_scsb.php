<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingTableScsb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_scsb', function (Blueprint $table) {
            //
            $table->id();
            $table->string('name', 40)->nullable();            // 代收類別
            $table->date('creddited_at')->nullable();          // 入帳日期
            $table->date('paid_at')->nullable();	           // 繳費日期
            $table->string('accounting_sn', 6)->nullable();    // 銷帳流水號
            $table->string('accounting_no', 16)->nullable();   // 銷帳編號
            $table->integer('amount')->nullable();             // 繳款金額
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
        Schema::dropIfExists('accounting_scsb');
    }
}
