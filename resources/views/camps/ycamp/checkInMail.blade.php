<style>
    u{
        color: red;
    }
</style>
<h2 class="center">{{ $applicant->batch->camp->fullName }}<br>【錄取/報到通知單】</h2>
<p class="card-text">親愛的 {{ $applicant->name }} 同學您好：</p>
<p class="card-text text-indent">非常恭喜您錄取「{{ $applicant->batch->camp->fullName }}」！竭誠歡迎您的到來！<u>請於6月20日(五) ~ 6月30日(一)上網回覆交通方式！</u>並請詳閱以下訊息，祝福您營隊收穫滿滿。</p>
<p class="card-text text-indent">
您的報名序號：{{ $applicant->id }}<br>
您的錄取編號：{{ $applicant->group }}{{ $applicant->number }}<br>
營隊日期：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }}) ~ {{ $applicant->batch->batch_end }}({{ $applicant->batch->batch_end_weekday }})，共4天<br>
營隊地點：{{ $applicant->batch->locationName }}({{ $applicant->batch->location }})<br>
</p>
<ul>
    <li><p class="card-text indent"><a href="http://bwcamp.bwfoce.org/downloads/ycamp2025/【2025第58屆大專青年生命成長營】錄取通知單.pdf">錄取/報到通知連結</a></p></li>
    <li><p class="card-text indent">上網<a href="https://bwcamp.bwfoce.org/camp/{{ $applicant->batch->id }}/showadmit?sn={{ $applicant->id }}&name={{ $applicant->name }}">回覆交通方式</a></p>
    <p>若以上連結無法點選，請複製下方文字後，再由瀏覽器進入頁面做回覆：</p>
    <p>https://bwcamp.bwfoce.org/camp/{{ $applicant->batch->id }}/queryadmit</p>
    </li>
</ul>
<br>
<p class="card-text text-right">財團法人福智文教基金會 敬啟</p>
<p class="card-text text-right">{{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}</p>
