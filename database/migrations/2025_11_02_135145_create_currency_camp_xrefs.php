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
        Schema::create('currency_camp_xrefs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camp_id')->constrained('camps');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->boolean('is_std');
            $table->boolean('is_fix_xrate');
            $table->decimal('xrate_to_std', total: 15, places: 6)->default(1.0);
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
        Schema::dropIfExists('currency_camp_xrefs');
    }
};
