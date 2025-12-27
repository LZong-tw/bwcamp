<body style="font-size:16px;">
{{ $applicant->name }} 您好：<br>
<br>
&emsp;&emsp;恭喜您已完成{{ $applicant->batch->camp->fullName }}網路報名程序。請記下您的<b>《 報名序號：{{ $applicant->id }} 》</b>作為日後查詢使用。<br>
<br>
&emsp;&emsp;如您需修改報名表內容，可憑報名序號至 <a href="https://bwcamp.bwfoce.org/camp/{{ $applicant->batch->id }}/query"> https://bwcamp.bwfoce.org/camp/{{ $applicant->batch->id }}/query</a> 頁面修改<br>
<br>
&emsp;&emsp;我們將於 {{ $campData->admission_announcing_date }}({{ $campData->admission_announcing_date_weekday }}) 寄發錄取與否通知信件至您的電子信箱，或您可至活動網站 {{ $campData->site_url }} 查詢。<br>
<br>
洽詢窗口<span class="font-bold">（週一至週五 10:00~17:30）：</span><br>
{!! nl2br(e(str_replace('\n', "\n", $applicant->batch->contact_card))) !!}
LINE@：http://bwfoce.org/line <br>
LINE ID：@bwfoce <br>
{{-- 電子郵件：bwmedu@blisswisdom.org<br> --}}
<br>
<blockquote>財團法人福智文教基金會　謹此</blockquote>
</body>
