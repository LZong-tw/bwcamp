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
        <td>錄取編號：{{ $applicant->group }}{{ $applicant->number }}</td>
    </tr>
</table>
<p>
　　歡迎您參加「2022卓越青年生命探索營」，我們誠摯歡迎您來共享這場心靈饗宴，希望您能獲得豐盛的收穫。
為使三天之研習進行順利，請詳閱下列須知。
</p>
<table width="100%" style="table-layout:fixed; border: 0; word-wrap: break-word;">
<tr><td>        
    <ol>
        <li>研習日期：2022年7月29日(星期五)至7月31日(星期日)，三天不過夜。</li>
        <li>報到時間：
            <p style="font-weight: bolder;">
                2022年7月29日(星期五)09:30～10:00(課程預計20:10結束，提供午晚餐) <br>
                2022年7月30日(星期六)08:30～08:50(課程預計20:30結束，提供午晚餐) <br>
                2022年7月31日(星期日)08:30～08:50(課程預計16:00結束，提供午餐) <br>
                <a style="color: red; font-weight: bolder;">敬請準時報到，若有特殊情形請告知關懷員。</a>
            </p>
        </li>            
        <li>報到地點：(請依照錄取組別區域參加，如有需要變更請盡早聯繫關懷員)
            <p>
                北區：大同大學 尚志大樓，台北市中山區中山北路三段40號(依現場義工引導)<br>
                桃區：桃園市桃園區大興西路二段99號2樓<br>
                竹區：新竹市東區忠孝路43號2樓<br>
                中區：台中市西屯區台灣大道二段659號11樓<br>
                雲嘉：雲林縣斗六市慶生路6號3樓<br>
                台南：台南市文南路67號3樓<br>
                高屏：高雄市鳳山區中山西路129號3樓<br>
            </p>
        </li>            
        <li>報到方式：出示本通知單附件，依照現場義工引導進行報到。</li>
        <li>交通：請以大眾交通工具優先，活動場地未提供停車空間。</li>
    </ol>
</td></tr>
</table>
<p>
    &#10070;以下謹列出參加此次活動所需攜帶物品，方便您準備之依據。
    <ul>
        <li>建議攜帶物品：<br>
            □ 隨身背包、文具用品、環保水杯、環保筷、摺疊傘。<br>
            □ 身份證、健保卡(生病時就診用)。<br>
            □ 個人常用藥物。<br>
        </li> 
        <li>請勿攜帶貴重物品，本會無法負保管責任！</li> 
    </ul>
    &#10070;注意事項：
    <ul>
        <li>若您無法全程參加，請告知關懷員，感恩您的善行。</li> 
        <li>若有任何問題，歡迎與關懷員聯絡，或來電本會。</li> 
    </ul>
</p>
<br>
<a class="right">主辦單位：財團法人福智文教基金會　敬啟</a><br> 
<a class="right">{{ \Carbon\Carbon::now()->year }}年 {{ \Carbon\Carbon::now()->month }}月 {{ \Carbon\Carbon::now()->day }}日</a>
<p>
    聯絡電話：02-7751-6788分機：610408、613091、610301 <br>
    洽詢時間：週一～週五 10:00 ~ 20:00、週六 10:00～16:00 <br>
    Facebook卓越青年 <a href="https://www.facebook.com/YoungOneCamp" target="_blank" rel="noopener noreferrer">https://www.facebook.com/YoungOneCamp</a> <br>
    卓越青年生命探索營官方網站 <a href="http://www.youngone.org.tw/camp/" target="_blank" rel="noopener noreferrer">http://www.youngone.org.tw/camp/</a> 
</p>
</body>