<style>
    @if($applicant->batch->name != "高雄")
        u{
            color: red;
        }
    @endif
</style>
<body style="font-size:16px;">
@if($applicant->batch->name != "高雄")
    <h2 class="center">{{ $applicant->batch->camp->fullName }} 義工錄取/報到通知單</h2>
    <table width="100%" style="table-layout:fixed; border: 0;">
        <tr>
            <!-- <td>梯次：{{ $applicant->batch->name }}</td> -->
            <td>班級：{{ $applicant->evcamp->lrclass }}</td>
            <td>{{ $applicant->name }}大德</td>
            <td>組別：{{ $org->section }}{{ $org->position }}</td>
        </tr>
    </table>
@else
    <h2 class="center">{{ $applicant->batch->camp->fullName }}</h2>
    <h2 class="center"><u>義工錄取/報到通知單</u></h2>
    <table width="100%" style="table-layout:fixed; border: 0;">
        <tr>
            <td>姓名：{{ $applicant->name }}</td>
            <td>組別：{{ $org->section }} {{ $org->position }}</td>
        </tr>
    </table>
@endif

<table width="100%" style="table-layout:fixed; border: 0; word-wrap: break-word;">
<tr><td>
@if($applicant->batch->name == "台北")
    隨喜您參加「{{ $applicant->batch->camp->fullName }} 」之護持義工，為使各組勤務順利進行，請詳閱下列事項：<br>
    <ol>
        <li>護持時間：（請攜帶本通知單辦理報到，並<u>請依各組實際任務需要及指定時間地點報到</u>）</li>
        <ul>
            <li>112年7月13日08:00~17:00<b>(請提前報到入座完畢)</b></li>
            <li>112年7月14日08:00~19:00<b>(請提前報到入座完畢)</b></li>
            <li>112年7月15日07:00~19:00<b>(請提前報到入座完畢)</b></li>
            <li>112年7月16日07:00~20:00<b>(請提前報到入座完畢)</b></li>
        </ul>
        <li>集合地點：東吳大學外雙溪校區/義工報到處 (傳賢堂)【台北市士林區臨溪路70號】</li>
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
            <li>時間：09:00~11:30</li>
            <li>地點：光北教室(台北市松山區光復北路112號4/5/6樓)</li>
        </ul>
    </ol>
@endif
@if($applicant->batch->name == "桃園")

@endif
@if($applicant->batch->name == "新竹")

@endif
@if($applicant->batch->name == "台中")

@endif
@if($applicant->batch->name == "雲林")

@endif
@if($applicant->batch->name == "嘉義")

@endif
@if($applicant->batch->name == "台南")

@endif
@if($applicant->batch->name == "高雄")
隨喜您參加「2023企業主管生命成長營」之護持義工，為順利進行各組勤務，請詳閱下列事項：
<ol>
    <li><b>義工正行時間</b>：請於每日正行時間開始前，攜帶本電子通知單辦理報到<br>
        　　　　　　&nbsp;112年7月13日08:15~17:00<br>
        　　　　　　&nbsp;112年7月14日07:50~19:30<br>
        　　　　　　&nbsp;112年7月15日06:50~19:30<br>
        　　　　　　&nbsp;112年7月16日06:50~20:00</li>
    <li><b>報到地點</b>：中華電信學院高雄所【高雄市仁武區仁勇路400號】。<br>
        　　　　　關懷大組義工報到處為教學大樓4樓1407教室。<br>
        　　　　　請向各組小組長報到，再由各組文書回報給義工小組統計。</li>
    <li><b>交通方式</b>：敬請自行前往。
        <ol type="I">
            <li>電信學院高雄所內停車位有限，7/14起義工汽車請依交通組義工引導至附近 <br>
                收費停車場停放。(60元/日)</li>
            <li>建議儘量騎機車，如有開車請儘量共乘。</li>
        </ol>
    <li><b>說明</b>：
        <ol type="I">
            <li>請攜帶下列物品：廣論、身分證、健保卡、口罩、環保杯/筷、個人常用藥品。</li>
            <li>義工正行期間請配戴名牌。</li>
            <li>7/14起請著義工服、白色襯衫、深色長褲或過膝裙、包頭鞋(勿露腳趾)，並配戴 <br>
                名牌。</li>
            <li>營隊活動期間需先行離開者，請先向分配小組之幹部請假。</li>
            <li>對營隊之學員、義工及中華電信學院之教職員，宜以有禮貌、親切的態度互動， <br>
                使對方能對團體有好印象，與團體建立和合增上緣。</li>
            <li>請配合政府最新防疫規範及大會指定防疫措施。</li>
            <li>如有任何問題及變動，請先向小組長反應，必要時向大組回饋，請求協助。</li>
        </ol>
    </li>
</ol>
@endif
</td></tr>
<tr>
    <td align="right">
        @if ($applicant->batch->name != "高雄")
            秘書組義工小組　合十<br>
            {{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日
        @else
            2023企業營秘書大組 義工小組 合十
        @endif
    </td>
</tr>
</table>
</body>
