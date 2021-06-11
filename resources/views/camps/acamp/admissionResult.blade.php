@extends('camps.acamp.layout')
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
        @if($applicant->is_admitted)
            <div class="col-sm-10">
                <div class="card">
                    <div class="card-header">
                        錄取通知
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $applicant->name }} 您好</p>
                        <p class="card-text indent">恭喜您錄取「{{ $camp_data->fullName }}」！歡迎您參加本研習活動，我們誠摯歡迎您來共享這場心靈饗宴。兩天研習務必全程參加，相關訊息請參閱下列說明。</p>
                        <p class="card-text indent">近期由於新冠疫情關係，福智文教基金會遵照政府發佈的全國防疫措施，原訂7/31~8/1的營隊日期將往後延期，並改為線上課程方式舉辦。線上營隊的舉辦日期、與報到相關資訊，我們將再另行通知即公告。</p>
                        <p class="card-text indent">若您對營隊有任何問題，歡迎您透過以下方式聯絡本基金會，我們將盡速為您服務。</p>
                        <p class="card-text indent">福智文教基金會 關心您的健康，感謝您的支持！</p>
                        <p class="card-text">
                            <h4>注意事項</h4>
                                <div class="ml-4 mb-2">兩天研習課程務必全程參加，若您無法全程參加，請告知關懷員，感謝您的貼心。謝謝！</div>
                                <div class="ml-4 mb-2">本會關懷員近日將以電話跟您確認，若有任何問題，歡迎與該關懷員聯絡，或來電本會。</div>
                        </p>
                        <p class="card-text text-right">主辦單位：財團法人福智文教基金會 敬啟</p>
                        <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
                        <p class="card-text">聯絡電話：02-7751-6788分機:610408、613091、610301</p>
                        <p class="card-text">洽詢時間：週一∼週五 10:00 ~ 20:00、週六 10:00～16:00</p>
                        <p class="card-text">Facebook 卓越青年<a href="https://www.facebook.com/YoungOneCamp" target="_blank" rel="noopener noreferrer">https://www.facebook.com/YoungOneCamp</a></p>
                        <p class="card-text">2021卓越青年生命探索營官方網站<a href="http://www.youngone.org.tw/camp/" target="_blank" rel="noopener noreferrer">http://www.youngone.org.tw/camp/</a></p>
                        <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                        <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                    </div>
                </div>
            </div>
            {{--
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
            --}}
        @else
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        錄取查詢結果
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $applicant->name }} 您好</p>
                        <p class="card-text indent">非常感謝您報名「{{ $camp_data->fullName }}」，由於場地與各項條件的限制，非常抱歉未能錄取您。誠摯地邀請您報名相關活動。</p>
                        <p class="card-text indent">相關活動訊息，請洽詢各區聯絡窗口：</p>
                        <p class="card-text indent"><a href="https://www.blisswisdom.org/about/branches" target="_blank" rel="noopener noreferrer">全球辦事處</a></p>
                        <p class="card-text indent"><a href="http://tp.blisswisdom.org/352-2" target="_blank" rel="noopener noreferrer">北區服務據點</a></p>
                        <p class="card-text indent">若有任何問題，歡迎來電本會02-7751-6788分機: 610408、613091、610301。</p>
                        <p class="card-text indent">(洽詢時間：週一∼週五 10:00 ~ 20:00、週六 10:00～16:00)</p>
                        <p class="card-text text-right">主辦單位：財團法人福智文教基金會 敬啟</p>
                        <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
                        <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                        <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop