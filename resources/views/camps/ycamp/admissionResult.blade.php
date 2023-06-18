@extends('camps.ycamp.layout')
@section('content')
    @if(Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get("error") }}
        </div>
    @endif
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
<!--
    @if($applicant->is_admitted)
        <div class="card">
            <div class="card-header">
                <h2>研習證明下載</h2>
            </div>
            <div class="card-body">
                <a href="https://bwcamp.bwfoce.org/downloads/ycamp2022/{{ $applicant->group }}{{ $applicant->number }}{{ $applicant->applicant_id }}.pdf" target="_blank" rel="noopener noreferrer" class="btn btn-success">下載</a>
            </div>
        </div>
        <br>
    @endif
-->
    <div class="card">
        <div class="card-header">
            錄取查詢
        </div>
        <div class="card-body">
            @if($applicant->is_admitted)
                <p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
                <p class="card-text text-indent">非常恭喜您錄取「{{ $camp_data->fullName }}」！</p>
                <p class="card-text text-indent">
                報名序號：{{ $applicant->applicant_id }}<br>
                錄取編號：{{ $applicant->group }}{{ $applicant->number }}<br>
                營隊日期：{{ $applicant->batch->batch_start }}(五) ~ {{ $applicant->batch->batch_end }}(一)，共4天<br>
                營隊地點：{{ $applicant->batch->locationName }}({{ $applicant->batch->location }})<br>
                </p>
                <p class="card-text text-indent">期待與您共享這場心靈饗宴！祝福您營隊收穫滿滿。</p><br>

                <h5>下載錄取/報到通知</h5>
                <div class="ml-2 mb-2">請下載<a href="{{ url('downloads/ycamp2023/【2023第56屆大專青年生命成長營】錄取通知單.pdf') }}">錄取/報到通知</a>，內含報到資訊、必帶物品，及交通資訊等等。</div>
                <div class="ml-2 mb-2"><a href="{{ url('downloads/ycamp2023/【2023第56屆大專青年生命成長營】錄取通知單.pdf') }}" class="btn btn-primary" target="_blank" style="margin-top: 10px">下載錄取/報到通知</a></div><br>

                <h5>放棄參加</h5>                
                <form class="ml-2 mb-2" action="{{ route('toggleAttend', $batch_id) }}" method="POST" id="attendcancel">
                    @csrf
                    <input type="hidden" name="id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                    <input type="hidden" name="camp" value="ycamp">
                    @if(!isset($applicant->is_attend) || $applicant->is_attend )
                        <div class="ml-0 mb-2 text-primary">您目前的狀態是「參加」。</div>
                        <div class="ml-0 mb-2">如您因故無法參加，請按放棄參加通知我們，謝謝！</div>
                        <div>
                        <input class="btn btn-danger" type="submit" value="放棄參加" id="cancel" name="cancel">
                        </div>
                    @else
                        <div class="ml-0 mb-2 text-danger">您目前的狀態是「放棄參加」。</div>
                        <div class="ml-0 mb-2">如您可以參加了，請按恢復參加，謝謝！</div>
                        <div>
                        <input class="btn btn-success" type="submit" value="恢復參加" id="confirmattend" name="confirmattend">
                        </div>
                    @endif
                </form><br>
                @if(!isset($applicant->is_attend) || $applicant->is_attend)
                    <h5>選擇交通方式</h5>
                    <form class="ml-2 mb-2" action="{{ route('modifyTraffic', $batch_id) }}" method="POST" id="selecttraffic">
                        @csrf
                        <div class="ml-0 mb-2">交通方式預設為自往及自回</div>
                        <div class="ml-0 mb-2">交通資訊請參閱<a href="{{ url('downloads/ycamp2023/【2023第56屆大專青年生命成長營】錄取通知單.pdf') }}">錄取/報到通知</a>之附件</div>
                        <input type="hidden" name="id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                        <input type="hidden" name="camp" value="ycamp">
                        <div class='row form-group required'>
                            <label for='inputDepartFrom' class='col-md-2 control-label text-md-right'>去程交通</label>
                            <div class="col-md-4">
                                <select required class='form-control' name='depart_from' id='inputDepartFrom'>
                                    <option value='自往' selected>自往</option>
                                    <option value='火車站接駁車' >火車站接駁車</option>
                                    <option value='台北專車' >台北專車</option>
                                    <option value='桃園專車' >桃園專車</option>
                                    <option value='新竹專車' >新竹專車</option>
                                    <option value='台中專車' >台中專車</option>
                                    <option value='台南專車' >台南專車</option>
                                    <option value='高雄專車' >高雄專車</option>
                                </select>
                                <div class="invalid-feedback">
                                    請選擇去程交通
                                </div>
                            </div>
                        </div>
                        <div class='row form-group required'>
                            <label for='inputBackTo' class='col-md-2 control-label text-md-right'>回程交通</label>
                            <div class="col-md-4">
                                <select required class='form-control' name='back_to' id='inputBackTo'>
                                    <option value='自回' selected>自回</option>
                                    <option value='火車站接駁車' >火車站接駁車</option>
                                    <option value='台北專車' >台北專車</option>
                                    <option value='桃園專車' >桃園專車</option>
                                    <option value='新竹專車' >新竹專車</option>
                                    <option value='台中專車' >台中專車</option>
                                    <option value='台南專車' >台南專車</option>
                                    <option value='高雄專車' >高雄專車</option>
                                </select>
                                <div class="invalid-feedback">
                                    請選擇回程交通
                                </div>
                            </div>
                        </div>
                        <input class="btn btn-success" type="submit" value="確認修改" id="confirmtraffic" name="confirmtraffic">
                    </form><br>
                    <div class="ml-2 mb-2">應交費用：{{ $traffic->fare }}；已交費用：{{ $traffic->deposit }}</div>
                    <div class="ml-2 mb-2"><a href="{{ route('showPaymentForm', [$camp_data->id, $applicant->applicant_id]) }}" class="btn btn-primary" target="_blank" style="margin-top: 10px">顯示繳費單</a></div>
                    <br>
                @endif

                <h5>聯絡我們</h5>
                <div class="ml-0 mb-2">若有任何問題，歡迎</div>
                <div class="ml-2 mb-2">1. 洽各組輔導員</div>
                <div class="ml-2 mb-2">2. 與『福智文教基金會』各區窗口聯絡</div>
                <div class="ml-4 mb-2">台北／林佳樺0908-604-972</div>
                <div class="ml-4 mb-2">桃園／李致緯0916-103-155</div>
                <div class="ml-4 mb-2">新竹／李欣蓓0978-393697</div>
                <div class="ml-4 mb-2">台中／鄭宜庭0978-175-705</div>
                <div class="ml-4 mb-2">雲嘉／周郁紋0961-552-325</div>
                <div class="ml-4 mb-2">台南／陳良彥0975-258-739</div>
                <div class="ml-4 mb-2">高雄／林采誼0979-625-652</div>
                <div class="ml-2 mb-2">3. Email福智青年：<a href="mailto:youth@blisswisdom.org">youth@blisswisdom.org</a></div>
                <div class="ml-2 mb-2">4.留言給福智青年：<a href="https://www.facebook.com/bwyouth" target="_blank" rel="noopener noreferrer">福智青年粉專</a></div>

                <p class="card-text text-right">主辦單位：財團法人福智文教基金會／國立雲林科技大學　敬啟</p>
                <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
            @elseif($applicant->created_at->gte(\Carbon\Carbon::parse('2023-06-20 00:00:00')))>
                <!-----錄取中----->
                <p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
                <p class="card-text indent">感謝您報名「{{ $camp_data->fullName }}」，錄取作業正在進行中，請稍後再進行錄取查詢。感謝您的耐心等待！</p>
                <p class="card-text text-right">財團法人福智文教基金會 敬啟</p>
                <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
            @else
<!--
                <p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
                <p class="card-text indent">感謝您報名「{{ $camp_data->fullName }}」，錄取作業正在進行中，請稍後再進行錄取查詢。感謝您的耐心等待！</p>
                <p class="card-text text-right">財團法人福智文教基金會 敬啟</p>
                <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
                </p>
-->
                <!-----備取=不錄取----->
                <p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
                <p class="card-text indent">感謝您報名「{{ $camp_data->fullName }}」，依報名資格規範，很抱歉於此活動未能錄取您。</p>
                <p class="card-text indent">福智文教基金會尚有各項精彩活動與課程，竭誠歡迎您的參與！</p>
                <p class="card-text indent"><a href="http://bwfoce.org/web" target="_blank" rel="noopener noreferrer">http://bwfoce.org/web</a></p>
                <p class="card-text indent">祝福您身心健康，吉祥如意！</p>
                <p class="card-text text-right">財團法人福智文教基金會 敬啟</p>
                <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
                </p>
            @endif
            <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
        </div>
    </div>
    <script>
        @if(!isset($applicant->is_attend) || $applicant->is_attend)
            let cancel = document.getElementById('cancel');
            cancel.addEventListener('click', function(event) {
                if(confirm('確認放棄參加？')){
                        return true;
                }
                event.preventDefault();
                return false;
            });
        @else
            let confirmattend = document.getElementById('confirmattend');
            confirmattend.addEventListener('click', function(event) {
                console.log("confirmattend");
                if(confirm('確認恢復參加？')){
                        return true;
                }
                event.preventDefault();
                return false;
            });
        @endif
        @if(!isset($applicant->is_attend) || $applicant->is_attend)
            let confirmtraffic = document.getElementById('confirmtraffic');
            confirmtraffic.addEventListener('click', function(event) {
                console.log("confirmtraffic");
                if(confirm('確認修改交通？')){
                        return true;
                }
                event.preventDefault();
                return false;
            });

            {{-- 回填交通選項 --}}
            (function() {
                let traffic_data = JSON.parse('{!! $traffic !!}');
                let selects = document.getElementsByTagName('select');
                console.log(traffic_data);
                for (var i = 0; i < selects.length; i++){
                    if(typeof traffic_data[selects[i].name] !== "undefined"){
                        selects[i].value = traffic_data[selects[i].name]; 
                    }
                }
            })();
        @endif
    </script>
@stop