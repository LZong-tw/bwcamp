<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApplicantsAddPaymentColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            //
            $table->string('store_first_barcode', 30)->nullable()->after('expectation'); 
            $table->string('store_second_barcode', 30)->nullable()->after('store_first_barcode');
            $table->string('store_third_barcode', 30)->nullable()->after('store_second_barcode');
            $table->string('bank_second_barcode', 30)->nullable()->after('store_third_barcode');
            $table->string('bank_third_barcode', 30)->nullable()->after('bank_second_barcode');
            $table->integer('fee')->nullable()->after('bank_third_barcode');
            $table->integer('deposit')->nullable()->after('fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            //
            $table->dropColumn('store_first_barcode');
            $table->dropColumn('store_second_barcode');
            $table->dropColumn('store_third_barcode');
            $table->dropColumn('bank_second_barcode');
            $table->dropColumn('bank_third_barcode');
            $table->dropColumn('fee');
            $table->dropColumn('deposit');
        });
    }
}
