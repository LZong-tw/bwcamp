Please keep your <span class="text-danger font-weight-bold">《 registration number：{{ $applicant->id }} 》</span>for future reference.
<br>
Once your registration is accepted, you will receive a confirmation email containing important camp details and payment instructions within 7 days. 
Your registration will be considered complete only after your payment has been received.<br>
<br><br>
請記下您的<span class="text-danger font-weight-bold">《 報名序號：{{ $applicant->id }} 》</span>作為日後查詢使用。
<br>
我們將於 <u>{{ \Carbon\Carbon::parse($camp_data->admission_announcing_date)->translatedFormat("Y年m月d日(l)") }}</u> 
寄發錄取與否通知信件至您的電子信箱，或您可至活動網站 <a href="http://bwfoce.org/2023tcamp">http://bwfoce.org/2023tcamp</a> 查詢。