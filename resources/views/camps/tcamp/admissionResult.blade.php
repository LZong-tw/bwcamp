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
        @if(!$skip)
            @if($applicant->is_admitted)
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>查詢結果</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                <table width="100%" style="table-layout:fixed; border: 0; text-align: center; margin-bottom: 12px; margin-top: -4px;">
                                    <tr>
                                        <td><h6>場次</h6></td>
                                        <td><h6>姓名</h6></td>
                                        <td><h6>錄取編號</h6></td>
                                        <td><h6>組別</h6></td>
                                    </tr>
                                    <tr>
                                        <td>{{ $applicant->batch->name }}</td>
                                        <td>{{ $applicant->name }}</td>
                                        <td><u>{{ $applicant->group }}{{ $applicant->number }}</u></td>
                                        <td><u>{{ $applicant->group }}</u></td>
                                        </tr>
                                </table>
                                &emsp;&emsp;恭喜您錄取「{{ $applicant->batch->camp->fullName }}」！竭誠歡迎您的到來。<br>
                                &emsp;&emsp;相關營隊訊息，將於營隊三周前寄發「報到通知單」，請記得到電子信箱收信。<br>
                                &emsp;&emsp;也歡迎加入[幸福心學堂online]臉書社團，收取營隊訊息和教育新知。<br>
                                &emsp;&emsp;很期待與您共享這場心靈的饗宴，預祝您歡喜學習，收穫滿滿。<br>
                                &emsp;&emsp;敬祝　教安<br>
                                <h5>請加入幸福心學堂online臉書社團</h5>
                                <div class="ml-4 mb-2">
                                    <a href="https://www.facebook.com/groups/bwfoce.happiness.new" target="_blank" rel="noopener noreferrer">https://www.facebook.com/groups/bwfoce.happiness.new</a>
                                </div>
                                <h5>關注「福智文教基金會」網站：</h5>
                                <div class="ml-4 mb-2">
                                    <a href="https://bwfoce.org" target="_blank" rel="noopener noreferrer">https://bwfoce.org</a><br>
                                </div>
                                <h5>諮詢窗口</h5>
                                <div class="ml-4 mb-2">
                                    營隊相關訊息我們會有義工與您聯繫，或諮詢窗口（請於周一至周五 10:00~17:30 來電）<br>
                                    陳昶安&emsp;先生<br>
                                    電話：07-9743280#68601<br>
                                    Email：ca7974zz@gmail.com<br>
                                </div>
                                {{-- <h4>營隊資訊</h4>
                                <div class="ml-4 mb-2">
                                    活動期間：2022/1/23、24 (日、一)<br>
                                    本次活動採線上舉辦。 <br>
                                    連線軟體與帳號：Zoom&nbsp;(請事先下載安裝，當日09:00將開放連線)：<br>
                                    Zoom 帳號：95556824059 密碼：703112
                                    </div>
                                <br><br> --}}
                                <h5>參加狀態</h5>
                                <div class="ml-4 mb-2">
                                    @if(!isset($applicant->is_attend))
                                        <div class="ml-4 mb-2 text-primary">狀態：未回覆參加。</div>
                                    @elseif($applicant->is_attend)
                                        <div class="ml-4 mb-2 text-success">狀態：已確認參加。如欲取消請按取消參加。</div>
                                    @else
                                        <div class="ml-4 mb-2 text-danger">狀態：取消參加。如欲恢復參加請按確認參加。</div>
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
                                    {{--全程參與者，發給研習證明文件。--}}
                                </div>
                            </p>
                            <br><br>
                            <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>查詢結果</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                敬愛的教育夥伴 {{ $applicant->name }} ，您好！ <br><br>
                                　　「教師生命成長營」自舉辦以來，每年都得到教育夥伴們的支持和肯定，思及社會上仍有這麼多人共同關心莘莘學子們的學習成長，令人深感振奮！每一位老師的報名都是鼓舞我們的一分力量，激勵基金會全體人員持續不懈，與大家共同攜手為教育盡心盡力。 <br><br>
                                　　非常感謝您的報名，由於我們的場地容量的侷限性，不克錄取，造成您的不便，敬請見諒包涵！ <br><br>
                                　　福智文教基金會在全省各縣市的分支機構，平日都設有適合各年齡層的多元心靈提升課程，誠摯歡迎您的參與！<br><br>
                                　　關注「福智文教基金會」網站：<a href="https://bwfoce.org" target="_blank" rel="noopener noreferrer">https://bwfoce.org</a><br>
                                　　關注「幸福心學堂online」FB社團：<a href="https://www.facebook.com/groups/bwfoce.happiness.new/" target="_blank" rel="noopener noreferrer">https://www.facebook.com/groups/bwfoce.happiness.new</a><br>
                                <br>
                                祝福　教安，健康平安！
                            </p>
                            <p class="right">財團法人福智文教基金會　敬啟</p>
                            <p class="right">{{ \Carbon\Carbon::now()->year }}  年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
                            <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
@stop
