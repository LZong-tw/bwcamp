{{-- <style>
    u{
        color: red;
    }
</style> --}}
<body style="font-size:16px;">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
        <tr>
            <td><img width="100%" src="{{ $message->embed(public_path() . '/img/ecamp2024/head.png') }}" /></td>
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
                                    <td>場次：{{ $applicant->batch->name }}</td>
                                </tr>
                            </table>
                            <table width="100%" style="table-layout:fixed; border: 0; word-wrap: break-word;">
                                <tr>
                                    <td>
                                        @if($applicant->batch->name == "第一梯次-北區場")
                                        親愛的企業主管您好 : <br>
                                        歡迎您參加「2024企業主管生命成長營」，我們誠摯期待您的到來，希望您能獲得豐盛的收穫。為使這三天研習進行順利，請詳閱下列須知。 <br>
                                        <u>並請於報到時攜帶電子郵件中，附件內含之QR&nbsp;Code報到。</u><br>
                                        <br>
                                        研習日期：2024年7月12日(星期五)至7月14日(星期日)<br>
                                        報到時間：2024年7月12日(星期五)&nbsp;09:30~10:30<br>
                                        報到地點：開南大學&nbsp;&nbsp;(桃園市蘆竹區開南路一號) <br>
                                        交通接駁：<br>
                                        本基金會於7/12&nbsp;上午09:00~10:10在以下地點提供交通接駁服務，現場將有穿黃色背心的義工協助引導(逾10:10請自行搭計程車前往開南大學)。<br>
                                        (1)&nbsp;桃園火車站後站出口處 <br>
                                        (2)&nbsp;桃園高鐵車站5號出口處 <br>
                                        自行前往者請導航:&nbsp;開南大學&nbsp;桃園市蘆竹區開南路一號。<br>
                                        　　　　　　　　　(https://maps.app.goo.gl/ZMi9usQo7SPTH7HV6) <br>
                                        <br>
                                        以下謹列出參加此次活動所需攜帶物品，方便您準備行李。<br>
                                        建議攜帶物品：<br>
                                        <ol>
                                            <li>多套換洗衣物(洗衣不方便)、衣物袋(裝使用過之衣物)</li>
                                            <li>毛巾、牙膏、牙刷、香皂、洗髮精、拖鞋、衣架、輕薄外套(防上課地點冷氣過冷)</li>
                                            <li>隨身背包(教材約A4大小)、文具用品、環保水杯(壺)、摺疊傘、遮陽帽</li>
                                            <li>刮鬍刀、指甲刀、耳塞(以防他人打鼾)、眼罩、口罩、手帕</li>
                                            <li>身份證、健保卡(生病時就醫用)</li>
                                            <li>個人常用藥物</li>
                                            <li>課程內容有健身操教學實作，請穿著（攜帶）輕便運動服裝、運動鞋。</li>
                                            <li>本營隊會提供包含枕頭x1、涼被x2、巧拼墊（60cm × 60cm x 1cm）3片等寢具。<br>
                                                另外每樓層服務台備有2-3隻吹風機，需要時可以直接借用，但因數量有限，請勿帶進寢室使用，吹風機亦可自備。</li>
                                        </ol>
                                        注意事項： <br>
                                        <ol>
                                            <li>如您目前懷孕中，考量本營隊因為休息皆為硬板床、一天十多小時上課及天氣炎熱且需多次出入冷氣房等各項因素，請務必依照您的身體狀況慎重考量。</li>
                                            <li>本次營隊報名踴躍，因場地容納有限，若您無法全程參加，請告知關懷員，感恩您的善行。謝謝！</li>
                                            <li>研習期間晚上也安排各項學習課程，為達成研習效果，請勿外出及外宿。</li>
                                            <li>會場停車位有限，響應節能減碳，懇請多利用本會提供之交通接駁服務。</li>
                                            <li>7/14課程預定於17:40結束，如需訂購回程車票，請考慮19:00以後之班次。</li>
                                        </ol>
                                        <br>
                                        <br>
                                        若有任何問題，歡迎與關懷員聯絡，或來電本會02-2545-3788 (分機545、517)企業營報名報到組。

                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        主辦單位：財團法人福智文教基金會　敬邀<br>
                                        {{ \Carbon\Carbon::now()->year }} 年　{{ \Carbon\Carbon::now()->month }} 月 　 {{ \Carbon\Carbon::now()->day }} 日
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td><br><br><img width="100%" height="20%" src="{{ $message->embed(public_path() . '/img/ecamp2024/footer.png') }}" /></td>
        </tr>
    </table>
</body>
