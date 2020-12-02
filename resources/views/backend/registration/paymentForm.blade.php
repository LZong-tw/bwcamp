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
    <li>活動費用：{{ $campFullData->fee }}元</li>
    <li>繳費地點：可至超商、上海銀行繳費，或使用ATM轉帳、臨櫃匯款。</li>
    <li>若完成繳費，請於至少一個工作天後，上網查詢是否已繳費完畢。<br>
        （{{ url('camp/' . $applicant->batch_id . '/queryadmit') }}）</li>
    <li>發票於營隊第一天提供，<strong>若需開立統一編號，請於 1/22 前填寫<a href="https://docs.google.com/forms/d/e/1FAIpQLSeVcqd01trNPKMSvc-RH8Zhac5Gexn-fBaAfAWMCn323PVgFw/viewform">申請單</a></strong>。</li>
    <li>若繳費後，因故無法參加研習需退費者，請參照<a href="https://bwfoce.wixsite.com/bwtcamp/faq">報名網申請退費注意事項</a>，並填寫<strong>退費申請單</strong>。</li>
    <li>各區諮詢窗口<strong>（請於周一至周五 10:00~17:30 來電）</strong>：
        <table width="100%" style="table-layout:fixed; border: 0;">
            <tr>
                <td>台北場　劉小姐 (02)2545-3788#529</td>
                <td>雲林場　吳小姐0921-013450</td>
            </tr>
            <tr>
                <td>桃園場　趙小姐  (03)275-6133#1312</td>
                <td>嘉義場　吳小姐0928-780108</td>
            </tr>
            <tr>
                <td>新竹場　張小姐 (03)532-5566#246</td>
                <td>台南場　簡小姐0919-852066</td>
            </tr>
            <tr>
                <td>台中場　蔣小姐  0933-199203</td>
                <td>高雄場　胡小姐(07)9769341#417</td>
            </tr>
        </table>	
	</li>
</ul>
<a class="right">財團法人福智文教基金會　謹此</a><br> 
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>
