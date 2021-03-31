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
            {{-- line-height 及 font-size 已達極限 --}}
            line-height: 15px;
            font-size: 14px;
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
財團法人福智文教基金會<br>
謙德管理顧問股份有限公司
<p style="float:right; clear: both; margin-top:90px; margin-right: -6px">代</p>
<p style="float:right; clear: both; margin-top:120px; margin-right: -6px">收</p>
<p style="float:right; clear: both; margin-top:150px; margin-right: -6px">行</p>
<p style="float:right; clear: both; margin-top:180px; margin-right: -6px">存</p>
<p style="float:right; clear: both; margin-top:210px; margin-right: -6px">查</p>
<p style="float:right; clear: both; margin-top:240px; margin-right: -6px">聯</p>
<table class="table">
    <tr>
        <td width="350px">
            親愛的客戶您好，請持本繳款單於繳費期限內至 7-11、<br>全家、萊爾富、OK 繳費，免付手續費。
        </td>
        <td style="padding: 0!important" width="200px">
            <table class="table" style="margin: -12px">
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
                        <img src="data:image/png;base64,{{ \DNS1D::getBarcodePNG($applicant->store_first_barcode, 'C39', 1, 30) }}" alt="barcode"/><br>
                        <a style="padding-top: 5px;" class="small">{{ $applicant->store_first_barcode }}</a><br>
                        <img src="data:image/png;base64,{{ \DNS1D::getBarcodePNG($applicant->store_second_barcode, 'C39', 1, 30) }}" alt="barcode" style="padding-top:8px;"/><br>
                        <a class="small">{{ $applicant->store_second_barcode }}</a><br>
                        <img src="data:image/png;base64,{{ \DNS1D::getBarcodePNG($applicant->store_third_barcode, 'C39', 1, 30) }}" alt="barcode" style="padding-top:8px;"/><br>
                        <a class="small">{{ $applicant->store_third_barcode }}</a>
                    </td>
                </tr>
                {{-- <tr>
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
                </tr> --}}
            </table>
        </td>
    </tr>
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
</body>
</html>