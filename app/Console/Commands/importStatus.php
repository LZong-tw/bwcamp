<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\SheetController;

class importStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:Status {camp_id} {--app_id=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '回填GF問卷結果至系統';

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
        $this->request->app_id = $this->option('app_id');
        $this->sheetControl->importGSStatus($this->request);
        return 0;
    }
}
