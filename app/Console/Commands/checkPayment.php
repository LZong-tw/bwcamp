<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class checkPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:Accounting {camp}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '對帳指令 - 上海銀行';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $ftp = \Storage::createFtpDriver([
            'host'     => '203.67.41.68',
            'username' => 'user040',
            'password' => '01120085',
            'port'     => '21',
            'passive'  => '1',
            'timeout'  => '30',
        ]); 
        // 當日對帳檔檔名
        $filename = \Carbon\Carbon::now()->format("yyyyMMdd") . config('camps_payments.' . $this->argument('camp') . '.對帳檔檔名後綴'); 
        // 2. 取得檔案列表
        $fileList = $ftp->files();
        // 3. 比對檔名，符合即下載
        foreach($fileList as $fileName){
            if(\Str::contains($fileName, $filename)){
                $ftp->download(storage_path('payment_data/' . $fileName));
            }
            // 4. 每個檔案在下載後立即 parse 對帳資料
        }
        // 5. 將對帳資料寫入資料庫
        // 6. 對帳資料 raw 檔改名 
        // 7. 輸出執行結果成功與否
        // 8. 寄出通知信 
        // 9. FTP 初始化
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return 0;
    }
}
