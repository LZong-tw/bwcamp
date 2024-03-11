<style>
    u{
        color: red;
    }
</style>
<h2 class="center">{{ $applicant->batch->camp->fullName }}<br>錄取通知單（三天）</h2>
<p class="card-text">{{ $applicant->name }} 您好</p>
<p class="card-text">您的錄取組別為：{{ $applicant->group }}，錄取編號為：{{ $applicant->group }}{{ $applicant->number }}。</p>
<p class="card-text">我們誠摯歡迎您來共享這場心靈饗宴。三天研習務必全程參加，請參閱下列說明。</p>
<p class="card-text">
    <h4>【營隊資訊】</h4>
        <div class="ml-4 mb-2">1.研習日期：2024年7月19日(星期五)至7月21日(星期日)，請務必<u>全程參加</u>。</div>
        <div class="ml-4 mb-2">2.報到時間：2024年7月19日(星期五)</div>
        <div class="ml-4 mb-2">3.報到地點：開南大學(桃園市蘆竹區開南路1號)(詳見報到通知單，預計6月寄出Email)</div>
    <h4>【參加確認回條】</h4>
        <div class="ml-4 mb-2">請點擊以下連結由瀏覽器進入頁面做回覆<a href="https://bwcamp.bwfoce.org/camp/{{ $applicant->batch->id }}/showadmit?sn={{ $applicant->id }}&name={{ $applicant->name }}">確認參加</a></div>
        <div class="ml-4 mb-2">若以上連結無法點選，請複製下方文字後，再由瀏覽器進入頁面做回覆：</div>
        <div class="ml-4 mb-2">https://bwcamp.bwfoce.org/camp/{{ $applicant->batch->id }}/queryadmit</div>
    <h4>【建議攜帶物品】</h4>
        <div class="ml-4 mb-2">以下謹列出參加此次活動所需攜帶物品，以及本營隊提供之物品，方便您準備之依據。</div>
        <br>
        <div class="ml-4 mb-2">＊多套換洗衣物(洗衣不方便)、備用袋(裝使用過之衣物)。</div>
        <div class="ml-4 mb-2">＊毛巾、牙膏、牙刷、香皂、洗髮精、拖鞋、衣架、輕薄外套(防上課地點冷氣過冷)。</div>
        <div class="ml-4 mb-2">＊寢具(睡袋或薄被、枕頭、軟墊)(寢室有冷氣)。</div>
        <div class="ml-4 mb-2">＊隨身背包(教材約A4大小)、文具用品、環保水杯、環保筷、摺疊傘、遮陽帽。</div>
        <div class="ml-4 mb-2">＊刮鬍刀、耳塞(睡覺時易受聲音干擾者)、眼罩、口罩、手帕。</div>
        <div class="ml-4 mb-2">＊身份證、健保卡(生病時就診用)。</div>
        <div class="ml-4 mb-2">＊個人常用藥物。</div>
        <br>
        <div class="ml-4 mb-2">請勿攜帶貴重物品，本會無法負保管責任！</div>
    <h4>【注意事項】</h4>
        <div class="ml-4 mb-2">＊<b>如您目前懷孕中，請考量本營隊因為休息皆為硬板床、一天十多小時上課、及天氣炎熱多次出入冷氣房等各項因素，依照您的身體狀況慎重考量</b>。</div>
        <div class="ml-4 mb-2">＊<u>校園停車位有限</u>，請多利用大眾交通運輸工具。預計在台鐵/高鐵桃園站備有接駁巴士接送。</div>
        <div class="ml-4 mb-2">＊本次營隊報名踴躍，因場地考量容納有限，若您無法全程參加，請告知關懷員，感謝您的善行。謝謝！</div>
</p>
<p class="card-text">本會關懷員近日將以電話跟您確認，若有任何問題，歡迎與該關懷員聯絡，或致電本會。</p>
<p class="card-text">聯絡電話：02-7751-6788 分機：610408、613091、610301</p>
<p class="card-text">洽詢時間：週一～週五 10:00～20:00、週六 10:00～16:00</p>
<br><br>
<p class="card-text text-right">主辦單位：財團法人福智文教基金會 敬啟</p>
<p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
<p class="card-text">Facebook 卓越青年 <a href="https://www.facebook.com/YoungOneCamp" target="_blank" rel="noopener noreferrer">https://www.facebook.com/YoungOneCamp</a></p>
<p class="card-text">{{ $applicant->batch->camp->fullName }}官方網站 <a href="http://www.youngone.org.tw/camp/" target="_blank" rel="noopener noreferrer">http://www.youngone.org.tw/camp/</a></p>
