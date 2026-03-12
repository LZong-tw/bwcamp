<h3 style="text-align: center">{{ $applicant->batch->camp->fullName }}【通知信]</h3>

親愛的{{ $applicant->name }}：<br><br>
&emsp;&emsp;感謝您報名參加「{{ $applicant->batch->camp->fullName }}」，我們非常珍惜您的熱情與支持。然而，經審核報名資格，很遺憾未能錄取您，對此深感抱歉。<br><br>
&emsp;&emsp;福智文教基金會多年來為心靈提升、教育活動付諸心力，在各縣市皆有分支機構，並設有各年齡層的多元課程，誠摯歡迎您的參與！<br><br>
&emsp;&emsp;若您對本活動有任何疑問，歡迎隨時與我們聯繫。祝福您在教學與人生旅程中豐盛成長、順遂圓滿！<br><br>
敬祝 平安順心！<br><br>
<p>
財團法人福智文教基金會<br>
{{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}<br>
{!! nl2br(e(str_replace('\n', "\n", $applicant->batch->contact_card))) !!}
</p>
