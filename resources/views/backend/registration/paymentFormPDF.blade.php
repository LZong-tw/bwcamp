<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>paymentForm</title>
    <style>
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
        html,body{
            font-size: 14px;
            font-family: "kaiu";
            word-wrap: break-word;
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
@if(!$download)
    <a href="{{ route('showPaymentForm', [$applicant->batch_id, $applicant->id]) }}?download=1" target="blank">下載繳費單</a>
@endif
財團法人福智文教基金會
<p style="float:right; clear: both; margin-top:180px; margin-right: -6px">代</p>
<p style="float:right; clear: both; margin-top:210px; margin-right: -6px">收</p>
<p style="float:right; clear: both; margin-top:240px; margin-right: -6px">行</p>
<p style="float:right; clear: both; margin-top:270px; margin-right: -6px">存</p>
<p style="float:right; clear: both; margin-top:300px; margin-right: -6px">查</p>
<p style="float:right; clear: both; margin-top:330px; margin-right: -6px">聯</p>
<table class="table">
    <tr>
        <td>
            親愛的客戶您好，請使用下列繳款方式繳納: <br>
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
                        繳費期限：{{ \Carbon\Carbon::now()->year }}/{{ substr($campFullData->payment_deadline, 2, 2) }}/{{ substr($campFullData->payment_deadline, 4, 2) }}<br>
                        應繳金額：{{ $campFullData->fee }}
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
<h2 class="center">2021教師生命成長營 錄取繳費通知單</h2>
<table style="width: 100%; table-layout:fixed; border: 0;">
    <tr>
        <td>場次：{{ $applicant->bName }}</td>
        <td>姓名：{{ $applicant->name }}</td>
        <td>錄取編號：{{ $applicant->group }}{{ $applicant->number }}</td>
        <td>組別：{{ $applicant->group }}</td>
    </tr>
</table>
恭喜您錄取「{{ $applicant->fullName }}」！竭誠歡迎您的到來，期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。請於{{ \Carbon\Carbon::now()->year }}年{{ substr($campFullData->payment_deadline, 2, 2) }}月{{ substr($campFullData->payment_deadline, 4, 2) }}日前完成繳費，<u>逾時將視同放棄錄取資格！</u>
<ul>
    <li>費用：1200元</li>
    <li>繳費地點：可至超商、上海銀行繳費，或使用ATM轉帳、臨櫃匯款。</li>
    <li>可自行於報名網頁( 網址http://{{ url('camp/' . $applicant->batch_id) }} )查詢是否已繳費完畢。</li>
    <li>若繳費後，因故無法參加研習需退費者，請參照報名網申請退費注意事項（https://bwfoce.wixsite.com/bwtcamp/faq），並填寫退費申請單。</li>
    <li>台北場 諮詢窗口：劉慧娟小姐 (02)2545-3788#529</li>
    <li>桃園場 諮詢窗口：趙小姐 (03)275-6133#1312</li>
    <li>新竹場 諮詢窗口：張小姐 (03)532-5566#246</li>
    <li>台中場 諮詢窗口：蔣小姐  0933-199203</li>
    <li>雲林場 諮詢窗口：吳春桂小姐0921-013450</li>
    <li>嘉義場 諮詢窗口：吳淑貞小姐0928-780108</li>
    <li>台南場 諮詢窗口：簡小姐0919-852066</li>
    <li>高雄場 諮詢窗口：胡小姐 (07)9769341#417 </li>
</ul>



<a class="right">財團法人福智文教基金會　謹此</a><br>  
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>

</body>
</html>