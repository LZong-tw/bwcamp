<style>
    u{
        @if($applicant->batch->camp->variant == "utcamp")
            color: red;
        @endif
    }

    .center{
        text-align: center;
    }

    .right{
        text-align: right;
    }
</style>
    <h2 class="center">{{ $applicant->batch->camp->fullName }}&emsp;報名結果通知單</h2>

    &emsp;&emsp;敬愛的教育夥伴，您好！ <br>
    &emsp;&emsp;「教師生命成長營」自舉辦以來，每年都得到教育夥伴們的支持和肯定，思及社會上仍有這麼多人共同關心莘莘學子們的學習成長，令人深感振奮！每一位老師的報名都是鼓舞我們的一分力量，激勵基金會全體人員持續不懈，與大家共同攜手為教育盡心盡力。
    非常感謝您的報名，由於我們的場地容量的侷限性，不克錄取，造成您的不便，敬請見諒包涵！ <br><br>
    &emsp;&emsp;福智文教基金會在各縣市的分支機構，平日都設有適合各年齡層的多元心靈提升課程，誠摯歡迎您的參與！<br><br>
    &emsp;&emsp;關注「福智文教基金會」網站：<a href="https://bwfoce.org" target="_blank" rel="noopener noreferrer">https://bwfoce.org</a><br>
    &emsp;&emsp;關注「幸福心學堂online」FB社團：<a href="https://www.facebook.com/groups/bwfoce.happiness.new" target="_blank" rel="noopener noreferrer">https://www.facebook.com/groups/bwfoce.happiness.new</a><br>
    <br>
    祝福&emsp;教安，健康平安！
@endif
<a class="right">財團法人福智文教基金會&emsp;謹此</a><br>
<a class="right">{{ now()->year }}&emsp;年&emsp;{{ now()->month }}&emsp;月&emsp;{{ now()->day }}&emsp;日</a>
