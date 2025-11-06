<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code');     //-- ISO 4217 貨幣代碼，例如 'TWD', 'USD'
            $table->string('symbol');   //-- 貨幣符號，例如 'NT$', '$'
            $table->string('name');     //-- 貨幣名稱，例如 '新台幣', 'US Dollar'
            $table->timestamps();
            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};

/*
CREATE TABLE `currencies` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(3) NOT NULL, -- ISO 4217 貨幣代碼，例如 'TWD', 'USD'
  `symbol` varchar(5) NOT NULL, -- 貨幣符號，例如 'NT$', '$'
  `name` varchar(50) NOT NULL, -- 貨幣名稱，例如 '新台幣', 'US Dollar'
  PRIMARY KEY (`id`),
  UNIQUE KEY `currencies_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 */
