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
@if($applicant->batch->camp->variant == "utcamp") 
    <h2 class="center">2022第30屆教師生命成長營</h2> 
    <h2 class="center">大專教職員梯 報名結果通知單</h2> 
@else
    <h2 class="center">2022教師生命成長營 報名結果通知單</h2>
@endif
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        @if(!$applicant->batch->camp->variant)
            <td>場次：幼小中高場(1/26-27)</td>
        @endif
        <td>姓名：{{ $applicant->name }}</td>
    </tr>
</table>
@if($applicant->batch->camp->variant == "utcamp") 
    未錄取信測試
@else
    &emsp;&emsp;敬愛的教育夥伴，您好！ <br>
    &emsp;&emsp;「教師生命成長營」自舉辦以來，每年都得到教育夥伴們的支持和肯定，思及社會上仍有這麼多人共同關心莘莘學子們的學習成長，令人深感振奮！每一位老師的報名都是鼓舞我們的一分力量，激勵基金會全體人員持續不懈，與大家共同攜手為教育盡心盡力。
    非常感謝您的報名，由於我們的視訊配備侷限，不克錄取，造成您的不便，敬請見諒包涵！ <br><br>
    &emsp;&emsp;福智文教基金會在全省各縣市的分支機構，平日都設有適合各年齡層的多元心靈提升課程，誠摯歡迎您的參與！<br><br>
    &emsp;&emsp;關注「福智文教基金會」網站：<a href="https://bwfoce.org" target="_blank" rel="noopener noreferrer">https://bwfoce.org</a><br>
    &emsp;&emsp;關注「哈特麥1D」(heart mind edu.)FB：<a href="https://www.facebook.com/heartmind1d/" target="_blank" rel="noopener noreferrer">https://www.facebook.com/heartmind1d/</a><br>
    <br>
    祝福　教安，健康平安！ 
@endif
<a class="right">財團法人福智文教基金會　@if($applicant->batch->camp->variant == "utcamp") 謹此 @else 敬啟 @endif</a><br> 
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>