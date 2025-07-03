<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 開發和測試環境的基本種子資料
        if (app()->environment(['local', 'testing'])) {
            $this->call('TestDataSeeder');
        } else {
            // 原有的種子資料（僅在正式環境）
            $this->call('CampBatchSeeder');
            $this->command->info('Camp and batch seeded!');
        }
    }
}
