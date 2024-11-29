@extends('camps.utcamp.layout')
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
            您好，感謝您報名 【{{ $applicant->batch->camp->fullName }} {{ $applicant->batch->name }}】，大會預計於：{{ $applicant->batch->camp->admission_announcing_date }}({{ $applicant->batch->camp->admission_announcing_date_Weekday }})寄發「錄取通知」電子信件(email)，並同步於報名網站提供查詢。
            </div>
        @endif
        @if(!$skip)
            @if($applicant->is_admitted)
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ $applicant->batch->camp->fullName }} {{ $applicant->batch->name }} 錄取結果</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                <table width="100%" style="table-layout:fixed; border: 0;">
                                <tr>
                                <td>報名序號：{{ $applicant->applicant_id }}</td>
                                <td>姓名：{{ $applicant->name }}</td>
                                <td>錄取編號：<u>{{ $applicant->group }}{{ $applicant->number }}</u></td>
                                <td>組別：<u>{{ $applicant->group }}</u></td>
                                </tr>
                                </table>
                                歡迎您參加「{{ $applicant->batch->camp->fullName }} {{ $applicant->batch->name }}」，誠摯地期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。請詳閱以下相關訊息，祝福您營隊收穫滿滿。
                                <h4>營隊資訊</h4>
                                <div class="ml-4 mb-2">
                                活動期間：{{ $applicant->batch->batch_start }}({{ $applicant->batch_start_Weekday }})～{{ $applicant->batch->batch_end }}({{ $applicant->batch_end_Weekday }})<br>
                                地點：{{ $applicant->batch->location }} {{ $applicant->batch->locationName }}<br>
                                {{-- 
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
                                    --}}
                                </div>
                                <div class="mb-2">
                                    全程參與者，發給研習證明文件。<br>
                                    若有任何問題，歡迎來電(02)7714-6066 分機20318 邱先生。<br>
                                    12/10(二)~12/16(一)將有營隊關懷員與您聯絡，提供營隊相關資訊。<br>
                                </div>
                            </p>
                            <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                            </div>
                        </div>
                    </div>
                {{-- 
                    <div class="col-sm-2">
                    @if($applicant->showCheckInInfo)
                        <div class="card">
                            <div class="card-header">
                                報到資訊
                            </div>
                            <div class="card-body">
                                <form action="{{ route          ("downloadCheckInNotification", $applicant->batch_id) }}" method="post" name="downloadCheckInNotification">
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
                            <h5>{{ $applicant->batch->camp->fullName }} {{ $applicant->batch->name }} 錄取結果</h5>
                        </div>
                        <div class="card-body">                                
                            <p class="card-text">
                            敬愛的教育夥伴，您好！<br>
                            　　非常感謝您的報名，但是很遺憾，因資格問題，您此次未獲錄取「{{ $applicant->batch->camp->fullName }} {{ $applicant->batch->name }}」。期盼未來仍能在福智文教基金會舉辦之心靈提升、教育活動見到您的身影。另外，福智文教基金會在各縣市的分支機構，平日都設有適合各年齡層的多元心靈提升課程，誠摯歡迎您的參與！<br>
                            關注「福智文教基金會」網站：<a href="https://bwfoce.org">https://bwfoce.org</a><br>
                            若有疑問，歡迎來訊：<a href="mailto:bwfaculty@blisswisdom.org">bwfaculty@blisswisdom.org</a><br>
                            敬祝　教安！<br>
                            </p>
                            <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
@stop