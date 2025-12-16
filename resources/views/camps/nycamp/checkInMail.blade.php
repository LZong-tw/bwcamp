<style>
    u{
        color: red;
    }
</style>
<h2 class="center">{{ $applicant->batch->camp->fullName }}<br>Pre-Departure Guide</h2>
<p class="card-text">Dear {{ $applicant->name }},</p>
<p class="card-text text-indent">Congratulations on your acceptance to the {{ $applicant->batch->camp->fullName }}! We're excited to have you join us on this meaningful journey. Before you come, please review the important information below carefully.</p>
<p class="card-text text-indent">
Your Registration Number: {{ $applicant->id }}<br>
Your Admission Number: {{ $applicant->group }}{{ $applicant->number }}<br>
Camp Dates: {{ $applicant->batch->batch_start }}({{ $batch_start_Weekday }}) ~ {{ $applicant->batch->batch_end }}({{ $batch_end_Weekday }}) (4 days, 3 nights)<br>
Camp Location: {{ $applicant->batch->locationName }}({{ $applicant->batch->location }})<br>
</p>
<ul>
    <li><p class="card-text indent">For more detailed information, please read <a href="{{ $content_link_eng }}">Pre-Departure Guide</a></p></li>
    <li><p class="card-text indent"><a href="{{ route('showadmit', ['batch_id' => $applicant->batch->id, 'sn' => $applicant->id, 'name' => $applicant->name]) }}">Click this link to make your lodging and transportation options</a> and pay to complete the registration process.</p>
    <p>If you have problem with the above link, you may copy the following url and paste to the your browser to enter the page.</p>
    <p>{{ route('queryadmitGET', ['batch_id' => $applicant->batch->id]) }}</p>
    </li>
</ul>
<br>
<p class="card-text text-right">Warm regards, </p>
<p class="card-text text-right">The Oneness Truth Foundation</p>
<p class="card-text text-right">{{ \Carbon\Carbon::now()->format('n/j/Y') }}</p>
<br>
<br>
<br>
<h2 class="center">{{ $applicant->batch->camp->fullName }}<br>【行前通知】</h2>

<p class="card-text">親愛的 {{ $applicant->name }} 同學您好：</p>
<p class="card-text text-indent">歡迎您參加「{{ $applicant->batch->camp->fullName }}」，共享這場心靈饗宴。在您出發前，請詳閱下列須知。</p>

<p class="card-text text-indent">
您的報名序號：{{ $applicant->id }}<br>
您的錄取編號：{{ $applicant->group }}{{ $applicant->number }}<br>
營隊日期：{{ $applicant->batch->batch_start }}({{ $batch_start_Weekday }}) ~ {{ $applicant->batch->batch_end }}({{ $batch_end_Weekday }})，共4天<br>
營隊地點：{{ $applicant->batch->locationName }}({{ $applicant->batch->location }})<br>
</p>
<ul>
    <li><p class="card-text indent"><a href="{{ $content_link_chn }}">行前通知連結</a></p></li>
    <li><p class="card-text indent"><a href="{{ route('showadmit', ['batch_id' => $applicant->batch->id, 'sn' => $applicant->id, 'name' => $applicant->name]) }}">您可按此連結，確認住宿及交通服務選項</a>及繳交費用。</p>
    <p>若以上連結無法點選，請複製下方文字後，再由瀏覽器進入頁面做回覆：</p>
    <p>{{ route('queryadmitGET', ['batch_id' => $applicant->batch->id]) }}</p>
    </li>
</ul>
<br>
<p class="card-text text-right">The Oneness Truth Foundation 敬啟</p>
<p class="card-text text-right">{{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}</p>
