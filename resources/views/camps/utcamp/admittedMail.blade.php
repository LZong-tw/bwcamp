<body style="font-size:16px;">

<!-- 一般教師營
    <h2 class="center">{{ $applicant->batch->camp->fullName }}&emsp;錄取通知</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>地點：{{ $applicant->batch->locationName }}</td>
        <td>時間：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }})至{{ $applicant->batch->batch_end }}({{ $applicant->batch->batch_end_weekday }})</td>
    </tr>
    <tr>
        <td>姓名：{{ $applicant->name }}</td>
        <td>錄取編號：<u>{{ $applicant->group }}{{ $applicant->number }}</u></td>
        <td>組別：<u>{{ $applicant->group }}</u></td>
    </tr>
</table>
    &emsp;&emsp;恭喜您錄取「{{ $applicant->batch->camp->fullName }}」，竭誠歡迎您的到來。<br>
    &emsp;&emsp;相關營隊訊息，將於營隊三周前寄發「報到通知單」，請記得到電子信箱收信。<br>
    &emsp;&emsp;也歡迎加入[幸福心學堂online]臉書社團，收取營隊訊息和教育新知。<br>
    &emsp;&emsp;很期待與您共享這場心靈的饗宴，預祝您歡喜學習，收穫滿滿。<br>
    &emsp;&emsp;敬祝&emsp;教安<br><br>
    <ul>
        <li>
            請加入幸福心學堂online臉書社團：<br>
            <a href="https://www.facebook.com/groups/bwfoce.happiness.new" target="_blank" rel="noopener noreferrer">https://www.facebook.com/groups/bwfoce.happiness.new</a>
        </li>
        <li>
            關注「福智文教基金會」網站：<a href="https://bwfoce.org">https://bwfoce.org</a>
        </li>
        <li>
            報名報到諮詢窗口<span class="font-bold">（周一至周五 10:00~17:30）：</span><br>
            王淑靜&emsp;小姐<br>
            電話：07-9769341#413<br>
            Email：shu-chin.wang@blisswisdom.org<br>
        </li>
    </ul>
<a class="right">財團法人福智文教基金會&emsp;謹此</a><br>
<a class="right">{{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}</a>
-->

@php
    $cancellation_add1_with_weekday = \Carbon\Carbon::parse($applicant->batch->camp->cancellation_deadline)->addDays(1)->isoFormat('YYYY-MM-DD(dd)');
    if ($applicant->created_at->lte($applicant->batch->camp->early_bird_last_day)) {
        $rate = "早鳥優惠、兩人同行優惠";
    } elseif ($applicant->created_at->lte($applicant->batch->camp->discount_last_day)) {
        $rate = "原價、兩人同行優惠";
    } else {
        $rate = "原價";
    }
@endphp
<h3 style="text-align: center">{{ $applicant->batch->camp->fullName }}【錄取繳費通知單]</h3>
親愛的 {{ $applicant->name }} 老師您好：<br>
<br>
&emsp;&emsp;恭喜您錄取「{{ $applicant->batch->camp->fullName }}」，我們竭誠歡迎您的到來，請留意以下資訊，期望您能留下最美好的回憶。<br>
<ol>
    <li>營隊期間：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }})～{{ $applicant->batch->batch_end }}({{ $applicant->batch->batch_end_weekday }})，共三天兩夜</li>
    <li>報到時間：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }})13:00～13:50</li>
    <li>報到地點：{{ $applicant->batch->locationName }}（{{ $applicant->batch->location }}）</li>
    <li>賦歸時間：{{ $applicant->batch->batch_end }}({{ $applicant->batch->batch_end_weekday }})17:00 (接駁車發車時間17:10)<br>
    <li>繳費：<b><span style="color: #DC3545;">請於收到錄取通知後7個工作天之內繳費</span></b>，繳費完成方視為完成報名。各房型費用詳見<a href="https://bwfoce.wixsite.com/ufscamp#comp-kvff5c8s" target="_blank"><span style="color: #0dcaf0;"><u>活動官網說明</u></span></a>。</li>
    <li>交通方式：<br>
    (1)自行前往：<br>
    (2)搭乘接駁車：新竹高鐵站來回接駁<br>
    &emsp;&emsp;{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }})前往麻布山林接駁車：將於新竹高鐵站出口4，12:30發車<br>
    &emsp;&emsp;{{ $applicant->batch->batch_end }}({{ $applicant->batch->batch_end_weekday }})前往新竹高鐵站接駁車：由麻布山林山居外P1停車場，17:00發車<br> 
    </li>
    <li>退費：<br>
    {{ $applicant->batch->camp->cancellation_deadline }}({{ $applicant->batch->camp->cancellation_deadline_weekday }})(含)前可全額退費(需扣除5%手續費)；<br>
    {{ $cancellation_add1_with_weekday }}(含)以後恕不退費。
    </li>
</ol>
<b>＊＊營隊仍可持續報名，歡迎您邀請好朋友一起來參加！＊＊<br>
&emsp;&emsp;報名網址：<a href="https://bwfoce.wixsite.com/ufscamp" target="_blank">https://bwfoce.wixsite.com/ufscamp</a><br></b>
<br>
<b>【繳費資訊】</b><br>
<ul>
    <li>您的報名日期：{{ $applicant->created_at->format('Y-m-d') }}</li>
    <li>您適用的費率：{{ $rate }}</li>
    <li>您選擇的房型：{{ $applicant->lodging->room_type }}</li>
    <li>您的應繳費用：NT$<span class="text-danger">{{ $applicant->lodging->fare }}</span>&emsp;</li>
    <li>匯款帳號：<br>
        &emsp;邱孟懿 中國信託 板新分行<br>
        &emsp;帳號：417540291289<br>
        &emsp;匯款時，請註記：{{ $applicant->name }}大專教師營<br>
        &emsp;匯款後，請回覆此信告知匯款銀行及尾數5碼。<br>    
    <li><a href="{{ route('showadmit', ['batch_id' => $applicant->batch->id, 'sn' => $applicant->id, 'name' => $applicant->name]) }}">按此更改住宿及交通服務選項</a><br>
        若以上連結無法點選，請複製下方文字後，再由瀏覽器進入頁面做回覆：<br>
        {{ route('queryadmitGET', ['batch_id' => $applicant->batch->id]) }}
    </li>
</ul>
<br>
<b>【聯絡我們】</b><br>
<ul>
    <li>{!! nl2br(e(str_replace('\n', "\n", $applicant->batch->contact_card))) !!}</li>
</ul>
<br>
<p style="text-align: right">財團法人福智文教基金會&emsp;敬上&emsp;<br>
{{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}&emsp;</p>
</body>