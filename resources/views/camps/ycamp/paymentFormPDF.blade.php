<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>paymentForm</title>
    <style>
        @font-face {
            font-family: 'msjh';
            font-style: normal;
            src: url('{{ storage_path('fonts/msjh.ttf') }}') format('truetype');
        }
        .table, table.table td{
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
            position:relative;
        }
        .center{
            text-align: center;
        }
        .padding{
            padding-top: 10px;
            padding-left: 10px;
        }
        html, body, h2, h3 {
            {{-- line-height 及 font-size 已達極限 --}}
            line-height: 15px;
            font-size: 14px;
            word-wrap: break-word;
            font-family: "msjh", sans-serif !important;
        }
        .right{
            float: right;
            margin-bottom: 5px;
            clear: both;
        }
        u{
            color: red;
        }
        .small{
            font-size: 12px;
        }
    </style>
</head>
<body>
{{-- 在正式環境用  h 系列標籤，中文字型會壞掉 --}}
<a style="font-family: 'msjh'">財團法人福智文教基金會</a>
<p style="float:right; clear: both; margin-top:180px; margin-right: -6px">代</p>
<p style="float:right; clear: both; margin-top:210px; margin-right: -6px">收</p>
<p style="float:right; clear: both; margin-top:240px; margin-right: -6px">行</p>
<p style="float:right; clear: both; margin-top:270px; margin-right: -6px">存</p>
<p style="float:right; clear: both; margin-top:300px; margin-right: -6px">查</p>
<p style="float:right; clear: both; margin-top:330px; margin-right: -6px">聯</p>
<table class="table">
    <tr>
        <td>
            親愛的同學您好，請使用下列繳款方式繳納: <br>
            ＊上海銀行繳納：請持本繳款單至全台上海商業儲蓄銀行<br>
            &nbsp;&nbsp;臨櫃繳納，免手續費。<br>
            ＊ATM 轉帳：選擇「轉帳」或「繳費」→ 輸入上海銀行<br>
            &nbsp;&nbsp;代號011 → 輸入銷帳編號輸入應繳金額，<br>
            &nbsp;&nbsp;跨行轉帳須支付手續費。 <br>
            ＊超商繳納：請持本繳款單至7-11、全家、萊爾富、OK<br>
            &nbsp;&nbsp;繳費，免付手續費。<br>
            ＊臨櫃匯款：收款行=上海商業儲蓄銀行南京東路分行，<br>
            &nbsp;&nbsp;銀行代碼=0110406、戶名=財團法人福智文教基金會，<br>
            &nbsp;&nbsp;帳號=銷帳編號(14碼)，須自付手續費。
        </td>
        <td style="padding: 0!important" width="200px">
            <table class="table" style="margin: -12px">
                <tr>
                    <td>
                        繳費期限：{{ \Carbon\Carbon::now()->year }}/{{ substr($applicant->batch->camp->payment_deadline, 2, 2) }}/{{ substr($applicant->batch->camp->payment_deadline, 4, 2) }}<br>
                        應繳金額：{{ $applicant->traffic?->fare ?? 0 }}
                    </td>
                </tr>
                <tr>
                    <td class="center">超商條碼區</td>
                </tr>
                <tr>
                    <td class="padding">
                        <img src="data:image/png;base64,{{ \DNS1D::getBarcodePNG($applicant->store_first_barcode, 'C39', 1, 30) }}" alt="barcode"/><br>
                        <a style="padding-top: 5px;" class="small">{{ $applicant->store_first_barcode }}</a><br>
                        <img src="data:image/png;base64,{{ \DNS1D::getBarcodePNG($applicant->store_second_barcode, 'C39', 1, 30) }}" alt="barcode" style="padding-top:8px;"/><br>
                        <a class="small">{{ $applicant->store_second_barcode }}</a><br>
                        <img src="data:image/png;base64,{{ \DNS1D::getBarcodePNG($applicant->store_third_barcode, 'C39', 1, 30) }}" alt="barcode" style="padding-top:8px;"/><br>
                        <a class="small">{{ $applicant->store_third_barcode }}</a>
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
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<hr>
<table style="width: 100%; border: 0;">
    <tr><td></td></tr>
    <tr>
        <td align="center">
	    <a style="font-size: 1.5em;">{{ $applicant->batch->camp->fullName }} 錄取繳費通知單</a>
        <td>
    <tr>
</table>
<table style="width: 100%; table-layout:fixed; border: 0;">
    <tr>
        <td align="center">姓名：{{ $applicant->name }}</td>
        <td align="center">報名序號：{{ $applicant->id }}</td>
        <td align="center">錄取編號：{{ $applicant->group }}{{ $applicant->number }}</td>
    </tr>
</table>
恭喜您錄取「{{ $applicant->batch->camp->fullName }}」！竭誠歡迎您的到來，期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。您選擇交通的方式及費用如下：
<ul>
    <li>去程：{{ $applicant->traffic?->depart_from ?? "未定" }}</li>
    <li>回程：{{ $applicant->traffic?->back_to ?? "未定" }}</li>
    <li>費用：{{ $applicant->traffic?->fare ?? 0 }}元</li>
</ul>
<a style="font-size: 1.17em;">【注意事項】</a>
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
</body>
</html>
