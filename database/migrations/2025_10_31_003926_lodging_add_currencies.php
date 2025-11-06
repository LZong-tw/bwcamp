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
        Schema::table('lodging', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('fare_currency_id')->after('fare')->default(1);
            $table->decimal('fare_xrate_to_std', total: 15, places: 6)->after('fare_currency_id')->default(1.0);
            $table->decimal('fare_std', total: 10, places: 2)->nullable()->after('fare_xrate_to_std');
            $table->unsignedBigInteger('deposit_currency_id')->after('deposit')->default(1);
            $table->decimal('deposit_xrate_to_std', total: 15, places: 6)->after('deposit_currency_id')->default(1.0);
            $table->decimal('deposit_std', total: 10, places: 2)->nullable()->after('deposit_xrate_to_std');
            $table->unsignedBigInteger('cash_currency_id')->after('cash')->default(1);
            $table->decimal('cash_xrate_to_std', total: 15, places: 6)->after('cash_currency_id')->default(1.0);
            $table->decimal('cash_std', total: 10, places: 2)->nullable()->after('cash_xrate_to_std');
            $table->decimal('fare', total: 10, places: 2)->change();
            $table->decimal('deposit', total: 10, places: 2)->change();
            $table->decimal('cash', total: 10, places: 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lodging', function (Blueprint $table) {
            //
            $table->integer('fare')->change();
            $table->integer('deposit')->change();
            $table->integer('cash')->change();
            $table->dropColumn('fare_currency_id');
            $table->dropColumn('fare_xrate_to_std');
            $table->dropColumn('fare_std');
            $table->dropColumn('deposit_currency_id');
            $table->dropColumn('deposit_xrate_to_std');
            $table->dropColumn('deposit_std');
            $table->dropColumn('cash_currency_id');
            $table->dropColumn('cash_xrate_to_std');
            $table->dropColumn('cash_std');
        });
    }
};
