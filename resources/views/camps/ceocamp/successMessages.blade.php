
請記下被推薦人 {{ $applicant->name }} 的<span class="text-danger font-weight-bold">《 報名序號：{{ $applicant->id }} 》</span>作為日後查詢使用。
<!-- 錄取結果將在 <u>{{ $camp_data->admission_announcing_date }} ({{ $camp_data->admission_announcing_date_weekday }})</u> 後陸續以E-mail通知。-->
<br>
<p>
財團法人福智文教基金會<br>
{{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}<br><br>
{!! nl2br(e(str_replace('\n', "\n", $applicant->batch->contact_card))) !!}
</p>