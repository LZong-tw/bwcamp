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
    protected $signature = 'check:Accounting';

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
        // 1. FTP 初始化
        // 2. 下載對帳資料
        // 3. Parse 對帳資料
        // 4. 將對帳資料寫入資料庫
        // 5. 對帳資料 raw 檔改名 
        // 6. 輸出執行結果成功與否
        // 7. 寄出通知信 
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
