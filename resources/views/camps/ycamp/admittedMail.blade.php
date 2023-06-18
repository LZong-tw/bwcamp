<style>
    u{
        color: red;
    }
</style>
<h2 class="center">{{ $applicant->batch->camp->fullName }}<br>【錄取/報到通知單】</h2>
<p class="card-text">親愛的 {{ $applicant->name }} 同學，您好：</p>
<p class="card-text">非常恭喜您錄取「{{ $applicant->batch->camp->fullName }}」！此次營隊報名人數超過1,500人，錄取1,100 名，竭誠歡迎您的到來！請於6月28日(三)前上網回覆交通方式！另請詳閱以下相關訊息，祝福您營隊收穫滿滿。</p>
<p class="card-text">
    錄取編號：{{ $applicant->group }}{{ $applicant->number }}<br>
    營隊期間：{{ $applicant->batch->batch_start }}(五) ~ {{ $applicant->batch->batch_end }}(一)，共4天<br>
    營隊地點：{{ $applicant->batch->locationName }}({{ $applicant->batch->location }})<br>
    報到時間：{{ $applicant->batch->check_in_day }}(五)上午10:30～11:30<br>
    交通方式：
    (1)	各區專車：台北、桃園、新竹、台中、台南、高雄等地備有專車接送。
    (2)	火車站接駁車：於斗六火車站武昌路出口處有免費接駁服務。若回程欲搭乘火車者，請務必事先買好回程車票，避免回程時買不到車票。
    (3)	各區專車與火車站集合接駁時間、地點、車資等，請詳見附件。
    交通方式：
    (1)	各區專車：台北、桃園、新竹、台中、台南、高雄等地備有專車接送。
    (2)	火車站接駁車：於斗六火車站武昌路出口處有免費接駁服務。若回程欲搭乘火車者，請務必事先買好回程車票，避免回程時買不到車票。
    (3)	各區專車與火車站集合接駁時間、地點、車資等，請詳見附件。

</p>    
<p class="card-text indent">期待與您共享這場心靈饗宴！請詳閱以下相關訊息，祝福您營隊收穫滿滿。</p>
<p class="card-text">
<h4>營隊相關資訊</h4>
    <div class="ml-0 mb-2">1.因應疫情，營隊各梯次皆改為線上舉辦。</div>
    <div class="ml-0 mb-2">2.經錄取後，敬請全程參與本活動。全程參與者，發給研習證明文件。</div>
    <div class="ml-0 mb-2">3.有任何問題，請Email至<a href="mailto:youth@blisswisdom.org">youth@blisswisdom.org</a>，或於<a href="https://www.facebook.com/bwyouth" target="_blank" rel="noopener noreferrer">福智青年粉專</a>留言</div>
<!--
<h4>確認參加</h4>
<p class="card-text indent">請點擊以下網址 <a href="https://bwcamp.bwfoce.org/camp/19/showadmit?sn={{ $applicant->id }}&name={{ $applicant->name }}">https://bwcamp.bwfoce.org/camp/19/queryadmit</a> 回覆確認參加。</p>
<p>若以上連結無法點選，請複製下方文字後，再由瀏覽器進入頁面做回覆：</p>
<p>https://bwcamp.bwfoce.org/camp/19/queryadmit</p>
-->
<br>
<p class="card-text text-right">財團法人福智文教基金會 敬啟</p>
<p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>