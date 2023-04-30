{{ $applicant->name }} 您好：<br>
<br>
    {{ $applicant->introducer_name }}推薦您報名{{ $applicant->batch->camp->fullName }}，推薦報名手續已完成。若您有任何問題，歡迎您與主辦單位聯繫。<br>
    主辦單位於營隊活動前，會安排關懷員與您聯繫，說明活動相關訊息及注意事項。<br>
    為落實個人資料之保護，主辦單位於本次營隊活動，將利用您所提供之個人資料通知您本次營隊活動相關行政、訊息之非營利目的使用。同意期間自您同意參加活動之日起(收到此通知信時)，至您提出刪除日止。營隊活動期間由主辦單位保存您的個人資料，以作為您、主辦單位查詢、確認證明之用。<br>
    主辦單位於本次營隊取得之個人資料，不會未經您以言詞、書面、電話、簡訊、電子郵件、傳真、電子文件等方式同意提供給第三單位使用。<br>
    <!-- 錄取名單將於 {{ $applicant->batch->camp->admission_announcing_date }} 後陸續以E-mail通知。<br> -->
    <br>
    洽詢電話：<br>
    　北區：陳月娥 0935-237-395，李隆安 0935-434-357<br>
    　竹區：邱雍凌 0922-437-236，李雪貞 0933-112-571<br>
    　中區：張月綺 0922-958-397，王麗娟 0938-135-180<br>
    　高區：李　莉 0932-866-345，許秀雲 0939-928-162<br>
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
