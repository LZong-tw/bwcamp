<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\Camp;
use App\Models\Batch;
use App\Models\Applicant;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 清除現有測試資料（測試環境）
        if (app()->environment('testing')) {
            DB::table('users')->truncate();
            DB::table('camps')->truncate();
            DB::table('batchs')->truncate();
            DB::table('applicants')->truncate();
        }

        // 建立測試用戶
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
        ]);

        // 建立測試營隊
        $camp = Camp::factory()->create();

        // 建立測試梯次
        $batch = Batch::factory()->create([
            'camp_id' => $camp->id,
            'name' => '第一梯次',
            'batch_start' => now()->addDays(50),
            'batch_end' => now()->addDays(53),
        ]);

        // 建立測試申請者
        Applicant::factory()->count(5)->create([
            'batch_id' => $batch->id,
        ]);

        // 建立已錄取的申請者
        Applicant::factory()->count(3)->create([
            'batch_id' => $batch->id,
            'is_admitted' => 1,
        ]);

        // 建立已拒絕的申請者
        Applicant::factory()->count(2)->create([
            'batch_id' => $batch->id,
            'is_admitted' => 0,
        ]);

        // 建立多個營隊類型的測試資料
        $campTypes = ['tcamp', 'ecamp', 'ceocamp', 'acamp'];
        foreach ($campTypes as $index => $campType) {
            $testCamp = Camp::factory()->create([
                'table' => $campType,
            ]);

            $testBatch = Batch::factory()->create([
                'camp_id' => $testCamp->id,
                'name' => '第一梯次',
            ]);

            // 每個營隊類型建立少量申請者
            Applicant::factory()->count(3)->create([
                'batch_id' => $testBatch->id,
            ]);
        }

        $this->command->info('測試資料種子已建立完成');
        $this->command->info('用戶數量: ' . User::count());
        $this->command->info('營隊數量: ' . Camp::count());
        $this->command->info('梯次數量: ' . DB::table('batchs')->count());
        $this->command->info('申請者數量: ' . Applicant::count());
    }
}
