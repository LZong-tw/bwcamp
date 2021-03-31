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
<a href="{{ route('showPaymentForm', [$applicant->batch_id, $applicant->id]) }}?download=1" target="_blank">下載繳費單</a>
財團法人福智文教基金會 <br>
謙德管理顧問股份有限公司
<a style="float:right; writing-mode: vertical-lr;text-orientation: mixed; margin-top: 125px">代收行存查聯</a>
<table class="table">
    <tr>
        <td rowspan="6" class="indent">
            親愛的客戶您好，請持本繳款單於繳費期限內至 7-11、全家、萊爾富、OK 繳費，免付手續費。
        </td>
    </tr>
    <tr>
        <td>
            繳費期限：{{ \Carbon\Carbon::now()->year }}/{{ substr($applicant->batch->camp->set_payment_deadline, 2, 2) }}/{{ substr($applicant->batch->camp->set_payment_deadline, 4, 2) }}<br>
            應繳金額：{{ $applicant->batch->camp->set_fee }}
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
    {{-- <tr>
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
    </tr> --}}
</table>
<hr>
<h2 class="center">{{ $applicant->batch->camp->fullName }} 繳費單</h2>
歡迎您報名「2021∞快樂營」！竭誠歡迎您的到來，期待與您一起發現快樂，經歷生動、活潑有趣的北極之旅課程。以下幾點事項，請您協助及配合：
<br>
活動費用：{{ $applicant->bank_third_barcode }}元 <br>
繳費地點：請於繳費期限內，至超商繳費。若完成繳費，請於至少 1 ~ 2 個工作天後，上網查詢是否已繳費完畢。 （網址：<a href="{{ url(route("query", $applicant->batch_id)) }}">{{ route("queryadmit", $applicant->batch_id) }}</a>）<br>
<ol>
    <li>發票於營隊第一天提供，若需開立統一編號，請回報名網站修改報名資料處填寫。</li>
    <li>若繳費後，因故無法參加需退費者，請參照營隊網站【常見問題-退費注意事項】，並回報名網站憑報名序號、姓名及身分證字號提出申請。</li>
    <li>本營隊密切注意新冠疫情發展，若因故必須取消營隊或改變舉辦方式，將公布於快樂營網頁。</li>
</ol>
電話洽詢窗口(限周一~周五 上午10時- 下午6時來電) <br>
● 符小姐 TEL:0921-093-420 <br>
● 小華老師 TEL:02-7751-6799#520031 <br>
