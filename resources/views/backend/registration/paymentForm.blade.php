<style>
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
<a href="{{ route('showPaymentForm', [$applicant->batch_id, $applicant->id]) }}?download=1" target="blank">下載繳費單</a>
財團法人福智文教基金會
<a style="float:right; writing-mode: vertical-lr;text-orientation: mixed; margin-top: 200px">代收行存查聯</a>
<table class="table">
    <tr>
        <td rowspan="6" class="indent">
            親愛的客戶您好，請使用下列繳款方式繳納: <br>
            *上海銀行繳納：請持本繳款單至全省上海商業儲蓄銀行臨櫃繳納，免手續費。<br>
            *ATM 轉帳：選擇「轉帳」或「繳費」→ 輸入上海銀行<br>
            &nbsp;&nbsp;代號011 → 輸入銷帳編號輸入應繳金額，跨行轉帳須支付手續費。 <br>
            *超商繳納：請持本繳款單至7-11、全家、萊爾富、OK 繳費，免付手續費。<br> 
            *臨櫃匯款：收款行 = 上海商業儲蓄銀行南京東路分行，<br>
            &nbsp;&nbsp;銀行代碼 = 0110406，戶名 = 財團法人福智文教基金會<br>
            &nbsp;&nbsp;帳號 = 銷帳編號(14碼)，須自付手續費。
        </td>
    </tr>
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
            <a style="float:left; margin-top: 35px; font-size: 4px">銷帳編號：</a>
            <a style="float:left; margin-left: -60px; font-size: 4px">{!! \DNS1D::getBarcodeSVG($applicant->bank_second_barcode, 'C39', 1, 50) !!} </a>
            <br><br><br>
            <a style="float:left; margin-top: 35px; font-size: 4px">應繳金額：</a>
            <a style="float:left; margin-left: -35px; font-size: 4px">{!! \DNS1D::getBarcodeSVG($applicant->bank_third_barcode, 'C39', 1, 50) !!} </a>
            <a style="float:right; margin-top: 15px; margin-right: 35px;">銀行代號：011</a>
        </td>
    </tr>
</table>
<hr>
<h2 class="center">{{ $applicant->fullName }} 錄取繳費通知單</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>場次：{{ $applicant->bName }}</td>
        <td>姓名：{{ $applicant->name }}</td>
        <td>錄取編號：{{ $applicant->group }}{{ $applicant->number }}</td>
        <td>組別：{{ $applicant->group }}</td>
    </tr>
</table><br>
恭喜您錄取「{{ $applicant->fullName }}」！竭誠歡迎您的到來，期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。請於{{ \Carbon\Carbon::now()->year }}年{{ substr($campFullData->payment_deadline, 2, 2) }}月{{ substr($campFullData->payment_deadline, 4, 2) }}日前完成繳費，<u>逾時將視同放棄錄取資格！</u>
<br>
<ul>
    <li>費用：1200元</li>
    <li>繳費地點：可至超商、上海銀行繳費，或使用ATM轉帳、臨櫃匯款。</li>
    <li>可自行於報名網頁（網址{{ url('camp/' . $applicant->batch_id) }}）查詢是否已繳費完畢。</li>
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
