{{ $applicant->introducer_name }} 您好：<br>
<br>
    您推薦{{ $applicant->name }}報名{{ $camp_info->fullName }}，推薦報名手續已完成，請記下{{ $applicant->name }}的報名序號： {{ $applicant->id }} 作為日後查詢使用。若您有任何問題，歡迎您與主辦單位聯繫。<br>
    主辦單位於營隊活動前，會安排關懷員與被推薦人聯繫，說明活動相關訊息及注意事項。<br>
    為落實個人資料之保護，於本次營隊活動及活動結束後，福智文教基金會（簡稱本基金會）將利用被推薦人所提供之個人資料通知被推薦人本次營隊活動相關訊息，及日後本基金會相關課程、活動訊息通知之非營利目的使用。同意期間自被推薦人同意參加活動之日起，至被推薦人提出刪除日止。營隊活動期間由本基金會保存被推薦人的個人資料，以作為被推薦人、本基金會查詢、確認證明之用。<br>
    除上述情形外，本基金會於本次營隊取得之個人資料，不會未經被推薦人以言詞、書面、電話、簡訊、電子郵件、傳真、電子文件等方式同意提供給第三單位使用。<br>
    <!-- 錄取名單將於 {{ $camp_info->admission_announcing_date }} 後陸續以E-mail通知。<br> -->
    <br>
<blockquote>財團法人福智文教基金會  敬啟</blockquote>
洽詢電話：<br>
{!! nl2br(e(str_replace('\n', "\n", $camp_info->contact_card))) !!}<br>
電子郵件：ceo.camp@blisswisdom.org<br>
<br>
--
為推薦{{ $applicant->name }}參加本次營隊活動，您於填寫推薦報名表時，提供了被推薦人的下述資料：<br>
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
