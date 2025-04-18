<?php

namespace App\Traits;

trait EmailConfiguration {
    public static function setEmail($camp, $variant = null) {
        if($variant){ $camp = $variant; }
        $config = \Config::get('mail.ses_' . $camp);    //get mail.ses_$camp version
        if (!$config || !$config['username']) {
            $config = \Config::get('mail.' . $camp);    //if null, get mail.$camp version
        }
        else {
            config(['mail.mailers.smtp.host' => $config['host']]);  //mail.ses_$camp uses AWS SES
        }
        if($config && $config['username']){     //override
            config([
                'mail.mailers.smtp.username' => $config['username'],
                'mail.mailers.smtp.password' => $config['password'],
                'mail.from.address' => $config['address'],
                'mail.from.name' => $config['name'],
            ]);
        }
        elseif (app()->isLocal()) {
            config([
                'mail.mailers.smtp.username' => env('MAIL_USERNAME'),
                'mail.mailers.smtp.password' => env('MAIL_PASSWORD'),
                'mail.mailers.smtp.host' => env('MAIL_HOST'),
                'mail.from.address' => env('MAIL_FROM_ADDRESS'),
                'mail.from.name' => env('MAIL_FROM_NAME'),
            ]);
        }
        app()->register('Illuminate\Mail\MailServiceProvider');
        if (app()->isLocal()) {
            app()->register(\App\Overrides\HeloLaravelServiceProvider::class);
        }
    }
}
