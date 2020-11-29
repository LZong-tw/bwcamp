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
    protected $signature = 'check:Accounting {camp} {--file=}';

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
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 1. FTP 初始化
        $ftp = \Storage::createFtpDriver([
            'host'     => '203.67.41.68',
            'username' => 'user040',
            'password' => '01120085',
            'port'     => '21',
            'passive'  => '1',
            'timeout'  => '30',
        ]); 
        // 當日對帳檔檔名格式
        $filenamePattern = \Carbon\Carbon::now()->format("Ymd") . config('camps_payments.' . $this->argument('camp') . '.對帳檔檔名後綴'); 
        $filename = '';
        if(!$this->option('file')){
            // 2. 取得檔案列表
            $fileList = $ftp->files();
            // 3. 比對檔名，符合即下載（正常情況下只會有一個檔案）
            foreach($fileList as $fileName){
                if(\Str::contains($fileName, $filenamePattern) && !\Str::contains($fileName, ".END")){
                    $content = $ftp->get($fileName);
                    $filename = $fileName;
                    \Storage::disk('local')->put('payment_data/' . $filename, $content);
                }
            }
        }
        else{
            $filename = $this->option('file');
        }
        // 4. Parse 對帳資料
        try{
            $records = \Storage::disk("local")->get('payment_data/' . $filename);
        }
        catch(\Exception $e){
            logger($e);
            $this->info("找不到檔案");   
            return;
        }
        $records = \Str::of($records)->explode(PHP_EOL);
        $type = "";
        $fileData = "";
        $sum = 0;
        $total = 0;
        $this->info("開始讀檔 : storage/payment_data/" . $filename);
        $mailContent = "開始讀檔 : storage/payment_data/" . $filename . "\n";
        $arrayList = array();
        foreach($records as $record){
            if(\Str::length($record) > 0) {
                $type = \Str::substr($record, 0, 1);
                switch($type) { 
                    case 1: // 首筆
                        if(\Str::length($record) == 9) {
                            $fileData = \Str::substr($record, 1, 8);
                            $this->info('BankFileParsing : 檔案日期 = ' . $fileData);
                        } else {
                            $this->info('BankFileParsing : 首筆 length != 9');
                            $mailContent .= "BankFileParsing : 首筆 length != 9\n";
                        }
                        break;
                    case 2: // 明細
                        if(\Str::substr($record, 30, 1) == config('camps_payments.' . $this->argument('camp') . '.銷帳流水號前1碼')){
                            if(\Str::length($record) == 48) {
                                $accountData = [];
                                $accountData["代收類別"] = (string)\Str::of(\Str::substr($record, 1, 6))->trim(' ');
                                $accountData["入帳日期"] = \Str::substr($record, 7, 8);
                                $accountData["繳費日期"] = \Str::substr($record, 15, 8);
                                $accountData["銷帳流水號"] = \Str::substr($record, 30, 6);
                                $accountData["銷帳帳號"] = \Str::substr($record, 23, 14);
                                $accountData["繳款金額"] = \Str::substr($record, 39, 9);
                                $arrayList[] = $accountData;
                            } else {
                                $this->info('BankFileParsing : 明細 length != 48');
                                $mailContent .= "BankFileParsing : 明細 length != 48\n";
                            } 
                        }                        
                        break; 
                    case 3: // 結尾
                        if(\Str::length($record) == 25) {
                            $sum = \Str::substr($record, 1, 15);
                            $total = \Str::substr($record, 15, 25);
                            $this->info('BankFileParsing : 總金額 = ' . $sum);
                            $this->info('BankFileParsing : 總筆數 = ' . $total);
                        } else {
                            $this->info("BankFileParsing : 明細 length != 25");
                            $mailContent .= "BankFileParsing : 明細 length != 25\n";
                        } 
                        break;
                }
            }
        }   
        $this->info("讀檔完畢");   
        $mailContent .= "讀檔完畢\n"; 
        // 5. 將對帳資料寫入資料庫
        if(count($arrayList) > 0){
            try{
                foreach($arrayList as $item) {
                    \DB::table('accounting_scsb')->insert(
                        ['name' => array_key_exists($item["代收類別"], config('camps_payments.' . $this->argument('camp') . '.scsb_enum')) ? config('camps_payments.' . $this->argument('camp') . '.scsb_enum')[$item["代收類別"]] : $item["代收類別"], 
                         'creddited_at' => \Carbon\Carbon::createFromFormat('Ymd', $item["入帳日期"]),
                         'paid_at' => \Carbon\Carbon::createFromFormat('Ymd', $item["繳費日期"]),
                         'accounting_sn' => $item["銷帳流水號"],
                         'accounting_no' => $item["銷帳帳號"],                         
                         'amount' => $item["繳款金額"],
                         'created_at' => \Carbon\Carbon::now(),
                         'updated_at' => \Carbon\Carbon::now(),]
                    );
                }
                $this->info("資料庫寫入完成");   
                $mailContent .= "資料庫寫入完成\n";
            }
            catch(\Exception $e){
                logger($e);
                $this->info("資料庫寫入錯誤");   
                $mailContent .= "資料庫寫入錯誤\n";
            } 
        }
        else{
            $this->info("今日無營隊所屬入帳資料");
            $mailContent .= "今日無營隊所屬入帳資料\n";
        }
        // 6. 對帳資料 raw 檔改名 
        \Storage::move('payment_data/' . $filename, 'payment_data/history/' . $filename);
        // 7. 寄出通知信
        $emails = config('camps_payments.' . $this->argument('camp') . '.email');
        foreach($emails as $email){
            \Mail::send([], [], function ($message) use ($email, $fileData, $mailContent){
                $message->to($email)
                  ->subject($this->argument('camp') . " 上海銀行自動對帳結果 - 檔案日期: " . $fileData)
                  ->setBody($mailContent); 
            });
        }
        return 0;
    }
}
