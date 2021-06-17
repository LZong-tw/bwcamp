<style>
    u{
        color: red;
    }
</style>
<p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
<p class="card-text indent">非常恭喜您錄取「{{ $applicant->batch->camp->fullName }}」，您的錄取編號為：{{ $applicant->group }}{{ $applicant->number }}。</p>
<p class="card-text indent">期待與你共享這場心靈饗宴！請詳閱以下相關訊息，祝福您活動收穫滿滿。</p>
<p class="card-text">
<h4>營隊資訊</h4>
    <div class="ml-4 mb-2 text-danger" style="color: red">本次營隊因應疫情改為線上舉辦。</div>
    <div class="ml-4 mb-2">營隊期間：2021/8/14~15 (六、日) 9:30~17:30  共 2天</div>
<h4>營隊場次：{{ $applicant->batch->name }}</h4>
<h4>確認參加</h4>
<p class="card-text indent">請點擊以下網址 <a href="https://bwcamp.bwfoce.org/camp/19/showadmit?sn={{ $applicant->id }}&name={{ $applicant->name }}">https://bwcamp.bwfoce.org/camp/19/queryadmit</a> 回覆確認參加。</p>
<h4>★錄取學員敬請全程參與本活動。全程參與者，發給研習證明文件。</h4>
<p class="card-text indent">有任何問題，請Email至<a href="mailto:youth@blisswisdom.org">youth@blisswisdom.org</a>，或於<a href="https://www.facebook.com/bwyouth" target="_blank" rel="noopener noreferrer">福智青年粉專</a>留言</p>
<p class="card-text text-right">財團法人福智文教基金會 敬啟</p>
<p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>



 錄取學員敬請全程參與本活動。全程參與者，發給研習證明文件。