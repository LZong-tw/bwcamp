<style>
    u{
        color: red;
    }
</style>
<body style="font-size:16px;">
<h2 class="center">{{ $applicant->batch->camp->fullName }} 學員報到通知單</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>姓名：{{ $applicant->name }}</td>
        <td>錄取編號：{{ $applicant->group }}{{ $applicant->number }}</td>
        <td>組別：{{ $applicant->group }}</td>
    </tr>
</table>
<p>{{ $applicant->name }}老師，您好！<br><br>
　　歡迎您參加「{{ $applicant->batch->camp->fullName }}」，為使研習進行順利，請詳閱下列須知。期待在營隊見到您！</p>
<table width="100%" style="table-layout:fixed; border: 0; word-wrap: break-word;">
<tr><td>
    <ol>
        <li>報到時間：113年1月27日(六) 10:30~11:30</li>
        <li>報到地點：<br>
        屏東縣大仁科技大學(地址：907屏東縣鹽埔鄉維新路20號)<br>
        2024教師生命成長營報到處。</li>
        <li>研習時間：113年1月27日(星期六)至113年1月30日(星期二)止。</li>
        <li>報到時，請出示附件之【QR code】（或列印出紙本）辦理報到。</li>
        <li>詳細報到注意事項，含攜帶物品、餐飲、住宿與交通方式等，請點選<a href="http://bwcamp.bwfoce.org/downloads/tcamp2024/2024教師營報到通知單0102_確認版(更正電話).pdf">「完整報到通知單」</a></li>
        <li>聯絡方式：<br>
        (07)9743280#68601 2024教師生命成長營報名組<br>
        113年1月27日(含)以後 陳昶安 0988-188-001</li>
    </ol>
</td></tr>
</table>
<a class="right">財團法人福智文教基金會　謹此</a><br>
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>
</body>