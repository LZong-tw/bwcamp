<style>
    u{
        color: red;
    }
</style>
<h2 class="center">{{ $applicant->batch->camp->fullName }} 報到通知單</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>場次：{{ $applicant->batch->name }}</td>
        <td>姓名：{{ $applicant->name }}</td>
        <td>錄取編號：{{ $applicant->group }}{{ $applicant->number }}</td>
        <td>組別：{{ $applicant->group }}</td>
    </tr>
</table><br>

<ol>
    <li>報到時間：08:20〜08:50</li>
    <li>報到地點：台北市大安區復興南路二段52號 (大安高工)</li>
    <li>報到流程：請於報到時手機出示【QRcode】（或列印出紙本報到須知）辦理報到。複製、轉寄均無效。</li>
    <li>交通方式：</li>
        <ol type="A">
            <li>聯營：20、22、38、41、204、226、0東、信義線，在「復興南路口站」下車，步行2分鐘即達。 </li>
            <li>聯營：74、278、685在「大安高工站」下車，步行1分鐘即達。 C.捷運：大安站</li>
        </ol>
    
    <li>注意事項：</li>
        <ol type="i">
            <li>本校校區停車位有限，請儘量搭乘大眾交通工具來校。</li>
            <li>請自備環保杯、環保筷。</li>
        </ol>
    <li>防疫規定：</li>
        <ol type="i">
            <li>實施實(聯)名制：並於集會、活動期間拍照，以利掌握參加人員資料及其在集會活動中之相對位置。</li>
            <li>衛生防護措施：請配合落實量測體溫、噴酒精、全程配戴口罩(用餐時間除外)等個人衛生防護措施，不開放發燒者進入場所或參與活動，並應加強環境清潔消毒。</li>
        </ol>
    <li>諮詢窗口：台北場 劉小姐 (02)2545-3788#529</li>
</ol>
<a class="right">財團法人福智文教基金會　謹此</a><br> 
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>