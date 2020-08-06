<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCampColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('camps', function (Blueprint $table) {
            $table->renameColumn('name', 'fullName');
            $table->string('name', 60)->change();
            $table->string('abbreviation', 30)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('camps', function (Blueprint $table) {
            $table->renameColumn('fullName', 'name');
            $table->string('name', 40)->change();
            $table->dropColumn('abbreviation');
        });
    }
}
