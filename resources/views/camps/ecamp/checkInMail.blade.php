{{-- <style>
    u{
        color: red;
    }
</style> --}}
<body style="font-size:16px;">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
        <tr>
            <td><img width="100%" src="{{ $message->embed(public_path() . '/img/ecamp2025/mail_header.png') }}" /></td>
        </tr>
        <tr>
            <td>
                <table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td>
                            <h2 align="center">{{ $applicant->batch->camp->fullName }}</h2>
                            <h2 align="center">報&nbsp;到&nbsp;通&nbsp;知&nbsp;單</h2>
                            <table width="100%" style="table-layout:fixed; border: 0;">
                                <tr>
                                    <td>姓名：{{ $applicant->name }}</td>
                                    <td>序號：{{ $applicant->id }}</td>
                                    <td>組別：{{ $applicant->group }}</td>
                                    <td>場次：{{ $applicant->batch->name }}場&nbsp;({{ $applicant->batch->locationName }})</td>
                                </tr>
                            </table>
                            <table width="100%" style="table-layout:fixed; border: 0; word-wrap: break-word;">
                                <tr>
                                    <td>
                                @if($applicant->batch->name == "南區")
                                        親愛的企業主管您好 : <br><br>
                                        &emsp;&emsp;歡迎您參加「{{ $applicant->batch->camp->fullName }}」，我們誠摯期待您的到來，希望您能獲得豐盛的收穫。〈<u>請於報到時攜帶電子郵件中，附件內含之QR&nbsp;Code報到。</u>〉<br><br>
                                        &emsp;&emsp;為使這三天研習進行順利，請詳閱下列須知。 <br><br>
                                        一、研習日期：{{ $applicant->batch->batch_start }}&nbsp;({{ $applicant->batch_start_Weekday }})&nbsp;至&nbsp;{{ $applicant->batch->batch_end }}&nbsp;({{ $applicant->batch_end_Weekday }})。<br>
                                        二、報到時間：{{ $applicant->batch->batch_start }}&nbsp;({{ $applicant->batch_start_Weekday }})&nbsp;<a style="color: red;">09:30~10:30。</a><br>
                                        三、報到地點：{{ $applicant->batch->locationName }}&nbsp;學生餐廳及宿舍門前〈{{ $applicant->batch->location }}〉。<br>
                                        四、交通方式：<br>
                                        <ol>
                                        <li>本基金會將於&nbsp;{{ $applicant->batch->batch_start }}&nbsp;上午&nbsp;09:10~10:10&nbsp;在屏東火車站出口處大廳提供交通接駁服務。現場有穿黃色背心義工協助引導。逾&nbsp;10:10&nbsp;抵達者請自行搭計程車前往屏東大學民生校區〈不是屏商校區〉。</li>
                                        <li>自行前往者請導航：&nbsp;{{ $applicant->batch->locationName }}〈{{ $applicant->batch->location }}〉。</li>
                                        <li>開車自往者請將電子版停車證印出放在副駕駛座前擋風玻璃下方，以利進入校園。</li>
                                        </ol><br>
                                        以下謹列出參加此次活動建議攜帶物品明細，方便您準備行李：<br>
                                        <ol>
                                            <li>多套換洗衣物(洗衣不方便)、衣物袋(裝使用過之衣物)。</li>
                                            <li>毛巾、牙膏、牙刷、香皂、洗髮精、拖鞋、衣架、輕薄外套(防上課地點冷氣過冷)。</li>
                                            <li>隨身背包(教材約A4大小)、文具用品、環保水杯(壺)、摺疊傘、遮陽帽。</li>
                                            <li>刮鬍刀、指甲刀、耳塞(以防他人打鼾)、眼罩、口罩、手帕。</li>
                                            <li>身份證、健保卡(生病時就醫用)。</li>
                                            <li>個人常用藥物。</li>
                                            <li>課程內容有健身操教學實作，請穿著(攜帶)輕便運動服裝、運動鞋。</li>
                                            <li>本營隊會提供包含枕頭x1、涼被x2、舒美墊x3(60cm x 60cm)等寢具。</li>
                                            <li>本營隊使用的寢室皆為硬板床，可依自己的需求，自行攜帶軟墊。</li>
                                            <li>每樓層服務台備有2-3支吹風機，需要時可以直接借用，但因數量有限，請勿帶進寢室使用，吹風機亦可自備。</li>
                                        </ol><br>
                                        注意事項：<br>
                                        <ol>
                                            <li>如有發燒及呼吸道症狀(額溫>=37.5度C)，為維護個人及他人的健康安全，敬請勿參加營隊活動。</li>
                                            <li>若您目前身體狀況為懷孕中，因本營隊一天長達十多小時上課，課程緊湊，且需住宿較為不便，請務必依照您的身體狀況慎重考量。</li>
                                            <li>本次營隊報名踴躍，因場地容納有限，若您無法全程參加，請告知關懷員，感恩您的善行。謝謝！</li>
                                            <li>研習期間晚上也安排各項學習課程，為達成研習效果，請勿外出及外宿。</li>
                                            <li>會場停車位有限，響應節能減碳，懇請多利用公共交通工具及本會提供的接駁服務。</li>
                                            <li>{{ $applicant->batch->batch_end }}&nbsp;課程預定於&nbsp;17:30&nbsp;結束，如需訂購回程車票，請考慮&nbsp;18:30&nbsp;以後之班次。</li>
                                        </ol><br>
                                        若有任何問題，歡迎與關懷員聯絡，或來電本會企業課&nbsp;07-2819498&nbsp;企業營報名報到組。<br>
                                @elseif($applicant->batch->name == "中區")
                                        親愛的企業主管您好:：<br><br>
                                        &emsp;&emsp;歡迎您參加「{{ $applicant->batch->camp->fullName }}」，我們誠摯期待您的到來，希望您能獲得豐盛的收穫。〈<u>請於報到時攜帶電子郵件中，附件內含之QR&nbsp;Code報到。</u>〉<br><br>
                                        &emsp;&emsp;為使這三天研習進行順利，請詳閱下列須知。 <br><br>
                                        一、研習日期：{{ $applicant->batch->batch_start }}&nbsp;({{ $applicant->batch_start_Weekday }})&nbsp;至&nbsp;{{ $applicant->batch->batch_end }}&nbsp;({{ $applicant->batch_end_Weekday }})<br>
                                        二、報到時間：{{ $applicant->batch->batch_start }}&nbsp;({{ $applicant->batch_start_Weekday }})&nbsp;<a style="color: red;">09:30~10:30</a><br>
                                        三、報到地點：{{ $applicant->batch->locationName }}&nbsp;養浩學舍&nbsp;({{ $applicant->batch->location }})<br>
                                        四、交通：<br>
                                        本基金會將於&nbsp;{{ $applicant->batch->batch_start }}&nbsp;上午&nbsp;09:00~10:00在以下地點提供交通接駁服務，現場安排穿黃色背心義工協助引導(逾10:00請自行搭計程車前往{{ $applicant->batch->locationName }})。<br>
                                        (1)&nbsp;台中火車站出口處大廳(3號出口)<br>
                                        (2)&nbsp;台中高鐵車站出口處大廳(4A出口)<br>
                                        (3)&nbsp;自行前往者請導航：&nbsp;{{ $applicant->batch->locationName }}&nbsp;{{ $applicant->batch->location }}<br>
                                        (https://maps.app.goo.gl/qjTTxRTdFjSw2ocB7)<br>
                                        <u>因會場停車位有限，懇請多利用公共交通工具及本會提供的接駁服務</u>。<br>
                                        <br>
                                        以下謹列出參加此次活動建議攜帶物品明細，方便您準備行李：<br>
                                        <ol>
                                            <li>多套換洗衣物(洗衣不方便)、衣物袋(裝使用過之衣物)。</li>
                                            <li>毛巾、牙膏、牙刷、香皂、洗髮精、拖鞋、衣架、輕薄外套(防上課地點冷氣過冷)。</li>
                                            <li>隨身背包(教材約A4大小)、文具用品、環保水杯(壺)、摺疊傘、遮陽帽。</li>
                                            <li>刮鬍刀、指甲刀、耳塞(以防他人打鼾)、眼罩、口罩、手帕。</li>
                                            <li>身份證、健保卡(生病時就醫用)。</li>
                                            <li>個人常用藥物。</li>
                                            <li>本營隊會提供包含枕頭x1、涼被x2等寢具。<br>
                                                本營隊使用的寢室皆為硬板床，可依自己的需求，自行攜帶軟墊。<br>
                                                另外每樓層服務台備有2-3支吹風機，需要時可以直接借用，但因數量有限，請勿帶進寢室使用，吹風機亦可自備。</li>
                                        </ol>
                                        注意事項： <br>
                                        <ol>
                                            <li>如有發燒及呼吸道症狀(額溫>=38度C)，為維護個人及他人的健康安全，敬請勿參加營隊活動。</li>
                                            <li><a style="color: red;">校內園全面禁煙</a>，包括所有場所、大禮堂教室及宿舍等等。</li>
                                            <li>若您目前懷孕中，因本營隊使用的寢室皆為硬板床、一天十多小時上課及天氣炎熱且需多次出入冷氣房等各項因素，請務必依照您的身體狀況慎重考量。</li>
                                            <li>本次營隊報名踴躍，因場地容納有限，若您無法全程參加，請告知關懷員，感恩您的善行。謝謝！</li>
                                            <li>研習期間晚上也安排各項學習課程，為達成研習效果，請勿外出及外宿。</li>
                                            <li>會場停車位有限，響應節能減碳，懇請多利用本會提供之交通接駁服務。</li>
                                            <li><a style="color: red;">{{ $applicant->batch->batch_end }}&nbsp;課程預定於&nbsp;16:40&nbsp;結束，如需訂購回程車票，請考慮&nbsp;19:00&nbsp;以後之班次。</a></li>
                                        </ol>
                                        <br>
                                        <br>
                                        若有任何問題，歡迎與關懷員聯絡，或來電本會<b>04-3706-9300&nbsp;(分機621201)</b>企業營報名報到組。
                                @elseif($applicant->batch->name == "北區")
                                        親愛的企業主管您好 : <br><br>
                                        &emsp;&emsp;歡迎您參加「{{ $applicant->batch->camp->fullName }}」，我們誠摯期待您的到來，希望您能獲得豐盛的收穫。〈<u>請於報到時攜帶電子郵件中，附件內含之QR&nbsp;Code報到。</u>〉<br><br>
                                        &emsp;&emsp;為使這三天研習進行順利，請詳閱下列須知。 <br><br>
                                        一、研習日期：{{ $applicant->batch->batch_start }}&nbsp;({{ $applicant->batch_start_Weekday }})&nbsp;至&nbsp;{{ $applicant->batch->batch_end }}&nbsp;({{ $applicant->batch_end_Weekday }})。<br>
                                        二、報到時間：{{ $applicant->batch->batch_start }}&nbsp;({{ $applicant->batch_start_Weekday }})&nbsp;<a style="color: red;">10:00~10:40。</a><br>
                                        三、報到地點：福智文教基金會{{ $applicant->batch->locationName }}<br>
                                        {{ $applicant->batch->location }}<br>
                                        (https://maps.app.goo.gl/12WLJ2MdiHmJp37T6)<br>
                                        四、交通：<br>
                                        因會場位於市區內交通便捷，<u>請搭乘台北捷運<a style="color: green;">綠線</a></u>至：<br>
                                        (1)&nbsp;<a style="color: green;">小巨蛋站</a>：4號出口直行至光復北路右轉即可到達&nbsp;(步行約8分鐘)<br>
                                        (2)&nbsp;<a style="color: green;">南京三民站</a>：2號出口直行過光復北路左轉即可到達&nbsp;(步行約8分鐘)<br>
                                        或<u>搭乘公車</u>至附近站牌:<br>
                                        <a style="color: brown;">博仁醫院站、榮民服務處、光復路口站、南京寧安站、南京三民站</a><br>
                                        <br>
                                        以下謹列出參加此次活動建議攜帶之物品明細，請參考：<br>
                                        <ol>
                                            <li>輕薄外套(防上課地點冷氣過冷)</li>
                                            <li>隨身背包(教材約A4大小)、文具用品、環保水杯(壺)</li>
                                            <li>身份證、健保卡(生病時就醫用)</li>
                                        </ol>
                                        <br>
                                        <br>
                                        若有任何問題，歡迎與關懷員們聯絡，或來電本會&nbsp;(02)7751-6799&nbsp;分機&nbsp;611204&nbsp;及分機&nbsp;611201&nbsp;企業營報名報到組。                                        
                                @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        <br><br>
                                        主辦單位：財團法人福智文教基金會　敬啟<br>
                                        {{ \Carbon\Carbon::now()->year }}&nbsp;年&nbsp;{{ \Carbon\Carbon::now()->month }}&nbsp;月&nbsp;{{ \Carbon\Carbon::now()->day }}&nbsp;日
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td><br><br><img width="100%" height="20%" src="{{ $message->embed(public_path() . '/img/ecamp2025/mail_footer.png') }}" /></td>
        </tr>
    </table>
</body>
