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
    <h2 class="center">{{ $applicant->batch->camp->fullName }}&emsp;錄取通知</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>地點：{{ $applicant->batch->locationName }}</td>
        <td>時間：{{ $applicant->batch->batch_start }}({{ $batch_start_Weekday }})至{{ $applicant->batch->batch_end }}({{ $batch_end_Weekday }})</td>
    </tr>
    <tr>
        <td>姓名：{{ $applicant->name }}</td>
        <td>錄取編號：<u>{{ $applicant->group }}{{ $applicant->number }}</u></td>
        <td>組別：<u>{{ $applicant->group }}</u></td>
    </tr>
</table>
    &emsp;&emsp;恭喜您錄取「{{ $applicant->batch->camp->fullName }}」，竭誠歡迎您的到來。<br>
    &emsp;&emsp;相關營隊訊息，將於營隊三周前寄發「報到通知單」，請記得到電子信箱收信。<br>
    &emsp;&emsp;也歡迎加入[幸福心學堂online]臉書社團，收取營隊訊息和教育新知。<br>
    &emsp;&emsp;很期待與您共享這場心靈的饗宴，預祝您歡喜學習，收穫滿滿。<br>
    &emsp;&emsp;敬祝&emsp;教安<br><br>
    <ul>
        <li>
            請加入幸福心學堂online臉書社團：<br>
            <a href="https://www.facebook.com/groups/bwfoce.happiness.new" target="_blank" rel="noopener noreferrer">https://www.facebook.com/groups/bwfoce.happiness.new</a>
        </li>
        <li>
            關注「福智文教基金會」網站：<a href="https://bwfoce.org">https://bwfoce.org</a>
        </li>
        <li>
            報名報到諮詢窗口<span class="font-bold">（周一至周五 10:00~17:30）：</span><br>
            王淑靜&emsp;小姐<br>
            電話：07-9769341#413<br>
            Email：shu-chin.wang@blisswisdom.org<br>
        </li>
    </ul>
<a class="right">財團法人福智文教基金會&emsp;謹此</a><br>
<a class="right">{{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}</a>

<!--
<h2 class="center">{{ $applicant->batch->camp->fullName }}</h2>
<h2 class="center">錄取通知單</h2>
<br>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>報名序號：{{ $applicant->id }}</td>
        <td>姓名：{{ $applicant->name }}</td>
        
        <td>錄取編號：<u>{{ $applicant->group }}{{ $applicant->number }}</u></td>
        <td>組別：<u>{{ $applicant->group }}</u></td>
    
    </tr>
</table>
<br>
&emsp;&emsp;歡迎參加「{{ $applicant->batch->camp->fullName }}」，誠摯地期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。請詳閱以下相關訊息，祝福您營隊收穫滿滿。<br>
<br>
&emsp;&emsp;請於收到錄取通知單七日內（{{ \Carbon\Carbon::now()->addDays(7)->year }}年{{ \Carbon\Carbon::now()->addDays(7)->month }}月{{ \Carbon\Carbon::now()->addDays(7)->day }}日前）完成轉帳 {{ $applicant->fee }}元整。確認款項後，您的報名才正式生效。匯款帳號如下：<br>
<br>
&emsp;&emsp;邱孟懿 中國信託 板新分行<br>
&emsp;&emsp;帳號：417540291289<br>
&emsp;&emsp;匯款時，請註記：{{ $applicant->name }}大專教師營<br>
<br>
<ol>
    <li>營隊資訊<br>
        <ul>
            <li>活動期間：{{ $applicant->batch->batch_start }}({{ $batch_start_Weekday }})～{{ $applicant->batch->batch_end }}({{ $batch_end_Weekday }})</li>
            <li>地點：{{ $applicant->batch->locationName }}（{{ $applicant->batch->location }}）</li>
        </ul>
    </li>

    <li>研習費用<br>
        含三天兩夜住宿、全程蔬食餐點、新竹高鐵站接駁。<br>
        ＊全程參與者，發給研習證明文件。<br>
    </li>
    <li>交通接駁<br>
    {{ $applicant->batch->batch_start }} 前往麻布山林接駁車：將於新竹高鐵站出口4，12:30發車<br>
    {{ $applicant->batch->batch_end }} 前往新竹高鐵站接駁車：由麻布山林山居外P1停車場，17:00發車<br> 
    </li>

    <li>取消參加退費原則<br>
    若您因故不克參與，2025-06-12(含)前可全額退費(需扣除5%手續費)；<br>
    2025-06-13(含)以後恕不退費。<br>
    </li>
</ol>
&emsp;&emsp;若有任何問題，歡迎來電(02)7714-6066 分機20317 陳小姐。或 email: bwfaculty@blisswisdom.org<br>
<br><br>

<a class="right">財團法人福智文教基金會&emsp;敬上</a><br>
<a class="right">{{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}</a>
-->