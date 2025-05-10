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
        Schema::table('evcamp', function (Blueprint $table) {
            //
            $table->string('trclass')->nullable()->after('lrclass');  //儲訓班別
            $table->string('trclass_no')->nullable()->after('trclass');  //儲訓班編號

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evcamp', function (Blueprint $table) {
            //
            $table->dropColumn('trclass');
            $table->dropColumn('trclass_no');
        });
    }
};
