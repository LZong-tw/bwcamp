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
<p>{{ $applicant->name }}老師，您好！<br><br>
　　&emsp;&emsp;歡迎您參加「{{ $applicant->batch->camp->fullName }}」，為使研習進行順利，請詳閱下列須知。期待在營隊見到您！</p>
<table width="100%" style="table-layout:fixed; border: 0; word-wrap: break-word;">
<tr><td>
    <ol>
        <li>報到時間：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }}) 10:00~10:50</li>
        <li>報到地點：<br>{{ $applicant->batch->locationName }}({{ $applicant->batch->location }})
        {{ $applicant->batch->camp->fullName }}報到處。</li>
        <li>研習時間：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }})至{{ $applicant->batch->batch_end }}({{ $applicant->batch->batch_end_weekday }})止。</li>
        <li>報到時，請出示附件之【QR code】（或列印出紙本）辦理報到。</li>
        <li>詳細報到注意事項，含攜帶物品、餐飲、住宿與交通方式等，請點選<a href="http://bwcamp.bwfoce.org/downloads/tcamp{{ $applicant->batch->camp->year }}/{{ $applicant->batch->camp->year }}教師營報到通知單_email版.pdf">「完整報到通知單」</a></li>
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

{{--
<h2 class="center">{{ $applicant->batch->camp->fullName }}&emsp;行前通知單</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>報名序號：{{ $applicant->id }}</td>
        <td>組別編號：{{ $applicant->group }}{{ $applicant->number }}</td>
        <td>姓名：{{ $applicant->name }}</td>
    </tr>
</table>
<p>{{ $applicant->name }}老師，您好！<br><br>
&emsp;&emsp;歡迎參加「{{ $applicant->batch->camp->fullName }}」，誠摯地期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。請詳閱以下相關訊息，祝福您營隊收穫滿滿。</p>
<table width="100%" style="table-layout:fixed; border: 0; word-wrap: break-word;">
<tr><td>
    <ol style="list-style-type: cjk-ideographic">
        <li>營隊資訊：
            <ul>
            <li>活動期間：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }}) ~ {{ $applicant->batch->batch_end }}({{ $applicant->batch->batch_end_weekday }})</li>
            <li>地點：{{ $applicant->batch->locationName }}({{ $applicant->batch->location }})</li>
            </ul>
        </li>
        <li>報到資訊：
            <ul>
            <li><b>報到時間：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }}) 13:00～13:50</b></li>
            <li>報到地點：詠山館一樓</li>
            <li>上課地點：詠山館三樓 M31</li>
            </ul>
        </li>
        <li>自備物品：
            <ul>
            <li>換洗衣物、薄外套（冷氣教室用）、雨具</li>
            <li>牙膏、牙刷、刮鬍刀、梳子、環保杯（因落實節能減碳政策及衛生考量，團體住房不提供以上之拋棄式用品，請自備。客房內有提供洗髮精、沐浴精、浴巾、毛巾。）</li>
            <li>文具用品、筆記本、身分證、健保卡、個人藥品</li>
            </ul>
        </li>
        <li>自行前往：請依義工引導將汽車停放於麻布山林停車場P2和P3中。詳附件。</li>
        <li>新竹高鐵交通接駁
            <ul>
            <li>2025-06-22 前往麻布山林接駁車：於<b><u>新竹高鐵站出口4，12:30發車。</b></u></li>
            <li>2025-06-24 前往新竹高鐵站接駁車：由麻布山林山居外P1停車場，<b><u>17:10發車</b></u>。建議訂購<b><u>18:00以後於新竹高鐵發車的車次</b></u>。</li>
            </ul>
        </li>
        <li>附件：麻布山林入園注意事項</li>
    </ol>
</td></tr>
</table>
<p>&emsp;&emsp;若有任何問題，歡迎來電(02)7714-6066 分機20317 陳小姐。或 email: bwfaculty@blisswisdom.org。或聯繫您的關懷員。</p>
<br><br>
<a class="right">財團法人福智文教基金會　敬上</a><br>
<a class="right">{{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}</a>

<p>附件：</p>
<p>
<ol style="list-style-type: cjk-ideographic">
    <li>開車自往相關：</li>
        <img width="100%" src="{{ $message->embed(public_path() . '/img/utcamp2025_mabuville.jpg') }}" />
    <li>麻布山林入園注意事項:</li>
    <ol style="list-style-type: decimal">
        <li>任何活動、課程以不影響園區內公共安寧為原則，若有影響他人安寧之狀況，【麻布山林】有權立即終止各項園區活動，已付款項恕不退還。</li>
        <li>若有媒體需入園進行採訪、報導等相關活動，需事前向管理單位申請，未經申請本園將有權拒絕入園。</li>
        <li>【麻布山林】全園禁菸，含教室、客房，如需吸菸請至指定吸菸區，違者依菸害防制法規定可處新台幣 2 千至 1 萬元罰鍰。</li>
        <li>【麻布山林】亦不開放教室、客房或公共區域有飲酒活動或行為，違者麻布山林有權終止活動進行，相關費用將不退還。</li>
        <li>落實節能減碳政策，團體住房不提供一次性拋棄式盥洗用品 (牙膏牙刷、刮鬍刀、梳子等請自備)；客房內有提供洗髮精、沐浴精、浴巾、毛巾。</li>
        <li>基於衛生及安全考量，【麻布山林】不提供任何杯具使用，請提醒學員自行攜帶環保杯。</li>
        <li>為維護教室區環境及上課品質，教室區內部禁止飲食及設置點心及飲料。</li>
        <li><u>【麻布山林】園區禁止攜帶外食，並嚴禁外送員至園區內送餐；若未事先告知，經發現將酌收清潔費用$50/人乘以團體總人數。</u></li>
        <li>園區內禁止隨意亂棄垃圾，若經發現將視情況酌收清潔費用。</li>
        <li>山林小舖營業時間：08:00-21:00 (依現場狀況彈性調整營業時間)</li>
    </ol>
</ol>
</p>
</body>
--}}
