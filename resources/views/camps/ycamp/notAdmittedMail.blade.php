<style>
    u{
        @if($applicant->batch->camp->variant == "utcamp")
            color: red;
        @endif
    }

    .center{
        text-align: center;
    }

    .right{
        text-align: right;
    }
</style>
<h2 class="center">{{ $applicant->batch->camp->fullName }}<br>備取通知單</h2>
<font size="3">
<p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
<p class="card-text indent">非常感謝您報名參加「{{ $campFullData->fullName }}」，由於本活動報名人數踴躍，且場地有限，非常抱歉未能在第一階段錄取您。我們已將您列入優先備取名單，若有遞補機會，基金會將儘速通知您!</p>
<p class="card-text indent">開學後，各區福青學堂定期都有精彩的課程活動，竭誠歡迎您的參與!也祝福您學業順利，吉祥如意！</p>
</font>
<br>
<font size="3">
<b><p class="card-text">各區福青學堂資訊</p></b>
<table class="table table-borderless">
    <tr>
        <td>
            <p class="card-text">
            台北福青學堂<br>
            02-2545-3788 #515、546、518、503、502<br>
            台北市松山區南京東路四段161號9樓<br>
            </p>
            <p class="card-text">
            桃園福青學堂<br>
            03-275-6133 #1324<br>
            桃園市中壢區強國路121號2樓<br>
            </p>
            <p class="card-text">
            新竹福青學堂<br>
            03-571-0968<br>
            新竹市東區忠孝路43號2樓<br>
            </p>
            <p class="card-text">
            台中福青學堂<br>
            04-37069300 #621101<br>
            台中市西屯區臺灣大道二段669號2樓<br>
            </p>
        </td>
        <td>
            <p class="card-text">
            雲嘉福青學堂<br>
            05-5370133 #125<br>
            雲林縣斗六市慶生路6號 (斗六火車站武昌路出口)<br>
            </p>
            <p class="card-text">
            台南福青學堂<br>
            06-289-6558<br>
            台南市東區崇明路405號4樓<br>
            </p>
            <p class="card-text">
            高雄福青學堂<br>
            07-974-1170<br>
            高雄市新興區中正四路53號12樓之7<br>
            </p>
            <p class="card-text">
            花蓮大專班籌備處<br>
            03-831-6305<br>
            花蓮市中華路243號2樓<br>
            </p>
        </td>  
    </tr>   
</table>
<!--
<p class="card-text indent"><a href="http://bwfoce.org/web" target="_blank" rel="noopener noreferrer">http://bwfoce.org/web</a></p>
<p class="card-text indent">祝福您身心健康，吉祥如意！</p>
-->
<p class="card-text text-right">財團法人福智文教基金會 敬啟</p>
<p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
</font>
