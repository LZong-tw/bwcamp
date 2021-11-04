{{ $applicant->name }} 您好：<br>
    <br>
    感謝您報名本次教師生命成長營，報名手續已完成，<br>
    請記下您的報名序號： {{ $applicant->id }} 作為日後查詢使用。<br>
    @if($camp == "utcamp")
        {{--
        1. 11/01 ~ 11/13: 11/16
        2. 11/14 ~ 11/27: 11/30
        3. 11/28 ~ 12/12: 12/15
        4. 12/13 ~      : 12/31
        --}}
        @if($applicant->created_at->gte("2021-11-01") && $applicant->created_at->lte("2021-11-13 23:59:59"))
            錄取名單將於 2021-11-16 開放查詢：https://bwfoce.org/tcamp/ <br>
        @elseif($applicant->created_at->gte("2021-11-14") && $applicant->created_at->lte("2021-11-27 23:59:59"))
            錄取名單將於 2021-11-30 開放查詢：https://bwfoce.org/tcamp/ <br>
        @elseif($applicant->created_at->gte("2021-11-28") && $applicant->created_at->lte("2021-12-12 23:59:59"))
            錄取名單將於 2021-12-15 開放查詢：https://bwfoce.org/tcamp/ <br>
        @else
            錄取名單將於 2021-12-31 開放查詢：https://bwfoce.org/tcamp/ <br>
        @endif
        <br>
        洽詢電話：(週一 ~ 週五 上午10時 ~ 下午5時)<br>
    　           (02)7751-6799*520023 邱先生<br>
        LINE@：http://bwfoce.org/line <br>
        LINE ID：@bwfoce  <br>
        電子郵件：bwfaculty@blisswisdom.org <br> 
    @else
        請於 {{ \Carbon\Carbon::parse($campData->admission_announcing_date)->translatedFormat("Y年m月d日(l)") }} 起，至活動網站 http://bwfoce.org/tcamp 查詢是否錄取。<br>
        <br>
        洽詢電話：(02)2545-3788*529 劉小姐 (週一〜週五 上午10時〜下午5時)<br>
    　  <br>
        LINE@：http://bwfoce.org/line <br>
        LINE ID：@bwfoce <br>
        電子郵件：bwmedu@blisswisdom.org<br>
    @endif
<blockquote>財團法人福智文教基金會  敬啟</blockquote>
