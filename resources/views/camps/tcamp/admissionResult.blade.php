@extends('camps.tcamp.layout')
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
        @php
            $skip = false;   
        @endphp
        @if($camp_data->variant == "utcamp" && now()->lt("2021-11-30"))
            @php
                $skip = true;
            @endphp        
            <div class="alert alert-success" role="alert">
                您好，感謝您報名 【2022第30屆教師生命成長營 大專教職員梯】，大會因故需調整錄取時程，將修正為：11/30(二)、12/20(一)、1/5(三)，將於以上時間點寄發「錄取通知」電子信件(email)。再次感謝您的報名！
            </div>
        @endif
        @if(!$skip)
            @if($camp_data->variant == "utcamp")
                @if($applicant->is_admitted)
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                查詢結果
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    親愛的{{ $applicant->name }}老師您好，非常恭喜您錄取「2022第30屆教師生命成長營-大專教職員梯」，誠摯地期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫！請詳閱以下相關訊息，祝福您營隊收穫滿滿：
                                    <h4>營隊資訊</h4>
                                    <div class="ml-4 mb-2">
                                        活動期間：2022/1/23、24 (日、一)<br>
                                        本次活動採線上舉辦。 <br>
                                        <a style="color: red;">連線軟體與帳號：Zoom</a> (請事先下載安裝，當日09:00將開放連線)：<br>
                                        Zoom 帳號：95556824059 密碼：703112
                                    </div>
                                    <h4>請回覆確認參加</h4>
                                    <div class="ml-4 mb-2">
                                        @if(!isset($applicant->is_attend))
                                            <div class="ml-4 mb-2 text-primary">狀態：未回覆參加。</div>
                                        @elseif($applicant->is_attend)
                                            <div class="ml-4 mb-2 text-success">狀態：已確認參加。</div>
                                        @else
                                            <div class="ml-4 mb-2 text-danger">狀態：不參加。</div>
                                        @endif
                                        <form class="ml-4 mb-2" action="{{ route('toggleAttend', $batch_id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                                            @if(!isset($applicant->is_attend))
                                                <input class="btn btn-success" type="submit" value="確認參加">
                                                <input class="btn btn-danger" type="submit" value="不參加" id="cancel" name="confirmation_no">
                                            @elseif($applicant->is_attend)
                                                <input class="btn btn-danger" type="submit" value="取消參加" id="cancel">
                                            @else
                                                <input class="btn btn-success" type="submit" value="確認參加">
                                            @endif
                                        </form>
                                        全程參與者，發給研習證明文件。
                                    </div>
                                    <div class="mb-2">
                                        有任何問題，歡迎與關懷員聯絡，或來電福智文教基金會02-7751-6799 #520023邱先生(2022第30屆教師生命成長營大專教職員梯秘書組)
                                    </div>
                                </p>
                                <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                                <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-sm-2">
                        @if($applicant->showCheckInInfo)
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
                    </div> --}}
                @else
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                查詢結果
                            </div>
                            <div class="card-body">                                
                                <p class="card-text">您好，感謝您報名 【2022第30屆教師生命成長營 大專教職員梯】，後續錄取時程為：12/20(一)、1/5(三)，將於以上時間點寄發電子信件通知錄取相關訊息。再次感謝您的報名！</p>
                                <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                                <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                            </div>
                        </div>
                    </div>
                @endif
            @else
            @endif
        @endif
    </div>
@stop