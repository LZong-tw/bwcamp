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
        Schema::table('traffic', function (Blueprint $table) {
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
        Schema::table('traffic', function (Blueprint $table) {
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

/*
CREATE TABLE `traffic` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint unsigned NOT NULL,
  `depart_from` text COLLATE utf8mb4_unicode_ci,
  `back_to` text COLLATE utf8mb4_unicode_ci,

  -- 費用的原始金額、幣別和當下匯率
  `fare_original` DECIMAL(10, 2) NOT NULL,
  `fare_currency_code` varchar(3) NOT NULL, -- 直接用'USD', 'TWD'，或用 currency_id
  `exchange_rate_to_twd` DECIMAL(15, 6) NOT NULL DEFAULT '1.000000', -- 交易當下的匯率快照

  -- 換算成本位幣（TWD）的金額，用於會計和報表
  `fare_twd` DECIMAL(10, 2) NOT NULL,

  -- 同樣的設計應用於訂金和現金支付
  `deposit_original` DECIMAL(10, 2) NOT NULL DEFAULT '0.00',
  `deposit_twd` DECIMAL(10, 2) NOT NULL DEFAULT '0.00',
  -- 如果訂金支付的幣別和時間點可能與 fare 不同，可以為 deposit 也加上 currency_code 和 exchange_rate 欄位

  `cash_original` DECIMAL(10, 2) NOT NULL DEFAULT '0.00',
  `cash_twd` DECIMAL(10, 2) NOT NULL DEFAULT '0.00',
  -- 同上，如果現金支付的幣別和時間點也不同，也加上對應欄位

  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `traffic_applicant_id_foreign` (`applicant_id`),
  CONSTRAINT `traffic_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`),
  KEY `traffic_fare_currency_code_foreign` (`fare_currency_code`),
  CONSTRAINT `traffic_fare_currency_code_foreign` FOREIGN KEY (`fare_currency_code`) REFERENCES `currencies` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
*/