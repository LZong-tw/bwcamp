<?php

return [
   
    'general' => [
        "超商代收代號" => "6W5",
        "7碼收款人帳戶" => "4090615",
        "銀行檢查碼權數" => "123987614321",
    ],


    // 上海銀行對帳 FTP（由環境變數提供，避免明文寫入原始碼/版本控制）
    'scsb_ftp' => [
        'host' => env('SCSB_FTP_HOST'),
        'username' => env('SCSB_FTP_USERNAME'),
        'password' => env('SCSB_FTP_PASSWORD'),
        'port' => env('SCSB_FTP_PORT', 21),
        'passive' => env('SCSB_FTP_PASSIVE', true),
        'timeout' => env('SCSB_FTP_TIMEOUT', 30),
        'root' => env('SCSB_FTP_ROOT', ''),
    ],
    'ycamp' => [
        "銷帳流水號前1碼" => "5",
    ],

    'tcamp' => [
        "銷帳流水號前1碼" => "5",
        "對帳檔檔名後綴" => "-FESNET-M011200894950615-FD913",
    ],
];
