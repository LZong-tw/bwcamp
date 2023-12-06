<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send any email
    | messages sent by your application. Alternative mailers may be setup
    | and used as needed; however, this mailer will be used by default.
    |
    */

    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers to be used while
    | sending an e-mail. You will specify which one you are using for your
    | mailers below. You are free to add additional mailers as required.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses",
    |            "postmark", "log", "array"
    |
    */

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'auth_mode' => null,
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'mailgun' => [
            'transport' => 'mailgun',
        ],

        'postmark' => [
            'transport' => 'postmark',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => '/usr/sbin/sendmail -bs',
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all e-mails sent by your application to be sent from
    | the same address. Here, you may specify a name and address that is
    | used globally for all e-mails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        //'address' => env('MAIL_USERNAME', 'hello@example.com'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

    'acamp' => [
        'address' => env('ACAMP_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('ACAMP_MAIL_USERNAME'),
        'password' => env('ACAMP_MAIL_PASSWORD'),
        'name' => env('ACAMP_MAIL_FROM_NAME'),
    ],

    'avcamp' => [
        'address' => env('AVCAMP_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('AVCAMP_MAIL_USERNAME'),
        'password' => env('AVCAMP_MAIL_PASSWORD'),
        'name' => env('AVCAMP_MAIL_FROM_NAME'),
    ],

    'ceocamp' => [
        'address' => env('CEOCAMP_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('CEOCAMP_MAIL_USERNAME'),
        'password' => env('CEOCAMP_MAIL_PASSWORD'),
        'name' => env('CEOCAMP_MAIL_FROM_NAME'),
    ],

    'ceovcamp' => [
        'address' => env('CEOVCAMP_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('CEOVCAMP_MAIL_USERNAME'),
        'password' => env('CEOVCAMP_MAIL_PASSWORD'),
        'name' => env('CEOVCAMP_MAIL_FROM_NAME'),
    ],

    'ecamp' => [
        'address' => env('ECAMP_MAIL_USERNAME', 'hello@example.com'),
        'username' => env('ECAMP_MAIL_USERNAME'),
        'password' => env('ECAMP_MAIL_PASSWORD'),
        'name' => env('ECAMP_MAIL_FROM_NAME'),
    ],

    'evcamp' => [
        'address' => env('EVCAMP_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('EVCAMP_MAIL_USERNAME'),
        'password' => env('EVCAMP_MAIL_PASSWORD'),
        'name' => env('EVCAMP_MAIL_FROM_NAME'),
    ],

    'hcamp' => [
        'address' => env('HCAMP_MAIL_USERNAME', 'hello@example.com'),
        'username' => env('HCAMP_MAIL_USERNAME'),
        'password' => env('HCAMP_MAIL_PASSWORD'),
        'name' => env('HCAMP_MAIL_FROM_NAME'),
    ],

    'icamp' => [
        'address' => env('ICAMP_MAIL_USERNAME', 'hello@example.com'),
        'username' => env('ICAMP_MAIL_USERNAME'),
        'password' => env('ICAMP_MAIL_PASSWORD'),
        'name' => env('ICAMP_MAIL_FROM_NAME'),
    ],

    'ses_icamp' => [
        'host' => env('SES_MAIL_HOST'),
        'address' => env('SES_ICAMP_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('SES_ICAMP_MAIL_USERNAME'),
        'password' => env('SES_ICAMP_MAIL_PASSWORD'),
        'name' => env('SES_ICAMP_MAIL_FROM_NAME'),
    ],

    'ivcamp' => [
        'address' => env('IVCAMP_MAIL_USERNAME', 'hello@example.com'),
        'username' => env('IVCAMP_MAIL_USERNAME'),
        'password' => env('IVCAMP_MAIL_PASSWORD'),
        'name' => env('IVCAMP_MAIL_FROM_NAME'),
    ],

    'ses_ivcamp' => [
        'host' => env('SES_MAIL_HOST'),
        'address' => env('SES_IVCAMP_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('SES_IVCAMP_MAIL_USERNAME'),
        'password' => env('SES_IVCAMP_MAIL_PASSWORD'),
        'name' => env('SES_IVCAMP_MAIL_FROM_NAME'),
    ],

    'tcamp' => [
        'address' => env('TCAMP_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('TCAMP_MAIL_USERNAME'),
        'password' => env('TCAMP_MAIL_PASSWORD'),
        'name' => env('TCAMP_MAIL_FROM_NAME'),
    ],

    'ses_tcamp' => [
        'host' => env('SES_MAIL_HOST'),
        'address' => env('SES_TCAMP_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('SES_TCAMP_MAIL_USERNAME'),
        'password' => env('SES_TCAMP_MAIL_PASSWORD'),
        'name' => env('SES_TCAMP_MAIL_FROM_NAME'),
    ],

    'tvcamp' => [
        'address' => env('TVCAMP_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('TVCAMP_MAIL_USERNAME'),
        'password' => env('TVCAMP_MAIL_PASSWORD'),
        'name' => env('TVCAMP_MAIL_FROM_NAME'),
    ],

    'utcamp' => [
        'address' => env('UTCAMP_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('UTCAMP_MAIL_USERNAME'),
        'password' => env('UTCAMP_MAIL_PASSWORD'),
        'name' => env('UTCAMP_MAIL_FROM_NAME'),
    ],

    'utvcamp' => [
        'address' => env('UTVCAMP_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('UTVCAMP_MAIL_USERNAME'),
        'password' => env('UTVCAMP_MAIL_PASSWORD'),
        'name' => env('UTVCAMP_MAIL_FROM_NAME'),
    ],

    'ycamp' => [
        'address' => env('YCAMP_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('YCAMP_MAIL_USERNAME'),
        'password' => env('YCAMP_MAIL_PASSWORD'),
        'name' => env('YCAMP_MAIL_FROM_NAME'),
    ],

    'yvcamp' => [
        'address' => env('YVCAMP_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('YVCAMP_MAIL_USERNAME'),
        'password' => env('YVCAMP_MAIL_PASSWORD'),
        'name' => env('YVCAMP_MAIL_FROM_NAME'),
    ],

    'coupon' => [
        'address' => env('COUPON_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('COUPON_MAIL_USERNAME'),
        'password' => env('COUPON_MAIL_PASSWORD'),
        'name' => env('COUPON_MAIL_FROM_NAME'),
    ],

    'ses_coupon' => [
        'host' => env('SES_MAIL_HOST'),
        'address' => env('SES_COUPON_MAIL_FROM_ADDRESS', 'hello@example.com'),
        'username' => env('SES_COUPON_MAIL_USERNAME'),
        'password' => env('SES_COUPON_MAIL_PASSWORD'),
        'name' => env('SES_COUPON_MAIL_FROM_NAME'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    |
    | If you are using Markdown based email rendering, you may configure your
    | theme and component paths here, allowing you to customize the design
    | of the emails. Or, you may simply stick with the Laravel defaults!
    |
    */

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
