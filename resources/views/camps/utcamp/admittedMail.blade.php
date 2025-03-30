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
@php
    $group = $applicant->group;
    if (str_contains($group,'B') !== false) {
        $applicant->xsession = '桃園場';
        $applicant->xaddr = '桃園市中壢區成章四街120號';
    } elseif (str_contains($group,'C') !== false) {
        $applicant->xsession = '新竹場';
        $applicant->xaddr = '新竹縣新豐鄉新興路1號';
    } elseif (str_contains($group,'D') !== false) {
        $applicant->xsession = '台中場';
        $applicant->xaddr = '台中市西區民生路227號';
    } elseif (str_contains($group,'E') !== false) {
        $applicant->xsession = '雲林場';
        $applicant->xaddr = '雲林縣斗六市慶生路6號';
    } elseif (str_contains($group,'F') !== false) {
        $applicant->xsession = '台南場';
        $applicant->xaddr = '台南市東區大學路1號';
    } elseif (str_contains($group,'G') !== false) {
        $applicant->xsession = '高雄場';
        $applicant->xaddr = '高雄市新興區中正四路53號12樓之7';
    } else {
        $applicant->xsession = '台北場';
        $applicant->xaddr = '台北市南京東路四段165號九樓 福智學堂';
    }
@endphp
<h2 class="center">{{ $applicant->batch->camp->fullName }}</h2>
<h2 class="center">{{ $applicant->batch->name }} 錄取通知單</h2>
<br>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>報名序號：{{ $applicant->id }}</td>
        <td>姓名：{{ $applicant->name }}</td>
        <td>錄取編號：<u>{{ $applicant->group }}{{ $applicant->number }}</u></td>
        <td>組別：<u>{{ $applicant->group }}</u></td>
    </tr>
</table>

&emsp;&emsp;歡迎參加「{{ $applicant->batch->camp->fullName }}」，誠摯地期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。請詳閱以下相關訊息，祝福您營隊收穫滿滿。<br>

&emsp;&emsp;請於收到錄取通知單七日內（{{ \Carbon\Carbon::now()->addDays(7)->year }}年　{{ \Carbon\Carbon::now()->addDays(7)->month }}月{{ \Carbon\Carbon::now()->addDays(7)->day }}日前）完成轉帳 {{ $applicant->fee }}元整。確認款項後，您的報名才正式生效。匯款帳號如下：<br>
<br>
&emsp;&emsp;邱孟懿 中國信託 板新分行<br>
&emsp;&emsp;帳號：417540291289<br>
&emsp;&emsp;匯款時，請註記：{{ $applicant->name }}大專教師營<br>
<br>
<ol>
    <li>營隊資訊<br>
        <ul>
            <li>活動期間：{{ $applicant->batch->batch_start }}({{ $batch_start_Weekday }})～{{ $applicant->batch->batch_end }}({{ $batch_end_Weekday }})</li>
            <li>地點：{{ $applicant->batch->locationName }} {{ $applicant->batch->location }}</li>
        </ul>
    </li>

    <li>研習費用<br>
        含三天兩夜住宿、全程蔬食餐點、新竹高鐵站接駁<br>
        ＊全程參與者，發給研習證明文件。<br>
    </li>
    <li>交通接駁<br>
    {{ $applicant->batch->batch_start }}前往麻布山林接駁車：將於新竹高鐵站出口4，12:30發車<br>
    {{ $applicant->batch->batch_end }}前往新竹高鐵站接駁車：由麻布山林山居外P1停車場，17:00發車<br> 
    </li>

    <li>取消參加退費原則
    若您因故不克參與，2025/6/12(含)前可全額退費(需扣除5%手續費)；<br>
    2025/6/13(含)以後恕不退費。<br>
    </li>
</ol>
&emsp;&emsp;若有任何問題，歡迎來電(02)7714-6066 分機20317 陳小姐。或 email: bwfaculty@blisswisdom.org<br>
<br><br>
<a class="right">財團法人福智文教基金會　敬上</a><br>
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>
