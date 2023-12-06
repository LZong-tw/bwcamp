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
        Schema::table('ivcamp', function (Blueprint $table) {
            //
            $table->string('group_priority1')->nullable()->after('applicant_id'); //報名組別志願1
            $table->string('lrclass')->nullable()->after('group_priority1'); //廣論研討班別
            $table->string('expertise')->nullable()->after('lrclass'); //專長
            $table->string('expertise_other')->nullable()->after('expertise'); //專長：自填

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ivcamp', function (Blueprint $table) {
            //
            $table->dropColumn('group_priority1');
            $table->dropColumn('lrclass');
            $table->dropColumn('expertise');
            $table->dropColumn('expertise_other');
        });
    }
};
