<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
    body { font-family:  '微軟正黑體'; } 
    .table, table.table td{
        border: 1px solid black;
        border-collapse: collapse;
        padding: 10px;
    }
    .center{
        text-align: center;
    }
    .padding{
        padding-top: 10px;
        padding-left: 10px;
    }
    html,body{
        padding:15px;
        height:297mm;
        width:210mm;
    }
    .right{
        float: right;
        margin-right: 15px;
    }
    u{
        color: red;
    }
</style>
<a href="{{ route('showPaymentForm', [$applicant->batch->camp_id, $applicant->id]) }}?download=1" target="_blank">下載繳費單</a>
財團法人福智文教基金會
<a style="float:right; writing-mode: vertical-lr;text-orientation: mixed; margin-top: 200px">代收行存查聯</a>
<table class="table">
    <tr>
        <td rowspan="6" class="indent">
            親愛的同學您好，請使用下列繳款方式繳納: <br>
            ＊上海銀行繳納：請持本繳款單至全台上海商業儲蓄銀行臨櫃繳納，免手續費。<br>
            ＊ATM 轉帳：選擇「轉帳」或「繳費」→ 輸入上海銀行<br>
            &nbsp;&nbsp;代號011 → 輸入銷帳編號輸入應繳金額，跨行轉帳須支付手續費。 <br>
            ＊超商繳納：請持本繳款單至7-11、全家、萊爾富、OK 繳費，免付手續費。<br> 
            ＊臨櫃匯款：收款行 = 上海商業儲蓄銀行南京東路分行，<br>
            &nbsp;&nbsp;銀行代碼 = 0110406，戶名 = 財團法人福智文教基金會<br>
            &nbsp;&nbsp;帳號 = 銷帳編號(14碼)，須自付手續費。
        </td>
    </tr>
    <tr>
        <td>
            繳費期限：{{ \Carbon\Carbon::now()->year }}/{{ substr($applicant->batch->camp->payment_deadline, 2, 2) }}/{{ substr($applicant->batch->camp->payment_deadline, 4, 2) }}<br>
            應繳金額：{{ $applicant->traffic->fare }}
        </td>
    </tr>
    <tr>
        <td class="center">超商條碼區</td>
    </tr>
    <tr>
        <td class="padding">
            {!! \DNS1D::getBarcodeSVG($applicant->store_first_barcode, 'C39', 1, 50) !!} <br><br>
            {!! \DNS1D::getBarcodeSVG($applicant->store_second_barcode, 'C39', 1, 50) !!} <br><br>
            {!! \DNS1D::getBarcodeSVG($applicant->store_third_barcode, 'C39', 1, 50) !!} <br>
        </td>
    </tr>
    <tr>
        <td class="center">銀行條碼區</td>
    </tr>
    <tr>
        <td class="padding">
            <img src="data:image/png;base64,{{ \DNS1D::getBarcodePNG($applicant->bank_second_barcode, 'C39', 1, 30) }}" alt="barcode" style="padding-top: 3px"/><br>
            <a class="small" style="padding-top: 3px"> 銷帳編號：{{ $applicant->bank_second_barcode }}</a>
            <br>                  
            <img src="data:image/png;base64,{{ \DNS1D::getBarcodePNG($applicant->bank_third_barcode, 'C39', 1, 30) }}" alt="barcode" style="padding-top: 12px"/><br>
            <a class="small">應繳金額：{{ $applicant->bank_third_barcode }}</a>
            <a style="float: right; right:10px">銀行代號：011</a>
            <!--
            <a style="float:left; margin-top: 0px; font-size: 4px">{!! \DNS1D::getBarcodeSVG($applicant->bank_second_barcode, 'C39', 1, 50) !!} </a>
            <br><br><br>
            <a style="float:left; margin-top: 0px; font-size: 12px">銷帳編號：</a>
            <br>
            <a style="float:left; margin-left: 60px; font-size: 4px">{!! \DNS1D::getBarcodeSVG($applicant->bank_third_barcode, 'C39', 1, 50) !!} </a>
            <br><br><br>
            <a style="float:left; margin-top: 0px; font-size: 12px">應繳金額：</a>
            <a style="float:right; margin-top: 15px; margin-right: 35px;">銀行代號：011</a>
            -->
        </td>
    </tr>
</table>
<hr>
<h2 class="center">{{ $applicant->batch->camp->fullName }} 錄取繳費通知單</h2>
<table style="width: 100%; table-layout:fixed; border: 0;">
    <tr>
        <td>姓名：{{ $applicant->name }}</td>
        <td>報名序號：{{ $applicant->id }}</td>
        <td>錄取編號：{{ $applicant->group }}{{ $applicant->number }}</td>
    </tr>
</table>
恭喜您錄取「{{ $applicant->batch->camp->fullName }}」！竭誠歡迎您的到來，期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。您選擇交通的方式及費用如下：
<ul>
    <li>去程：{{ $applicant->traffic->depart_from }}</li>
    <li>回程：{{ $applicant->traffic->back_to }}</li>
    <li>費用：{{ $applicant->traffic->fare }}元</li>
</ul>

<h3>【注意事項】</h3>
<ul>
    <li>繳費時間：請於{{ \Carbon\Carbon::now()->year }}年{{ substr($applicant->batch->camp->payment_deadline, 2, 2) }}月{{ substr($applicant->batch->camp->payment_deadline, 4, 2) }}日前完成繳費，<a style="color: red;">逾時將視同放棄搭乘專車！</a>
    <li>繳費方式：請見上面繳費單說明。</li>
    <li>查詢：完成繳費後，請於至少一個工作天後，再上網查詢是否已繳費完畢。<br>
        （<a href="http://bwcamp.bwfoce.org/camp/{{ $applicant->batch->id }}/queryadmit" target="_blank" rel="noopener noreferrer">http://bwcamp.bwfoce.org/camp/{{ $applicant->batch->id }}/queryadmit</a> ）</li>
        {{-- （<a href="{{ url('camp/' . $applicant->batch_id . '/queryadmit') }}" target="_blank">{{ url('camp/' . $applicant->batch_id . '/queryadmit') }}</a>） --}}
    <li>發票：本交通服務為代收代付，故不提供發票，敬請見諒。</li>
    <li>退費：車資繳交後視為已訂位，恕不退費。</li>
</ul>
<a class="right">財團法人福智文教基金會　敬啟</a><br>  
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>
