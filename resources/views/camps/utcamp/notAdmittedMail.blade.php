<style>
    u{
        color: red;
    }

    .center{
        text-align: center;
    }

    .right{
        text-align: right;
    }
</style>
    <h2 class="center">{{ $applicant->batch->camp->fullName }}</h2> 
    <h2 class="center">{{ $applicant->batch->name }} 報名結果通知單</h2>
<!--<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>姓名：{{ $applicant->name }}</td>
    </tr>
</table>-->
    敬愛的教育夥伴 {{ $applicant->name }} ，您好！ <br><br>
    &emsp;&emsp;「教師生命成長營」自舉辦以來，每年都得到教育夥伴們的支持和肯定，思及社會上仍有這麼多人共同關心莘莘學子們的學習成長，令人深感振奮！每一位老師的報名都是鼓舞我們的一分力量，激勵基金會全體人員持續不懈，與大家共同攜手為教育盡心盡力。<br><br>
    &emsp;&emsp;非常感謝您的報名，但是很遺憾，因資格問題，您此次未獲錄取「{{ $applicant->batch->camp->fullName }}  {{ $applicant->batch->name }}」。期盼未來仍能在福智文教基金會舉辦之心靈提升、教育活動見到您的身影。另外，福智文教基金會在各縣市的分支機構，平日都設有適合各年齡層的多元心靈提升課程，誠摯歡迎您的參與！ <br><br>
    &emsp;&emsp;關注「福智文教基金會」網站：<a href="https://bwfoce.org" target="_blank" rel="noopener noreferrer">https://bwfoce.org</a><br><br>
    &emsp;&emsp;若有疑問，歡迎來訊：<a href="mailto:bwfaculty@blisswisdom.org">bwfaculty@blisswisdom.org</a><br><br>
    敬祝　教安！<br><br>
<a class="right">財團法人福智文教基金會　敬上</a><br> 
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>