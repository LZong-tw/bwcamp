{{ $applicant->name }} 您好：<br>
<br>
    恭喜您已完成{{ $applicant->batch->camp->fullName }}網路報名程序，
    請記下您的《 報名序號：{{ $applicant->id }} 》作為日後查詢使用。<br>
    <!-- 錄取名單將於 {{ $applicant->batch->camp->admission_announcing_date }} 後陸續以E-mail通知。<br>-->
    <br>
    洽詢電話及電子郵件：<br>
    @if (str_contains($applicant->batch->name, "北區"))
    紀雅真：0921-059-597 yc890906@gmail.com<br>
    張惠琴：0920-846-619 lilychang846619@gmail.com<br>
    @else
    　中區場：陳蘊蓁 0931-993-726 amanda672672@gmail.com<br>
    　南區場：方嘉麟 0934-151-353 meibao288@gmail.com<br>
    @end
    <br>
    <br>
<blockquote>財團法人福智文教基金會  敬啟</blockquote>
