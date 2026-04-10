<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\EmailConfiguration;
use App\Models\Traffic;
use App\Models\Lodging;
use Carbon\Carbon;

class CheckPayment extends Command
{
    use EmailConfiguration;

    /**
     * The name and signature of the console command.
     * 如要使用 --file, 請將檔案存放在 /storage/payment_data 中
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

    protected $arrayList;
    protected $errMessage;
    protected $filename;
    protected $fileDate;
    protected $mailContent;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->arrayList = array();
        $this->errMessage = "";
        $this->filename = "";
        $this->fileDate = \Carbon\Carbon::now()->format("Ymd");
        $this->mailContent = "";
        return;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 1. FTP 下載檔案
        if (!$this->option('file')) {
            $this->ftpFilesFromServer();
            if ($this->errMessage !== "") {
                $this->sendNotificationEmail();
                return;
            }
        } else {
            $this->filename = $this->option('file');
        }

        // 2. 檢查檔案存在否(--file)
        $path = 'payment_data/' . $this->filename;
        if (\Storage::disk('local')->exists($path)) {
            $records = \Storage::disk("local")->get($path);
        } else {
            $this->error("找不到檔案: {$path}。");
            return;
        }

        // 3. Parse 對帳資料
        $this->info("開始讀檔: storage/payment_data/" . $this->filename);
        $this->mailContent .= "開始讀檔: storage/payment_data/" . $this->filename . "\n";

        //this function updates $this->arrayList
        $this->parseTransactionRecords($records);

        $this->info("讀檔完畢。");
        $this->mailContent .= "讀檔完畢。\n";

        // 4. 將對帳資料寫入資料庫
        if (count($this->arrayList) > 0) {
            try {
                $arrayList = $this->arrayList;
                // 使用資料庫事務：只要裡面噴錯，所有變動都會收回（Rollback）
                \DB::transaction(function () use ($arrayList) {
                    foreach ($arrayList as $item) {
                        $this->processPaymentItem($item);
                    }
                });

                $this->info("資料庫所有資料寫入完成");
                $this->mailContent .= "資料庫寫入完成\n";
            } catch (\Exception $e) {
                // 記錄詳細錯誤到 Log
                \Log::error("[check:Accounting] 資料庫寫入錯誤:" . $e->getMessage(), ['exception' => $e]);

                // 終端機顯示紅色的錯誤訊息與行號
                $this->error("資料庫寫入錯誤: " . $e->getMessage() . " (Line: " . $e->getLine() . ")");
                $this->mailContent .= "資料庫寫入錯誤，已停止執行。\n";
            }
        } else {
            $this->info("今日無營隊所屬入帳資料。");
            $this->mailContent .= "今日無營隊所屬入帳資料。\n";
        }

        // 5. archive 對帳資料
        \Storage::move('payment_data/' . $this->filename, 'payment_data/history/' . $this->filename);

        // 6. 寄出通知信
        $this->sendNotificationEmail();
        return 0;
    }

    private function ftpFilesFromServer()
    {
        // 1. FTP 初始化
        $ftp = \Storage::createFtpDriver([
            'host'     => config('camps_payments.scsb_ftp.host'),
            'username' => config('camps_payments.scsb_ftp.username'),
            'password' => config('camps_payments.scsb_ftp.password'),
            'port'     => 21,
            'passive'  => true,
            'timeout'  => 30,
        ]);
        // 當日對帳檔檔名格式
        $filenamePattern = \Carbon\Carbon::now()->format("Ymd") . config('camps_payments.' . $this->argument('camp') . '.對帳檔檔名後綴');
        try {
            // 2. 取得檔案列表
            $fileList = $ftp->files();
            // 3. 比對檔名，符合即下載（正常情況下只會有一個檔案）
            foreach ($fileList as $fileName) {
                if (\Str::contains($fileName, $filenamePattern) && !\Str::contains($fileName, ".END")) {
                    $content = $ftp->get($fileName);
                    $this->filename = $fileName;
                    \Storage::disk('local')->put('payment_data/' . $this->filename, $content);
                    //一天只會有一個檔案，應該可以結束?
                    //if not break：$this->filename會是最後一個符合條件的檔，但之前每個檔都會下載。
                    //break;
                }
            }
        } catch (\Exception $e) {
            // 記錄詳細錯誤到 Log
            \Log::error("[check:Accounting] FTP下載檔案失敗: " . $e->getMessage(), ['exception' => $e]);
            // 終端機顯示紅色的錯誤訊息與行號
            $this->error("FTP下載檔案失敗: " . $e->getMessage());
            $this->mailContent = "FTP下載檔案失敗，已停止執行。\n";
            $this->errMessage = $this->mailContent;
        }
        return;
    }

    private function parseTransactionRecords($records)
    {
        $type = "";
        $sum = 0;
        $total = 0;

        //讀入檔案並分行
        $records = \Str::of($records)->explode(PHP_EOL);
        foreach ($records as $record) {
            if (\Str::length($record) > 0) {
                $type = \Str::substr($record, 0, 1);
                switch ($type) {
                    case 1: // 首筆
                        if (\Str::length($record) == 9) {
                            $this->fileDate = \Str::substr($record, 1, 8);
                            $this->info('BankFileParsing : 檔案日期 = ' . $this->fileDate);
                        } else {
                            $this->info('BankFileParsing : 首筆 length != 9');
                            $this->mailContent .= "BankFileParsing : 首筆 length != 9\n";
                        }
                        break;
                    case 2: // 明細
                        if (\Str::substr($record, 30, 1) == config('camps_payments.' . $this->argument('camp') . '.銷帳流水號前1碼')) {
                            if (\Str::length($record) == 48) {
                                $accountData = [];
                                $accountData["代收類別"] = (string)\Str::of(\Str::substr($record, 1, 6))->trim(' ');
                                $accountData["入帳日期"] = \Str::substr($record, 7, 8);
                                $accountData["繳費日期"] = \Str::substr($record, 15, 8);
                                $accountData["銷帳流水號"] = \Str::substr($record, 30, 6);
                                $accountData["銷帳帳號"] = \Str::substr($record, 23, 14);
                                $accountData["繳款金額"] = \Str::substr($record, 39, 9);
                                $this->arrayList[] = $accountData;
                            } else {
                                $this->info('BankFileParsing : 明細 length != 48');
                                $this->mailContent .= "BankFileParsing : 明細 length != 48\n";
                            }
                        }
                        break;
                    case 3: // 結尾
                        if (\Str::length($record) == 25) {
                            $sum = \Str::substr($record, 1, 14);
                            $total = \Str::substr($record, 15, 10);
                            $this->info('BankFileParsing : 總金額 = ' . $sum);
                            $this->info('BankFileParsing : 總筆數 = ' . $total);
                        } else {
                            $this->info("BankFileParsing : 明細 length != 25");
                            $this->mailContent .= "BankFileParsing : 明細 length != 25\n";
                        }
                        break;
                    default:
                        break;
                }
            }
        }
    }

    /**
     * 拆分出的私有方法：專注處理單筆資料寫入
     */
    private function processPaymentItem(array $item)
    {
        $camp = $this->argument('camp');
        $scsbEnum = config("camps_payments.{$camp}.scsb_enum");

        // 1. 寫入 accounting_scsb 表
        \DB::table('accounting_scsb')->insert([
            'name'          => $scsbEnum[$item["代收類別"]] ?? $item["代收類別"],
            'creditted_at'  => \Carbon\Carbon::createFromFormat('Ymd', $item["入帳日期"]),
            'paid_at'       => \Carbon\Carbon::createFromFormat('Ymd', $item["繳費日期"]),
            'accounting_sn' => $item["銷帳流水號"],
            'accounting_no' => $item["銷帳帳號"],
            'amount'        => $item["繳款金額"],
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // 2. 尋找對應的申請人
        $applicant = \App\Models\Applicant::where('bank_second_barcode', $item["銷帳帳號"])
            ->orderBy("id", "desc")
            ->first();

        if (!$applicant) {
            return; // 找不到人就跳過後續更新
        }

        // 3. 更新申請人金額與狀態
        $applicant->deposit += $item["繳款金額"];
        $camp_table = $applicant->batch->camp->table;

        if ($camp_table == 'hcamp') {
            $applicant->is_admitted = 1;
        } elseif (in_array($camp_table, ['ycamp'])) {
            $this->updateTraffic($applicant);
        } elseif (in_array($camp_table, ['ceocamp', 'utcamp'])) {
            $this->updateLodging($applicant);
        }

        $applicant->save();
    }

    private function updateTraffic($applicant)
    {
        // 尋找 applicant_id 匹配的資料
        // 如果找不到，就用後方的預設值建立新資料
        $applicant->traffic()->updateOrCreate(
            ['applicant_id' => $applicant->id], // 查詢條件
            [
                'depart_from' => $applicant->traffic ? $applicant->traffic->depart_from : "自往",
                'back_to'     => $applicant->traffic ? $applicant->traffic->back_to : "自回",
                'deposit'     => $applicant->deposit,
                'cash'        => $applicant->traffic->cash ?? 0,
                'sum'         => $applicant->deposit + ($applicant->traffic->cash ?? 0),
            ]
        );
    }

    private function updateLodging($applicant)
    {
        $applicant->lodging()->updateOrCreate(
            ['applicant_id' => $applicant->id],
            [
                'room_type' => $applicant->lodging ? $applicant->lodging->room_type : "不住宿",
                'deposit'   => $applicant->deposit,
                'cash'      => $applicant->lodging->cash ?? 0,
                'sum'       => $applicant->deposit + ($applicant->lodging->cash ?? 0),
            ]
        );
    }

    private function sendNotificationEmail()
    {
        $emails = config('camps_payments.' . $this->argument('camp') . '.email');
        $fileDate = $this->fileDate;
        $mailContent = $this->mailContent;
        // 動態載入電子郵件設定
        $this->setEmail($this->argument('camp'));
        foreach ($emails as $email) {
            //laravel9
            \Mail::raw($mailContent, function ($message) use ($email, $fileDate) {
                $message->to($email)
                  ->subject($this->argument('camp') . " 上海銀行自動對帳結果 - 檔案日期: " . $fileDate);
            });
        }
    }

}
