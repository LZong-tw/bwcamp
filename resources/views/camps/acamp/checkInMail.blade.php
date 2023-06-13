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
　　歡迎您參加「{{ $applicant->batch->camp->fullName }}」，我們誠摯歡迎您來共享這場心靈饗宴，希望您能獲得豐盛的收穫。為使三天之研習進行順利，請詳閱下列須知。
</p>
<table width="100%" style="table-layout:fixed; border: 0; word-wrap: break-word;">
<tr><td>        
    <ol>
        <li>研習日期：2023年8月4日(星期五)至8月6日(星期日)</li>
        <li><b>報到時間：2023年8月4日(星期五) 09:00~09:50</b>
            <p style="color: red; font-weight: bolder;">敬請準時報到，若有特殊情形請告知關懷員。</p>
        </li>            
        <li>報到地點：開南大學(桃園市開南路一號)(開南大學全面禁菸)
        <li>交通接駁：
            <ul>
                <li>本基金會將於 09:00~10:20 在以下地點提供交通接駁服務，現場有「福智」字樣之黃色背心義工協助引導(逾10:20請自行搭計程車前往開南大學)。
                (1)桃園<u><b>台鐵車站後站</b>出口處</u>；(2)桃園<u><b>高鐵車站5號</b>出口處</u><br>
                </li>
                <li>交通資訊請參閱開南大學網頁<br>
                <a href="https://recruit.knu.edu.tw/p/412-1003-461.php?Lang=zh-tw">https://recruit.knu.edu.tw/p/412-1003-461.php?Lang=zh-tw</a><br>
                </li>
            </ul>
        </li>            
        <li>搭乘各區專車者，請依搭車地點及時間準時上車。</li>
    </ol>
</td></tr>
</table>
<p>
    &#10070;攜帶物品：
    <ul>
        <li>以下謹列出參加此次活動所需攜帶物品，方便您準備行李之依據。<br>
            □ 多套換洗衣物(洗衣不方便)、備用袋(裝使用過之衣物)。<br>
            □ 毛巾、牙膏、牙刷、香皂、洗髮精、拖鞋、衣架、輕薄外套(防上課地點冷氣過冷)。<br>
            □ 寢具(睡袋或薄被、枕頭、軟墊)。(寢室有冷氣)<br>
            □ 隨身背包(教材約A4大小)、文具用品、環保水杯、環保筷、摺疊傘、遮陽帽。<br>
            □ 刮鬍刀、耳塞(睡覺時易受聲音干擾者)、眼罩、口罩、手帕。<br>
            □ 身份證、健保卡(生病時就診用)。<br>
            □ 個人常用藥物。<br>
        </li> 
        <li>請勿攜帶貴重物品，本會無法負保管責任！</li> 
    </ul>
    &#10070;注意事項：
    <ul>
        <li>如您目前懷孕中，請考量本營隊因為休息皆為硬板床、一天十多小時上課、及天氣炎熱多次出入冷氣房等各項因素，依照您的身體狀況慎重考量。</li> 
        <li>若有任何問題，歡迎與關懷員聯絡，或來電本會。本次營隊報名踴躍，因場地考量容納有限，若您無法全程參加，請告知關懷員，感恩您的善行。</li>
        <li>研習期間晚上也安排各項學習課程，為達成研習效果，請勿外出及外宿。</li>
        <li>會場停車位有限，響應節能減碳，請多利用大眾捷運工具，本會將提供交通接駁服務。</li>
        <li>本營隊沒有提供網路服務。</li>
        <li>請先購妥8/6(星期日)下午5:00以後班次之回程車票(課程預計於下午3:35結束)。</li>
        <li>為了維持自己上課品質，且不影響他人學習，請不要攜帶酒類飲料進入營隊。</li>
        <li>本基金會依著愛護地球少吃肉的理念，在這三天營隊期間將提供精緻蔬食。</li>
        <li>若有任何問題，歡迎與關懷員聯絡，或來電本會<br>
        聯絡電話：02-7751-6788分機：610408、613091、610301 <br>
        洽詢時間：週一～週五 10:00 ~ 20:00、週六 10:00～16:00 <br>
        </li>
    </ul>
</p>
<br>
<a class="right">主辦單位：財團法人福智文教基金會　敬啟</a><br> 
<a class="right">{{ \Carbon\Carbon::now()->year }}年 {{ \Carbon\Carbon::now()->month }}月 {{ \Carbon\Carbon::now()->day }}日</a>
<p>
    Facebook卓越青年 <a href="https://www.facebook.com/YoungOneCamp" target="_blank" rel="noopener noreferrer">https://www.facebook.com/YoungOneCamp</a> <br>
    卓越青年生命探索營官方網站 <a href="http://www.youngone.org.tw/camp/" target="_blank" rel="noopener noreferrer">http://www.youngone.org.tw/camp/</a> 
</p>
</body>