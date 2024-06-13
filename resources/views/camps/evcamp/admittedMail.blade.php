<style>
    {{-- u{
        color: red;
    } --}}    
    .right{
        text-align: right;
    }
    .dblUnderlined { border-bottom: 3px double; }
</style>
<body>
<table role="presentation"  cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
<tr><td><img width="100%" src="{{ $message->embed(public_path() . '/img/ecamp2024/head.png') }}" /></td></tr>
<tr><td>
<table width="80%" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td>
<h2 class="center">2024企業主管生命成長營<br><a class="dblUnderlined">義工錄取/報到通知單</a></h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>班級：{{ $applicant->lrclass }}</td>
        <td>姓名：{{ $applicant->name }}</td>
        <td>組別：{{ $applicant->user->roles->where("camp_id", \App\Models\Vcamp::find($applicant->camp->id)->mainCamp->id)->first()?->section }}</td> 
    </tr>
</table><br>

隨喜您參加「2024企業主管生命成長營」之護持義工，為使各組勤務順利進行，請詳閱下列事項：<br>
<ol>
    <li>
        全程義工報到時間：&nbsp;113&nbsp;年&nbsp;7&nbsp;月&nbsp;11&nbsp;日上午&nbsp;08:20&nbsp;~&nbsp;08:50 <br>
        （<strong>單日義工或特殊組別</strong>：請<u>依各組實際任務需要及指定時間地點報到</u>）
    </li>
    <li>報到地點：開南大學-<strong>永裕體育館</strong>【桃園縣蘆竹鄉開南路一號】</li>
    <li>交通方式：
        <ol>
            <li>搭義工遊覽車（義工停車位有限，鼓勵儘量購票搭乘義工遊覽車前往） <br>
            <strong>購票時間/地點：6/6~7/1台北學苑10F淨智服務台購票</strong>（基隆站可於基隆支苑購票） <br>
            （7/11、7/14正行來回車資：基隆、淡水350元/台北地區300元，不售單程，回程有需求，正行期間至行政中心登記，視空位提供）</li>
            <li>自行前往：鼓勵共乘，路線可查詢「開南大學交通資訊」。</li>
        </ol>
    </li>
    <li>
        說明：
        <ol>
            <li>請攜帶下列物品：身分證、健保卡、<strong><u>名牌</u></strong>、<strong><u>睡袋</u></strong>、枕頭、拖鞋、換洗衣物（請儘量帶回家清洗）、盥洗用具、環保杯、環保筷、個人常用藥品、其他視個人需要(例如：吹風機等)。</li>
            <li>到達營隊，請先至報到地點依指定位置放妥行李後，並向各組文書報到就位。</li>
            <li>營隊活動期間需先行離開者，請先向分配小組之幹部請假。</li>
            <li>寢室經安排後，請勿任意更換；如有任何住宿問題，請先向所屬組別反應，請求協助。</li>
            <li>注意服裝穿著，不宜著涼鞋，請穿包鞋。</li>
        </ol>
    </li>
    <li>
        全體義工提升，鼓勵大家踴躍參加。 <br>
        <table width="100%" style="table-layout:fixed; border: 1px solid #ccc;">
            <tr style="table-layout:fixed; border: 1px solid #ccc;">
                <td style="table-layout:fixed; border: 1px solid #ccc;">日　期</td>
                <td style="table-layout:fixed; border: 1px solid #ccc;">時　間</td>
                <td style="table-layout:fixed; border: 1px solid #ccc;">主　場　地</td> 
                <td style="table-layout:fixed; border: 1px solid #ccc;">備　註</td>
            </tr>
            <tr style="table-layout:fixed; border: 1px solid #ccc;">
                <td style="table-layout:fixed; border: 1px solid #ccc;">6/29(六)</td>
                <td style="table-layout:fixed; border: 1px solid #ccc;">09:00&nbsp;~&nbsp;11:30</td>
                <td style="table-layout:fixed; border: 1px solid #ccc;">台北光北教室(台北市光復北路112號)</td>
                <td style="table-layout:fixed; border: 1px solid #ccc;">場地分配及其他連線點另行通知</td>
            </tr>
        </table>
    </li>
    <li>
        打掃法會：
        <ol>
            <li>（1）集合時間6/30(日)上午08:20以前；地點：開南大學-永裕體育館。</li>
            <li>（2）義工遊覽車購票時間/地點：6/6~6/21，台北學苑10F淨智服務台購票（基隆站可於基隆支苑購票）</li>
            <li>（3）請穿著適宜服裝，並<strong>請攜帶名牌、環保杯</strong>。</li>
        </ol>
    </li>
</ol>
<br>
＊&nbsp;6/30及7/11&nbsp;預定發車站點如下：<strong>（實際發車狀況視各站購票數）</strong><br>
國館、新店、基隆、新莊、天母、中和、淡水、板橋、三重、公館、東湖、士林 
</td></tr><tr><td align="right">
<br>
<a class="right">秘書組義工小組&nbsp;合十</a>
</td></tr></table></td></tr>
<tr><td><br><br><img width="100%" height="20%" src="{{ $message->embed(public_path() . '/img/ecamp2024/footer.png') }}" /></td></tr></table>
</body>