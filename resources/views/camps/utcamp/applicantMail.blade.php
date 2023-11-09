{{ $applicant->name }} 您好：<br>
    <br>
    感謝您報名{{ $camp_data->fullName }}，報名手續已完成，<br>
    請記下您的報名序號： {{ $applicant->id }} 作為日後查詢使用。<br>
        {{--
            1. 11/01 ~ 11/28: 11/30
            2. 11/30 ~ 12/18: 12/20
            3. 12/19 ~      : 1/5
        @if($applicant->created_at->gte("2021-11-01") && $applicant->created_at->lte("2021-11-28 23:59:59"))
            錄取名單將於 2021-11-30 開放查詢：https://bwfoce.org/tcamp/ <br>
        @elseif($applicant->created_at->gte("2021-11-30") && $applicant->created_at->lte("2021-12-18 23:59:59"))
            錄取名單將於 2021-12-30 開放查詢：https://bwfoce.org/tcamp/ <br>
        @elseif($applicant->created_at->gte("2021-12-19"))
            錄取名單將於 2021-01-05 開放查詢：https://bwfoce.org/tcamp/ <br>
        @else
            錄取名單將於 2021-01-05 開放查詢：https://bwfoce.org/tcamp/ <br>
        @endif
        --}}
        錄取名單將於 2023-12-04 開放查詢：https://bwfoce.org/tcamp/ <br>
        <br>
        洽詢電話：(週一 ~ 週五 上午10時 ~ 下午5時)<br>
                (02)7714-6066 分機 20318 邱先生<br>
                (07)974-1170 洪小姐<br>
        LINE@：http://bwfoce.org/line <br>
        LINE ID：@bwfoce  <br>
        電子郵件：bwfaculty@blisswisdom.org <br> 
<blockquote>財團法人福智文教基金會  敬啟</blockquote>
