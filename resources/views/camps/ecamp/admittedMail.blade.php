<style>
    u{
        color: red;
    }
</style>
<h2 class="center">{{ $applicant->batch->camp->fullName }}<br>分組通知函</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>姓名：{{ $applicant->name }}</td>
        <td>序號：{{ $applicant->id }}</td>
        <td>組別：{{ $applicant->groupRelation->alias }}</td>
        <td>場次：{{ $applicant->batch->name }}</td>
    </tr>
</table><br>

親愛的企業主管您好 :<br>
感謝您報名「2023企業主管生命成長營」，誠摯歡迎您參加本研習活動。我們已經為您分編好組別，並安排了充滿熱誠的關懷員提供服務，誠摯的歡迎您來共享這場心靈饗宴，一起翻轉人生，開創無限美好的幸福。<br><br>
為使研習進行順利，請詳閱下列須知：
@if (str_contains($applicant->batch->camp->fullName, "台北"))
    <ol>
        <li>營隊上課時間：<ul>
                <li>7/14 (五) 13:50~18:00</li>
                <li>7/15 (六) 09:00~18:00</li>
                <li>7/16 (日) 09:00~18:30</li>
            </ul></li>
        <li>舉辦地點 : 東吳大學 外雙溪校區（台北市士林區臨溪路70號）</li>
        <li>
            交通參考 :<br>
            請參閱以下 東吳大學 外雙溪校區 -交通資訊說明<br>
            <a href="https://www-ch.scu.edu.tw/october/school_traffic">https://www-ch.scu.edu.tw/october/school_traffic</a>
        </li>
        <li>營隊關懷員近日內將透過簡訊及電話與您聯繫。<br>
            如有任何問題，也歡迎主動與關懷員聯絡。
        </li>
        <li>
            {{ $applicant->groupRelation->alias }}關懷員 :
            <ol>
                @foreach(\App\Models\User::whereHas('roles', function($q) use ($applicant) {
                    $q->where('group_id', $applicant->group_id);
                })->get() as $user)
                    <li>{{ $user->application_log()->orderByDesc('id')->first()->name }} {{ $user->application_log()->orderByDesc('id')->first()->mobile }}</li>
                @endforeach
            </ol>
        </li>
    </ol>
@endif
@if (str_contains($applicant->batch->camp->fullName, "高雄"))
    <ol>
        <li>營隊上課時間：<ul>
                <li>7/14 (五) 13:50~18:00</li>
                <li>7/15 (六) 09:00~18:00</li>
                <li>7/16 (日) 09:00~18:30</li>
            </ul></li>
        <li>舉辦地點 : 中華電信學院 高雄所（高雄市仁武區仁勇路400號）</li>
        <li>
            交通參考 :<br>
            請參閱以下 中華電信學院 高雄所 -交通資訊說明<br>
            <a href="https://www.chtti.cht.com.tw/portal/traffic_info_kaohsiung.jsp">https://www.chtti.cht.com.tw/portal/traffic_info_kaohsiung.jsp</a>
        </li>
        <li>營隊關懷員近日內將透過簡訊及電話與您聯繫。<br>
            如有任何問題，也歡迎主動與關懷員聯絡。
        </li>
        <li>
            {{ $applicant->groupRelation->alias }}關懷員 :
            <ol>
                @foreach(\App\Models\User::whereHas('roles', function($q) use ($applicant) {
                    $q->where('group_id', $applicant->group_id);
                })->get() as $user)
                    <li>{{ $user->application_log()->orderByDesc('id')->first()->name }} {{ $user->application_log()->orderByDesc('id')->first()->mobile }}</li>
                @endforeach
            </ol>
        </li>
    </ol>
@endif
@if (str_contains($applicant->batch->camp->fullName, "中區"))
    <ol>
        <li>營隊上課時間：7月14日(星期五)至7月16日(星期日)</li>
        <li>報到時間：7月14日 (星期五)12:30～13:30</li>
        <li>報到地點：聯合大學 二坪山校區（36003苗栗縣苗栗市聯大1號）活動中心</li>
        <li>交通接駁：本基會將於11:30~12:30在【臺中市政府臺灣大道市政大樓廣場】集合，提供交通接駁服務，現場有穿黃色背心義工協助引導 (逾12:30請自行車前往聯合大學)。</li>
        <li>
            自行前往交通資訊 : <br>
            請參閱以下 聯合大學 二坪山校區 -交通資訊說明<br>
            <a href="https://www.nuu.edu.tw/p/412-1000-82.php?Lang=zh-tw">https://www.nuu.edu.tw/p/412-1000-82.php?Lang=zh-tw</a>
        </li>
        <li>營隊關懷員近日內將透過簡訊及電話與您聯繫。<br>
            如有任何問題，也歡迎主動與關懷員聯絡，或來電本會（04）3706-9300 #62-1201企業營報名報到組。<br>
            {{ $applicant->groupRelation->alias }}關懷員 :
            <ol>
                @foreach(\App\Models\User::whereHas('roles', function($q) use ($applicant) {
                    $q->where('group_id', $applicant->group_id);
                })->get() as $user)
                    <li>{{ $user->application_log()->orderByDesc('id')->first()->name }} {{ $user->application_log()->orderByDesc('id')->first()->mobile }}</li>
                @endforeach
            </ol>
        </li>
    </ol>
    以下謹列出參加此次活動所需攜帶物品，以及本營隊提供之物品，方便您準備行李之依據。<br>
    ※ 應攜帶物品：
    <ul type="disc">
        <li>多套換洗衣物(洗衣不方便)、塑膠袋(裝使用過之衣物)</li>
        <li>毛巾、牙膏、牙刷、香皂、洗髮精、拖鞋、衣架。</li>
        <li>隨身背包(教材約A4大小)、文具用品、環保水杯、環保筷。</li>
        <li>刮鬍刀、指甲刀、耳塞(睡覺時易受聲音干擾者)。</li>
        <li>身分證、健保卡(生病時就診用)</li>
        <li>個人常用藥物</li>
    </ul>
    ※ 本營隊已提供寢具，可不必攜帶，也可自行攜帶。<br>
    ※ 注意事項：<br>
    ⬥研習期間晚上也安排各項成長活動，為達成研習效果，請盡量不外出及外宿。<br>
    ⬥會場停車位有限，請多利用本會提供之交通接駁服務。
@endif
@if (str_contains($applicant->batch->camp->fullName, "新竹"))
    <ol>
        <li>營隊上課時間：<ul>
                <li>7/14 (五) 13:50~18:00</li>
                <li>7/15 (六) 09:00~18:00</li>
                <li>7/16 (日) 09:00~18:30</li>
            </ul></li>
        <li>舉辦地點 : 工研院 中興院區51館（新竹縣竹東鎮中興路四段195號51館）</li>
        <li>
            交通參考 :<br>
            請參閱以下 工研院 中興院區 -交通資訊說明<br>
            <a href="https://www.itri.org.tw/ListStyle.aspx?DisplayStyle=20&SiteID=1&MmmID=1036712143533651215&MGID=1036712143620275737">https://www.itri.org.tw/ListStyle.aspx?DisplayStyle=20&SiteID=1&MmmID=1036712143533651215&MGID=1036712143620275737</a><br>
            位置圖 <a href="https://imap.itri.org.tw/QRCode.aspx?from=E&to=51">ITRIiMap 院區導覽地圖</a>
        </li>
        <li>營隊關懷員近日內將透過簡訊及電話與您聯繫。<br>
            如有任何問題，也歡迎主動與關懷員聯絡。
        </li>
        <li>
            {{ $applicant->groupRelation->alias }}關懷員 :
            <ol>
                @foreach(\App\Models\User::whereHas('roles', function($q) use ($applicant) {
                    $q->where('group_id', $applicant->group_id)->where('position', 'like', '%組長%');
                })->get() as $user)
                    <li>{{ $user->application_log()->orderByDesc('id')->first()->name }} {{ $user->application_log()->orderByDesc('id')->first()->mobile }}</li>
                @endforeach
            </ol>
        </li>
    </ol>
@endif
@if (str_contains($applicant->batch->camp->fullName, "台南"))
    <ol>
        <li>營隊上課時間：<ul>
                <li>7/14 (五) 13:50~18:00</li>
                <li>7/15 (六) 09:00~18:00</li>
                <li>7/16 (日) 09:00~18:30</li>
            </ul></li>
        <li>舉辦地點 : 台南市大成國中中正大樓演藝廳 (地址：臺南市南區西門路一段306號)</li>
        <li>
            交通參考 :<br>
            <ol type="i">
                <li>
                    搭公車站名：
                    <ol type="a">
                        <li>台南火車站公車(南站-新興國小站) <br>
                            台南火車站(南站)公車1,綠17,藍24,紅幹線 <br>
                            新興國小站下步行3分鐘</li>
                        <li>台南火車站公車(北站-台南站) <br>
                            台南火車站(北站)公車2,5,11,18,紅2 <br>
                            台南站(健康路口)下步行6分鐘</li>
                    </ol>
                </li>
                <li>
                    高鐵接駁巴士(2號出口、第1月台)
                    H31往台南市政府高鐵接駁公車
                    大億麗緻酒店站(小西門)下步行13分鐘
                </li>
                <li>
                    自行開車
                </li>
            </ol>
        </li>
        <li>營隊關懷員近日內將透過簡訊及電話與您聯繫。<br>
            如有任何問題，也歡迎主動與關懷員聯絡。
        </li>
        <li>
            {{ $applicant->groupRelation->alias }}關懷員 :
            <ol>
                @foreach(\App\Models\User::whereHas('roles', function($q) use ($applicant) {
                    $q->where('group_id', $applicant->group_id);
                })->get() as $user)
                    <li>{{ $user->application_log()->orderByDesc('id')->first()->name }} {{ $user->application_log()->orderByDesc('id')->first()->mobile }}</li>
                @endforeach
            </ol>
        </li>
    </ol>
@endif
@if (str_contains($applicant->batch->camp->fullName, "桃園"))
    <ol>
        <li>營隊上課時間：<ul>
                <li>7/14 (五) 13:50~18:00</li>
                <li>7/15 (六) 09:00~18:00</li>
                <li>7/16 (日) 09:00~18:30</li>
            </ul></li>
        <li>舉辦地點 : 桃園市立內壢高中（桃園市中壢區成章四街120號）</li>
        <li><br>
            <table width="100%" style="table-layout:fixed; border: 0; margin-top: -12px; top: -12px; float: left; position: relative; z-index: 1">
                <tr>
                    <td>
                        交通參考 :<br>
                        請參閱以下 桃園市立內壢高中 -交通說明 <br>
                        <a href="https://reurl.cc/OVylVy">https://reurl.cc/OVylVy</a> <br>
                        Google MAP : <br>
                        <a href="https://reurl.cc/lvxqgY">https://reurl.cc/lvxqgY</a>
                    </td>
                    <td><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHIAAACCCAMAAACtkWxWAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMAUExURf///wAAAAgICFJSUvfv97W1tUJCQubv72NrY5SElHt7jDEpMWtKOubvSmtKEBAZ5hAZtdbe1pScnObm3sW9vZQQnJQQGc7FznNzaxBK5hBKtZytreaUzs6UnBAZjOZrzs5rnBAZYzFCOhAQGWtSWjEZ5nMZ5jEZtXMZteYQ7+YQa+YQreYQKbW95rW9Ge+9hFIZ5lIZteYQzuYQSuYQjOYQCJS95pS9Gc69hJxjnJS9hNa9GSkhEHPv3nPvWghCOjHv3jHvWmOcOjHvnDHvGXPvnHPvGaWU77VC3rVCWrVCnLVCGbWUWrWUGXMZjDHF3jHFWmOcEDHFnDHFGaVr73PF3nPFWnPFnHPFGbVrWrVrGXMZY2N77xCcWhCc3hCcGRCcnAhCEGOczjF7WjF73jF7GTF7nFLv3lLvWlLvnFLvGZRC3pRCWpRCnJRCGZSUWpSUGVIZjFLF3lLFWlLFnFLFGZRrWpRrGVIZY5SUjN697zEQOhBKjBBKY3NK5nOUYzFK5jFKtXNKtbW9SlJK5lKUY1JKtZS9SjFKCNa9SuaU7+aUa+ZC7+ZCa++UnOZCreZCKeaUKbXv5nOcnDEZjLXvGe/vhOZr7+9rnDEZY+Zra+ZrKVpznOaUSuZCzuZCSuZCjOZCCOaUCJTv5lKcnJTvGc7vhOZrSuZrCCEpKZTmtZTvhNbvGVoZOloZEKWcpWOc7zGcWjGc3jGcGTGcnLW9hPe9Ge+9te/mtXNShDFKjLXvSjFKYzEIEM7mtVJShJTvSvfF5ntzezFKKfe9SrXmtbXvhPfvGXsZOnsZENbWzhDv7xDva3N7OhDvrRDvKbWUzhDF7xDFa3N7EBDFrRDFKbVrznN7zhB7axB77xB7KRB7rRDvzhDvSlJ7OhDvjBDvCJSUzhDFzhDFSlJ7EBDFjBDFCJRrzlJ7zhB7ShB7zhB7CBB7jLUQ77UQa7UQrbUQKZQQ75QQa7UQzrUQSrUQjLUQCJQQzpQQSggQOggAKffv3vf/3hAAAPf//wAAAGgGWggAAAEAdFJOU////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wBT9wclAAAACXBIWXMAAA7EAAAOxAGVKw4bAAANjklEQVRoQ+1bS46rShY0YH7FCIyEJWamluGNedTrYeQ90DO2VKqnjkgyXKfSiavek3rQrRuUL788nIzzy+RzD3/w/4jyUB53fr7FF/wJQo0Evxv7hThNn1O6s9x9G2HeDje4SuubZP5Ut3+VKVTaJvu4+TbCvB2ejofDum0mhT918/sxfPg2wskfj6HzbYTrdviKTakUy9rvxxCyLHnwOj0tKQ5LZVme3NJM7tzcnsrBixVl+cFLkqVE7cJmMcM2f5Vw87ffocJxqSzoqM9pWhEOOLtO08QeFWiXf05JhSZkuXy/BH7HskmSzxjLxm9bvFmV2Cbabbf83HYplmNN45Jl7c5+B1QmJ78t0CkxlWcc31H54XcZOlT5YOnOfgciLt1leWofC9ssOK6IzabrzL8V26e2HGZsezHLkirLtnxcB7tRlvTlBes8mVJkEf9SylLlI2KdW47IjUN5TdMZbupxmixHrN+xlsrzJy/BJU0H7MdYMknUXeFJ5RdK0GNefvV0M2xnxIQc+2QZjVisf6sSYT/7UiBf0rD0JcOHgS54lmHEHuVLWko4Y58qeY2ybVsjQ5ZX7LfzNBdt+3FHM+vLGMuYL1+xzOCcng03kOXEjePhmMNfbG4j1rKkWITlI2J3VWLN7nqIJeEqEGBZMrcEqtzzpeJACFXSKR4sAwwfQr6wKn/B8uFLq5KWokrmJVV2bbt6Ncdmngtc47iubT5jB6AqROwjtwSxDH35U5JQZeKTzAEJin8HHCqOSFiOzUDMl/80L51KwBiXUNQJNCwD3Pry70SsDPuDSg3PxIuI3a2xQwPPbMvM7krleZ4bHqTKdli5AC17SLESB1hMFT5v8K2/ihOJsWQZYcRicgW/uMX1Sirdke3Q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" alt="內壢高中-交通"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG4AAAB9CAMAAAB0x6b8AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMAUExURf///wAAABAQEN7m5t7e1tbWzggACCEhIWNrY/fv7+bvShAZvXNzc7W1tbUQGXt7jJQQ3pQQWpQQnJQQGTFCOjEpMVJSWmNaYxBKvcW9teaUzs6UnBAZ7xAZlOZrzs5rnBAZa5ytrXOUYzEZveYQ7+YQa+YQreYQKbW95nMZvbW9GQhKEFKUY+YQzuYQSuYQjOYQCJS95lIZvZS9GVpKGVoZEJS9hNa9GVJKSpSUjHPv3nPvWjHv3jHvWjHvnDHvGXPvnHPvGaWU77VC3rVCWrVCnLVCGbWUWrWUGXMZlDHF3jHFWjHFnDHFGaVr73PF3nPFWnPFnHPFGbVrWrVrGXMZa2N77xCcWhCc3mN7MRCcGRCcnGOczjF7WjF73mOcEDF7GTF7nFLv3lLvWlLvnFLvGZRC3pRCWpRCnJRCGZSUWpSUGVIZlFLF3lLFWlLFnFLFGZRrWpRrGVIZa9697++9rRBKlBBK7xBKazEQQqWcpc7FzkI6Qu/mrUJKQjFKvXNKvbW9Su+9ezEIEM7mrVJKvZS9Ss69e5xrjDFKCNa9SuaU7+aUawhKQuZC7+ZCa3NSjO+UnOZCreZCKeaUKbXv5nOcnDEZ7zEZlHMZ77XvGeZr7+9rnDEZa+Zra+ZrKVpznOaUSuZCzuZCSlJSjOZCjOZCCOaUCJTv5lKcnFIZ75TvGeZrSuZrCJTmtVoZQpTvhNbvGZSUnO/v72Oc7zGcWjGc3mOcMTGcGTGcnIyMjHtKGXsZELW9hPe9GXNK73NKQjFKlDFK77XvSu/vezFKa1JK75TvSs7vewgZOpxrrTFKKfe9SrXmtXsZQrXvhPfvGRDv7xDvaxDvrRDvKbWUzhDF7xDFaxDFrRDFKbVrznN7zhB7axB773N7EBB7KRB7rRDvzhDvShDvjBDvCJSUzhDFzhDFShDFjBDFCJRrzlJ7zhB7ShB7zlJ7EBB7CBB7jPfF5rUQ77UQa7UQrbUQzrUQSrUQjDopEAgAMXtzWvf/3hAAAAAIAPf//wAAAKJ80ykAAAEAdFJOU////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wBT9wclAAAACXBIWXMAAA7EAAAOxAGVKw4bAAANQUlEQVRoQ+1awXKjuhI1MrE0VVDZWLhY8hf2f72VcbGZP4Idf/ZcceqdI7qTjgKZzL2ze3PsBBCSWqe71Wphdn/x5zH5GOP63yxVBL3c6HAeYjxOSzExfm6sf6VUEZTFJoJUEfRSzFFEHE1H1XJnDb1UEXwhrpIqAituKAr3PXEr7B7e6eftBMXKbprKaT/t2+W2G1EEcQd2VOIWDhQn7fjRkweKV9g10yd0KFZ2x4MjBrnFIrLbgzCKOSiK6+S2QYPiJ1Z/xx5Fg5wbtChWdjQUEOWSEGVyrGdczjjSgzIEFGfiEjs5N6hRrOxWxKGIyqQ52Zjs6uWOBdmtKJPs9n379sElxSm7EG/HeIy83OM2bVbFeIMydawUd93tXp/eO2GtL9hVxUNQeJjHshPQaCOK6SoCyw7KnLx2sjQmO4o1oO1U/QseTsQpOwN6kJn9lh1t53EUUBzZrXkmjmwh8K+r7IgLig0725i2y8SteCZb0Hbv7ArLru+uXXL9BVRmqLsOdtt1Xc1GFMcjbJeLO+H4DXa4VHYcoPE5iiNQNDESAEaZsN0bNth9tt0Hdpn6r6nCIk76/kKZv2+7zLloO4KdCDtaQtj9e9txgGGuZrHfU7Vg5hf/AIrZEPdNdrhUdrQ2Qd8wYKAxHiTK/Pe2YwsWZbNVVwQBxa145jfZiTgdIMARWMiKoBB2v2G7J0ww/eBS2bWpJM29smkCv/ic0G9aEVBwQS2dd/UoPYwdWX0x7zIoOwMWGVAcY6b1zAxf2C6DsjPQ5EGg652ddxm+sF2GFXZccQ3Sao6jivseO4pzwxDzD4rJbsYpBjgNt2ODZYz9BhxlebXs3lrq5zYwEKyw24A6V4taL8tqzgVIUqOc3QZW2G1A5x3Z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" alt="內壢高中-地圖"></td>
                </tr>
            </table>
        </li>
        <li>營隊關懷員近日內將透過簡訊及電話與您聯繫。<br>
            如有任何問題，也歡迎主動與關懷員聯絡。
        </li>
        <li>
            {{ $applicant->groupRelation->alias }}關懷員 :
            <ol>
                @foreach(\App\Models\User::whereHas('roles', function($q) use ($applicant) {
                    $q->where('group_id', $applicant->group_id);
                })->get() as $user)
                    <li>{{ $user->application_log()->orderByDesc('id')->first()->name }} {{ $user->application_log()->orderByDesc('id')->first()->mobile }}</li>
                @endforeach
            </ol>
        </li>
    </ol>
@endif
<a>敬祝～闔家平安、健康喜樂！</a><br>
<a class="right">2023企業主管生命成長營關懷大組</a><br>
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>
