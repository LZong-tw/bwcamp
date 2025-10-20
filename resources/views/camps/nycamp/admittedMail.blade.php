<style>
    u{
        color: red;
    }
</style>
<h2 class="center">{{ $applicant->batch->camp->fullName }}<br>Acceptance Letter</h2>
<p class="card-text">Dear {{ $applicant->name }},</p>
<p class="card-text text-indent">Congratulations on your acceptance to the {{ $applicant->batch->camp->fullName }}! We're excited to have you join us on this meaningful journey. Please review the important information below carefully.</p>
<p class="card-text text-indent">
Your Registration Number: {{ $applicant->id }}<br>
Your Admission Number: {{ $applicant->group }}{{ $applicant->number }}<br>
Camp Dates: {{ $applicant->batch->batch_start }}({{ $applicant->batch_start_Weekday }}) ~ {{ $applicant->batch->batch_end }}({{ $applicant->batch_end_Weekday }}) (4 days, 3 nights)<br>
Camp Location: {{ $applicant->batch->locationName }}({{ $applicant->batch->location }})<br>
</p>
<ul>
    <li><p class="card-text indent">For more detailed information, please read <a href="https://docs.google.com/document/d/1DK2mQK6beqShyK82Jigyt1z7PJv_MwWa2yEadLCrsR8/edit?tab=t.0#heading=h.ba2ohoogolxy">Acceptance Letter</a></p></li>
    <li><p class="card-text indent"><a href="{{ route('showadmit', ['batch_id' => $applicant->batch->id, 'sn' => $applicant->id, 'name' => $applicant->name]) }}">Make your lodging and transportation options</a> and pay to complete the registration process.</p>
    <p>If you have problem with the above link, you may copy the following url and paste to the your browser to enter the page.</p>
    <p>{{ route('queryadmitGET', ['batch_id' => $applicant->batch->id]) }}</p>
    </li>
</ul>
<br>
<p class="card-text text-right">Warm regards, </p>
<p class="card-text text-right">The Oneness Truth Foundation</p>
<p class="card-text text-right">{{ \Carbon\Carbon::now()->month }}/{{ \Carbon\Carbon::now()->day }}/{{ \Carbon\Carbon::now()->year }}</p>
<br>
<br>
<br>
<h2 class="center">{{ $applicant->batch->camp->fullName }}<br>【錄取/報到通知單】</h2>
<p class="card-text">親愛的 {{ $applicant->name }} 同學您好：</p>
<p class="card-text text-indent">非常恭喜您錄取「{{ $applicant->batch->camp->fullName }}」！竭誠歡迎您的到來！<u>請於6月20日(五) ~ 6月30日(一)上網回覆交通方式！</u>並請詳閱以下訊息，祝福您營隊收穫滿滿。</p>
<p class="card-text text-indent">
您的報名序號：{{ $applicant->id }}<br>
您的錄取編號：{{ $applicant->group }}{{ $applicant->number }}<br>
營隊日期：{{ $applicant->batch->batch_start }}({{ $applicant->batch_start_Weekday }}) ~ {{ $applicant->batch->batch_end }}({{ $applicant->batch_end_Weekday }})，共4天<br>
營隊地點：{{ $applicant->batch->locationName }}({{ $applicant->batch->location }})<br>
</p>
<ul>
    <li><p class="card-text indent"><a href="{{ url('downloads/' . $applicant->batch->camp->table . '/' . $applicant->batch->camp->year . '/【' . $applicant->batch->camp->year . '第58屆大專青年生命成長營】錄取通知單.pdf') }}">錄取/報到通知連結</a></p></li>
    <li><p class="card-text indent">上網<a href="{{ route('showadmit', ['batch_id' => $applicant->batch->id, 'sn' => $applicant->id, 'name' => $applicant->name]) }}">回覆交通方式</a></p>
    <p>若以上連結無法點選，請複製下方文字後，再由瀏覽器進入頁面做回覆：</p>
    <p>{{ route('queryadmitGET', ['batch_id' => $applicant->batch->id]) }}</p>
    </li>
</ul>
<br>
<p class="card-text text-right">The Oneness Truth Foundation 敬啟</p>
<p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
