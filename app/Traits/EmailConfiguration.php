<?php

namespace App\Traits;

trait EmailConfiguration {
    public static function setEmail($camp, $variant = null) {
        if($variant){ $camp = $variant; }
        $config = \Config::get('mail.ses_' . $camp);    //get mail.ses_$camp version
        if (!$config) {
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
        app()->register('Illuminate\Mail\MailServiceProvider');
        if (app()->isLocal()) {
            app()->register(\App\Overrides\HeloLaravelServiceProvider::class);
        }
    }
}