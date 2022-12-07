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
        if(!Schema::hasColumn('permissions', 'resource')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('resource')->nullable()->after('display_name');
            });
        }
        if(!Schema::hasColumn('permissions', 'action')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('action')->nullable()->after('resource');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permission', function (Blueprint $table) {
            //
        });
    }
};
