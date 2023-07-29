<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject, $content, $attachment, $camp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $content, $attachment, $campOrVariant) {
        //
        $this->subject = $subject;
        $this->content = $content;
        $this->attachment = $attachment;
        $this->camp = $campOrVariant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        logger( \Config::get('mail'));        
        if(!str_contains(config('mail.mailers.smtp.host'),"amazon")) {
            sleep(10);
        }
        $this->withSwiftMessage(function ($message) {
            $headers = $message->getHeaders();
            $headers->addTextHeader('time', time());
        });
        $email = $this->subject($this->subject)->view("backend.other.customMailView");
        $attachmentCount = count($this->attachment);
        $attachments = null;
        if($attachmentCount > 0){
            foreach($this->attachment as $attachment){
                $attachments[storage_path('attachment/' . $attachment)]['as'] = \Str::substr($attachment, 10);
                $attachments[storage_path('attachment/' . $attachment)]['mime'] = \Storage::mimeType('attachment/' . $attachment);
            }
            foreach($attachments as $filePath => $fileParameters){
                $email->attach($filePath, $fileParameters);
            }
        }
        return $email;
    }
}
