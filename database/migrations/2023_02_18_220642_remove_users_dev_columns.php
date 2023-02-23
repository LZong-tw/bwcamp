<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_p6')) {
                $table->dropColumn('is_p6');
            }
            if (Schema::hasColumn('users', 'is_p7')) {
                $table->dropColumn('is_p7');
            }
            if (Schema::hasColumn('users', 'is_p9')) {
                $table->dropColumn('is_p9');
            }
            if (Schema::hasColumn('users', 'is_p10')) {
                $table->dropColumn('is_p10');
            }
            if (Schema::hasColumn('users', 'is_p14')) {
                $table->dropColumn('is_p14');
            }
            if (Schema::hasColumn('users', 'is_p15')) {
                $table->dropColumn('is_p15');
            }
            if (Schema::hasColumn('users', 'is_p16')) {
                $table->dropColumn('is_p16');
            }
            if (Schema::hasColumn('users', 'is_p18')) {
                $table->dropColumn('is_p18');
            }
        });
    }
};
