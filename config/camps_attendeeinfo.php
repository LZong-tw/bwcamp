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
        'sec_transport' => [
            'is_shown' => 1,
        ],
        'sec_lodging' => [
            'is_shown' => 0,
        ],
        'sec_file_upload' => [
            'is_shown' => 0,
        ],
        'sec_3cols_1' => [
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
        'sec_3cols_2' => [
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
        'sec_3cols_3' => [
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
        'sec_transport' => [
            'is_shown' => 0,
        ],
        'sec_lodging' => [
            'is_shown' => 1,
        ],
        'sec_file_upload' => [
            'is_shown' => 1,
        ],
        'sec_3cols_1' => [
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
        'sec_3cols_2' => [
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

        'sec_3cols_3' => [
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
        'sec_transport' => [
            'is_shown' => 0,
        ],
        'sec_lodging' => [
            'is_shown' => 0,
        ],
        'sec_file_upload' => [
            'is_shown' => 1,
        ],
        'sec_3cols_1' => [
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
        'sec_3cols_2' => [
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
        'sec_3cols_3' => [
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

    'utcamp' => [
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
        'sec_transport' => [
            'is_shown' => 0,
        ],
        'sec_lodging' => [
            'is_shown' => 1,
        ],
        'sec_file_upload' => [
            'is_shown' => 1,
        ],
        'sec_3cols_1' => [
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
        'sec_3cols_2' => [
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

        'sec_3cols_3' => [
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
        'sec_transport' => [
            'is_shown' => 0,
        ],
        'sec_lodging' => [
            'is_shown' => 1,
        ],
        'sec_file_upload' => [
            'is_shown' => 1,
        ],
        'sec_3cols_1' => [
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
        'sec_3cols_2' => [
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

        'sec_3cols_3' => [
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
