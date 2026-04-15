
請記下您的<b><span style="color: #DC3545;">《 報名序號：{{ $applicant->id }} 》</span></b>作為日後查詢使用。<br>
<u>{{ $camp_info->admission_announcing_date }} ({{ $camp_info->admission_announcing_date_weekday }}) </u>以後將以E-mail通知您營隊後續訊息。
<br>
<p>
財團法人福智文教基金會<br>
{{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}<br><br>
{!! nl2br(e(str_replace('\n', "\n", $applicant->batch->contact_card))) !!}
</p>