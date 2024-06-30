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
        Schema::table('user_can_access_resource_or_not_results', function (Blueprint $table) {
            //
            $table->integer('camp_id')->nullable()->after('user_id');
            $table->integer('batch_id')->nullable()->after('camp_id');
            $table->integer('region_id')->nullable()->after('batch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('user_can_access_resource_or_not_results', ['camp_id', 'batch_id', 'region_id']);
    }
};
