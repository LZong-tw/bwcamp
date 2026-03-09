{{-- <style>
    u{
        color: red;
    }
</style> --}}

@php
$today = \Carbon\Carbon::now();
$str1 = '/img/'. $applicant->batch->camp->table . $today->year . '/mail_header.png';
$str2 = '/img/'. $applicant->batch->camp->table . $today->year . '/mail_footer.png';
$header_path = $message->embed(public_path() . $str1);
$footer_path = $message->embed(public_path() . $str2);
@endphp
<body style="font-size:16px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
        <tr>
            <td><img width="100%" src="{{ $header_path }}" /></td>
        </tr>
        <!--<h2 align="center">{{ $applicant->batch->camp->fullName }}</h2>
        <h2 align="center">報&nbsp;到&nbsp;通&nbsp;知&nbsp;單</h2>-->
        <tr><td>
            <table width="100%" style="table-layout:fixed; border: 0;">
                <tr>
                    <td>姓名：{{ $applicant->name }}</td>
                    <td>序號：{{ $applicant->id }}</td>
                    <td>組別：{{ $applicant->group }}</td>
                </tr>
            </table>
        </td></tr>
        <tr>
            <td>
            @if($applicant->groupRelation?->category == 2)
                <!-- 廣論學員 -->
                <br>親愛的醫事人員您好：<br><br>
                &emsp;&emsp;歡迎您參加「{{ $applicant->batch->camp->fullName }}」，我們誠摯期待您的到來，希望您能獲得豐盛的收穫。<br><br>
                &emsp;&emsp;為使此次營隊學習進行順利，請詳閱下列須知。 <br>
                <ol>
                    <li>上課時間：{{ $applicant->batch->batch_start }}&nbsp;({{ $applicant->batch->batch_start_weekday }})。</li>
                    <li><span style="color: red;">報到時間</span>：{{ $applicant->batch->batch_start }}&nbsp;({{ $applicant->batch->batch_start_weekday }})&nbsp;<span style="color: red;">08:30~09:00</span></li>
                    <li>舉辦地點：{{ $applicant->batch->vbatch?->locationName?? [] }}&nbsp;<br>
                    &emsp;&emsp;&emsp;&emsp;&emsp;<span style="color: red;">({{ $applicant->batch->vbatch?->location?? [] }})</span></li>
                    <li>交通方式：因會場位於市區內交通便捷，請
                    <ul>
                        <li>搭乘台北捷運<span style="color: green">綠線</span>至<span style="color: green">小巨蛋站</span>，5號出口往南京東路5段方向直行即可到達(步行約5分鐘)</li>
                        <li>搭乘公車至附近站牌：<br>
                            <span style="color: brown">南京寧安街口</span>」(步行約2分鐘)、或<br>
                            <span style="color: brown">南京三民路口站</span>」(步行約5分鐘)
                        </li>
                    </ul>
                </ol>
            @else
                <!-- 非廣論學員 -->
                <br>親愛的醫事人員您好：<br><br>
                &emsp;&emsp;歡迎您參加「{{ $applicant->batch->camp->fullName }}」，我們誠摯期待您的到來，希望您能獲得豐盛的收穫。<u>請於報到時攜帶電子郵件中，附件內含之QR&nbsp;Code報到。</u><br><br>
                &emsp;&emsp;為使此次營隊學習進行順利，請詳閱下列須知：<br>
                <ol>
                    <li>上課時間：{{ $applicant->batch->batch_start }}&nbsp;({{ $applicant->batch->batch_start_weekday }})。</li>
                    <li><span style="color: red;">報到時間</span>：{{ $applicant->batch->batch_start }}&nbsp;({{ $applicant->batch->batch_start_weekday }})&nbsp;<span style="color: red;">08:30~09:00</span></li>
                    <li>舉辦地點：{{ $applicant->batch->locationName }}&nbsp;<br>
                    &emsp;&emsp;&emsp;&emsp;&emsp;<span style="color: red;">({{ $applicant->batch->location }})</span></li>
                    <li>交通方式：因會場位於市區內交通便捷，請
                        <ul>
                            <li>搭乘捷運<span style="color: green">「松山新店線」綠線往松山方向，</span>至<span style="color: green">「台北小巨蛋站」</span>下：
                                <ul>
                                    <li>3號出口（設有電梯，需過馬路）。出站後，請直接跨越南京東路（對面是麥當勞），接著沿著南京東路四段直行</li>
                                    <li>5號出口（無電梯，不需過馬路）。出站後，沿著南京東路四段直行</li>
                                    <li>＊小撇步：兩個出口步行時間皆約5分鐘，行進時右手邊是南京東路，與車流反方向，方向就對囉！</li>
                                </ul>
                            </li>
                            <li>搭乘公車至附近站牌：
                                <ul>   
                                    <li><span style="color: brown">「南京寧安街口」站</span>：下車後，直接跨越南京東路（往里仁方向），過里仁再向前直行約2分鐘</li>                               
                                    <li><span style="color: brown">「南京三民路口」站</span>：下車後，直接跨越南京東路（往玉山銀行方向），過玉山銀行再向前直行約7分鐘</li>
                                </ul>
                            </li>
                        </ul>
                </ol>
            @endif
                <br>以下謹列出參加此次活動建議攜帶物品明細，方便您預作準備：
                <ol>
                    <li>外套或圍巾 (防上課地點冷氣過冷)</li>
                    <li>隨身背包放置教材 (約A4大小)</li>
                    <li>文具用品</li>
                    <li>環保水杯 (壺)</li>
                    <li>口罩 (依個人習慣無規定配戴)</li>
                </ol>
                <br>注意事項：
                <ol>
                    <li>此次營隊報名踴躍，因場地容納有限，若您無法全程參加，請告知關懷員，感恩您的善行。謝謝！</li>
                    <li>會場無提供停車位，響應節能減碳，懇請多利用大眾交通工具。</li>
                    <li><span style="color: red;">課程預定於17:40結束。</span></li>
                </ol>
                <br>若有任何問題，歡迎與關懷員聯絡，或來電本會<span style="color: red;">&nbsp;02-2545-3788&nbsp;(分機545、517)&nbsp;醫事營關服組</span>。<br>
            </td>
        </tr>
        <tr>
            <td align="right">
                <br><br>
                主辦單位：財團法人福智文教基金會　敬啟<br>
                {{ $today->format('Y 年 n 月 j 日') }}
            </td>
        </tr>
        <tr>
            <td><br><br><img width="100%" src="{{ $footer_path }}" /></td>
        </tr>
    </td></tr>
    </table>
</body>