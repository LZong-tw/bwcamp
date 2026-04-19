{{ $applicant->substitute_name }}您好：<br>
<br>
    {{ $applicant->introducer_name }}推薦{{ $applicant->name }}報名「{{ $camp_info->year }}企業菁英生命成長營&nbsp; {{$applicant->batch->locationName }}場」，推薦報名手續已完成，<br>
    請記下{{ $applicant->name }}的<b><span style="color: #DC3545;">《 報名序號：{{ $applicant->id }} 》</span></b>作為日後查詢使用。<br>
    <!--錄取名單將於 {{ $camp_info->admission_announcing_date }} 後陸續以E-mail通知。<br> -->
    <br>
<blockquote>財團法人福智文教基金會  敬啟</blockquote>
洽詢電話：<br>
{!! nl2br(e(str_replace('\n', "\n", $applicant->batch->contact_card))) !!}<br>
電子郵件：ceo.camp@blisswisdom.org<br>
<br>
