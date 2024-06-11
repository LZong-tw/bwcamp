<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Services\PaymentflowService;
use App\Models\Camp;
use App\Models\Applicant;

class genBankSecondBarcode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:BankSecondBarcode {camp_id} {--app_id=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '一次產生某營隊中所有報名者的虛擬帳號';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
    ) {
        parent::__construct();
        return;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $camp = Camp::find($this->argument('camp_id'));
        $applicants = $camp->applicants;
        $table = $camp->table;

        try {
            $config_camp = config('camps_payments.' . $table);
        } catch(\Exception $e) {
            logger($e);
            return;
        }

        $data = array_merge(config('camps_payments.general'), $config_camp);

        foreach ($applicants as $applicant) {
            $data["銷帳編號"] = $data["銷帳流水號前1碼"] . str_pad($applicant->id, 5, '0', STR_PAD_LEFT);
            $paymentflow = new paymentflowService($data);
            $applicant->bank_second_barcode = $paymentflow->getBankSecondBarcode();
            $applicant->save();
            unset($paymentflow);
        }
        return 0;
    }
}
