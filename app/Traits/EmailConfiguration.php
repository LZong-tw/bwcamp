<?php

namespace App\Traits;

trait EmailConfiguration {
    public static function setEmail($camp) {
        $config = \Config::get('mail.' . $camp);
        if($config){
            config([
                'mail.mailers.smtp.username' => $config['username'],
                'mail.mailers.smtp.password' => $config['password'],
                'mail.from.address' => $config['address'],
            ]);
        }
        $app = \App::getInstance();
        $app->register('Illuminate\Mail\MailServiceProvider');
    }
}