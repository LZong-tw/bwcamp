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
        Schema::table('nycamp', function (Blueprint $table) {
            //
            $table->string('companion_name')->nullable()->after('accommodation_needs');  //同行人
            $table->boolean('companion_as_roommate')->nullable()->after('companion_name');    //同住
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nycamp', function (Blueprint $table) {
            //
            $table->dropColumn('companion_name');
            $table->dropColumn('companion_as_roommate');
        });
    }
};
