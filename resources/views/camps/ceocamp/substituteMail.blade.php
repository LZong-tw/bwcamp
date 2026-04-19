{{ $applicant->substitute_name }}您好：<br>
<br>
    {{ $applicant->introducer_name }}推薦{{ $applicant->name }}報名{{ $camp_info->fullName }}，推薦報名手續已完成，<br>
    請記下{{ $applicant->name }}的報名序號： {{ $applicant->id }} 作為日後查詢使用。<br>
    <!--錄取名單將於 {{ $camp_info->admission_announcing_date }} 後陸續以E-mail通知。<br> -->
    <br>
<blockquote>財團法人福智文教基金會  敬啟</blockquote>
洽詢電話：<br>
{!! nl2br(e(str_replace('\n', "\n", $camp_info->contact_card))) !!}<br>
電子郵件：ceo.camp@blisswisdom.org<br>
<br>
