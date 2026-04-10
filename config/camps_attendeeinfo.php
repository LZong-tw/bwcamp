<?php

return [
    'acamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [  //姓名姓別年齡
                'name'  => '中文姓名',
                'gender' => '生理性別',
                'birthdate' => '生日',
                'education' => '最高學歷',
                'is_student' => '身分別',
                'is_manager' => '是否為主管',
                'belief' => '宗教信仰',
            ],
            'fields2' => [  //工作
                'industry' => '產業別',
                'job_property' => '工作屬性',
                'unit' => '服務單位',
                'unit_county' => '服務地點-縣市',
                'unit_subarea' => '服務地點-區',
                'title' => '職稱',
            ],
            'fields3' => [  //營隊
                'batch_name' => '梯次',
                'applicant_id' => '報名序號',
                'created_at' => '報名日期',
                'admitted_at' => '錄取日期',
                'region' => '區域',
                'group' => '組別',
                'number' => '座號',
            ],
        ],

        'sec_attend' => [
            'is_shown' => 1,
        ],
        'sec_lodging' => [
            'is_shown' => 1,
            'is_lodging' => 1,
        ],
        'sec_traffic' => [
            'is_shown' => 1,
            'is_traffic' => 0,  //0: use transportation
        ],
        'sec_file_upload' => [
            'is_shown' => 0,
        ],
        'sec_adv1' => [
            'is_shown' => 1,
            'title' => '聯絡方式',
            'fields' => [
                'mobile' => '行動電話',
                'phone_home' => '住家電話',
                'phone_work' => '工作電話',
                'email' => '電子郵件',
                'line' => 'lineID',
                'address' => '現居住地點',
                'class_county' => '上課地點-縣市',
                'class_subarea' => '上課地點-區',
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
                'introducer_name' => '介紹人姓名',
                'introducer_phone' => '介紹人電話',
                'introducer_relationship' => '介紹人關係',
                'introducer_participated' => '介紹人福智班別',
                'emergency_name' => '緊急聯絡人姓名',
                'emergency_mobile' => '緊急聯絡人電話',
                'emergency_relationship' => '緊急聯絡人關係',
                'is_inperson' => '是否本人填表',
                'agent_name' => '代填人姓名',
                'agent_phone' => '代填人電話',
                'agent_relationship' => '代填人關係',
            ],
        ],

        'sec_adv3' => [
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'way' => '如何得知營隊',
                'motivation' => '報名動機',
                'motivation_other' => '報名動機(其它)',
                'blisswisdom_type' => '參與過福智活動',
                'blisswisdom_type_other' => '參與過福智活動(其它)',
                'portrait_agree' => '肖像權',
                'profile_agree' => '個資使用',
            ],
        ],
        'sec_interest' => [
            'is_shown' => 0,
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



    'actcamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [  //姓名姓別年齡
                'name'  => '中文姓名',
                'gender' => '生理性別',
                'category' => '身分別',
            ],
            'fields2' => [  //工作
                'unit' => '服務單位',
                'title' => '職稱',
            ],
            'fields3' => [  //營隊
                'batch_name' => '梯次',
                'applicant_id' => '報名序號',
                'created_at' => '報名日期',
                'admitted_at' => '錄取日期',
            ],
        ],

        'sec_attend' => [
            'is_shown' => 1,
        ],
        'sec_lodging' => [
            'is_shown' => 1,
            'is_lodging' => 0,
        ],
        'sec_traffic' => [
            'is_shown' => 1,
            'is_traffic' => 0,
        ],
        'sec_file_upload' => [
            'is_shown' => 0,
        ],
        'sec_adv1' => [  //聯絡方式
            'is_shown' => 1,
            'title' => '聯絡方式',
            'fields' => [
                'mobile' => '行動電話',
                'email' => '電子郵件',
            ],
        ],
        'sec_adv2' => [  //關係人
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                'emergency_name' => '聯絡人姓名',
                'emergency_mobile' => '聯絡人電話',
                'emergency_relationship' => '關係',
            ],
        ],

        'sec_adv3' => [  //其它
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'lrclass_year' => '廣論班年份',
                'lrclass_number' => '廣論班別',
                'portrait_agree' => '肖像權',
                'profile_agree' => '個資使用',
            ],
        ],
        'sec_interest' => [
            'is_shown' => 0,
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


    'ceocamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [
                'name'  => '中文姓名',
                'birthdate' => '生日',
                'english_name'  => '英文慣用名',
                'gender' => '生理性別',
                'is_lrclass' => '是否已加入廣論班',
                'lrclass' => '廣論班別',
            ],
            'fields2' => [
                'industry' => '產業別',
                'unit' => '公司名稱',
                'title' => '職稱',
                'job_property' => '職務類型',
            ],
            'fields3' => [
                'applicant_id' => '報名序號',
                'created_at' => '報名日期',
                'batch_name' => '梯次',
                'region' => '區域',
                'group' => '組別',
                'carers' => '關懷員',
                'participation_mode' => '參加形式',
                'reasons_online' => '選擇上述形式原因',

            ],
        ],

        'sec_attend' => [
            'is_shown' => 1,
        ],
        'sec_lodging' => [
            'is_shown' => 1,
            'is_lodging' => 0,
            'fields' => [
                'bank_second_barcode' => '繳費虛擬帳號',
                'fare' => '應繳金額',
                'deposit' => '已繳金額',
            ],
        ],
        'sec_traffic' => [
            'is_shown' => 0,
            'is_traffic' => 0,  //0: use transportation
        ],
        'sec_file_upload' => [
            'is_shown' => 1,
        ],
        'sec_adv1' => [
            'is_shown' => 1,
            'title' => '聯絡方式',
            'fields' => [
                'mobile'  => '行動電話',
                'phone_work' => '工作電話',
                'email' => '電子信箱',
                'line' => 'lineID',
                'zipcode' => '郵遞區號',
                'address' => '通訊地址',
                'contact_time' => '適合聯絡時段',
                'exceptional_conditions' => '特別關懷事項',
            ],
        ],
        'sec_adv2' => [
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                'introducer_name'  => '推薦人姓名',
                'introducer_participated' => '推薦人廣論班別',
                'introducer_phone' => '推薦人行動電話',
                'introducer_email' => '推薦人電子信箱',
                'introducer_relationship' => '與推薦人關係',
                'reasons_recommend' => '特別推薦理由或社會影響力說明',
                'substitute_name' => '代理人姓名',
                'substitute_phone' => '代理人聯絡電話',
                'substitute_email' => '代理人電子信箱',
            ],
        ],

        'sec_adv3' => [
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'employees' => '公司員工總數',
                'direct_managed_employees' => '所轄員工人數',
                'capital' => '資本額',
                'capital_unit' => '資本額單位',
                'org_type' => '公司組織形式',
                //'org_type_other' => '公司組織形式:自填',
                'years_operation' => '公司成立幾年',
                'portrait_agree' => '肖像權',
                'profile_agree' => '個資使用',
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
            'is_shown' => 0,
            'title' => '訪談記錄',
        ],
    ],


    'ecamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [
                'photo' => '照片',
                'name'  => '中文姓名',
                'gender' => '生理性別',
                'birthday' => '生日',
            ],
            'fields2' => [
                'unit' => '服務單位',
                'unit_location' => '服務單位所在地',
                'title' => '職稱',
                'job_property' => '工作屬性',
                'experience' => '經歷',
                'employees' => '公司員工人數',
                'direct_managed_employees' => '直屬管轄人數',
                'industry' => '產業別',
            ],
            'fields3' => [
                'applicant_id' => '報名序號',
                'created_at' => '報名日期',
                'batch_name' => '梯次',
                'region' => '區域',
                'group' => '組別',
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
        'sec_adv1' => [
            'is_shown' => 1,
            'title' => '聯絡方式',
            'fields' => [
                'mobile' => '行動電話',
                'phone_home' => '住家電話',
                'phone_work' => '工作電話',
                'line' => 'LineID',
                'wechat' => '微信ID',
                'email' => 'Email',
                'zipcode' => '郵遞區號',
                'address' => '地址',
            ],
        ],
        'sec_adv2' => [
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                'emergency_name' => '緊急聯絡人姓名',
                'emergency_relationship' => '緊急聯絡人關係',
                'emergency_mobile' => '緊急聯絡人電話',
                'introducer_name' => '介紹人姓名',
                'introducer_relationship' => '介紹人關係',
                'introducer_phone' => '介紹人電話',
                'introducer_participated' => '介紹人參加過的福智活動',
            ],
        ],
        'sec_adv3' => [
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'expectation' => '對活動的期望',
                'portrait_agree' => '肖像權',
                'profile_agree' => '個資使用',
                'after_camp_available_day' => '後續課程時間',
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
            'fields1' => [  //姓名姓別年齡
                'name'  => '中文姓名',
                'gender' => '生理性別',
                'age' => '年齡',
            ],
            'fields2' => [  //工作
                'unit' => '服務機構',
                'title' => '職稱',
                'status' => '身分別',
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
        'sec_adv1' => [
            'is_shown' => 1,
            'title' => '聯絡方式',
            'fields' => [
                'mobile' => '行動電話',
                'address' => '居住縣市',
                'line' => 'lineID',
                'email' => 'Email',
            ],
        ],
        'sec_adv2' => [
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                'info_source' => '得知管道',
                'introducer_name' => '介紹人姓名',
                'emergency_name' => '緊急聯絡人姓名',
                'emergency_mobile' => '緊急聯絡人電話',
            ],
        ],

        'sec_adv3' => [
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'expectation' => '對營隊的期待',
                'medical_specialty' => '醫事人員職種類別',
                'work_category' => '長照人員職種類別',
                'portrait_agree' => '肖像權',
                'profile_agree' => '個資使用',
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



    'ycamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [  //姓名姓別年齡
                'name'  => '中文姓名',
                'gender' => '生理性別',
                'birthdate' => '生日',
            ],
            'fields2' => [  //工作
                "system" => "課程學制",
                "day_nigh" => "部別",
                "school" => "就讀學校",
                "department" => "系所",
                "grade" => "年級",
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
                'mobile' => '行動電話',
                'phone_home' => '住家電話',
                'line' => 'lineID',
                'email' => 'Email',
                'zipcode' => '郵遞區號',
                'address' => '通訊地址',
            ],
        ],
        'sec_adv2' => [  //關係人
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                "father_name" => "父親姓名",
                "father_lamrim" => "父親廣論班別",
                "father_phone" => "父親聯絡電話",
                "mother_name" => "母親姓名",
                "mother_lamrim" => "母親廣論班別",
                "mother_phone" => "母親聯絡電話",
                "introducer_name" => "介紹人姓名",
                "introducer_relationship" => "介紹人關係",
                "introducer_phone" => "介紹人電話",
                "introducer_participated" => "介紹人參加過的福智活動",
                "in_inperson" => "是否本人填表",
                "agent_name" => "代填人姓名",
                "agent_phone" => "代填人電話",
                "emergency_name" => "緊急聯絡人姓名",
                "emergency_relationship" => "緊急聯絡人關係",
                "emergency_mobile" => "緊急聯絡人電話",
            ],
        ],

        'sec_adv3' => [  //其它
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                "way" => "如何得知活動",
                "club" => "社團活動及擔任職務",
                "goal" => "這一生目標",
                "expectation" => "對活動的期望",
                "blisswisdom_type" => "曾參與福智活動",
                "blisswisdom_type_other" => "曾參與福智活動(其它)",
                'portrait_agree' => '肖像權',
                'profile_agree' => '個資使用',
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

    'default_applicant' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [  //姓名姓別年齡
                'name'  => '中文姓名',
                'english_name'  => '英文姓名',
                'gender' => '生理性別',
                'age' => '年齡',
            ],
            'fields2' => [  //工作
                'unit' => '服務學校/單位',
                'unit_county' => '服務單位所在縣市',
                'position' => '身分別',
                'title' => '職稱',
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
                'mobile' => '行動電話',
                'phone_home' => '住家電話',
                'phone_work' => '工作電話',
                'email' => 'Email',
                'line' => 'lineID',
                'address' => '居住地',
            ],
        ],
        'sec_adv2' => [  //關係人
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                'emergency_name' => '緊急聯絡人姓名',
                'emergency_mobile' => '緊急聯絡人電話',
            ],
        ],
        'sec_adv3' => [  //其它
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'portrait_agree' => '肖像權',
                'profile_agree' => '個資使用',
            ],
        ],
        'sec_interest' => [
            'is_shown' => 0,
            'title' => '關心議題',
            'fields' => [
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

    'default_volunteer' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [  //姓名姓別年齡
                'name'  => '中文姓名',
                'english_name'  => '英文姓名',
                'gender' => '生理性別',
                'age' => '年齡',
            ],
            'fields2' => [  //工作
                'unit' => '服務學校/單位',
                'unit_county' => '服務單位所在縣市',
                'position' => '身分別',
                'title' => '職稱',
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
                'mobile' => '行動電話',
                'phone_home' => '住家電話',
                'phone_work' => '工作電話',
                'email' => 'Email',
                'line' => 'lineID',
                'address' => '居住地',
            ],
        ],
        'sec_adv2' => [  //關係人
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                'emergency_name' => '緊急聯絡人姓名',
                'emergency_mobile' => '緊急聯絡人電話',
            ],
        ],
        'sec_adv3' => [  //其它
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'portrait_agree' => '肖像權',
                'profile_agree' => '個資使用',
            ],
        ],
        'sec_lrclass' => [  //volunteer only
            'is_shown' => 1,
            'title' => '護持記錄',
            'fields' => [
                'lrclass' => '廣論班別',
                'trclass' => '儲訓班別',
                'cadre_experiences' => '班級護持記錄',
                'volunteer_experiences' => '義工護持記錄',
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
            'is_shown' => 0,
            'title' => '報到條碼',
        ],
        'sec_contact_log' => [
            'is_shown' => 0,
            'title' => '關懷記錄',
        ],
       'sec_ceovcamp_excel' => [
            'is_shown' => 0,
            'title' => '訪談記錄',
        ],
    ],

];
