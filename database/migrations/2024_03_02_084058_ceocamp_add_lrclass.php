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
        Schema::table('ceocamp', function (Blueprint $table) {
            //
            $table->boolean('is_lrclass')->nullable()->after('contact_time'); //是否有廣論研討班別
            $table->string('lrclass')->nullable()->after('is_lrclass'); //廣論研討班別
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ceocamp', function (Blueprint $table) {
            //
            $table->dropColumn('is_lrclass');
            $table->dropColumn('lrclass');
        });
    }
};
