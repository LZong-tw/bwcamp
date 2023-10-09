
請記下您的<span class="text-danger font-weight-bold">《 報名序號：{{ $applicant->id }} 》</span>作為日後查詢使用。
<br>
我們將於 <u>{{ \Carbon\Carbon::parse($camp_data->admission_announcing_date)->translatedFormat("Y年m月d日(l)") }}(星期一)</u> 寄發錄取與否通知信件至您的電子信箱，或您可至活動網站 <a href="{{ $camp_data->site_url }}">{{ $camp_data->site_url }}</a> 查詢。