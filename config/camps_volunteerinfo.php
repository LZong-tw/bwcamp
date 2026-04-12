<?php

return [
    'avcamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [  //姓名姓別年齡
                'name'  => '中文姓名',
                'gender' => '生理性別',
                'age' => '年齡',
            ],
            'fields2' => [  //工作
                'unit' => '服務單位',
                'unit_county' => '服務地點-縣市',
                'unit_subarea' => '服務地點-區',
                'title' => '職稱',
            ],
            'fields3' => [  //營隊
                'roles' => '義工任務',
                'transportation' => '交通方式',
                'expertise' => '專長',
                'language' => '語言',
                'applicant_id' => '報名序號',
                'created_at' => '報名日期',
                'region' => '區域',
            ],
        ],

        'sec_attend' => [
            'is_shown' => 1,
        ],
        'sec_lodging' => [
            'is_shown' => 0,
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
                'line' => 'lineID',
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
                'self_intro' => '自我介紹',
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
        ]
    ],

    'actvcamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [  //姓名姓別年齡
                'name'  => '中文姓名',
                'gender' => '生理性別',
                'age' => '年齡',
            ],
            'fields2' => [  //工作
                'unit' => '服務單位',
                'unit_county' => '服務地點-縣市',
                'unit_subarea' => '服務地點-區',
                'title' => '職稱',
            ],
            'fields3' => [  //營隊
                'roles' => '義工任務',
                'batch_name' => '梯次',
                'applicant_id' => '報名序號',
                'created_at' => '報名日期',
                'region' => '區域',
                'org' => '職務',
            ],
        ],

        'sec_attend' => [
            'is_shown' => 1,
        ],
        'sec_lodging' => [
            'is_shown' => 0,
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
                'line' => 'lineID',
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
                'self_intro' => '自我介紹',
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
        ]
    ],

    'ceovcamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [
                'photo' => '照片',
                'name'  => '中文姓名',
                'english_name'  => '英文慣用名',
                'gender' => '生理性別',
                'birthdate' => '生日',
            ],
            'fields2' => [
                'unit' => '公司名稱',
                'industry' => '產業別',
                'title' => '職稱',
                'job_property' => '職務類型',
                'employees' => '公司員工總數',
                'direct_managed_employees' => '所轄員工人數',
            ],
        'fields3' => [
                'roles' => '義工任務',
                'applicant_id' => '報名序號',
                'created_at' => '報名日期',
                'batch_name' => '梯次',
                'region' => '區域',
                'group_priority1' => '組別志願1',
                'group_priority2' => '組別志願2',
                'group_priority3' => '組別志願3',
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
            'is_shown' => 1,
        ],
        'sec_adv1' => [
            'is_shown' => 1,
            'title' => '聯絡方式',
            'fields' => [
                'mobile'  => '行動電話',
                'email' => '電子信箱',
                'line' => 'lineID',
            ],
        ],
        'sec_adv2' => [
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                'introducer_name' => '邀請人姓名'
            ],
        ],

        'sec_adv3' => [
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'transport' => '日常交通方式',
                'transport_other' => '日常交通方式:其它',
                'expertise' => '專長',
                'expertise_other' => '專長:其它',
                'language' => '語言',
                'language_other' => '語言:其它',
                'portrait_agree' => '肖像權',
                'profile_agree' => '個資使用',
            ],
        ],
        'sec_lrclass' => [  //volunteer only
            'is_shown' => 1,
            'title' => '護持記錄',
            'fields' => [
                'lrclass_level' => '廣論班別',
                'lrclass' => '廣論班別詳',
                'cadre_experiences' => '班級護持記錄',
                'volunteer_experiences' => '義工護持記錄',
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

    'evcamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [
                'photo' => '照片',
                'name'  => '中文姓名',
                'english_name' => '英文慣用名',
                'gender' => '生理性別',
                'birthdate' => '生日',
            ],
            'fields2' => [
                'unit' => '公司名稱',
                'industry' => '產業別',
                'title' => '職稱',
                'job_property' => '職務類型',
                'employees' => '公司員工總數',
                'direct_managed_employees' => '所轄員工人數',
            ],
            'fields3' => [
                'roles' => '義工任務',
                'applicant_id' => '報名序號',
                'created_at' => '報名日期',
                'batch_name' => '梯次',
                'region' => '區域',
                'group_priority1' => '組別志願1',
                'group_priority2' => '組別志願2',
                'group_priority3' => '組別志願3',
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
                'email' => '電子信箱',
                'line' => 'LineID',
            ],
        ],
        'sec_adv2' => [
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                'introducer_name' => '邀請人姓名'
            ],
        ],
        'sec_adv3' => [
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'transport' => '日常交通方式',
                'transport_other' => '日常交通方式:其它',
                'expertise' => '專長',
                'expertise_other' => '專長:其它',
                'language' => '語言',
                'language_other' => '語言:其它',
                'portrait_agree' => '肖像權',
                'profile_agree' => '個資使用',
            ],
        ],
        'sec_lrclass' => [  //volunteer only
            'is_shown' => 1,
            'title' => '護持記錄',
            'fields' => [
                'trclass' => '儲訓班別',
                'trclass_no' => '儲訓班編號',
                'lrclass' => '廣論班別',
                'cadre_experiences' => '班級護持記錄',
                'volunteer_experiences' => '義工護持記錄',
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
    ],

    'mvcamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [  //姓名姓別年齡
                'name'  => '中文姓名',
                'gender' => '生理性別',
                'age' => '年齡',
            ],
                'fields2' => [  //工作
            ],
            'fields3' => [  //營隊
                'roles' => '義工任務',
                'applicant_id' => '報名序號',
                'created_at' => '報名日期',
                'role_section' => '功能組別',
                'role_position' => '職務',
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
                'email' => 'Email',
            ],
        ],
        'sec_adv2' => [  //關係人
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
            ],
        ],

        'sec_adv3' => [  //其它
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'lrclass' => '廣論班別',
            ],
        ],
        'sec_interest' => [
            'is_shown' => 0,
            'title' => '關心議題',
        ],
        'sec_lrclass' => [
            'is_shown' => 0,
            'title' => '護持記錄',
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

    'nyvcamp' => [
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
                'roles' => '義工任務',
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
                'address' => '居住縣市',
                'line' => 'lineID',
                'email' => 'Email',
            ],
        ],
        'sec_adv2' => [  //關係人
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                'info_source' => '得知管道',
                'introducer_name' => '介紹人姓名',
                'expectation' => '對營隊的期待',
                'emergency_name' => '緊急聯絡人姓名',
                'emergency_mobile' => '緊急聯絡人電話',
            ],
        ],

        'sec_adv3' => [  //其它
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
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

    'svcamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [  //姓名姓別年齡
                'name'  => '中文姓名',
                'gender' => '生理性別',
            ],
            'fields2' => [  //工作
            ],
            'fields3' => [  //營隊
                'roles' => '義工任務',
                'applicant_id' => '報名序號',
                'created_at' => '報名日期',
                'region' => '區域',
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
                'line' => 'lineID',
            ],
        ],
        'sec_adv2' => [  //關係人
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
            ],
        ],

        'sec_adv3' => [  //其它
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'self_intro' => '自我介紹',
            ],
        ],
        'sec_interest' => [
            'is_shown' => 0,
            'title' => '關心議題',
        ],
        'sec_remark' => [
            'is_shown' => 0,
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

    'tvcamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [  //姓名姓別年齡
                'name'  => '中文姓名',
                'gender' => '生理性別',
                'age' => '年齡',
            ],
            'fields2' => [  //工作
            ],
            'fields3' => [  //營隊
                'roles' => '義工任務',
                'applicant_id' => '報名序號',
                'created_at' => '報名日期',
                'region' => '區域'
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
                'email' => 'Email',
            ],
        ],
        'sec_adv2' => [  //關係人
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                'info_source' => '得知管道',
                'introducer_name' => '介紹人姓名',
                'expectation' => '對營隊的期待',
                'emergency_name' => '緊急聯絡人姓名',
                'emergency_mobile' => '緊急聯絡人電話',
            ],
        ],

        'sec_adv3' => [  //其它
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'self_intro' => '自我介紹',
            ],
        ],
        'sec_interest' => [
            'is_shown' => 0,
            'title' => '關心議題',
        ],
        'sec_remark' => [
            'is_shown' => 0,
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

    //---------- utvcamp --------------------
    //---------- utvcamp --------------------
    //---------- utvcamp --------------------

    'utvcamp' => [
        'sec_basic' => [
            'is_shown' => 1,
            'title' => 'none',
            'fields1' => [  //姓名姓別年齡
                'name'  => '中文姓名',
                'gender' => '生理性別',
            ],
            'fields2' => [  //工作
                'region' => '區域',
            ],
            'fields3' => [  //營隊
                'roles' => '義工任務',
                'applicant_id' => '報名序號',
                'created_at' => '報名日期',
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
                'email' => 'Email',
            ],
        ],
        'sec_adv2' => [  //關係人
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
            ],
        ],
        'sec_adv3' => [  //其它
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
                'self_intro' => '我是誰',
            ],
        ],
        'sec_lrclass' => [
            'is_shown' => 1,
            'title' => '護持記錄',
            'fields' => [
            ],
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
            'is_shown' => 1,
            'title' => '關懷記錄',
        ],
    ],

    'wvcamp' => [
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
                'roles' => '義工任務',
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
                'address' => '居住縣市',
                'line' => 'lineID',
                'email' => 'Email',
            ],
        ],
        'sec_adv2' => [  //關係人
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                'info_source' => '得知管道',
                'introducer_name' => '介紹人姓名',
                'emergency_name' => '緊急聯絡人姓名',
                'emergency_mobile' => '緊急聯絡人電話',
            ],
        ],

        'sec_adv3' => [  //其它
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

    'yvcamp' => [
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
                'roles' => '義工任務',
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
                'address' => '居住縣市',
                'line' => 'lineID',
                'email' => 'Email',
            ],
        ],
        'sec_adv2' => [  //關係人
            'is_shown' => 1,
            'title' => '關係人資訊',
            'fields' => [
                'info_source' => '得知管道',
                'introducer_name' => '介紹人',
                'expectation' => '對營隊的期待',
                'emergency_name' => '緊急聯絡人姓名',
                'emergency_mobile' => '緊急聯絡人電話',
            ],
        ],

        'sec_adv3' => [  //其它
            'is_shown' => 1,
            'title' => '其它資訊',
            'fields' => [
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
