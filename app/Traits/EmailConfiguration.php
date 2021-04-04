<?php

namespace App\Traits;

trait EmailConfiguration {
    public static function setEmail($camp) {
        if($camp == "hcamp"){
            $config = \Config::get('mail.hcamp');
            config([
                'mail.mailers.smtp.username' => $config['username'],
                'mail.mailers.smtp.password' => $config['password'],
                'mail.from.address' => $config['address'],
            ]);
        }
        $app = \App::getInstance();
        // $app->register('App\Providers\MailServiceProvider');
        $app->register('Illuminate\Mail\MailServiceProvider');
    }
}