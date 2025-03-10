{{ $applicant->name }} 您好：<br>
<br>
    {{ $applicant->introducer_name }}推薦您報名{{ $applicant->batch->camp->fullName }}，推薦報名手續已完成。若您有任何問題，歡迎您與主辦單位聯繫。<br>
    主辦單位於營隊活動前，會安排關懷員與您聯繫，說明活動相關訊息及注意事項。<br>
    為落實個人資料之保護，於本次營隊活動及活動結束後，福智文教基金會（簡稱本基金會）及本基金會所屬福智團體將利用被推薦人所提供個人資料通知被推薦人本次營隊活動相關訊息，及日後福智團體相關課程、活動訊息通知之非營利目的使用。同意期間自被推薦人同意參加活動之日起，至被推薦人提出刪除日止。營隊活動期間由本基金會及本基金會所屬福智團體保存被推薦人的個人資料，以作為被推薦人、本基金會查詢、確認證明之用。<br>
    除上述情形外，本基金會於本次營隊取得之個人資料，不會未經被推薦人以言詞、書面、電話、簡訊、電子郵件、傳真、電子文件等方式同意提供給第三單位使用。<br>
    <!-- 錄取名單將於 {{ $applicant->batch->camp->admission_announcing_date }} 後陸續以E-mail通知。<br> -->
    <br>
    洽詢電話：<br>
    　北區：陳小姐 0958367318、陳先生 0966891868、吳小姐 0910123257<br>
    　竹區：邱小姐 0922437236、陳小姐 0921625305<br>
    　中區：陳小姐 0972087070、王小姐 0937308673<br>
    　高區：陳小姐 0922208027<br>
    電子郵件：ceo.camp@blisswisdom.org<br>
    <br>
<blockquote>財團法人福智文教基金會  敬啟</blockquote>
--
為推薦您參加本次營隊活動，推薦人{{ $applicant->introducer_name }}於填寫推薦報名表時，提供了您的下述資料：<br>
中文姓名：{{ $applicant->name }}<br>
英文慣用名：{{ $applicant->english_name }}<br>
性別：{{ $applicant->gender }}<br>
出生年月：{{ $applicant->birthyear }}/{{ $applicant->birthmonth }}<br>
手機號碼：{{ $applicant->mobile }}<br>
電子信箱：{{ $applicant->email }}<br>
通訊地址：{{ $applicant->address }}<br>
適合聯絡時段：{{ $applicant->ceocamp->contact_time }}<br>
公司名稱：{{ $applicant->ceocamp->unit }}<br>
職稱：{{ $applicant->ceocamp->title }}<br>
所轄員工人數：{{ $applicant->ceocamp->direct_managed_employees }}<br>
