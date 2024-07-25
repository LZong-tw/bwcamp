<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\SheetController;

class importStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //ds_id: dynamic_stat_id
    //is_app: import applicant or not
    //is_org: assign org or not
     protected $signature = 'import:Applicant {camp_id} {ds_id} {--is_app=1} {--is_org=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '匯入報名者並指定職務';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        SheetController $sheetController,
        Request $request
    ) {
        parent::__construct();
        $this->sheetControl = $sheetController;
        $this->request = $request;
        return;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->request->camp_id = $this->argument('camp_id');
        $this->request->ds_id = $this->argument('ds_id');
        $this->request->is_app = $this->option('is_app');
        $this->request->is_org = $this->option('is_org');
        $this->sheetControl->importGSApplicants($this->request);
        return 0;
    }
}
