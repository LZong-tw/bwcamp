<style>
    u{
        color: red;
    }
</style>
<p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
<p class="card-text">非常恭喜您錄取「{{ $applicant->batch->camp->fullName }}」！</p>
<p class="card-text">
    錄取編號：{{ $applicant->group }}{{ $applicant->number }}<br>
    營隊場次：{{ $applicant->batch->name }} <br>
    營隊日期：{{ $applicant->batch->batch_start }} ~ {{ $applicant->batch->batch_end }}（六、日），共2天<br>
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