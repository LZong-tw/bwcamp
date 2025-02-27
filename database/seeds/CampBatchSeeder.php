<?php

use Illuminate\Database\Seeder;

class CampBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $camp = App\Models\Camp::create([
            'fullName' => '測試105屆大專青年生命成長營',
            'abbreviation' => '105屆大專營',
            'icon' => '',
            'table' => 'ycamp',
            'registration_start' => '2020-09-01',
            'registration_end' => '2020-09-30',
            'admission_announcing_date' => '2020-10-07',
            'admission_confirming_end' => '2020-10-15'
        ]);
        \DB::table('batchs')->insert([
            'camp_id' => $camp->id, 
            'name' => '測試大學',
            'batch_start' => '2020-12-01',
            'batch_end' => '2020-12-11'
        ]);

        $camp = App\Models\Camp::create([
            'fullName' => '第20屆測試教師營',
            'abbreviation' => '20屆教師營',
            'icon' => '',
            'table' => 'tcamp',
            'registration_start' => '2020-09-01',
            'registration_end' => '2020-09-30',
            'admission_announcing_date' => '2020-10-07',
            'admission_confirming_end' => '2020-10-15'
        ]);
        \DB::table('batchs')->insert([
            'camp_id' => $camp->id, 
            'name' => '台北場',
            'batch_start' => '2020-12-01',
            'batch_end' => '2020-12-11'
        ]);
        \DB::table('batchs')->insert([
            'camp_id' => $camp->id, 
            'name' => '高雄場',
            'batch_start' => '2020-12-06',
            'batch_end' => '2020-12-13'
        ]);
    }
}
