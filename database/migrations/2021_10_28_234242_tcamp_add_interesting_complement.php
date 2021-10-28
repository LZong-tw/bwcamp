<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TcampAddInterestingComplement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tcamp', function (Blueprint $table) {
            //
            $table->string('interesting_complement')->nullable()->after('interesting');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tcamp', function (Blueprint $table) {
            //
            $table->dropIfExists('interesting_complement');
        });
    }
}
