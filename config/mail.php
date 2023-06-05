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
        // 'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'address' => env('MAIL_USERNAME', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

    'tcamp' => [
        'address' => env('TCAMP_MAIL_USERNAME', 'hello@example.com'),
        'username' => env('TCAMP_MAIL_USERNAME'),
        'password' => env('TCAMP_MAIL_PASSWORD'),
    ],

    'utcamp' => [
        'address' => env('UTCAMP_MAIL_USERNAME', 'hello@example.com'),
        'username' => env('UTCAMP_MAIL_USERNAME'),
        'password' => env('UTCAMP_MAIL_PASSWORD'),
    ],

    'hcamp' => [
        'address' => env('HCAMP_MAIL_USERNAME', 'hello@example.com'),
        'username' => env('HCAMP_MAIL_USERNAME'),
        'password' => env('HCAMP_MAIL_PASSWORD'),
    ],

    'ceocamp' => [
        'address' => env('CEOCAMP_MAIL_USERNAME', 'hello@example.com'),
        'username' => env('CEOCAMP_MAIL_USERNAME'),
        'password' => env('CEOCAMP_MAIL_PASSWORD'),
    ],

    'ceovcamp' => [
        'address' => env('CEOVCAMP_MAIL_USERNAME', 'hello@example.com'),
        'username' => env('CEOVCAMP_MAIL_USERNAME'),
        'password' => env('CEOVCAMP_MAIL_PASSWORD'),
    ],

    'ecamp' => [
        'address' => env('ECAMP_MAIL_USERNAME', 'hello@example.com'),
        'username' => env('ECAMP_MAIL_USERNAME'),
        'password' => env('ECAMP_MAIL_PASSWORD'),
    ],

    'evcamp' => [
        'address' => env('EVCAMP_MAIL_USERNAME', 'hello@example.com'),
        'username' => env('EVCAMP_MAIL_USERNAME'),
        'password' => env('EVCAMP_MAIL_PASSWORD'),
    ],

    'ycamp' => [
        'address' => env('YCAMP_MAIL_USERNAME', 'hello@example.com'),
        'username' => env('YCAMP_MAIL_USERNAME'),
        'password' => env('YCAMP_MAIL_PASSWORD'),
    ],

    'acamp' => [
        'address' => env('ACAMP_MAIL_USERNAME', 'hello@example.com'),
        'username' => env('ACAMP_MAIL_USERNAME'),
        'password' => env('ACAMP_MAIL_PASSWORD'),
    ],

    'coupon' => [
        'address' => env('COUPON_MAIL_USERNAME', 'hello@example.com'),
        'username' => env('COUPON_MAIL_USERNAME'),
        'password' => env('COUPON_MAIL_PASSWORD'),
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
