<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CampsAddPaymentColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('camps', function (Blueprint $table) {
            //
            $table->string('payment_startdate', 10)->nullable()->after('admission_confirming_end'); 
            $table->string('payment_deadline', 10)->nullable()->after('payment_startdate'); 
            $table->integer('fee')->nullable()->after('payment_deadline'); 
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
            $table->dropColumn('payment_startdate');
            $table->dropColumn('payment_deadline');
            $table->dropColumn('fee');
        });
    }
}
