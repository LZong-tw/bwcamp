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
    'ceocamp' => [
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
            "ceo.camp@blisswisdom.org",
        ]
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
            "新竹專車" => "270",
            "台中專車" => "200",
            "台南專車" => "250",
            "高雄專車" => "400",
            "火車站接駁車" => "0",
            "親友接送" => "0",
            "自往" => "0",
        ],
        "nycamp" => [
            "Shuttle Bus 接駁服務" => "35",
            "Drive 開車" => "0",
            "Drop off 自往" => "0",
        ],
    ],

    'fare_back_to' => [
        "ycamp" => [
            "台北專車" => "400",
            "桃園專車" => "350",
            "新竹專車" => "270",
            "台中專車" => "200",
            "台南專車" => "250",
            "高雄專車" => "400",
            "火車站接駁車" => "0",
            "親友接送" => "0",
            "自回" => "0",
        ],
        "nycamp" => [
            "Shuttle Bus 接駁服務" => "35",
            "Drive 開車" => "0",
            "Pick up 自回" => "0",
        ],

    ],

    'fare_room' => [
        /* 2024
        "ceocamp" => [
            "單人房" => "3650",
            "雙人房" => "2000",
            "不住宿" => "0",
            "尚未調查" => "0",
        ],*/
        "ceocamp" => [
            "單人房" => "4800",
            "雙人房" => "2400",
            "不住宿" => "0",
            "尚未調查" => "0",
        ],
        "utcamp" => [
            "雙人房" => "6000",
            "單人房" => "8000",
            "優惠價" => "5000",
        ],
        "nycamp_earlybird" => [
            "Early Bird 早鳥價" => "450",
            "Early Bird_Single Room 早鳥價_單人房" => "660",
        ],
        "nycamp_discount" => [
            "Discount 優惠價" => "499",
            "Discount_Single Room 優惠價_單人房" => "709",
        ],
        "nycamp" => [
            "Regular 活動費" => "720",
            "Regular_Single Room 活動費_單人房" => "950",
        ],
    ],

];
