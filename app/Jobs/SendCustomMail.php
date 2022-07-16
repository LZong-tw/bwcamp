<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\EmailConfiguration;
use Illuminate\Queue\Middleware\WithoutOverlapping;

/**
 *
 * 本 Job 未使用
 *
 */

class SendCustomMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, EmailConfiguration;

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
        /**
         *
         * 本 Job 未使用
         *
         */
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
        \Mail::to($this->receiver)->send(new \App\Mail\CustomMail($this->subject, $this->content, $this->attachment, $this->receiver->batch->camp->variant ?? $this->receiver->batch->camp->table));
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware() {
        return [new WithoutOverlapping($this->applicant->batch->camp->id)];
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->applicant->id;
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addMinutes(60);
    }
}
