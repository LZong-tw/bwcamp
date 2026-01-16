<style>
    {{-- u{
        color: red;
    } --}}
    .right{
        text-align: right;
    }
    .indent{
        text-indent: 22px;
    }
</style>
<body>
<table role="presentation"  cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
<tr><td>
    <p>{{ $applicant->name }} 您好</p>
    <p class="indent">非常感謝您報名「{{ $applicant->camp->fullName }}」，由於場地與各項條件的限制，非常抱歉未能錄取您。誠摯地邀請您參與本會其它活動。</p>
    <p class="indent">相關活動訊息，請洽詢各區聯絡窗口：</p>
    <p class="indent"><a href="https://www.blisswisdom.org/about/branches" target="_blank" rel="noopener noreferrer">全球辦事處</a></p>
    <p class="indent"><a href="http://tp.blisswisdom.org/352-2#local" target="_blank" rel="noopener noreferrer">北區服務據點</a></p>
    <p class="indent">若有任何問題，歡迎來電本會。</p>
    <p class="indent">聯絡電話：<br>
    　　北區(含宜蘭、花蓮)：02-775-16788#610301、#613091、#610408<br>
    　　基隆：02-242-31289#15<br>
    　　桃區：03-275-6133#1305<br>
    　　竹區：03-532-5566<br>
    　　中區：04-370-69300#620202<br>
    　　雲嘉：05-283-3940#203<br>
    　　台南：06-264-6831#351<br>
    　　高屏(含台東)：07-974-3280#68104</p>
    <p class="indent">（洽詢時間：週一～週五 10:00～ 20:00、週六 10:00～16:00）</p>
</td></tr><tr><td align="right">
<br>
<a class="right">主辦單位：財團法人福智文教基金會&nbsp;敬啟</a><br>
<a class="right">{{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}</a>
</td></tr></table></td></tr>
</body>
