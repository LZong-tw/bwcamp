<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\SheetController;

class exportApplicant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:Applicant {camp_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新報名者至GS頁面';

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
        $this->request->camp_id = $this->argument('camp_id') ;
        $this->sheetControl->exportGSApplicants($this->request);
        return 0;
    }
}
