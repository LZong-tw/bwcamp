{{ $applicant->name }} 您好：<br>
<br>
    恭喜您已完成{{ $campData->fullName }}網路報名，<br>
    您在本活動所填寫的個人資料，僅用於本活動報名及聯絡之用。<br>
    大會將依個人資料保護法<br>
    及相關法令之規定善盡保密責任。<br><br>

    請記下您的報名序號： {{ $applicant->id }} 作為日後查詢使用。<br><br>

    @if(now()->gt($applicant->camp->registration_end))
        報名截止日期 {{ $applicant->camp->registration_end }} 已過。<br>
    @else
    @endif
    <br>
    查詢網址：{{ $campData->site_url }}<br>
    洽詢郵件：施佳妤師姐，電郵：international@blisswisdom.org<br>
    <br>
    <br>
<blockquote>國際事務法會報名服務處  敬啟</blockquote>