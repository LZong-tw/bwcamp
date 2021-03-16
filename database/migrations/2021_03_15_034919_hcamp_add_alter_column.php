<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HcampAddAlterColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hcamp', function (Blueprint $table) {
            //
            $table->string('traffic_return', 50)->nullable()->after('traffic_depart');
            $table->boolean('is_lamrim')->default(false)->before('parent_lamrim_class');  
            $table->string('is_child_blisswisdommed', 255)->nullable()->before('is_recommended_by_reading_class');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hcamp', function (Blueprint $table) {
            //
            $table->dropColumn('traffic_return');
            $table->dropColumn('is_lamrim');  
            $table->dropColumn('is_child_blisswisdommed');
        });
    }
}
