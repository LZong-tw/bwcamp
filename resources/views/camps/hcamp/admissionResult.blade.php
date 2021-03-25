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
                        恭喜您錄取「{{ $camp_data->fullName }}」！竭誠歡迎您的到來，期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。以下幾點事情需要您的協助與配合：
                        <h4>研習期間</h4>
                            <div class="ml-4 mb-2">2021年1月30日（六）至1月31日（日），共2天（北桃竹嘉南高）<br>
                            2021年2月3日（三）至2月4日（四），共2天（中）</div>
                        <h4>活動費用</h4>
                            <div class="ml-4 mb-2">1200元，繳費聯請下載附件檔。</div>
                        <h4>報到時間</h4>
                            <div class="ml-4 mb-2">請於{{ \Carbon\Carbon::now()->year }}年{{ substr($camp_data->payment_deadline, 2, 2) }}月{{ substr($camp_data->payment_deadline, 4, 2) }}日前完成繳費，逾時將視同放棄錄取資格。</div>
                        <h4>繳費地點</h4>
                            <div class="ml-4 mb-2">可至超商、上海銀行繳費，或使用ATM轉帳、臨櫃匯款。<br>
                            若完成繳費，請於至少一個工作天後，上網查詢是否已繳費完畢。</div>
                        <h4>其他事項</h4>
                        <ul>
                            <li>發票於營隊第一天提供，<strong>若需開立統一編號，請於 1/22 前填寫<a href="https://docs.google.com/forms/d/e/1FAIpQLSeVcqd01trNPKMSvc-RH8Zhac5Gexn-fBaAfAWMCn323PVgFw/viewform">申請單</a></strong>。</li>
                            <li>若繳費後，因故無法參加研習需退費者，請參照<a href="https://bwfoce.wixsite.com/bwtcamp/faq" target="_blank">報名網站申請退費注意事項</a>，並填寫<strong>退費申請單</strong>。</li>
                            <li><a style="color: red;">本會密切注意新冠疫情發展，若因故必須取消營隊或改變舉辦方式，將公布於快樂營網頁。</a></li>
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