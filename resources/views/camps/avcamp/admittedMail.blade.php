<style>
    u{
        color: red;
    }
</style>
<p class="card-text">{{ $applicant->name }} 您好</p>
<p class="card-text indent">恭喜您錄取「{{ $applicant->batch->camp->fullName }}」！您的錄取組別為：{{ $applicant->group }}，錄取編號為：{{ $applicant->group }}{{ $applicant->number }}。
歡迎您參加本研習活動，我們誠摯歡迎您來共享這場心靈饗宴。相關訊息請參閱下列說明。</p>
<p class="card-text">
    <h4>營隊資訊</h4>
        <div class="ml-4 mb-2">由於新冠疫情，依中央流行疫情指揮中心公布防疫措施進行營隊，目前規劃為實體，未來會視疫情實際情況調整，若改為線上課程方式舉辦會另行通知。</div>
        <div class="ml-4 mb-2">研習日期：2022/7/29(五) ~ 7/31(日) 三天不住宿</div>
        <div class="ml-4 mb-2">報到地點：東吳大學、各區同步場地（詳見報到通知單）</div>
    <h4>填寫收件地址</h4>
        <div class="ml-4 mb-2">請提供收件地址，以利收到課前禮物包。</div>
        <div class="ml-4 mb-2">收件地址調查問卷 <a href="https://forms.gle/kxw2W81SjKxkrV8A9">https://forms.gle/kxw2W81SjKxkrV8A9</a></div>
    <h4>確認參加</h4>
        <p class="card-text indent">請點擊以下網址 <a href="https://bwcamp.bwfoce.org/camp/{{ $applicant->batch_id }}/showadmit?sn={{ $applicant->id }}&name={{ $applicant->name }}">https://bwcamp.bwfoce.org/camp/{{ $applicant->batch_id }}/queryadmit</a> 回覆確認參加。</p>
        <p>若以上連結無法點選，請複製下方文字後，再由瀏覽器進入頁面做回覆：</p>
        <p>https://bwcamp.bwfoce.org/camp/{{ $applicant->batch_id }}/queryadmit</p>
    <h4>建議攜帶物品</h4>
        <div class="ml-4 mb-2">以下謹列出參加此次活動所需攜帶物品，以及本營隊提供之物品，方便您準備之依據。</div>
        <div class="ml-4 mb-2">＊隨身背包（教材約A4大小）、文具用品、環保水杯、環保筷、摺疊傘、遮陽帽。</div>
        <div class="ml-4 mb-2">＊身份證、健保卡（生病時就診用）。</div>
        <div class="ml-4 mb-2">＊個人常用藥物。</div>
        <div class="ml-4 mb-2">請勿攜帶貴重物品，本會無法負保管責任！</div>
    <h4>注意事項</h4>
        <div class="ml-4 mb-2">＊三天研習課程務必全程參加，若您無法全程參加，請告知關懷員，感謝您的貼心。</div>
        <div class="ml-4 mb-2">＊本會關懷員近日將以電話跟您確認，若有任何問題，歡迎與該關懷員聯絡，或來電本會。</div>
</p>
<br>
<p class="card-text">若您對營隊有任何問題，歡迎您透過以下方式聯絡本基金會，我們將盡速為您服務。</p>
<p class="card-text">感謝您的支持！</p>
<p class="card-text text-right">主辦單位：財團法人福智文教基金會 敬啟</p>
<p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
<p class="card-text">聯絡電話：02-7751-6788 分機：610408、613091、610301</p>
<p class="card-text">洽詢時間：週一～週五 10:00～20:00、週六 10:00～16:00</p>
<p class="card-text">Facebook 卓越青年 <a href="https://www.facebook.com/YoungOneCamp" target="_blank" rel="noopener noreferrer">https://www.facebook.com/YoungOneCamp</a></p>
<p class="card-text">{{ $applicant->batch->camp->fullName }}官方網站 <a href="http://www.youngone.org.tw/camp/" target="_blank" rel="noopener noreferrer">http://www.youngone.org.tw/camp/</a></p>
