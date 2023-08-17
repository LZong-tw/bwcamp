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
        "accounting_table" => "accounting_scsb",
        "銷帳流水號前1碼" => "5",
        "對帳檔檔名後綴" => "-FESNET-M011200894950615-FD913",
        "超商代收代號" => "6W5",
        "7碼收款人帳戶" => "4090615",
        "銀行檢查碼權數" => "123987614321",
        'scsb_enum' => [
            "71" => "7-11",
            "ATM" => "ATM",
            "OK" => "OK",
            "FA" => "全家",
            "HI" => "萊爾富",
        ],
        'email' => [
            "lzong.tw@gmail.com",
            "minchen.ho@blisswisdom.org",
            "youth@blisswisdom.org",
        ]
    ],

    'tcamp' => [
        "accounting_table" => "accounting_scsb",
        "銷帳流水號前1碼" => "5",
        "對帳檔檔名後綴" => "-FESNET-M011200894950615-FD913",
        "超商代收代號" => "6W5",
        "7碼收款人帳戶" => "4090615",
        "銀行檢查碼權數" => "123987614321",
        'scsb_enum' => [
            "71" => "7-11",
            "ATM" => "ATM",
            "OK" => "OK",
            "FA" => "全家",
            "HI" => "萊爾富",
        ],
        'email' => [
            "lzong.tw@gmail.com",
            "bwmedu@blisswisdom.org",
        ]
    ],

    'hcamp' => [
        "accounting_table" => "accounting_scsb",
        "銷帳流水號前1碼" => "0",
        "對帳檔檔名後綴" => "-FESNET-M011428510393316-FD913",
        "超商代收代號" => "6W5",
        "7碼收款人帳戶" => "4093316",
        "銀行檢查碼權數" => "123987614321",
        'scsb_enum' => [
            "71" => "7-11",
            "ATM" => "ATM",
            "OK" => "OK",
            "FA" => "全家",
            "HI" => "萊爾富",
        ],
        'email' => [
            "lzong.tw@gmail.com",
            "worldofethics@gmail.com"
        ]
    ],

    'fare_depart_from' => [
        "ycamp" => [
            "台北專車" => "400",
            "桃園專車" => "350",
            "新竹專車" => "250",
            "台中專車" => "200",
            "台南專車" => "200",
            "高雄專車" => "350",
            "火車站接駁車" => "0",
            "自往" => "0",
        ],
    ],

    'fare_back_to' => [
        "ycamp" => [
            "台北專車" => "400",
            "桃園專車" => "350",
            "新竹專車" => "250",
            "台中專車" => "200",
            "台南專車" => "200",
            "高雄專車" => "350",
            "火車站接駁車" => "0",
            "自回" => "0",
        ],
    ],
];
