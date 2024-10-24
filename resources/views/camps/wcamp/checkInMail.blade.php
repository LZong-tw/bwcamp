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
        @if ($applicant->batch->name == "雲嘉")
            @if ($applicant->group == "第一組")
                <td>組別：第十一組</td>
            @else
                <td>組別：第十二組</td>
            @endif
        @else
            <td>組別：{{ $applicant->group }}</td>
        @endif
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
            營隊上課時間：7/14&nbsp;(五)&nbsp;13:50~18:00<br>
            　　　　　　　7/15&nbsp;(六)&nbsp;09:00~18:00<br>
            　　　　　　　7/16&nbsp;(日)&nbsp;09:00~18:10
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
        <li>
            報到時間：7/14 (五) 13:00~13:40<br>
            　　　　　7/15 (六) 08:00~08:50<br>
            　　　　　7/16 (日) 08:00~08:50
        </li>
        <li>
            營隊上課時間：7/14 (五) 13:50~18:00<br>
            　　　　　　　7/15 (六) 09:00~18:00<br>
            　　　　　　　7/16 (日) 09:00~18:10
        </li>
        <li>
            報到地點：桃園市立內壢高中-正門穿廊（桃園市中壢區成章四街120號）
        </li>
        <li>交　　通：<br>
            <table width="100%" style="table-layout:fixed; border: 0;">
                <tr>
                    <td>
                        請參閱以下&nbsp;桃園市立內壢高中&nbsp;-交通說明<br>
                        <a href="https://reurl.cc/OVylVy">https://reurl.cc/OVylVy</a><br>
                        Google&nbsp;MAP&nbsp;:<br>
                        <a href="https://reurl.cc/lvxqgY">https://reurl.cc/lvxqgY</a>
                    </td>
                    <td>
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHMAAACBCAMAAADEx3XGAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMAUExURf///wAAAO/m797e3jExMWNaWpSUlAAICBAQCCkZMVJSUrW1tffv95SElJycpebvSjFKEBAZ5hAZtaWtrc7FzkpKQiEhGZQQ3pQQWpQQnJQQGXt7jHNzcxAQGRBK5hBKtTFKOuaUzs6UnBAZjOZrzs5rnBAZY3OUYzEZ5nMZ5jEZtXMZteYQ7+YQa+YQreYQKbW95rW9GVKUY1IZ5lIZteYQzuYQSuYQjOYQCJS95pS9GZxjnJS9hNa9GWNra3Pv3nPvWjHv3jHvWjHvnDHvGXPvnHPvGaWU77VC3rVCWrVCnLVCGXMZjLWUWrWUGTHF3jHFWjHFnDHFGaVr73PF3nPFWnPFnHPFGXMZY7VrWrVrGWN77xCcWhCc3mN7MRCcGRCcnGOczjF7WjF73mOcEDF7GTF7nFLv3lLvWlLvnFLvGZRC3pRCWpRCnJRCGVIZjJSUWpSUGVLF3lLFWlLFnFLFGVIZY5RrWpRrGcW9td697++9rRBKjBBKY3NK5u/mrWtKOjFK5jFKtXNKtXNKELW9Su+9e1JK5s7mrVJKtVJKEJS9Ss69e9a9SuaU7+aUa3NShOZC7+ZCa++UnOZCreZCKeaUKbXv5nOcnHMZOjEZjLXvGeZr7+9rnDEZY+Zra+ZrKXMZEFpznOaUSlJShOZCzuZCSuZCjOZCCOaUCJTv5lKcnFIZOpTvGeZrSuZrCFIZEJTmtZTvhNbvGWOc7zGcWjGc3mOcMTGcGTGcnLW9hPe9GTFKjLXvSu/vezFKY5TvSs7ve/fF5ve9SrXmtbXvhPfvGdbWzhDv7xDvaxDvrRDvKbWUzhDF7xDFaxDFrRDFKbVrznN7zhB7axB773N7EBB7KRB7rTEIEAgxOggxEBDvzhDvShDvjBDvCJSUzhDFzhDFShDFjBDFCJRrzlJ7zhB7ShB7zlJ7EBB7CBB7jLUQ77UQa7UQrbUQKbUQzrUQSrUQjLUQCAhSOghSEAgQMdbv91pzUntzWggAKffv3vf/3u/39xAAAPf//wAAAKZpgqkAAAEAdFJOU////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wBT9wclAAAACXBIWXMAAA7EAAAOxAGVKw4bAAANyUlEQVRoQ+2bWY6jShqFzWCcBuMXhheD5Le7C9bUr7mF3pi3kMqFVKZSTqvPF47fFcaBq+6VWmq16thZQBBwfOIfYoBa/cH/K9Jl+Bo/EZSfg31gxzH4Kjek79+bzeYl8rfZlL6OoUiup2rtD75Wcz21Kv1x5K8/+zqGy3eyiM7XMRS+fND+p98vrqdWpT+OYDMXmm78mQjmOmtfDs/g901n548jGH2Vnxj1Q/q8f4Aqm840u6Lwpw7an7iZLiu0v1MddC7c5mWu84u6XDRDpXLTudc+oE2B2lW/9KoXLrSis3Jn75CKdJzb86yr3+eFApym0zgnf3zwx3CiF064I5wrcW6+/L4Be25/odN8xzjNlr+j833Bnu9q8EvbnuybqTjUWfU56ImRH6px0DGNC+fac5rOLLjPRZzbiN9iT3SetDUcVR7qDEG7wk2bxnTm2nps9NujOlOvM/MVQa7yUGcIcW4sPmP2PGprOKn53rR9sOdLkrypEUJOuJ7pNK69tta2z3TO29b5LTqD3BDqTF/bCvsa4ERnnR/zum1buEKdHBvglD2j8bnVNrRnqLPxW4PpBGgEv9IZi89RDb5kTzg/XM0r4Dz4fdoXhDoDTnRiz2i+JQ8t2RNOi0vwLx0bp+nErqYz8KFFv7X4fKazq6pGPujQ5F2HrqyqquHYObzq+Ik9H/zW2XPGGeqc5z0DGs2uAK5W21nbRu1JfFL4TCdYu9o/YTnBsGDPs+z54LfOntqGfmucoc7QdwE5d64zwhnVafZMy58g/kxn213LuP+puaFCF2WtDnTvm87hWt3hqT1/0a8YbJxggBOuMD5ncKG4FJ9zmM4QS5zEiumcgT47Pk7w+yFalc91Wj9qgNNyvPntHIt5qNgL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" alt="">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG4AAAB9CAMAAAB0x6b8AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMAUExURf///wAAABAQEN7m5t7e1tbWzggACCEhIWNrY/fv7+bvShAZvXNzc7W1tbUQGXt7jJQQ3pQQWpQQnJQQGTFCOjEpMVJSWmNaYxBKvcW9teaUzs6UnBAZ7xAZlOZrzs5rnBAZa5ytrXOUYzEZveYQ7+YQa+YQreYQKbW95nMZvbW9GQhKEFKUY+YQzuYQSuYQjOYQCJS95lIZvZS9GVpKGVoZEJS9hNa9GVJKSpSUjHPv3nPvWjHv3jHvWjHvnDHvGXPvnHPvGaWU77VC3rVCWrVCnLVCGbWUWrWUGXMZlDHF3jHFWjHFnDHFGaVr73PF3nPFWnPFnHPFGbVrWrVrGXMZa2N77xCcWhCc3mN7MRCcGRCcnGOczjF7WjF73mOcEDF7GTF7nFLv3lLvWlLvnFLvGZRC3pRCWpRCnJRCGZSUWpSUGVIZlFLF3lLFWlLFnFLFGZRrWpRrGVIZa9697++9rRBKlBBK7xBKazEQQqWcpc7FzkI6Qu/mrUJKQjFKvXNKvbW9Su+9ezEIEM7mrVJKvZS9Ss69e5xrjDFKCNa9SuaU7+aUawhKQuZC7+ZCa3NSjO+UnOZCreZCKeaUKbXv5nOcnDEZ7zEZlHMZ77XvGeZr7+9rnDEZa+Zra+ZrKVpznOaUSuZCzuZCSlJSjOZCjOZCCOaUCJTv5lKcnFIZ75TvGeZrSuZrCJTmtVoZQpTvhNbvGZSUnO/v72Oc7zGcWjGc3mOcMTGcGTGcnIyMjHtKGXsZELW9hPe9GXNK73NKQjFKlDFK77XvSu/vezFKa1JK75TvSs7vewgZOpxrrTFKKfe9SrXmtXsZQrXvhPfvGRDv7xDvaxDvrRDvKbWUzhDF7xDFaxDFrRDFKbVrznN7zhB7axB773N7EBB7KRB7rRDvzhDvShDvjBDvCJSUzhDFzhDFShDFjBDFCJRrzlJ7zhB7ShB7zlJ7EBB7CBB7jPfF5rUQ77UQa7UQrbUQzrUQSrUQjDopEAgAMXtzWvf/3hAAAAAIAPf//wAAAKJ80ykAAAEAdFJOU////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wBT9wclAAAACXBIWXMAAA7EAAAOxAGVKw4bAAANNElEQVRoQ+1ay3KruhI1MrY4VVCZWLgY8hf2f92RcTHZfyRm/Nl1bafuWqI76SiQnX3Ont2zcAIISa3V3Wo1j92/+POYfAhh/W+WKoJBLvQ4rkI4TUsxET831r9SqgjKYhOVVBEMUsxRBOxNR/VyZQ2DVBF8Ia6WKgIrbiwK9z1xK+ye3un2doBiZTdN5bSf9t1y2UUUQdyRHZW4hB3FSTtuevBE8Qq7dvqEHsXK7nR0xCiXWER2exBGMQdFcb1cNmhRfGD1d+xRNMqxQYdiZUdDAUFOCVEmx3rB6Yw9PShDheJMXGInxwYNipXdijgUUZk0JxuTXbNcsSC7FWWS3X7o3jacUpyyq8L9FE6Bp3tcps3qEO5Qpo6V4m673evhvRPW+oJdXTwFhYd5LDsBjRZRTFcRWHZQ5uS1k6Ux2VGsAW2n6l/wdCJO2RnQg8zst+xoO4+9gOLIbs0zsWcLgX9dZUdcUWzY2ca0XSZuxTPZgrZ7Z1dYdkN/65PrL6Ayq6bvYbdd3zdsRHHcw3a5uDP232CHU2XHARqfozgCRRMjAWCUCdu9YYPdZ9t9YJep/5YqLOKk7y+U+fu2y5yLtiPYibCjJYTdP7cdB1jN9Sz2O9QLZv7wD6CYDXHfZIdTZUdrE/QNAwYa40GizH9uO7ZgUTZbdUUQUNyKZ36TnYjTAQIcgYWsCAph9xu2O2CC6YZTZdelkjT3yrat+MN2Rr9pRUDBFbV03jVReog9WX0x7zIoOwMWGVAcY6b1zAxf2C6DsjPQ5EGg652ddxm+sF2GFXZccQ3Sao69ivseO4pz4xjyDcVkN+MQA5zG+6nFMsZ+K+xlebXs3lrqdh8ZCFbYbUCdq0Otn8tqzgVIUqOc3QZW2G1A5x3Z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" alt="">
                    </td>
                </tr>
            </table>
        </li>
        <li>若有任何問題，歡迎與關懷員聯絡反應。</li>
    </ol>
    &nbsp;建議攜帶物品：<br>
    　　　 隨身背包、文具用品、環保水杯（壺）、禦寒薄外套<br>
    　　　 身份證、健保卡&nbsp;(遇緊急狀況就醫時使用)<br>
    　　　 個人常用藥物<br>
    &nbsp;<u><strong>敬請您報到時出示本通知書下方&nbsp;QR&nbsp;Code&nbsp;進行報到</strong></u>。
@endif
@if($applicant->batch->name == "新竹")
    <ol>
        <li>
            報到時間：7/14 (五) 13:00~13:40<br>
            　　　　　7/15 (六) 08:00~08:50<br>
            　　　　　7/16 (日) 08:00~08:50
        </li>
        <li>
            營隊上課時間：7/14 (五) 13:50~18:00<br>
            　　　　　　　7/15 (六) 09:00~18:00<br>
            　　　　　　　7/16 (日) 09:00~18:10
        </li>
        <li>
            報到地點：<a style="color: #002060;"><b>工研院&nbsp;中興院區51館（新竹縣竹東鎮中興路四段195號51館）</b></a>
        </li>
        <li>
            交　　通：<a style="color: #002060;"><b>請參閱以下&nbsp;工研院&nbsp;中興院區&nbsp;-交通資訊說明</b></a> <br>
            <a href="https://www.itri.org.tw/ListStyle.aspx?DisplayStyle=20&SiteID=1&MmmID=1036712143533651215&MGID=1036712143620275737">https://www.itri.org.tw/ListStyle.aspx?DisplayStyle=20&SiteID=1&MmmID=1036712143533651215&MGID=1036712143620275737</a><br>
            <br>
            工研院中興院區平面圖及交通停車資訊 <br>
            <a href="https://drive.google.com/file/d/1WzosYMtfnFcuiLPMkiOumRcvwPhVCj_3/view?usp=drive_link">https://drive.google.com/file/d/1WzosYMtfnFcuiLPMkiOumRcvwPhVCj_3/view?usp=drive_link</a><br>
            <br>
            學員三天皆由<b><u>東大門</u></b>進入院區。學員停車場以&nbsp;7P/8P&nbsp;為主，停車後，引導步行至51館(約3分鐘)。若&nbsp;7P/8P&nbsp;停滿則外擴至&nbsp;9P-11P&nbsp;請接受義工引導停車。
            <br>
            請依工研院規定，進場需取得停車證並放於車上，行駛時請依院內速限，並停在停車格上。
        </li>
        <li>若有任何問題，歡迎與關懷員聯絡反應。</li>
    </ol>
    &nbsp;建議攜帶物品：<br>
    　　　 隨身背包、文具用品、環保水杯（壺）、環保筷、禦寒薄外套<br>
    　　　 身份證、健保卡&nbsp;(遇緊急狀況就醫時使用)<br>
    　　　 個人常用藥物<br>
    &nbsp;<u><strong>報到時請攜帶附件之報到用 QR Code 報到單</strong></u>。
@endif
@if($applicant->batch->name == "中區")
    <ol>
        <li>營隊上課時間：7月14日(星期五)至7月16日(星期日)</li>
        <li>報到時間：7月14日&nbsp;(星期五)12:30～13:20</li>
        <li>報到地點：聯合大學&nbsp;二坪山校區（36003苗栗縣苗栗市聯大1號）學生活動中心</li>
        <li>交通接駁：本基金會將於11:30~12:00在【臺中市政府臺灣大道市政大樓廣場】集合，提供交通接駁服務，現場有穿黃色背心義工協助引導&nbsp;(逾12:00請自行前往聯合大學)。</li>
        <li>自行前往交通資訊 : <br>
            請參閱以下&nbsp;聯合大學&nbsp;二坪山校區&nbsp;-交通資訊說明 <br>
            <a href="https://www.nuu.edu.tw/p/412-1000-82.php?Lang=zh-tw">https://www.nuu.edu.tw/p/412-1000-82.php?Lang=zh-tw</a> </li>
        <li>如有任何問題，也歡迎主動與關懷員聯絡，<br>
            或來電本會（04）3706-9300&nbsp;#62-1201企業營報名報到組</li>
    </ol>
    以下謹列出參加此次活動所需攜帶物品，以及本營隊提供之物品，方便您準備行李之依據。<br>
    ※&nbsp;應攜帶物品：<br>
    　⬥&nbsp;多套換洗衣物(洗衣不方便)、塑膠袋(裝使用過之衣物)<br>
    　⬥&nbsp;毛巾、牙膏、牙刷、香皂、洗髮精、拖鞋、衣架。<br>
    　⬥&nbsp;隨身背包(教材約A4大小)、文具用品、環保水杯、環保筷、天氣炎熱請帶帽子或陽傘。<br>
    　⬥&nbsp;刮鬍刀、指甲刀、耳塞(睡覺時易受聲音干擾者) 。<br>
    　⬥&nbsp;身分證、健保卡(生病時就診用)<br>
    　⬥&nbsp;個人常用藥物<br>
    ※&nbsp;本營隊提供寢具(枕頭及薄被)，可不必攜帶，也可自行攜帶。<br>
    ※&nbsp;注意事項：<br>
    　⬥&nbsp;研習期間晚上也安排各項成長活動，為達成研習效果，請盡量不外出及外宿。<br>
    　⬥&nbsp;會場停車位有限，請多利用本會提供之交通接駁服務。<br>
    敬祝～闔家平安、健康喜樂！<br>
    <br>
    &nbsp;<u><strong>報到時請攜帶附件之報到用 QR Code 報到單</strong></u>。
@endif
@if($applicant->batch->name == "雲嘉")
<ol>
    <li><b>報到時間</b>&nbsp;7月14日(五)12:30～13:30。</li>
    <li><b>報到流程</b>
        <ol type="i">
            <li>出示報到單，開車進入校內，女宿[第三停車場]、男宿[第四停車場]。</li>
            <li>先將行李暫放在宿舍一樓。</li>
            <li>至報到區，出示[報到QR Code]或[報名序號]快速完成報到。</li>
            <li>取得房卡等資料，回宿舍，將行李放置於寢室。</li>
            <li>攜帶 研習資料、水瓶、筷子、外套至活動會場。</li>
        </ol>
    </li>
    <li><b>交通參考</b>&nbsp;國立聯合大學&nbsp;二坪山校區<br>
        ⬥&nbsp;自行開車：國道一號苗栗交流道下,往北約6分鐘車程。<br>
        ⬥&nbsp;台鐵苗栗站，依義工指示共乘計程車，自備零錢。<br>
        　去程7/14[自強116班次]嘉義10:26→斗六10:50→苗栗12:21<br>
        　回程7/16[自強139班次]苗栗18:38，或[自強141班次]苗栗19:08<br>
        ⬥&nbsp;高鐵苗栗站，依義工指示轉乘快捷公車，或共乘計程車。<br>
        　去程7/14[高鐵818次]嘉義11:00→苗栗11:56→12:10搭公車(101B往雪霸)<br>
        　回程7/16[高鐵849次]苗栗18:58，或苗栗19:25到台中轉車。</li>
    <li><b>攜帶物品</b><br>
        　※&nbsp;研習：報到通知單、筆、水瓶、筷子。會場開冷氣，帶外套禦寒。<br>
        　※&nbsp;住宿：<br>
        　⬥&nbsp;身分證、健保卡、個人藥物、毛巾牙刷等衛生用品、自備塑膠袋裝換洗衣物。<br>
        　⬥&nbsp;大會提供睡墊.薄被.枕頭。寢室六人房有冷氣。您也可自備睡袋、耳塞等。</li>
    <li><b>注意事項</b><br>
        　⬥&nbsp;研習期間，請勿外出及外宿，配合營隊作息，充分體驗學習活動喔！<br>
        　⬥&nbsp;如有任何問題，歡迎聯絡雲嘉區關懷窗口-陳珮瑩&nbsp;0928-501899<br>
        　　　　　　　　　　　　　　　　　　　第十一組&nbsp;陳玉青&nbsp;0919-166777、李佩玲&nbsp;0932-713138<br>
        　　　　　　　　　　　　　　　　　　　第十二組&nbsp;林永昌&nbsp;0928-927967、蘇桂梅&nbsp;0933-568577</li>
</ol>
@endif
{{--@if($applicant->batch->name == "嘉義")
<ol>
    <li><b>報到時間</b>&nbsp;7月14日(五)12:30～13:30。</li>
    <li><b>報到流程</b>
        <ul type="i">
            <li>出示報到單，開車進入校內，女宿[第三停車場]、男宿[第四停車場]。</li>
            <li>先將行李暫放在宿舍一樓。</li>
            <li>至報到區，出示[報到QR Code]或[報名序號]快速完成報到。</li>
            <li>取得房卡等資料，回宿舍，將行李放置於寢室。</li>
            <li>攜帶 研習資料、水瓶、筷子、外套至活動會場。</li>
        </ul>
    </li>
    <li><b>交通參考</b>&nbsp;國立聯合大學&nbsp;二坪山校區<br>
        ⬥&nbsp;自行開車：國道一號苗栗交流道下,往北約6分鐘車程。<br>
        ⬥&nbsp;台鐵苗栗站，依義工指示共乘計程車，自備零錢。<br>
        　去程7/14[自強116班次]嘉義10:26→斗六10:50→苗栗12:21<br>
        　回程7/16[自強139班次]苗栗18:38，或[自強141班次]苗栗19:08<br>
        ⬥&nbsp;高鐵苗栗站，依義工指示轉乘快捷公車，或共乘計程車。<br>
        　去程7/14[高鐵818次]嘉義11:00→苗栗11:56→12:10搭公車(101B往雪霸)<br>
        　回程7/16[高鐵849次]苗栗18:58，或苗栗19:25到台中轉車。</li>
    <li><b>攜帶物品</b><br>
        　※&nbsp;研習：報到通知單、筆、水瓶、筷子。會場開冷氣，帶外套禦寒。<br>
        　※&nbsp;住宿：<br>
        　⬥&nbsp;身分證、健保卡、個人藥物、毛巾牙刷等衛生用品、自備塑膠袋裝換洗衣物。<br>
        　⬥&nbsp;大會提供睡墊.薄被.枕頭。寢室六人房有冷氣。您也可自備睡袋、耳塞等。</li>
    <li><b>注意事項</b><br>
        　⬥&nbsp;研習期間，請勿外出及外宿，配合營隊作息，充分體驗學習活動喔！<br>
        　⬥&nbsp;如有任何問題，歡迎聯絡雲嘉區關懷窗口-陳珮瑩&nbsp;0928-501899<br>
        　　　　　　　　　　　　　　　　　　　第十一組&nbsp;陳玉青&nbsp;0919-166777、李佩玲&nbsp;0932-713138<br>
        　　　　　　　　　　　　　　　　　　　第十二組&nbsp;林永昌&nbsp;0928-927967、蘇桂梅&nbsp;0933-568577</li>
</ol>
@endif--}}
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
