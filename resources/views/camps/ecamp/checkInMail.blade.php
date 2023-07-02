{{-- <style>
    u{
        color: red;
    }
</style> --}}
<body style="font-size:16px;">
<h2 class="center">{{ $applicant->batch->camp->fullName }}</h2>
<h2 class="center">報&nbsp;到&nbsp;通&nbsp;知&nbsp;單</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>姓名：{{ $applicant->name }}</td>
        <td>序號：{{ $applicant->id }}</td>
        <td>組別：{{ $applicant->group }}</td>
        <td>場次：{{ $applicant->batch->name }}</td>
    </tr>
</table>
<table width="100%" style="table-layout:fixed; border: 0; word-wrap: break-word;">
<tr><td>
感謝您報名「2023企業主管生命成長營」，歡迎您參加本研習活動，我們誠摯歡迎您來共享這場心靈饗宴。為使研習進行順利，請詳閱下列須知。
@if($applicant->batch->name == "台北")
    <ol>
        <li>
            報到時間：7/14&nbsp;(五)&nbsp;13:00~13:40<br>
            　　　　　7/15&nbsp;(六)&nbsp;08:00~08:50<br>
            　　　　　7/16&nbsp;(日)&nbsp;08:00~08:50
        </li>
        <li>
            營隊上課時間：7/14&nbsp;(五)&nbsp; 13:00~13:40<br>
            　　　　　　　7/15&nbsp;(六)&nbsp;08:00~08:50<br>
            　　　　　　　7/16&nbsp;(日)&nbsp;08:00~08:50
        </li>
        <li>報到地點：東吳大學外雙溪校區&nbsp;綜合大樓&nbsp;（台北市士林區臨溪路70號）</li>
        <li>
            交　　通：<br>
            本基金會在7/14(五)&nbsp;12:00~13:30<br>
            　　　　　7/15(六)&nbsp;06:45~08:30<br>
            　　　　　7/16(日)&nbsp;06:45~08:30<br>
            於以下地點提供交通接駁服務，現場將有穿著黃色背心的義工協助引導。
            <ol type="a">
                <li>捷運淡水線&nbsp;&nbsp;劍潭站&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2號出口</li>
                <li>捷運文湖線&nbsp;&nbsp;劍南路站&nbsp;&nbsp;2號出口</li>
            </ol>
            <br>
            騎機車前往會場者，每次停車費20元，請自備零錢投幣，現場不找零。
        </li>
        <li>若有任何問題，歡迎與關懷員聯絡反應。</li>
    </ol>
    &nbsp;建議攜帶物品：<br>
    　　　 隨身背包、文具用品、環保水杯（壺）、禦寒薄外套<br>
    　　　 身份證、健保卡&nbsp;(遇緊急狀況就醫時使用)<br>
    　　　 個人常用藥物<br>
    &nbsp;<u><strong>報到時請攜帶附件之報到用 QR Code 報到單。</strong></u>
@endif
@if($applicant->batch->name == "桃園")
    <ol>
        <li>報到時間：</li>
        <ol type="A">
            <li>1/30(六) 08:20〜08:50</li>
            <li>1/31(日) 08:20〜08:50</li>
        </ol>
        <li>報到地點：桃園市平鎮區民族路雙連二段118巷19號</li>
        <li>報到流程：請於報到時以手機出示附件之【QR code】（或列印出紙本）辦理報到。</li>
        <li>交通方式：</li>
        <ol type="A">
            <li>桃園客運：5035、5032、5033，在「盛貿工廠」下車，步行約2分鐘即達。</li>
            <li>中壢客運：131，在「盛貿工廠」下車，步行約2分鐘即達。</li>
        </ol>
        <li>注意事項：</li>
        <ol type="A">
            <li>雙連坡教室停車位有限，請儘量搭乘大眾交通工具前往，或是與其他學員一同共乘。</li>
            <li>請自備環保杯、環保筷。</li>
        </ol>
        <li>防疫規定：</li>
        <ol type="A">
            <li>實施實(聯)名制：並於集會、活動期間拍照，以利掌握參加人員資料及其在集會活動中之相對位置。</li>
            <li>衛生防護措施：請配合落實量測體溫、噴酒精、全程配戴口罩(用餐時間除外)等個人衛生防護措施，不開放發燒者進入場所或參與活動，會加強環境清潔消毒。</li>
            <li>若因配合政府防疫措施，活動有任何更改，將公佈於活動官方網站，敬請密切注意。</li>
        </ol>
        <li>營隊活動報導：請見【哈特麥1D】粉絲頁，1/25熱烈推出<a href="https://www.facebook.com/heartmind1d">https://www.facebook.com/heartmind1d</a></li>
        <li>諮詢窗口：桃園場 趙小姐 (03)275-6133#1312</li>
    </ol>
@endif
@if($applicant->batch->name == "新竹")
    <ol>
        <li>報到時間：1/30(六)、1/31(日) 08:20〜08:50</li>
        <li>報到地點：新竹市東區忠孝路43號2F</li>
        <li>報到流程：請於報到時以手機出示附件之【QR code】（或列印出紙本）辦理報到。</li>
        <li>交通方式：</li>
        <ol type="A">
            <li>開車者，路邊有收費停車場、停車格(部分假日不收費)</li>
            <li>騎車者，可停愛買露天停車格，步行至會場7分鐘</li>
            <li>搭乘大眾交通工具者，火車站後站設有免費接駁車。</li>
        </ol>
        <li>注意事項：請自備環保杯、環保筷。</li>
        <li>防疫規定：</li>
        <ol type="A">
            <li>實施實(聯)名制。</li>
            <li>衛生防護措施：請配合落實量測體溫、噴酒精、全程配戴口罩(用餐時間除外)等個人衛生防護措施，不開放發燒者進入場所或參與活動。</li>
            <li>若因配合政府防疫措施，活動有任何更改，將公佈於活動官方網站，敬請密切注意。</li>
        </ol>
        <li>營隊活動報導：請見【哈特麥1D】粉絲頁，1/25熱烈推出<a href="https://www.facebook.com/heartmind1d">https://www.facebook.com/heartmind1d</a></li>
        <li>諮詢窗口：新竹場 黃小姐  0928-021-036</li>
    </ol>
@endif
@if($applicant->batch->name == "台中")
    <ol>
        <li>報到時間：</li>
        <ol type="A">
            <li>2/3(三) 08:30〜08:50</li>
            <li>2/4(四) 08:30〜08:50</li>
        </ol>
        <li>報到地點：台中市北區三民路三段129號(台中科技大學三民校區-中商大樓2F) <br>*註明:中商大樓有二個出入口(三民路大門口或中華路)</li>
        <li>報到流程：請於報到時以手機出示附件之【QR code】（或列印出紙本）辦理報到。</li>
        <li>交通方式：<br>
            大眾交通：<br>
            搭乘路經「國立臺中科技大學」站名之公車，皆可直抵本校區。</li>
        <ol type="A">
            <li>臺中客運：6、8、9(圖書館路)、12、14、15、26、29、35、70、71、82、99、108(港尾路)、132、201、304、307、324、500、700、901路等直抵本校區。</li>
            <li>仁友客運：21、105路直抵本校區。</li>
            <li>統聯客運：1、25、61、73、301、303、308、326號直抵本校區。</li>
            <li>豐原客運：55、203、280、285(台中二中路)、286、288、289、900號直抵本校區。</li>
            <li>全航客運：5、58路直抵本校區。</li>
        </ol>
        *台中公車動態:<a href="http://citybus.taichung.gov.tw/ebus/driving-map">http://citybus.taichung.gov.tw/ebus/driving-map</a>
        <li>注意事項：</li>
        <ol type="A">
            <li>本校無提供停車位，請利用搭乘大眾交通工具來校。</li>
            <li>請自備環保杯、環保筷。</li>
        </ol>
        <li>防疫規定：</li>
        <ol type="A">
            <li>實施實(聯)名制：並於集會、活動期間拍照，以利掌握參加人員資料及其在集會活動中之相對位置。</li>
            <li>衛生防護措施：請配合落實量測體溫、噴酒精、全程配戴口罩(用餐時間除外)等個人衛生防護措施，不開放發燒者進入場所或參與活動，會加強環境清潔消毒。</li>
            <li>若因配合政府防疫措施，活動有任何更改，將公佈於活動官方網站，敬請密切注意。</li>
        </ol>
        <li>營隊活動報導：請見【哈特麥1D】粉絲頁，1/25熱烈推出<a href="https://www.facebook.com/heartmind1d">https://www.facebook.com/heartmind1d</a></li>
        <li>諮詢窗口：台中場 江小姐 (04)37069300*621700</li>
    </ol>
@endif
@if($applicant->batch->name == "雲林")
    <ol>
        <li>報到時間：</li>
        <ol type="A">
            <li>1/30(六) 08:20〜08:50</li>
            <li>1/31(日) 08:20〜08:50</li>
        </ol>
        <li>報到地點：雲林縣斗六市慶生路 6 號  (斗六後火車站前方里仁樓上)</li>
        <li>報到流程：請於報到時以手機出示附件之【QR code】（或列印出紙本）辦理報到。</li>
        <li>交通方式：</li>
        <ol type="A">
            <li>台鐵鐵路：斗六站下車，從後站出可以看到里仁看板，就在里仁樓上</li>
            <li>高鐵：從虎尾高鐵站下車，搭乘接駁車到斗六後火車站下車，轉到慶生路即可看到里仁</li>
            <li>台西客運：在斗六站後站下車</li>
        </ol>
        <li>注意事項：</li>
        <ol type="A">
            <li>雲林支苑上課區域停車位有限，請儘量搭乘大眾交通工具來上課。</li>
            <li>請自備環保杯、環保筷。</li>
        </ol>
        <li>防疫規定：</li>
        <ol type="A">
            <li>實施實(聯)名制：並於集會、活動期間拍照，以利掌握參加人員資料及其在集會活動中之相對位置。</li>
            <li>衛生防護措施：請配合落實量測體溫、噴酒精、全程配戴口罩(用餐時間除外)等個人衛生防護措施，不開放發燒者進入場所或參與活動，會加強環境清潔消毒。</li>
            <li>若因配合政府防疫措施，活動有任何更改，將公佈於活動官方網站，敬請密切注意。</li>
        </ol>
        <li>營隊活動報導：請見【哈特麥1D】粉絲頁，1/25熱烈推出<a href="https://www.facebook.com/heartmind1d">https://www.facebook.com/heartmind1d</a></li>
        <li>諮詢窗口：雲林場  吳春桂老師  <br>
        (05)5370133 # 301 <br>
        0921-013450</li>
    </ol>
@endif
@if($applicant->batch->name == "嘉義")
    <ol>
        <li>報到時間：</li>
        <ol type="A">
            <li>1/30(六) 08:30〜08:50</li>
            <li>1/31(日) 08:30〜08:50</li>
        </ol>
        <li>報到地點：嘉義市西區金山路106號</li>
        <li>報到流程：請於報到時以手機出示附件之【QR code】（或列印出紙本）辦理報到。</li>
        <li>交通方式：</li>
        <ol type="A">
            <li>自行開車:停在文小八停車場。</li>
            <li>自行騎摩托車，分苑旁停車場。</li>
        </ol>
        以上都有義工引導，如有特殊需求(例如車站接駁)，請來電告知。
        <li>注意事項：</li>
        <ol type="A">
            <li>本區停車位有限，請儘量騎乘摩托車來參加活動。</li>
            <li>請自備環保杯、環保筷。</li>
        </ol>
        <li>防疫規定：</li>
        <ol type="A">
            <li>實施實(聯)名制：並於集會、活動期間拍照，以利掌握參加人員資料及其在集會活動中之相對位置。</li>
            <li>衛生防護措施：請配合落實量測體溫、噴酒精、全程配戴口罩(用餐時間除外)等個人衛生防護措施，不開放發燒者進入場所或參與活動，會加強環境清潔消毒。</li>
            <li>若因配合政府防疫措施，活動有任何更改，將公佈於活動官方網站，敬請密切注意。</li>
        </ol>
        <li>營隊活動報導：請見【哈特麥1D】粉絲頁，1/25熱烈推出<a href="https://www.facebook.com/heartmind1d">https://www.facebook.com/heartmind1d</a></li>
        <li>諮詢窗口：嘉義場  吳小姐 0928-780108</li>
    </ol>
@endif
@if($applicant->batch->name == "台南")
    <ol>
        <li>
            報到時間：7/14&nbsp;(五)&nbsp;13:00~13:40<br>
            　　　　　7/15&nbsp;(六)&nbsp;08:00~08:50<br>
            　　　　　7/16&nbsp;(日)&nbsp;08:00~08:50
        </li>
        <li>
            營隊上課時間：7/14&nbsp;(五)&nbsp;13:50~18:00<br>
            　　　　　　　7/15&nbsp;(六)&nbsp;09:00~18:00<br>
            　　　　　　　7/16&nbsp;(日)&nbsp;09:00~18:10
        </li>
        <li>
            報到地點：台南市大成國中綜合大樓1F&nbsp;(地址：臺南市南區西門路一段306號)
        </li>
        <li>
            交通參考：<br>
            <ol type="i">
                <li>搭公車站名：
                    <ol type="a">
                        <li>台南火車站公車(南站-新興國小站)<br>
                            台南火車站(南站)公車1,綠17,藍24,紅幹線<br>
                            新興國小站下步行3分鐘</li>
                        <li>台南火車站公車(北站-台南站)<br>
                            台南火車站(北站)公車2,5,11,18,紅2<br>
                            台南站(健康路口)下步行6分鐘</li>
                    </ol>
                </li>
                <li>高鐵接駁巴士(2號出口、第1月台)<br>
                    H31&nbsp;往台南市政府高鐵接駁公車<br>
                    大億麗緻酒店站(小西門)下步行13分鐘</li>
                <li>自行開車(汽車/機車/共乘等)，請依現場義工引導停車。</li>
            </ol>
        </li>
        <li>若有任何問題，歡迎與關懷員聯絡反應。</li>
    </ol>
    &nbsp;建議攜帶物品：<br>
    　　　 隨身背包、文具用品、環保水杯（壺）、環保筷、<strong>禦寒薄外套</strong><br>
    　　　 身份證、健保卡&nbsp;(遇緊急狀況就醫時使用)、請配戴口罩<br>
    　　　 個人常用藥物<br>
    &nbsp;<u><strong>報到時請攜帶附件之報到用 QR Code 報到單。</strong></u>
@endif
@if($applicant->batch->name == "高雄")
    <ol>
        <li>
            <u><strong>報到時間</strong></u>：7/14&nbsp;(五)&nbsp;12:50~13:20<br>
            　　　　　7/15&nbsp;(六)&nbsp;08:00~08:50<br>
            　　　　　7/16&nbsp;(日)&nbsp;08:00~08:50
        </li>
        <li>
            <u>上課時間</u>：7/14&nbsp;(五)&nbsp;13:20~18:00<br>
            　　　　　7/15&nbsp;(六)&nbsp;09:00~18:00<br>
            　　　　　7/16&nbsp;(日)&nbsp;09:00~18:10
        </li>
        <li>
            <u>報到地點</u>：中華電信學院高雄所教學大樓6F<br>
            　　　　　（高雄市仁武區仁勇路400號）
        </li>
        <li>
            <u>交　　通</u>：參閱以下中華電信學院高雄所-交通資訊說明<br>
            　　　　　<a href="https://www.chtti.cht.com.tw/portal/traffic_info_kaohsiung.jsp">https://www.chtti.cht.com.tw/portal/traffic_info_kaohsiung.jsp</a><br>
            　　　　　※現場備有汽機車停車位，請依義工引導停車。<br>
            　　　　　然因車位數量有限，敬請提早入場。謝謝合作！
        </li>
        <li>若有任何問題，歡迎與關懷員聯絡反應，或來電&nbsp;(07)281-9498&nbsp;企業營報名報到組。</li>
    </ol>
    &nbsp;建議攜帶物品：<br>
    　　　 口罩、隨身背包、文具用品、環保水杯（壺）、禦寒薄外套<br>
    　　　 身份證、健保卡&nbsp;(遇緊急狀況就醫時使用)<br>
    　　　 個人常用藥物<br>
    &nbsp;<u><strong>報到時請攜帶附件之報到用 QR Code 報到單。</strong></u>
@endif
</td></tr>
<tr><td align="right">
主辦單位：財團法人福智文教基金會　敬邀<br>
{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日
</td></tr>
</table>
</body>
