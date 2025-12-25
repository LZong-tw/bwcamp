<style>
    u{
        color: red;
    }
</style>
<body style="font-size:16px;">
<h2 class="center">{{ $applicant->batch->camp->fullName }}&emsp;學員報到通知單</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>姓名：{{ $applicant->name }}</td>
        <td>錄取編號：{{ $applicant->group }}{{ $applicant->number }}</td>
        <td>組別：{{ $applicant->group }}</td>
    </tr>
</table>
<p>{{ $applicant->name }}您好！<br><br>
　　&emsp;&emsp;歡迎您參加「{{ $applicant->batch->camp->fullName }}」，為使研習進行順利，請詳閱下列須知。期待在營隊見到您！</p>
<table width="100%" style="table-layout:fixed; border: 0; word-wrap: break-word;">
<tr><td>
    <ol>
        <li>報到時間：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }}) 10:00~10:50</li>
        <li>報到地點：<br>{{ $applicant->batch->locationName }}({{ $applicant->batch->location }})
        {{ $applicant->batch->camp->fullName }}報到處。</li>
        <li>研習時間：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }})至{{ $applicant->batch->batch_end }}({{ $applicant->batch->batch_end_weekday }})止。</li>
        <li>報到時，請出示附件之【QR code】（或列印出紙本）辦理報到。</li>
        <li>詳細報到注意事項，含攜帶物品、餐飲、住宿與交通方式等，請點選<a href="http://bwcamp.bwfoce.org/downloads/{{ $applicant->batch->camp->table }}{{ $applicant->batch->camp->year }}/{{ $applicant->batch->camp->year }}報到通知單_email版.pdf">「完整報到通知單」</a></li>
        <li>有任何問題，歡迎與{{ $applicant->batch->camp->fullName }}報名報到組聯絡：<br>
        王淑靜&emsp;小姐<br>
        電話：07-9769341#413<br>
        Email：shu-chin.wang@blisswisdom.org</li>
    </ol>
</td></tr>
</table>
<a class="right">財團法人福智文教基金會&emsp;謹此</a><br>
<a class="right">{{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}</a>
</body>
