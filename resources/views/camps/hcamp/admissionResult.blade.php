@extends('camps.hcamp.layout')
@section('content')
    <style>
        .indent{
            text-indent: 22px;
        }
    </style>
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
    <div class="row">
        <div class="col-sm-10">
            <div class="card">
                <div class="card-header">
                    繳費通知
                </div>
                
                
                
                
                
                

                <div class="card-body">
                    <p class="card-text">
                        歡迎您報名「2021∞快樂營」！竭誠歡迎您的到來，期待與您一起發現快樂，經歷生動、活潑有趣的北極之旅課程。以下幾點事項，請您協助及配合：
                        <h4>活動期間</h4>
                            <div class="ml-4 mb-2">2021年7月11日(日)至7月13日(二)，共三天，全程住宿。</div>
                        <h4>活動費用</h4>
                            <div class="ml-4 mb-2">定價新台幣5,500元，請務必於2021年5月4日前繳納費用，逾期視為放棄錄取。<br>
                            <strong>註：</strong>4/20 前報名並繳費者，享早鳥優惠價4,950元，4/21起即自動恢復原價5500元，請重新下載繳費單並於5/4前繳費，始享有錄取資格。</div>
                        <h4>繳費地點</h4>
                            <div class="ml-4 mb-2">請下載右方繳費聯，並至超商刷條碼轉帳。<br>
                            完成繳費後，請於至少1~2個工作天後，上網查詢是否已繳費完畢。</div>
                        <h4>其他事項</h4>
                        <ol>
                            <li>發票於營隊第一天提供，若需開立統一編號，請回報名網站修改報名資料處填寫。</li>
                            <li>若繳費後，因故無法參加需退費者，請參照營隊網站【常見問題-退費注意事項】，並回報名網站憑報名序號、姓名及身分證字號提出申請。</li>
                            <li>本營隊密切注意新冠疫情發展，若因故必須取消營隊或改變舉辦方式，將公布於快樂營網頁。</li>
                            <li>營隊問題諮詢方式如下：</li>
                            <ol type="i">
                                <li>可掃描 QR-Code 或點選連結 <a href="https://line.me/R/ti/g/HbQvGwJyng" target="_blank" rel="noopener noreferrer">https://line.me/R/ti/g/HbQvGwJyng</a><br>
                                加入2021年∞快樂營諮詢line群組。<br>
                                <img src="{{ url('img/hcamp_line.png') }}" alt=""></li>
                                <li>電話洽詢窗口(限周一~周五 上午10時- 下午6時來電) <br>
                                ● 符小姐 TEL:0921-093-420 <br>
                                ● 小華老師 TEL:02-7751-6799#520031</li>
                            </ol>
                        </ol>
                    </p>
                    <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                    <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="card">
                <div class="card-header">
                    相關資訊
                </div>
                <div class="card-body">
                    <p class="card-text">
                        繳費狀態：<strong>{{ $applicant->payment_status }}</strong>
                    </p>
                    <form action="{{ route("downloadPaymentForm", $applicant->batch_id) }}" method="post" name="downloadPaymentForm">
                        @csrf
                        <input type="hidden" name="applicant_id" value="{{ $applicant->applicant_id }}">
                        <button class="btn btn-success" onclick="this.innerText = '正在產生繳費聯'; this.disabled = true; document.downloadPaymentForm.submit();">下載繳費聯</button>
                    </form>
                    <h6>請注意，若您使用 Line 開啟網頁，將會無法下載繳費單，請在普通瀏覽器開啟本站後再下載。</h6>
                </div>
            </div>
            @if($applicant->showCheckInInfo)
                <br>
                <div class="card">
                    <div class="card-header">
                        報到資訊
                    </div>
                    <div class="card-body">
                        <form action="{{ route("downloadCheckInNotification", $applicant->batch_id) }}" method="post" name="downloadCheckInNotification">
                            @csrf
                            <input type="hidden" name="applicant_id" value="{{ $applicant->applicant_id }}">
                            <button class="btn btn-primary" onclick="this.innerText = '正在產生報到通知單'; this.disabled = true; document.downloadCheckInNotification.submit();">報到通知單</button>
                        </form>
                        <form action="{{ route("downloadCheckInQRcode", $applicant->batch_id) }}" method="post" name="downloadCheckInQRcode">
                            @csrf
                            <input type="hidden" name="applicant_id" value="{{ $applicant->applicant_id }}">
                            <button class="btn btn-success" onclick="this.innerText = '正在產生 QR code 報到單'; this.disabled = true; document.downloadCheckInQRcode.submit();">QR Code 報到單</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop