<style>
    {{-- u{
        color: red;
    } --}}    
    .right{
        text-align: right;
    }
</style>
<body>
<table role="presentation"  cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
<tr><td><img width="100%" src="{{ $message->embed(public_path() . '/img/ecamp2024/head.png') }}" /></td></tr>
<tr><td>
<table width="80%" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td>
<h2 class="center">{{ $applicant->batch->camp->fullName }}<br>感&nbsp;謝&nbsp;函</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>姓名：{{ $applicant->name }}</td>
        <td>序號：{{ $applicant->id }}</td>
        <td>組別：{{ $applicant->groupRelation?->alias ?? "--" }}</td> 
        <td>場次：@if (str_contains($applicant->batch->name, "第一梯")) 北區場(開南大學) @else 中區(勤益科大) @endif</td>
    </tr>
</table><br>

親愛的企業主管您好 :<br><br>
&emsp;感謝您報名2024企業主管生命成長營，這對暱稱「黃背心」的我們是極大的鼓勵。<br><br>

&emsp;今年企業營重返桃園開南大學舉辦，但受到疫情的影響，整體環境已大幅改變。由於場地的種種限制，無法容納所有希望來參加的學員，在此請您能諒解。<br><br>
 
&emsp;為了讓更多還沒有機會接觸廣論的福友，能夠透過營隊認識師父老師的志業及理念，與我們一起進入廣論班學習，成為我們的同行善友，在此分享另一種營隊學習的方法給您─加入「黃背心」的行列！<br><br>

&emsp;日常法師曾說「營隊的正行是義工」，成為義工，護持他人的學習，以及對境歷事練心，其成長與受用並不亞於營隊學員。 如果您有意願與我們一同穿上黃背心，熱情服務營隊學員，歡迎您與我們聯繫。<br><br>

請洽: 02-2545-3788 (分機545、517) 林小姐<br><br>
</td></tr><tr><td align="right">
<br>
<a class="right">主辦單位：財團法人福智文教基金會&nbsp;敬啟</a><br>
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>
</td></tr></table></td></tr>
<tr><td><br><br><img width="100%" height="20%" src="{{ $message->embed(public_path() . '/img/ecamp2024/footer.png') }}" /></td></tr></table>
</body>