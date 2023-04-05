{{ $applicant->name }} 您好：<br>
<br>
    感謝您報名{{ $applicant->batch->camp->fullName }}，報名手續已完成，
    請記下您的《 報名序號：{{ $applicant->id }} 》作為日後查詢使用。<br>
    {{ $applicant->batch->camp->admission_announcing_date }} 以後將以E-mail通知您營隊後續訊息。<br>
    <br>
    若對營隊相關訊息有任何問題或建議，歡迎洽詢各區服務電話：<br>
    洽詢電話：(週一至週五10:00~17:30)<br>
    @if(\Str::contains($applicant->batch->camp->fullName, "台北"))
    　台北 (02)2545-2787(分機507,510企業營報名組)<br>
    @elseif(\Str::contains($applicant->batch->camp->fullName, "桃園"))
    　桃園 (03)275-6133(分機1325陳先生)<br>
    @elseif(\Str::contains($applicant->batch->camp->fullName, "新竹"))
    　新竹 (03)532-5566(分機246張小姐)<br>
    @elseif(\Str::contains($applicant->batch->camp->fullName, "中區"))
    　中區 (04)3706-9300(分機621200,621201企業營報名組)<br>
    @elseif(\Str::contains($applicant->batch->camp->fullName, "雲嘉"))
    　雲嘉 (05)2833-940(分機305楊昀儒小姐)<br>
    @elseif(\Str::contains($applicant->batch->camp->fullName, "台南"))
    　台南 (06)264-6831(分機316 或 0933-296506陳姿縈)<br>
    @elseif(\Str::contains($applicant->batch->camp->fullName, "高雄"))
    　高雄 (07)281-9498 或 (07)976-9341(分機404)<br>
    @else
    @endif
    電子郵件：ent.camp@blisswisdom.org<br>
    <br>
    <br>
<blockquote>財團法人福智文教基金會  敬啟</blockquote>
