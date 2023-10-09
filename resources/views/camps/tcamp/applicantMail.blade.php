{{ $applicant->name }} 您好：<br>
    <br>
    恭喜您已完成{{ $camp_data->fullName }}網路報名程序。請記下您的報名序號： <strong>{{ $applicant->id }}</strong>作為日後查詢使用。<br>
    我們將於 {{ \Carbon\Carbon::parse($campData->admission_announcing_date)->translatedFormat("Y年m月d日(l)") }}(星期一) 寄發錄取與否通知信件至您的電子信箱，或您可至活動網站 {{ $camp_data->site_url }} 查詢。<br>
    <br>
    洽詢窗口<span class="font-bold">（週一至週五 10:00~17:30）：</span><br>
    陳昶安　先生<br>
    電話：07-9743280#68601<br>
    Email：ca7974zz@gmail.com<br>
    <br>
    LINE@：http://bwfoce.org/line <br>
    LINE ID：@bwfoce <br>
    {{-- 電子郵件：bwmedu@blisswisdom.org<br> --}}
<br>
<br>
<blockquote>財團法人福智文教基金會　謹此</blockquote>
