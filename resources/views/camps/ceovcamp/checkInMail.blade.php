<style>
    u{
        color: red;
    }
</style>
<body style="font-size:16px;">
<h2 class="center">{{ $applicant->batch->camp->fullName }} 義工錄取/報到通知單</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <!--td>梯次：{{ $applicant->batch->name }}</td>-->
        <td>{{ $applicant->name }}大德</td>
        <!--td>組別：{{ $org->section }}{{ $org->position }}</td>-->
    </tr>
</table>

<table width="100%" style="table-layout:fixed; border: 0; word-wrap: break-word;">
<tr><td>
@if($applicant->batch->name == "北區")
    隨喜您參加「{{ $applicant->batch->camp->fullName }} 」之護持義工，為使各組勤務順利進行，請詳閱下列事項：<br>
    <ol>
        <li>護持時間：（<u>請依各組實際任務需要及指定時間地點報到</u>）</li>
        <ul>
            <li>112年7月14日08:00~19:00 (請於08:30前入座完畢)</li>
            <li>112年7月15日07:00~19:00 (請於07:10前入座完畢)</li>
            <li>112年7月16日07:00~20:00 (請於07:10前入座完畢)</li>
        </ul>
        <li>集合地點：東吳大學外雙溪校區/義工報到處 (普仁堂)【台北市士林區臨溪路70號】</li>
        <li>交通方式：請自行前往</li>
        <ul>
            <li>校區停車位有限，未便提供義工停車，請儘量搭乘大眾交通工具前往。</i>
            <li>大會有規劃在捷運淡水線「劍潭」站及文湖線「劍南路」站提供義工接駁車服務，實際接駁服務時間，屆時請注意交通組之訊息通知。</i>
        </ul>
        <li>說明：</li>
        <ul>
            <li>請攜帶下列物品：廣論、身分證、健保卡、名牌、環保杯、環保筷、個人常用藥品。</li>
            <li>到達營隊，請至集合地點由各組文書引導報到、入座。</li>
            <li>營隊活動期間需先行離開者，請先向分配小組之幹部請假。</li>
            <li>注意服裝穿著，不宜著涼鞋，請穿包鞋。</li>
            <li>請配合政府最新防疫規範及大會指定防疫措施辦理。</li>
        </ul>
        <li>***** 營隊活動，請務必參加。*****</li>
        <ul>
            <li>活動：全體義工提升</li>
            <li>日期：6/11(日)</li>
            <li>時間：09:00~18:00</li>
            <li>地點：南京東路四段180號3-2教室(教務+行政)<br>
            　　　南京東路四段180號3-3教室(越南)<br>
            　　　南京東路四段180號3-4教室(關懷)
            </li>
        </ul>
    </ol>
@endif
@if($applicant->batch->name == "竹區")

@endif
@if($applicant->batch->name == "中區")

@endif
@if($applicant->batch->name == "高區")

@endif
</td></tr>
</table>
<a class="right">秘書組義工小組　合十</a><br>
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>
</body>