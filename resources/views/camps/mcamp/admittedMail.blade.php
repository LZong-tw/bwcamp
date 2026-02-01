<style>
    u{
        color: red;
    }
    .center{
        text-align: center;
    }

    .right{
        text-align: right;
    }
</style>
<body style="font-size:16px;">
    <h2 class="center">{{ $applicant->batch->camp->fullName }}&emsp;錄取通知單</h2>
    <table width="100%" style="table-layout:fixed; border: 0;">
        <tr>
            <td>
            姓名：{{ $applicant->name }}<br>
            錄取編號：<u>{{ $applicant->group }}{{ $applicant->number }}</u><br>
            組別：<u>{{ $applicant->group }}</u>
            </td>
        </tr>
    </table>
    @if($applicant->groupRelation?->alias == "第5組" || $applicant->groupRelation?->alias == "第6組")
    <br>
    親愛的福智學員您好：<br>
    <br>
    &emsp;&emsp;感謝您報名「{{ $applicant->batch->camp->fullName }}」，誠摯的歡迎您來共享這場心靈饗宴，串習翻轉人生的智慧法門，開創無限美好的幸福。<br><br>
    &emsp;&emsp;為使研習進行順利，請詳閱下列須知：<br>
    <ol>
        <li>上課時間：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }})</li>
        <li>報到時間：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }}) <span style="color: red">08:30 ~ 09:00</span></li>
        <li>舉辦地點：{{ $applicant->batch->vbatch->locationName }}<br>
        ({{ $applicant->batch->vbatch->location }})</li>
        <li>交通：會場位於市區內，交通便捷，您可以<br>
        (1) 搭乘台北捷運<span style="color: green">綠線</span>至「<span style="color: green">小巨蛋站</span>」，由5號出口往南京東路5段方向直行即可到達(步行約5分鐘)，或<br>
        (2) 搭乘公車至附近站牌「<span style="color: red">南京寧安街口</span>」(步行約2分鐘)、或「<span style="color: red">南京三民路口站</span>」(步行約5分鐘)</li>
        <li>接下來，為您安排的福智義工將透過簡訊或電話與您聯繫，請留意訊息及來電，如有任何問題，也歡迎主動與福智義工聯絡。<br>
        義工窗口：
        <ul>
            @foreach ($carers_unified as $carer)
                <li>{{ $carer->name }} {{ $carer->mobile }}</li>
            @endforeach
        </ul>

    </ol>
    @else
    <br>
    親愛的醫事人員您好：<br>
    <br>
    &emsp;&emsp;感謝您報名「{{ $applicant->batch->camp->fullName }}」，誠摯的歡迎您來共享這場心靈饗宴，一起翻轉人生，開創無限美好的幸福。<br>
    &emsp;&emsp;為使研習進行順利，請詳閱下列須知：<br>
    <ol>
        <li>上課時間：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }})</li>
        <li>報到時間：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }}) <span style="color: red">08:30 ~ 09:00</span></li>
        <li>舉辦地點：{{ $applicant->batch->locationName }}<br>
        ({{ $applicant->batch->location }})</li>
        <li>交通：會場位於市區內，交通便捷，您可以<br>
        (1) 搭乘台北捷運<span style="color: green">綠線</span>至「<span style="color: green">小巨蛋站</span>」，由5號出口往南京東路5段方向直行即可到達(步行約5分鐘)，或<br>
        (2) 搭乘公車至附近站牌「<span style="color: red">南京寧安街口</span>」(步行約2分鐘)、或「<span style="color: red">南京三民路口站</span>」(步行約5分鐘)</li>
        <li>
        <strong>接下來為您安排的關懷員將陸續透過簡訊或電話與您聯繫，<u>請留意陌生訊息及來電</u>，如有任何問題，也歡迎主動與關懷員聯絡。</strong> <br>
        {{ $applicant->groupRelation?->alias }}關懷員 :
        <ul>
            @foreach ($carers as $carer)
                <li>{{ $carer->name }} {{ $carer->mobile }}</li>
            @endforeach
        </ul>
        </li>
    </ol>
    @endif
    <br>
    <a class="right">主辦單位：財團法人福智文教基金會&emsp;敬啟</a><br>
    <a class="right">{{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}</a>
</body>