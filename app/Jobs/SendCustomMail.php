<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * 
 * 本 Job 未使用
 * 
 */

class SendCustomMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $receiver, $subject, $content, $attachment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($receiver, $subject, $content, $attachment)
    {
        //
        $this->receiver = \App\Models\Applicant::find($receiver);
        $this->subject = $subject;
        $this->content = $content;
        $this->attachment = $attachment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        sleep(10);
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 180);
        \Mail::to($this->receiver)->send(new \App\Mail\CustomMail($this->subject, $this->content, $this->attachment));
    }
}
