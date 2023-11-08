<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('regions')) {
            Schema::create('regions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }
        \App\Models\Region::firstOrCreate(['name' => '台北']);
        \App\Models\Region::firstOrCreate(['name' => '台中']);
        \App\Models\Region::firstOrCreate(['name' => '雲嘉']);
        \App\Models\Region::firstOrCreate(['name' => '高雄']);
        \App\Models\Region::firstOrCreate(['name' => '新竹']);
        \App\Models\Region::firstOrCreate(['name' => '台南']);
        \App\Models\Region::firstOrCreate(['name' => '桃園']);
        \App\Models\Region::firstOrCreate(['name' => '其他']);
        \App\Models\Region::firstOrCreate(['name' => '北部']);
        \App\Models\Region::firstOrCreate(['name' => '中部']);
        \App\Models\Region::firstOrCreate(['name' => '南部']);
        \App\Models\Region::firstOrCreate(['name' => '東部']);
        \App\Models\Region::firstOrCreate(['name' => '金馬']);
        \App\Models\Region::firstOrCreate(['name' => '北區']);
        \App\Models\Region::firstOrCreate(['name' => '中區']);
        \App\Models\Region::firstOrCreate(['name' => '基隆']);
        \App\Models\Region::firstOrCreate(['name' => '桃區']);
        \App\Models\Region::firstOrCreate(['name' => '竹區']);
        \App\Models\Region::firstOrCreate(['name' => '高屏']);
        \App\Models\Region::firstOrCreate(['name' => '北苑']);
    }
};
