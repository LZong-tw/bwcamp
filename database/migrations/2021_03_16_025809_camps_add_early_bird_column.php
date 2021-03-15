<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CampsAddEarlyBirdColumn extends Migration
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
            $table->boolean('has_early_bird')->default(false)->after('registration_end');  
            $table->integer('early_bird_fee')->default(0)->after('has_early_bird');  
            $table->date('early_bird_last_day')->nullable()->after('early_bird_fee');
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
            $table->dropColumn('has_early_bird');
            $table->dropColumn('early_bird_fee');  
            $table->dropColumn('early_bird_last_day');
        });
    }
}
