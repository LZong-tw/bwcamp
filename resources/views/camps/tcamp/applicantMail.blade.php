{{ $applicant->name }} 您好：<br>
<br>
　　恭喜您已完成{{ $campData->fullName }}網路報名程序。請記下您的報名序號： <strong>{{ $applicant->id }}</strong> 作為日後查詢使用。<br>
<br>
　　如您需修改報名表內容，可憑報名序號至 <a href="https://bwcamp.bwfoce.org/camp/{{ $applicant->batch->id }}/query"> https://bwcamp.bwfoce.org/camp/{{ $applicant->batch->id }}/query</a> 頁面修改<br>
<br>
　　我們將於 {{ \Carbon\Carbon::parse($campData->admission_announcing_date)->translatedFormat("Y年m月d日(l)") }} 寄發錄取與否通知信件至您的電子信箱，或您可至活動網站 {{ $campData->site_url }} 查詢。<br>
　　<br>
　　洽詢窗口<span class="font-bold">（週一至週五 10:00~17:30）：</span><br>
　　王淑靜　小姐<br>
　　電話：07-9769341#413<br>
　　Email：shu-chin.wang@blisswisdom.org<br>
　　<br>
　　LINE@：http://bwfoce.org/line <br>
　　LINE ID：@bwfoce <br>
　　{{-- 電子郵件：bwmedu@blisswisdom.org<br> --}}
<br>
<br>
<blockquote>財團法人福智文教基金會　謹此</blockquote>
