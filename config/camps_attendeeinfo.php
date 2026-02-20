<?php

return [
    'acamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields' => [
                'photo' => '照片',
                'name'  => '中文姓名',
                'birthday' => '生日',
                'english_name' => '英文姓名',
                'gender' => '性別',
                'industry' => '產業別',
                'unit' => '公司名稱',
                'title' => '職稱',
                'job_property' => '職務類型',
                'applicant_id' => '報名序號',
                'group' => '所屬組別',
                'carers' => '關懷員',
                'participation_mode' => '參加形式',
            ],
        ],

        'sec_attend' => [
            'is_shown' => 1,
        ],
        'sec_traffic' => [
            'is_shown' => 1,
        ],
        'sec_lodging' => [
            'is_shown' => 0,
        ],
        'sec_file_upload' => [
            'is_shown' => 0,
        ],
        'sec_adv1' => [
            'is_shown' => 1,
            'title' => '聯絡方式',
            'fields' => [
                'mobile'  => '手機號碼',
                'phone_work' => '公司電話',
                'email' => '電子信箱',
                'line' => 'lineID',
                'substitute' => '代理人',
                'substitute_phone' => '代理人電話',
                'substitute_email' => '代理人電子信箱',
                'contact_time' => '適合聯絡時段',
                'address' => '地址',
            ],
        ],
        'sec_adv2' => [
            'is_shown' => 1,
            'title' => '推薦人資訊',
            'fields' => [
                'introducer_name'  => '推薦人',
                'introducer_participated' => '廣論班別',
                'introducer_phone' => '手機號碼',
                'introducer_email' => '電子信箱',
                'introducer_relationship' => '與推薦人關係',
                'reasons_recommend' => '特別推薦理由或社會影響力說明',
            ],
        ],
        'sec_adv3' => [
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'employees'  => '公司員工',
                'managed_employees' => '所轄員工',
                'capital_unit' => '資本額',
                'org_type' => '公司/組織形式',
                'years_operation' => '公司成立幾年',
                'profile_agree' => '同意個資使用',
                'portrait_agree' => '同意肖像權使用',
            ],
        ],
        'sec_interest' => [
            'is_shown' => 1,
            'title' => '關心議題',
        ],
        'sec_remark' => [
            'is_shown' => 1,
            'title' => '備註',
        ],
        'sec_contact_log' => [
            'is_shown' => 1,
            'title' => '關懷記錄',
        ],
        'sec_qrcode' => [
            'is_shown' => 1,
            'title' => '報到QRCode',
        ],
        'sec_ceovcamp_excel' => [
            'is_shown' => 0,
            'title' => '訪談記錄',
        ],
    ],
    
    'ceocamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields' => [
                'photo' => '照片',
                'name'  => '中文姓名',
                'birthday' => '生日',
                'english_name'  => '英文姓名',
                'gender' => '性別',
                'belief' => '是否已加入廣論班',
                'belief' => '廣論班別',
                'industry' => '產業別',
                'unit' => '公司名稱',
                'title' => '職稱',
                'job_property' => '職務類型',
                'applicant_id' => '報名序號',
                'group' => '所屬組別',
                'carers' => '關懷員',
                'participation_mode' => '參加形式',
            ],
        ],

        'sec_attend' => [
            'is_shown' => 1,
        ],
        'sec_traffic' => [
            'is_shown' => 1,
        ],
        'sec_lodging' => [
            'is_shown' => 1,
        ],
        'sec_file_upload' => [
            'is_shown' => 1,
        ],
        'sec_adv1' => [
            'is_shown' => 1,
            'title' => '聯絡方式',
            'fields' => [
                'mobile'  => '手機號碼',
                'phone_work' => '公司電話',
                'email' => '電子信箱',
                'line' => 'lineID',
                'substitute' => '代理人',
                'substitute_phone' => '代理人電話',
                'substitute_email' => '代理人電子信箱',
                'contact_time' => '適合聯絡時段',
                'address' => '地址',
            ],
        ],
        'sec_adv2' => [
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                'introducer_name'  => '推薦人',
                'introducer_participated' => '廣論班別',
                'introducer_phone' => '手機號碼',
                'introducer_email' => '電子信箱',
                'introducer_relationship' => '與推薦人關係',
                'reasons_recommend' => '特別推薦理由或社會影響力說明',
            ],
        ],

        'sec_adv3' => [
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'employees'  => '公司員工',
                'managed_employees' => '所轄員工',
                'capital_unit' => '資本額',
                'org_type' => '公司/組織形式',
                'years_operation' => '公司成立幾年',
                'profile_agree' => '同意個資使用',
                'portrait_agree' => '同意肖像權使用',
            ],
        ],
        'sec_interest' => [
            'is_shown' => 1,
            'title' => '關心議題',
            'fields' => [
                'employees'  => '公司員工人數',
            ],
        ],
        'sec_remark' => [
            'is_shown' => 1,
            'title' => '備註',
        ],
        'sec_qrcode' => [
            'is_shown' => 1,
            'title' => '報到條碼',
        ],
        'sec_contact_log' => [
            'is_shown' => 1,
            'title' => '關懷記錄',
        ],
       'sec_ceovcamp_excel' => [
            'is_shown' => 0,
            'title' => '訪談記錄',
        ],
    ],

    'ecamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields' => [
                'photo' => '照片',
                'name'  => '中文姓名',
                'gender' => '性別',
                'birthday' => '生日',
                'belief' => '宗教信仰',
                'education' => '最高學歷',
                'unit' => '服務單位',
                'unit_location' => '服務單位所在地',
                'title' => '職稱',
                'job_property' => '工作屬性',
                'experience' => '經歷',
                'applicant_id' => '報名序號',
                'group' => '所屬組別',
                'carers' => '關懷員',
            ],
        ],

        'sec_attend' => [
            'is_shown' => 1,
        ],
        'sec_traffic' => [
            'is_shown' => 0,
        ],
        'sec_lodging' => [
            'is_shown' => 0,
        ],
        'sec_file_upload' => [
            'is_shown' => 1,
        ],
        'sec_adv1' => [
            'is_shown' => 1,
            'title' => '聯絡方式',
            'fields' => [
                'mobile'  => '行動電話',
                'phone_work' => '公司電話',
                'phone_home' => '住家電話',
                'line' => 'lineID',
                'wechat' => '微信ID',
                'email' => '電子郵件',
                'address' => '通訊地址',
            ],
        ],
        'sec_adv2' => [
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                'emergency_name'  => '緊急聯絡人',
                'emergency_relationship' => '關係',
                'emergency_mobile' => '聯絡電話',
                'introducer_name'  => '介紹人',
                'introducer_relationship' => '關係',
                'introducer_phone' => '聯絡電話',
                'introducer_participated' => '福智班別',
            ],
        ],
        'sec_adv3' => [
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'employees'  => '公司員工人數',
                'managed_employees' => '直屬管轄人數',
                'industry' => '產業別',
                'profile_agree' => '同意個資使用',
                'portrait_agree' => '同意肖像權使用',
                'after_camp_available_day' => '方便參加課程時段',
            ],
        ],
        'sec_interest' => [
            'is_shown' => 1,
            'title' => '有興趣的活動',
            'fields' => [
                'favored_event_split'  => '有興趣的活動',
            ],
        ],
        'sec_remark' => [
            'is_shown' => 1,
            'title' => '備註',
        ],
        'sec_qrcode' => [
            'is_shown' => 1,
            'title' => '報到條碼',
        ],
        'sec_contact_log' => [
            'is_shown' => 1,
            'title' => '關懷記錄',
        ],
       'sec_ceovcamp_excel' => [
            'is_shown' => 0,
            'title' => '訪談記錄',
        ],
    ],

    //---------- mcamp --------------------
    //---------- mcamp --------------------
    //---------- mcamp --------------------

    'mcamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [  //姓名姓別年齡
                'name'  => '中文姓名',
                'gender' => '性別',
                'age' => '年齡',
            ],
            'fields2' => [  //工作
                "unit" => "服務機構",
                "title" => "職稱",
                "status" => "身分別",
            ],
            'fields3' => [  //營隊
                'applicant_id' => '報名序號',
                'created_at' => '報名日期',
                'admitted_at' => '錄取日期',
                'group' => '組別',
                'number' => '座號',
                'carers' => '關懷員',
            ],
        ],

        'sec_attend' => [
            'is_shown' => 1,
        ],
        'sec_lodging' => [
            'is_shown' => 0,
        ],
        'sec_traffic' => [
            'is_shown' => 0,
        ],
        'sec_file_upload' => [
            'is_shown' => 0,
        ],
        'sec_adv1' => [  //聯絡方式
            'is_shown' => 1,
            'title' => '聯絡方式',
            'fields' => [
                "mobile" => "聯絡電話(手機)",
                "address" => "居住縣市",
                "line" => "lineID",
                "email" => "Email",
            ],
        ],
        'sec_adv2' => [  //關係人
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                "info_source" => "得知管道",
                "introducer_name" => "介紹人",
                "expectation" => "對營隊的期待",
                "emergency_name" => "緊急聯絡人",
                "emergency_mobile" => "緊急聯絡人手機",
            ],
        ],

        'sec_adv3' => [  //其它
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                "medical_specialty" => "醫事人員職種類別",
                "work_category" => "長照人員職種類別",
                "profile_agree" => "個人資料允許",
                "portrait_agree" => "肖像權",
            ],
        ],
        'sec_interest' => [
            'is_shown' => 0,
            'title' => '關心議題',
        ],
        'sec_remark' => [
            'is_shown' => 1,
            'title' => '備註',
        ],
        'sec_qrcode' => [
            'is_shown' => 1,
            'title' => '報到條碼',
        ],
        'sec_contact_log' => [
            'is_shown' => 1,
            'title' => '關懷記錄',
        ],
       'sec_ceovcamp_excel' => [
            'is_shown' => 1,
            'title' => '訪談記錄',
        ],
    ],


    //---------- utcamp --------------------
    //---------- utcamp --------------------
    //---------- utcamp --------------------

    'utcamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [  //姓名姓別年齡
                'name'  => '中文姓名',
                'english_name'  => '英文姓名',
                'gender' => '性別',
                'age' => '年齡',
            ],
            'fields2' => [  //工作
                "unit" => "服務學校/單位",
                "unit_county" => "服務單位所在縣市",
                "department" => "服務系所/部門",
                "position" => "身分別",
                "title" => "職稱",
            ],
            'fields3' => [  //營隊
                'applicant_id' => '報名序號',
                'created_at' => '報名日期',
                'admitted_at' => '錄取日期',
                'group' => '組別',
                'number' => '座號',
                'carers' => '關懷員',
            ],
        ],

        'sec_attend' => [
            'is_shown' => 1,
        ],
        'sec_lodging' => [
            'is_shown' => 1,
        ],
        'sec_traffic' => [
            'is_shown' => 1,
        ],
        'sec_file_upload' => [
            'is_shown' => 0,
        ],
        'sec_adv1' => [  //聯絡方式
            'is_shown' => 1,
            'title' => '聯絡方式',
            'fields' => [
                "mobile" => "聯絡電話(手機)",
                "phone_home" => "聯絡電話(H)",
                "phone_work" => "聯絡電話(O)",
                "address" => "居住縣市",
                "line" => "lineID",
                "email" => "Email",
            ],
        ],
        'sec_adv2' => [  //關係人
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                "info_source" => "得知管道",
                "introducer_name" => "介紹人",
                "expectation" => "對營隊的期待",
                "emergency_name" => "緊急聯絡人",
                "emergency_mobile" => "緊急聯絡人手機",
            ],
        ],

        'sec_adv3' => [  //其它
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                "is_civil_certificate" => "公務員研習時數",
                "idno" => "身分證字號",
                "is_bwfoce_certificate" => "基金會研習證明",
                "invoice_type" => "發票類型",
                "taxid" => "統編",
                "invoice_title" => "發票抬頭",
                "portrait_agree" => "肖像權",
                "profile_agree" => "個人資料允許",
            ],
        ],
        'sec_interest' => [
            'is_shown' => 0,
            'title' => '關心議題',
            'fields' => [
                'employees'  => '公司員工人數',
            ],
        ],
        'sec_remark' => [
            'is_shown' => 1,
            'title' => '備註',
        ],
        'sec_qrcode' => [
            'is_shown' => 1,
            'title' => '報到條碼',
        ],
        'sec_contact_log' => [
            'is_shown' => 1,
            'title' => '關懷記錄',
        ],
       'sec_ceovcamp_excel' => [
            'is_shown' => 1,
            'title' => '訪談記錄',
        ],
    ],

    //---------- utvcamp --------------------
    //---------- utvcamp --------------------
    //---------- utvcamp --------------------

    'utvcamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [  //姓名姓別年齡
                'name'  => '中文姓名',
                'english_name'  => '英文姓名',
                'gender' => '性別',
                'age' => '年齡',
            ],
            'fields2' => [  //工作
                "unit" => "服務學校/單位",
                "unit_county" => "服務單位所在縣市",
                "department" => "服務系所/部門",
                "position" => "身分別",
                "title" => "職稱",
            ],
            'fields3' => [  //營隊
                'applicant_id' => '報名序號',
                'created_at' => '報名日期',
                'admitted_at' => '錄取日期',
                'group' => '組別',
                'number' => '座號',
                'carers' => '關懷員',
            ],
        ],

        'sec_attend' => ['is_shown' => 1,],
        'sec_lodging' => ['is_shown' => 1,],
        'sec_traffic' => ['is_shown' => 1,],
        'sec_file_upload' => ['is_shown' => 0,],

        'sec_adv1' => [  //聯絡方式
            'is_shown' => 1,
            'title' => '聯絡方式',
            'fields' => [
                "mobile" => "聯絡電話(手機)",
                "phone_home" => "聯絡電話(H)",
                "phone_work" => "聯絡電話(O)",
                "address" => "居住縣市",
                "line" => "lineID",
                "email" => "Email",
            ],
        ],
        'sec_adv2' => [  //關係人
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                "info_source" => "得知管道",
                "introducer_name" => "介紹人",
                "expectation" => "對營隊的期待",
                "emergency_name" => "緊急聯絡人",
                "emergency_mobile" => "緊急聯絡人手機",
            ],
        ],
        'sec_adv3' => [  //其它
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                "is_civil_certificate" => "公務員研習時數",
                "idno" => "身分證字號",
                "is_bwfoce_certificate" => "基金會研習證明",
                "invoice_type" => "發票類型",
                "taxid" => "統編",
                "invoice_title" => "發票抬頭",
            ],
        ],
        'sec_interest' => [
            'is_shown' => 0,
            'title' => '關心議題',
            'fields' => [
                'employees'  => '公司員工人數',
            ],
        ],
        'sec_remark' => [
            'is_shown' => 1,
            'title' => '備註',
        ],
        'sec_qrcode' => [
            'is_shown' => 1,
            'title' => '報到條碼',
        ],
        'sec_contact_log' => [
            'is_shown' => 1,
            'title' => '關懷記錄',
        ],
       'sec_ceovcamp_excel' => [
            'is_shown' => 0,
            'title' => '訪談記錄',
        ],
    ],

    'default' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields' => [
                'photo' => '照片',
                'name'  => '中文姓名',
                'birthday' => '生日',
                'english_name'  => '英文姓名',
                'gender' => '性別',
                'belief' => '是否已加入廣論班',
                'belief' => '廣論班別',
                'industry' => '產業別',
                'unit' => '公司名稱',
                'title' => '職稱',
                'job_property' => '職務類型',
                'applicant_id' => '報名序號',
                'group' => '所屬組別',
                'carers' => '關懷員',
                'participation_mode' => '參加形式',
            ],
        ],

        'sec_attend' => [
            'is_shown' => 1,
        ],
        'sec_traffic' => [
            'is_shown' => 0,
        ],
        'sec_lodging' => [
            'is_shown' => 1,
        ],
        'sec_file_upload' => [
            'is_shown' => 1,
        ],
        'sec_adv1' => [
            'is_shown' => 1,
            'title' => '聯絡方式',
            'fields' => [
                'mobile'  => '手機號碼',
                'phone_work' => '公司電話',
                'email' => '電子信箱',
                'line' => 'lineID',
                'substitute' => '代理人',
                'substitute_phone' => '代理人電話',
                'substitute_email' => '代理人電子信箱',
                'contact_time' => '適合聯絡時段',
                'address' => '地址',
            ],
        ],
        'sec_adv2' => [
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                'introducer_name'  => '推薦人',
                'introducer_participated' => '廣論班別',
                'introducer_phone' => '手機號碼',
                'introducer_email' => '電子信箱',
                'introducer_relationship' => '與推薦人關係',
                'reasons_recommend' => '特別推薦理由或社會影響力說明',
            ],
        ],

        'sec_adv3' => [
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'employees'  => '公司員工',
                'managed_employees' => '所轄員工',
                'capital_unit' => '資本額',
                'org_type' => '公司/組織形式',
                'years_operation' => '公司成立幾年',
                'profile_agree' => '同意個資使用',
                'portrait_agree' => '同意肖像權使用',
            ],
        ],
        'sec_interest' => [
            'is_shown' => 1,
            'title' => '關心議題',
            'fields' => [
                'employees'  => '公司員工人數',
            ],
        ],
        'sec_remark' => [
            'is_shown' => 1,
            'title' => '備註',
        ],
        'sec_qrcode' => [
            'is_shown' => 1,
            'title' => '報到條碼',
        ],
        'sec_contact_log' => [
            'is_shown' => 1,
            'title' => '關懷記錄',
        ],
       'sec_ceovcamp_excel' => [
            'is_shown' => 1,
            'title' => '訪談記錄',
        ],
    ],

];
