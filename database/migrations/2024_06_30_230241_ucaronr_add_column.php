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
        Schema::table('user_can_access_resource_or_not_results', function (Blueprint $table) {
            //
            $table->string('context')->nullable()->after('accessible_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_can_access_resource_or_not_results', function (Blueprint $table) {
            //
        });
    }
};
