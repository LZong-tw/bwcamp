{{ $applicant->name }} 您好：<br>
<br>
    恭喜您已完成{{ $campData->fullName }}（簡稱本營隊）網路報名，<br>
    您在本營隊所填寫的個人資料，僅用於本營隊報名及活動聯絡之用。<br>
    本基金會（財團法人福智文教基金會）將依個人資料保護法<br>
    及相關法令之規定善盡保密責任。<br>

    請記下您的報名序號： {{ $applicant->id }} 作為日後查詢使用。<br>
    本營隊將於 {{ $campData->admission_announcing_date }} ({{ $admission_announcing_date_Weekday }}) 於官網公布錄取名單，並提供錄取查詢。<br>
    <br>
    查詢網址：{{ $campData->site_url }}<br>
    洽詢電話：(02)7751-6788 分機：610408、613091、610301<br>
    洽詢時間：週一 ~ 週五 10:00~20:00、週六 10:00~16:00<br>
    <br>
    <br>
<blockquote>財團法人福智文教基金會  敬啟</blockquote>