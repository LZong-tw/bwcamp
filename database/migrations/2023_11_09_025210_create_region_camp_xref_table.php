<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('region_camp_xref')) {
            Schema::create('region_camp_xref', function (Blueprint $table) {
                $table->id();
                $table->foreignId('camp_id');
                $table->foreignId('region_id');
                $table->timestamps();
            });
        }
    }
};
