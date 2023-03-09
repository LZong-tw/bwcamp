<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('permissions', 'camp_id')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('camp_id')->nullable()->after('id');
            });
        }
        if (!Schema::hasColumn('permissions', 'batch_id')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('batch_id')->nullable()->after('camp_id');
            });
        }
    }
};
