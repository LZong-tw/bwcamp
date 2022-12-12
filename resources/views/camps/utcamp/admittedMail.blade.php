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
    <h2 class="center">2023第31屆教師生命成長營</h2>
    <h2 class="center">大專教職員梯 {{ $applicant->xsession }} 錄取通知單</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>報名序號：{{ $applicant->id }}</td>
        <td>姓名：{{ $applicant->name }}</td>
        <td>錄取編號：<u>{{ $applicant->group }}{{ $applicant->number }}</u></td>
        <td>組別：<u>{{ $applicant->group }}</u></td>
    </tr>
</table>
    歡迎您參加「2023第31屆教師生命成長營-大專教職員梯 {{ $applicant->xsession }}」，誠摯地期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。請詳閱以下相關訊息，祝福您營隊收穫滿滿：<br>
    <br>
    營隊資訊
    <ol>
        <li>活動期間：2023/1/31(二)、2/1(三)</li>
        <li>地點：{{ $applicant->xaddr }}</li>
    </ol>
    全程參與者，發給研習證明文件或公務員研習證明。<br>
    若有任何問題，歡迎來電(02)7714-6066 分機20318 邱先生。<br>
    12/12~15將有營隊關懷員與您聯絡，提供營隊相關資訊。<br>
<br>
<a class="right">財團法人福智文教基金會　謹此</a><br>
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>
