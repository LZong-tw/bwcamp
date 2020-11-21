<style>
    table, td{
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
</style>
<p>
    <h5>{{ $applicant->name }}({{ $applicant->gender }})</h5>
    報名序號：{{ $applicant->id }} <br>
    @if($applicant->region)
        分區：{{ $applicant->region }} <br>
    @endif
    @if(isset($applicant->group) && isset($applicant->number))
        錄取序號：{{ $applicant->group.$applicant->number }} <br>
    @endif            
    應繳日期(mmdd)：{{ $campFullData->payment_startdate }} <br>
</p>

<table>
    <tr>
        <td rowspan="6">
            親愛的客戶您好，請使用下列繳款方式繳納: <br>
            *上海銀行繳納：請持本繳款單至全省上海商業儲蓄銀行臨櫃繳納，免手續費。<br>
            *ATM 轉帳：選擇「轉帳」或「繳費」→ 輸入上海銀行<br>
            &nbsp;&nbsp;代號011 → 輸入銷帳編號輸入應繳金額，跨行轉帳須支付手續費。 <br>
            *超商繳納：請持本繳款單至7-11、全家、萊爾富、OK 繳費，免付手續費。<br> 
            *臨櫃匯款：收款行 = 上海商業儲蓄銀行南京東路分行，<br>
            &nbsp;&nbsp;銀行代碼 = 0110406，戶名 = 財團法人福智文教基金<br>
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

-------------------------------------------------------------------------------------------
2021教師生命成長營　　錄取繳費通知單
場次：　　　　　　　姓名 : 　　　　　　　錄取編號 : 　　　　　　　組別：
恭喜您錄取「2021教師生命成長營 」！竭誠歡迎您的到來，期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。請於2020年12月14日前完成繳費，逾時將視同放棄錄取資格！
	費用：1200元
	繳費地點：可至超商、上海銀行繳費，或使用ATM轉帳、臨櫃匯款。
	可自行於報名網頁(  網址http://XXXXXXX.xXXXXX   )查詢是否已繳費完畢。
	若繳費後，因故無法參加研習需退費者，請參照報名網申請退費注意事項（https://bwfoce.wixsite.com/bwtcamp/faq），並填寫退費申請單。
	台北場 諮詢窗口：劉慧娟小姐 (02)2545-3788#529 
	桃園場 諮詢窗口：趙小姐 (03)275-6133#1312
	新竹場 諮詢窗口：張小姐 (03)532-5566#246
	台中場 諮詢窗口：蔣小姐  0933-199203
	雲林場 諮詢窗口：吳春桂小姐0921-013450
	嘉義場 諮詢窗口：吳淑貞小姐0928-780108
	台南場 諮詢窗口：簡小姐0919-852066
	高雄場 諮詢窗口：胡小姐 (07)9769341#417 

　　　　　　　　　　　　　　　　　　　　　　　　　財團法人福智文教基金會　謹此
　　　　　　　　　　　　　　　　　　　　2020年 　  月 　    日
