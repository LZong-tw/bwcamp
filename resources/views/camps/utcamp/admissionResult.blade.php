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
                您好，感謝您報名 【2022第30屆教師生命成長營 大專教職員梯】，大會因故需調整錄取時程，將修正為：11/30(二)、12/20(一)、1/5(三)，將於以上時間點寄發「錄取通知」電子信件(email)。再次感謝您的報名！
            </div>
        @endif
        @if(!$skip)
            @if($applicant->is_admitted)
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                查詢結果
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <table width="100%" style="table-layout:fixed; border: 0;">
                                        <tr>
                                            {{-- <td>梯次：{{ $applicant->batch->name }}</td> --}}
                                            <td>姓名：{{ $applicant->name }}</td>
                                            <td>錄取編號：<u>{{ $applicant->group }}{{ $applicant->number }}</u></td>
                                            <td>組別：<u>{{ $applicant->group }}</u></td>
                                        </tr>
                                    </table>
                                    親愛的老師您好，非常恭喜您錄取「2023第31屆教師生命成長營-大專教職員梯 {{ $applicant->xsession }}」，誠摯地期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。請詳閱以下相關訊息，祝福您營隊收穫滿滿。
                                    <h4>營隊資訊</h4>
                                    <div class="ml-4 mb-2">
                                        活動期間：2023/1/31(二)～2/1(三)<br>
                                        地點：{{ $applicant->xaddr }}<br>
                                    {{-- <h4>請回覆確認參加</h4>
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
                                        </form> --}}
                                    </div>
                                    <div class="mb-2">
                                    全程參與者，發給研習證明文件或公務員研習證明。<br>
                                    若有任何問題，歡迎來電(02)7714-6066 分機20318 邱先生。<br>
                                    12/12~15將有營隊關懷員與您聯絡，提供營隊相關資訊。<br>
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
                            <p class="card-text">
                            敬愛的教育夥伴，您好！<br>
                            「教師生命成長營」自舉辦以來，每年都得到教育夥伴們的支持和肯定，思及社會上仍有這麼多人共同關心莘莘學子們的學習成長，令人深感振奮！每一位老師的報名都是鼓舞我們的一分力量，激勵基金會全體人員持續不懈，與大家共同攜手為教育盡心盡力。 非常感謝您的報名，由於名額限制不克錄取，造成您的不便，敬請見諒包涵！<br>
                            福智文教基金會在全省各縣市的分支機構，平日都設有適合各年齡層的多元心靈提升課程，誠摯歡迎您的參與！<br>
                            關注「福智文教基金會」網站：<a href="https://bwfoce.org">https://bwfoce.org</a><br>
                            祝福　教安，健康平安！<br>
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