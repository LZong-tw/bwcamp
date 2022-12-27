<style>
    u{
        color: red;
    }
</style>
<body style="font-size:16px;">
<h2 class="center">{{ $applicant->batch->camp->fullName }} 報到通知單</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>場次：{{ $applicant->batch->name }}</td>
        <td>姓名：{{ $applicant->name }}</td>
        <td>錄取編號：{{ $applicant->group }}{{ $applicant->number }}</td>
        <td>組別：{{ $applicant->group }}</td>
    </tr>
</table>
<table width="100%" style="table-layout:fixed; border: 0; word-wrap: break-word;">
<tr><td>
@if($applicant->batch->name == "台北" || $applicant->batch->name == "臺北")
    <ol>
        <li>報到時間：</li>
        <ol type="A">
            <li>01/31 (二) 09:00~09:20</li>
            <li>02/01 (三) 08:30~08:50</li>
        </ol>
        <li>報到地點：</li>
        <ol type="A">
            <li>地點：臺北市私立育達高級中等學校</li>
            <li>地址：台北市松山區寧安街12號</li>
        </ol>
        <li>報到流程：</li>
        <ol type="A">
            <li>請於報到時以手機出示附件之【QR code】（或列印出紙本）辦理報到。</li>
        </ol>
        <li>交通方式：</li>
        <ol type="A">
            <li>聯營公車：</li>
                <ul style="list-style-type:disc">
                    <li>南京東路寧安街口站：46&nbsp;248&nbsp;266&nbsp;277&nbsp;279&nbsp;282&nbsp;288&nbsp;306&nbsp;307&nbsp;604&nbsp;605&nbsp;622&nbsp;668&nbsp;675&nbsp;棕9&nbsp;棕10</li>
                    <li>八德路美仁里站：０東&nbsp;202&nbsp;203&nbsp;205&nbsp;257&nbsp;276&nbsp;278&nbsp;605&nbsp;667</li>
                </ul>
            <li>捷運松山線：從【台北小巨蛋站】4號出口步行約三分鐘。</li>
        </ol>
        <li>注意事項：</li>
        <ol type="A">
            <li>校區不提供停車位，請儘量搭乘大眾交通工具來校，若開車前來，需請自行尋找停車位。</li>
            <li>請自備環保杯、環保筷、雨具、健保卡。</li>
        </ol>
        <li>防疫規定：</li>
        <ol type="A">
            <li>衛生防護措施：請配合落實噴酒精、全程配戴口罩(用餐時間除外)等個人衛生防護措施，不開放發燒者進入場所或參與活動，並應加強環境清潔消毒。</li>
        </ol>
        <li>營隊活動報導：</li>
        <ul style="list-style-type: none">
            <li>請見<a href="https://www.facebook.com/groups/bwfoce.happiness.new">「幸福心學堂online」FB社團</a></li>
        </ul>
        <li>
            歡迎加入【幸福橘之森】App，每日靜心祈求幸福靈籤，日日驅散陰霾溫暖內心洋溢幸福，還有讓身心幸福的課程與每日方便寫下幸福的日記喔~
            <table border="1">
                <tr><td align="center">手機系統</td><td align="center">網址</td><td align="center">QR碼</td></tr>
                <tr><td align="center">Apple iOS</td><td align="center"><a href="http://bwfoce.org/mamoria">http://bwfoce.org/mamoria</a></td><td align="center">
                        <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_iOS.png" alt="http://bwfoce.org/mamoria"></td></tr>
                <tr><td align="center">Android</td><td align="center"><a href="http://bwfoce.org/mamorib">http://bwfoce.org/mamorib</a></td><td align="center">
                        <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_Android.png" alt="http://bwfoce.org/mamorib"></td></tr>
            </table>
        </li>
        <li>諮詢窗口：臺北場 劉小姐 (02)25453788 #529</li>
    </ol>
@endif
@if($applicant->batch->name == "桃園")
        <ol>
            <li>報到時間：</li>
            <ol type="A">
                <li>01/31 (二) 09:00~09:20</li>
                <li>02/01 (三) 08:30~08:50</li>
            </ol>
            <li>報到地點：</li>
            <ol type="A">
                <li>地點：桃園市立內壢高級中等學校</li>
                <li>地址：桃園市中壢區成章四街120號</li>
            </ol>
            <li>報到流程：</li>
            <ol type="A">
                <li>請於報到時以手機出示附件之【QR code】（或列印出紙本）辦理報到。</li>
            </ol>
            <li>交通方式：</li>
            <ol type="A">
                <li>搭乘火車：內壢火車站下車，步行約十分鐘。</li>
                <li>自行開車：下內壢交流道->中園路->吉林路->文化路->成章一街->成章四街。</li>
            </ol>
            <li>注意事項：</li>
            <ol type="A">
                <li>學校停車位不足，請盡量共乘或搭大眾運輸工具。</li>
                <li>請自備環保杯、環保筷。</li>
            </ol>
            <li>防疫規定：</li>
            <ol type="A">
                <li>衛生防護措施：請配合落實噴酒精、全程配戴口罩(用餐時間除外)等個人衛生防護措施，不開放發燒者進入場所或參與活動，並應加強環境清潔消毒。</li>
            </ol>
            <li>營隊活動報導：</li>
            <ul style="list-style-type: none">
                <li>請見<a href="https://www.facebook.com/groups/bwfoce.happiness.new">「幸福心學堂online」FB社團</a></li>
            </ul>
            <li>
                歡迎加入【幸福橘之森】App，每日靜心祈求幸福靈籤，日日驅散陰霾溫暖內心洋溢幸福，還有讓身心幸福的課程與每日方便寫下幸福的日記喔~
                <table border="1">
                    <tr><td align="center">手機系統</td><td align="center">網址</td><td align="center">QR碼</td></tr>
                    <tr><td align="center">Apple iOS</td><td align="center"><a href="http://bwfoce.org/mamoria">http://bwfoce.org/mamoria</a></td><td align="center">
                            <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_iOS.png" alt="http://bwfoce.org/mamoria"></td></tr>
                    <tr><td align="center">Android</td><td align="center"><a href="http://bwfoce.org/mamorib">http://bwfoce.org/mamorib</a></td><td align="center">
                            <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_Android.png" alt="http://bwfoce.org/mamorib"></td></tr>
                </table>
            </li>
            <li>諮詢窗口：桃園場 李小姐 (03)2756133 #1324</li>
        </ol>
@endif
@if($applicant->batch->name == "新竹")
        <ol>
            <li>報到時間：</li>
            <ol type="A">
                <li>01/31 (二) 09:00~09:20</li>
                <li>02/01 (三) 08:30~08:50</li>
            </ol>
            <li>報到地點：</li>
            <ol type="A">
                <li>地點：明新科技大學</li>
                <li>地址：新竹縣新豐鄉新興路1號</li>
            </ol>
            <li>報到流程：</li>
            <ol type="A">
                <li>請於報到時以手機出示附件之【QR code】（或列印出紙本）辦理報到。</li>
            </ol>
            <li>交通方式：</li>
            <ol type="A">
                <li>搭乘火車：可搭電車至新豐火車站，出車站大門左轉，步行約10分鐘即可抵達本校。</li>
                <li>自行開車：</li>
                <ul style="list-style-type:disc">
                    <li>中山高湖口交流道下(83.8公里出口)，往湖口、新豐方向行駛，遇士林電機(丁字路口)右轉400公尺後，再左轉走新興路(省道台一線)，經新豐火車站約900公尺，抵達明新科技大學。</li>
                    <li>中山高竹北交流道下(91.0公里出口)，往竹北市區方向行駛，走光明六路，遇中華路(省道台一線)右轉往新豐方向，經竹北派出所後約4公里即可抵達明新科技大學。</li>
                </ul>
            </ol>
            <li>注意事項：</li>
            <ol type="A">
                <li>校內備有汽機車停車場，汽車一次進出收費50元，機車一次進出收費20元。</li>
                <li>請自備環保杯、環保筷。</li>
            </ol>
            <li>防疫規定：</li>
            <ol type="A">
                <li>衛生防護措施：請配合落實噴酒精、全程配戴口罩(用餐時間除外)等個人衛生防護措施，不開放發燒者進入場所或參與活動，並應加強環境清潔消毒。</li>
            </ol>
            <li>營隊活動報導：</li>
            <ul style="list-style-type: none">
                <li>請見<a href="https://www.facebook.com/groups/bwfoce.happiness.new">「幸福心學堂online」FB社團</a></li>
            </ul>
            <li>
                歡迎加入【幸福橘之森】App，每日靜心祈求幸福靈籤，日日驅散陰霾溫暖內心洋溢幸福，還有讓身心幸福的課程與每日方便寫下幸福的日記喔~
                <table border="1">
                    <tr><td align="center">手機系統</td><td align="center">網址</td><td align="center">QR碼</td></tr>
                    <tr><td align="center">Apple iOS</td><td align="center"><a href="http://bwfoce.org/mamoria">http://bwfoce.org/mamoria</a></td><td align="center">
                            <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_iOS.png" alt="http://bwfoce.org/mamoria"></td></tr>
                    <tr><td align="center">Android</td><td align="center"><a href="http://bwfoce.org/mamorib">http://bwfoce.org/mamorib</a></td><td align="center">
                            <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_Android.png" alt="http://bwfoce.org/mamorib"></td></tr>
                </table>
            </li>
            <li>諮詢窗口：新竹場 張小姐 (03)5325566 #246</li>
        </ol>
@endif
@if($applicant->batch->name == "台中" || $applicant->batch->name == "臺中")
        <ol>
            <li>報到時間：</li>
            <ol type="A">
                <li>01/31 (二) 09:00~09:20</li>
                <li>02/01 (三) 08:30~08:50</li>
            </ol>
            <li>報到地點：</li>
            <ol type="A">
                <li>地點：國立臺中教育大學英才校區</li>
                <li>地址：臺中市西區民生路227號</li>
            </ol>
            <li>報到流程：</li>
            <ol type="A">
                <li>請於報到時以手機出示附件之【QR code】（或列印出紙本）辦理報到。</li>
            </ol>
            <li>交通方式：</li>
            <ol type="A">
                <li>搭火車：</li>
                <ul style="list-style-type:disc">
                    <li>在臺中火車站搭乘公車27、290 號，到向上英才路口下車，步行約5分鐘。</li>
                    <li>在臺中火車站搭乘5號公車，到五權自立街口下車，步行約6分鐘。</li>
                    <li>在臺中火車站搭乘公車11左、32 、323 、324 、325號，到臺中教育大學站下車，由民生校區步行到英才校區約8~9分鐘。</li>
                </ul>
                <li>自行開車：</li>
                <ul style="list-style-type:disc">
                    <li>中教大的民生校區（台中市民生路140號）提供平面車位約80車位，步行到英才校區（主場地）約8-9分鐘，一次收費50元。</li>
                    <li>自行在英才路附近找停車位，附近設有多個停車場及路邊可停，但依政府收費標準收費。</li>
                </ul>
            </ol>
            <li>注意事項：</li>
            <ol type="A">
                <li>請自備環保杯、環保筷及隨身帶健保卡。</li>
            </ol>
            <li>防疫規定：</li>
            <ol type="A">
                <li>衛生防護措施：請配合落實噴酒精、全程配戴口罩(用餐時間除外)等個人衛生防護措施，不開放發燒者進入場所或參與活動，並應加強環境清潔消毒。</li>
            </ol>
            <li>營隊活動報導：</li>
            <ul style="list-style-type: none">
                <li>請見<a href="https://www.facebook.com/groups/bwfoce.happiness.new">「幸福心學堂online」FB社團</a></li>
            </ul>
            <li>
                歡迎加入【幸福橘之森】App，每日靜心祈求幸福靈籤，日日驅散陰霾溫暖內心洋溢幸福，還有讓身心幸福的課程與每日方便寫下幸福的日記喔~
                <table border="1">
                    <tr><td align="center">手機系統</td><td align="center">網址</td><td align="center">QR碼</td></tr>
                    <tr><td align="center">Apple iOS</td><td align="center"><a href="http://bwfoce.org/mamoria">http://bwfoce.org/mamoria</a></td><td align="center">
                            <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_iOS.png" alt="http://bwfoce.org/mamoria"></td></tr>
                    <tr><td align="center">Android</td><td align="center"><a href="http://bwfoce.org/mamorib">http://bwfoce.org/mamorib</a></td><td align="center">
                            <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_Android.png" alt="http://bwfoce.org/mamorib"></td></tr>
                </table>
            </li>
            <li>諮詢窗口：臺中場 廖小姐 (04)37069300 #621400</li>
        </ol>
@endif
@if($applicant->batch->name == "雲林")
    <ol>
        <li>報到時間：</li>
        <ol type="A">
            <li>01/31 (二) 09:00~09:20</li>
            <li>02/01 (三) 08:30~08:50</li>
        </ol>
        <li>報到地點：</li>
        <ol type="A">
            <li>地點：福智雲林支苑（里仁斗六後驛店旁）</li>
            <li>地址：雲林縣斗六市慶生路6號</li>
        </ol>
        <li>報到流程：</li>
        <ol type="A">
            <li>請於報到時以手機出示附件之【QR code】（或列印出紙本）辦理報到。</li>
        </ol>
        <li>交通方式：</li>
        <ol type="A">
            <li>搭火車：搭至斗六車站，月台出來往左，武昌路出口。里仁斗六後驛店旁。</li>
            <li>自行開車GPS定位：斗六市慶生路6號，或斗六火車站後站</li>
        </ol>
        <li>注意事項：</li>
        <ol type="A">
            <li>慶生路旁有收費停車場，以及路邊停車格。</li>
            <li>可停放雲林縣公誠國小停車場（距離研習地點，走路約5分鐘），有門禁，無法自由進出。需當天研習結束後才可以開車離開。</li>
            <li>請自備環保杯、環保筷。</li>
        </ol>
        <li>防疫規定：</li>
        <ol type="A">
            <li>衛生防護措施：請配合落實噴酒精、全程配戴口罩(用餐時間除外)等個人衛生防護措施，不開放發燒者進入場所或參與活動，並應加強環境清潔消毒。</li>
        </ol>
        <li>營隊活動報導：</li>
        <ul style="list-style-type: none">
            <li>請見<a href="https://www.facebook.com/groups/bwfoce.happiness.new">「幸福心學堂online」FB社團</a></li>
        </ul>
        <li>
            歡迎加入【幸福橘之森】App，每日靜心祈求幸福靈籤，日日驅散陰霾溫暖內心洋溢幸福，還有讓身心幸福的課程與每日方便寫下幸福的日記喔~
            <table border="1">
                <tr><td align="center">手機系統</td><td align="center">網址</td><td align="center">QR碼</td></tr>
                <tr><td align="center">Apple iOS</td><td align="center"><a href="http://bwfoce.org/mamoria">http://bwfoce.org/mamoria</a></td><td align="center">
                        <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_iOS.png" alt="http://bwfoce.org/mamoria"></td></tr>
                <tr><td align="center">Android</td><td align="center"><a href="http://bwfoce.org/mamorib">http://bwfoce.org/mamorib</a></td><td align="center">
                        <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_Android.png" alt="http://bwfoce.org/mamorib"></td></tr>
            </table>
        </li>
        <li>諮詢窗口：</li>
        <ol type="A">
            <li>陳小姐 雲林 (05)5370133 #303</li>
            <li>方先生 嘉義 (05)2833940 #305</li>
        </ol>
    </ol>
@endif
@if($applicant->batch->name == "嘉義")
    <ol>
        <li>報到時間：</li>
        <ol type="A">
            <li>01/31 (二) 09:00~09:20</li>
            <li>02/01 (三) 08:30~08:50</li>
        </ol>
        <li>報到地點：</li>
        <ol type="A">
            <li>地點：福智雲林支苑（里仁斗六後驛店旁）</li>
            <li>地址：雲林縣斗六市慶生路6號</li>
        </ol>
        <li>報到流程：</li>
        <ol type="A">
            <li>請於報到時以手機出示附件之【QR code】（或列印出紙本）辦理報到。</li>
        </ol>
        <li>交通方式：</li>
        <ol type="A">
            <li>搭火車：搭至斗六車站，月台出來往左，武昌路出口。里仁斗六後驛店旁。</li>
            <li>自行開車GPS定位：斗六市慶生路6號，或斗六火車站後站</li>
        </ol>
        <li>注意事項：</li>
        <ol type="A">
            <li>慶生路旁有收費停車場，以及路邊停車格。</li>
            <li>可停放雲林縣公誠國小停車場（距離研習地點，走路約5分鐘），有門禁，無法自由進出。需當天研習結束後才可以開車離開。</li>
            <li>請自備環保杯、環保筷。</li>
        </ol>
        <li>防疫規定：</li>
        <ol type="A">
            <li>衛生防護措施：請配合落實噴酒精、全程配戴口罩(用餐時間除外)等個人衛生防護措施，不開放發燒者進入場所或參與活動，並應加強環境清潔消毒。</li>
        </ol>
        <li>營隊活動報導：</li>
        <ul style="list-style-type: none">
            <li>請見<a href="https://www.facebook.com/groups/bwfoce.happiness.new">「幸福心學堂online」FB社團</a></li>
        </ul>
        <li>
            歡迎加入【幸福橘之森】App，每日靜心祈求幸福靈籤，日日驅散陰霾溫暖內心洋溢幸福，還有讓身心幸福的課程與每日方便寫下幸福的日記喔~
            <table border="1">
                <tr><td align="center">手機系統</td><td align="center">網址</td><td align="center">QR碼</td></tr>
                <tr><td align="center">Apple iOS</td><td align="center"><a href="http://bwfoce.org/mamoria">http://bwfoce.org/mamoria</a></td><td align="center">
                        <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_iOS.png" alt="http://bwfoce.org/mamoria"></td></tr>
                <tr><td align="center">Android</td><td align="center"><a href="http://bwfoce.org/mamorib">http://bwfoce.org/mamorib</a></td><td align="center">
                        <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_Android.png" alt="http://bwfoce.org/mamorib"></td></tr>
            </table>
        </li>
        <li>諮詢窗口：</li>
        <ol type="A">
            <li>陳小姐 雲林 (05)5370133 #303</li>
            <li>方先生 嘉義 (05)2833940 #305</li>
        </ol>
    </ol>
@endif
@if($applicant->batch->name == "台南" || $applicant->batch->name == "臺南")
    <ol>
        <li>報到時間：</li>
        <ol type="A">
            <li>01/31 (二) 09:00~09:20</li>
            <li>02/01 (三) 08:30~08:50</li>
        </ol>
        <li>報到地點：</li>
        <ol type="A">
            <li>地點：臺南市立大成國中</li>
            <li>地址：臺南市南區西門路一段306號</li>
        </ol>
        <li>報到流程：</li>
        <ol type="A">
            <li>請於報到時以手機出示附件之【QR code】（或列印出紙本）辦理報到。</li>
        </ol>
        <li>交通方式：</li>
        <ol type="A">
            <li>台南火車站公車：</li>
            <ul style="list-style-type:disc">
                <li>(南站-新興國小)：公車1，綠17，藍24，紅幹線至新興國小，步行3分鐘。</li>
                <li>(北站-台南站)：公車2，5，11，18，紅2至台南站(健康路口)，步行6分鐘。</li>
            </ul>
            <li>高鐵接駁巴士(2號出口、第1月台)：H31往台南市政府至大億麗緻酒店(小西門)，步行16分鐘至大成國中。</li>
        </ol>
        <li>注意事項：</li>
        <ol type="A">
            <li>校區提供免費機、汽車停車位。若車位已滿，請依義工引導停放。</li>
            <li>請自備環保杯、環保筷。</li>
        </ol>
        <li>防疫規定：</li>
        <ol type="A">
            <li>衛生防護措施：請配合落實噴酒精、全程配戴口罩(用餐時間除外)等個人衛生防護措施，不開放發燒者進入場所或參與活動，並應加強環境清潔消毒。</li>
        </ol>
        <li>營隊活動報導：</li>
        <ul style="list-style-type: none">
            <li>請見<a href="https://www.facebook.com/groups/bwfoce.happiness.new">「幸福心學堂online」FB社團</a></li>
        </ul>
        <li>
            歡迎加入【幸福橘之森】App，每日靜心祈求幸福靈籤，日日驅散陰霾溫暖內心洋溢幸福，還有讓身心幸福的課程與每日方便寫下幸福的日記喔~
            <table border="1">
                <tr><td align="center">手機系統</td><td align="center">網址</td><td align="center">QR碼</td></tr>
                <tr><td align="center">Apple iOS</td><td align="center"><a href="http://bwfoce.org/mamoria">http://bwfoce.org/mamoria</a></td><td align="center">
                        <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_iOS.png" alt="http://bwfoce.org/mamoria"></td></tr>
                <tr><td align="center">Android</td><td align="center"><a href="http://bwfoce.org/mamorib">http://bwfoce.org/mamorib</a></td><td align="center">
                        <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_Android.png" alt="http://bwfoce.org/mamorib"></td></tr>
            </table>
        </li>
        <li>諮詢窗口：臺南場 吳小姐 (06)2646831 #412</li>
    </ol>
@endif
@if($applicant->batch->name == "高雄")
    <ol>
        <li>報到時間：</li>
        <ol type="A">
            <li>01/31 (二) 09:00~09:20</li>
            <li>02/01 (三) 08:30~08:50</li>
        </ol>
        <li>報到地點：</li>
        <ol type="A">
            <li>地點：高雄科技大學</li>
            <li>地址：高雄市三民區建工路415號</li>
        </ol>
        <li>報到流程：</li>
        <ol type="A">
            <li>請於報到時以手機出示附件之【QR code】（或列印出紙本）辦理報到。</li>
        </ol>
        <li>交通方式：</li>
        <ol type="A">
            <li>搭火車：由高雄火車站直接轉公車紅30至高雄科大(建工校區)。</li>
            <li>搭高鐵：由左營高鐵站直接轉公車16A至高雄科大(建工校區)。</li>
            <li>搭捷運：由後驛站直接轉公車紅30至高雄科大(建工校區)。</li>
            <li>自行開車：</li>
            <ul style="list-style-type:disc">
                <li>路線1：下九如交流道往高雄市區方向→九如路→大昌路→建工路</li>
                <li>路線2：下中正交流道往高雄市區方向→中正路→大順路→建工路</li>
            </ul>
        </ol>
        <li>注意事項：</li>
        <ol type="A">
            <li>校區提供免費機、汽車停車位。</li>
            <li>請自備環保杯、環保筷、雨具、健保卡。</li>
        </ol>
        <li>防疫規定：</li>
        <ol type="A">
            <li>衛生防護措施：請配合落實噴酒精、全程配戴口罩(用餐時間除外)等個人衛生防護措施，不開放發燒者進入場所或參與活動，並應加強環境清潔消毒。</li>
        </ol>
        <li>營隊活動報導：</li>
        <ul style="list-style-type: none">
            <li>請見<a href="https://www.facebook.com/groups/bwfoce.happiness.new">「幸福心學堂online」FB社團</a></li>
        </ul>
        <li>
            歡迎加入【幸福橘之森】App，每日靜心祈求幸福靈籤，日日驅散陰霾溫暖內心洋溢幸福，還有讓身心幸福的課程與每日方便寫下幸福的日記喔~
            <table border="1">
                <tr><td align="center">手機系統</td><td align="center">網址</td><td align="center">QR碼</td></tr>
                <tr><td align="center">Apple iOS</td><td align="center"><a href="http://bwfoce.org/mamoria">http://bwfoce.org/mamoria</a></td><td align="center">
                        <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_iOS.png" alt="http://bwfoce.org/mamoria"></td></tr>
                <tr><td align="center">Android</td><td align="center"><a href="http://bwfoce.org/mamorib">http://bwfoce.org/mamorib</a></td><td align="center">
                        <img width="30%" src="https://bwcamp.bwfoce.org/img/mamori_Android.png" alt="http://bwfoce.org/mamorib"></td></tr>
            </table>
        </li>
        <li>諮詢窗口：高雄場 林小姐 (07)9769341 #417</li>
    </ol>
@endif
</td></tr>
</table>
<a class="right">財團法人福智文教基金會　謹此</a><br>
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>
</body>
