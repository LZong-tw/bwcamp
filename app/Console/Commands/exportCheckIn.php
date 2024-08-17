<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\SheetController;

class exportCheckIn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:CheckIn {camp_id} {--renew=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新報到資訊至GS頁面';

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
        $this->request->renew = $this->option('renew');
        $this->sheetControl->exportGSCheckIn($this->request);
        return 0;
    }
}
