<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('camp_org', 'all_group')) {
            Schema::table('camp_org', function (Blueprint $table) {
                $table->boolean('all_group')->default(false)->after('group_id');
            });
        }
    }
};
