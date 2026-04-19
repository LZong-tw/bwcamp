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
        Schema::table('ecamp', function (Blueprint $table) {
            //
            $table->string('info_source')->after('industry')->nullable();
            $table->string('info_source_other')->after('info_source')->nullable();
        });
    }

    /**
     * Reverse the migrations.E
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecamp', function (Blueprint $table) {
            //
            $table->dropColumn('info_source');
            $table->dropColumn('info_source_other');
        });
    }
};
