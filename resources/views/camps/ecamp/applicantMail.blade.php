{{ $applicant->name }} 您好：<br>
<br>
感謝您報名{{ $applicant->batch->camp->fullName }}，報名手續已完成，
請記下您的《 報名序號：{{ $applicant->id }} 》作為日後查詢使用。<br>
{{ $applicant->batch->camp->admission_announcing_date }} 以後將以E-mail通知您營隊後續訊息。<br>
<br>
若對營隊相關訊息有任何問題或建議，歡迎洽詢<br>
活動聯繫窗口：(週一至週五10:00~17:30)<br>
@if (str_contains($batch->name, "北區"))
紀雅真：0921-059-597 yc890906@gmail.com<br>
張惠琴：0920-846-619 lilychang846619@gmail.com<br>
@else
南區場：07-2819498 方小姐<br>
中區場：04-3706-9300 (分機621201) 陳小姐<br>
電子郵件：ent.camp@blisswisdom.org<br>
@end
<br>
<br>
<blockquote>財團法人福智文教基金會  敬啟</blockquote>
