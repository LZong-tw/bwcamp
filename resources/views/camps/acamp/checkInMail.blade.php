<style>
    u{
        color: red;
    }
    .right{
        float: right;
        margin-bottom: 5px;
        clear: both;
    }
</style>
<body style="font-size:16px;">
<h2 class="center">{{ $applicant->batch->camp->fullName }}</h2>
<h2 class="center">報到通知單(三天)</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>姓名：{{ $applicant->name }}</td>
        <td>組別：{{ $applicant->group }}</td>
        <td>錄取序號：{{ $applicant->group }}{{ $applicant->number }}</td>
    </tr>
</table>
<p>
　　歡迎您參加「{{ $applicant->batch->camp->fullName }}」，共享這場心靈饗宴。為使三天之研習進行順利，請詳閱下列須知。
</p>
<h4>&#10023;報到及交通：</h4>
<p><ol>
    <li>研習日期：2023年8月4日(星期五)至8月6日(星期日)</li>
    <li><b>報到時間：2023年8月4日(星期五) <u>09:00~09:50</u></b><br>
        敬請準時報到，若有特殊情形請告知關懷員。</li>            
    <li>報到地點：開南大學。地址：桃園市開南路一號。</li>
    <li>報到方式：報到時請先準備好個人專屬QR code (印出來或手機直接顯示皆可) ，
        掃完QR Code確認報到後，請至所屬組別桌辦理住宿及領取個人名牌</li>
    <li>交通資訊請參閱開南大學網頁<br>
        <a href="https://recruit.knu.edu.tw/p/412-1003-461.php?Lang=zh-tw">https://recruit.knu.edu.tw/p/412-1003-461.php?Lang=zh-tw</a></li>
    <li>接駁服務：
        <ul>
            <li>接駁車地點：<br>
                (1)桃園<b>台鐵車站<u>後站(往延平路/大林路出口)</u></b>出口處。<br>
                (2)桃園<b>高鐵車站<u>5號</u></b>出口處。</li>
            <li>接駁服務時間：2023年8月4日(五) 08:30~09:30。<br>
                逾時請自行搭計程車前往開南大學。</li>
            <li><u>現場有「福智」字樣之黃色背心義工協助引導。</u><br></li>
        </ul>
    </li>            
    <li>搭乘各區專車者，請依搭車地點及時間準時上車。</li>
    <li>會場停車位有限，請多利用大眾捷運工具，及本會提供交通接駁服務。</li>
</ol></p>
<h4>&#10023;攜帶物品：</h4>
<p><ul>
    <li>以下謹列建議攜帶物品，請參考。<br>
        □ 多套換洗衣物(洗衣不方便)、備用袋(裝使用過之衣物)。<br>
        □ 盥洗用品：如毛巾、牙膏、牙刷、香皂、洗髮精等。<br>
        □ 其他生活用品，如拖鞋、衣架、刮鬍刀輕、耳塞、眼罩、口罩、手帕等。<br>
        □ 營隊備有簡單寢具(枕頭、薄被、簡易軟墊)，可依個人需求自行攜帶添增。(寢室有冷氣)<br>
        □ 隨身背包(教材約B5大小)<br>
        □ 文具用品、環保水杯、環保筷、摺疊傘、遮陽帽、輕薄外套(上課地點有冷氣)。<br>
        □ 身份證、健保卡(生病時就診用)。<br>
        □ 個人常用藥物。<br>
    </li> 
    <li>請勿攜帶貴重物品，本會無法負保管責任！</li> 
</ul></p>
<h4>&#10023;注意事項：</h4>
<p><ul>
    <li>本營隊寢室皆為硬板床、每天上課時數長、並且會在天氣炎熱情況下多次出入冷氣房。請依照您的身體狀況（如：懷孕中，臨時感冒等）慎重考量是否參加。</li> 
    <li>本次營隊報名踴躍，因場地容納人數有限，若您無法全程參加，請提早告知關懷員，感恩您的配合。</li>
    <li>為達較佳的研習效果，營隊期間請勿外出及外宿。</li>
    <li>營隊期間提供<u>精緻蔬食</u>，不提供葷食。</li>
    <li>營隊場地<u>沒有</u>提供網路服務。</li>
    <li>請勿攜帶酒類飲料進入營隊。</li>
    <li>配合開南大學，本營隊場地全面禁菸。</li>
    <li>課程預計於8月6日下午3:20結束。如有回程車票需求，建議購買下午5:00以後班次。</li>
</ul></p>
<h4>&#10023;聯絡我們：</h4>
<p><ul>
    <li>若有任何問題，歡迎與關懷員聯絡，或來電本會。<br></li>
    <li>聯絡電話：<br>
    北區：02-7751-6788分機:610408、613091、610301<br>
    桃區：03-275-6133#1305<br>
    竹區：03-532-5566<br>
    中區：04-3706-9300#620202<br>
    雲嘉：05-2833940#203<br>
    台南：06-264-6831 #353<br>
    高屏：07-9743280#68102<br></li>
    <li>洽詢時間：週一∼週五 10:00 ~ 20:00、週六 10:00～16:00</li>
</ul></p>
<br>
<br>
<p style="text-align:right;">主辦單位：財團法人福智文教基金會　敬啟<br>
{{ \Carbon\Carbon::now()->year }}年 {{ \Carbon\Carbon::now()->month }}月 {{ \Carbon\Carbon::now()->day }}日</p>
<p>
    --<br>
    Facebook卓越青年 <a href="https://www.facebook.com/YoungOneCamp" target="_blank" rel="noopener noreferrer">https://www.facebook.com/YoungOneCamp</a> <br>
    卓越青年生命探索營官方網站 <a href="http://www.youngone.org.tw/camp/" target="_blank" rel="noopener noreferrer">http://www.youngone.org.tw/camp/</a> 
</p>
</body>