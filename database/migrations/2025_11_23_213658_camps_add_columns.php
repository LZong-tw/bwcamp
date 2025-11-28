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
        Schema::table('camps', function (Blueprint $table) {
            //
            $table->decimal('discount_fee', total: 10, places: 2)->nullable()->after('early_bird_last_day');
            $table->date('discount_last_day')->nullable()->after('discount_fee');
            $table->decimal('fee', total: 10, places: 2)->change();
            $table->decimal('early_bird_fee', total: 10, places: 2)->change();
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
            $table->dropColumn('discount_fee');
            $table->dropColumn('discount_last_day');
            $table->integer('fee')->change();
            $table->integer('early_bird_fee')->change();
        });
    }
};
