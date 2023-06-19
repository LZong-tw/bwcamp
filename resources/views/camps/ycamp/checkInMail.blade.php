<style>
    u{
        color: red;
    }
</style>
<h2 class="center">{{ $applicant->batch->camp->fullName }}<br>【錄取/報到通知單】</h2>
<p class="card-text">親愛的 {{ $applicant->name }} 同學您好：</p>
<p class="card-text text-indent">非常恭喜您錄取「{{ $campFullData->fullName }}」！</p>
<p class="card-text text-indent">
您的報名序號：{{ $applicant->id }}<br>
您的錄取編號：{{ $applicant->group }}{{ $applicant->number }}<br>
營隊日期：{{ $applicant->batch->batch_start }}(五) ~ {{ $applicant->batch->batch_end }}(一)，共4天<br>
營隊地點：{{ $applicant->batch->locationName }}({{ $applicant->batch->location }})<br>
</p>
<p class="card-text text-indent">此次營隊報名人數超過1,500人，錄取1,100 名，竭誠歡迎您的到來！請詳閱錄取/報到通知，並於6月28日(三)前回覆交通方式！祝福您營隊收穫滿滿。</p>
<ul>
    <li><p class="card-text indent"><a href="http://bwcamp.minuet.com/downloads/ycamp2023/【2023第56屆大專青年生命成長營】錄取通知單.pdf">錄取/報到通知連結</a></p></li>
    <li><p class="card-text indent">上網<a href="https://bwcamp.bwfoce.org/camp/{{ $applicant->batch->id }}/showadmit?sn={{ $applicant->id }}&name={{ $applicant->name }}">回覆交通方式</a></p>
    <p>若以上連結無法點選，請複製下方文字後，再由瀏覽器進入頁面做回覆：</p>
    <p>https://bwcamp.bwfoce.org/camp/{{ $applicant->batch->id }}/queryadmit</p>
    </li>
</ul>
<br>
<p class="card-text text-right">財團法人福智文教基金會 敬啟</p>
<p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
