<?php

namespace App\Traits;

trait EmailConfiguration {
    public function setEmail() {
        if($this->applicant->batch->camp->table == "hcamp"){
            $config = \Config::get('mail.hcamp');
            config([
                'mail.mailers.smtp.username' => $config['username'],
                'mail.mailers.smtp.password' => $config['password'],
                'mail.from.address' => $config['address'],
            ]);
            // $app = \App::getInstance();

            // $app->singleton('swift.transport', function ($app) {
            //     return new \Illuminate\Mail\MailManager($app);
            // });
            
            // $mailer = new \Swift_Mailer($app['swift.transport']->driver());
            // \Mail::setSwiftMailer($mailer);
        }
    }
}