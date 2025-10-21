<style>
    u{
        color: red;
    }
</style>
@extends('camps.nycamp.layout')
@section('content')
    @if(Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get("error") }}
        </div>
    @endif
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
{{--
    @if($applicant->is_admitted)
        <div class="card">
            <div class="card-header">
                <h2>研習證明下載</h2>
            </div>
            <div class="card-body">
                <a href="https://bwcamp.bwfoce.org/downloads/{{ $camp_data->table }}{{ $camp_data->year }}/{{ $applicant->group }}{{ $applicant->number }}{{ $applicant->applicant_id }}.pdf" target="_blank" rel="noopener noreferrer" class="btn btn-success">下載</a>
            </div>
            <div class="card-body">
                如下載顯示錯誤，請聯絡您的帶組老師，謝謝！
            </div>
        </div>
        <br>
    @endif
--}}
    <div class="card">
        <div class="card-header">
            Admission 錄取查詢
        </div>
        <div class="card-body">
            @if($applicant->is_admitted && !$applicant->deleted_at)
                <p class="card-text">Dear {{ $applicant->name }} </p>
                <p class="card-text text-indent">It is our honor to welcome you to 「{{ $camp_data->fullName }}」！We hope you will have a great time in the camp.
                    The following are information you need to know before you come. Please read carefully.
                </p>
                <p class="card-text text-indent">
                Your Application Number 您的報名序號：{{ $applicant->applicant_id }}<br>
                Your Admission Number 您的錄取編號：{{ $applicant->group }}{{ $applicant->number }}<br>
                Datas 營隊期間：{{ $applicant->batch->batch_start }} ({{ $applicant->batch_start_Weekday }}) ~ {{ $applicant->batch->batch_end }} ({{ $applicant->batch_end_Weekday }})，共4天<br>
                Location 營隊地點：{{ $applicant->batch->locationName }} ({{ $applicant->batch->location }})<br>
                </p>

                <h4>Before you come 錄取/報到通知</h4>
                <div class="ml-0 mb-2">
                請詳閱<a href="https://docs.google.com/document/d/1t56h4BsWBqC_r38rtGekn24GQDW8_2oA/edit">錄取通知</a>，內含報到資訊、必帶物品，及交通資訊等等。<br>
                </div>
                <div class="ml-0 mb-2">
                Please read carefully the <a href="https://docs.google.com/document/d/1DK2mQK6beqShyK82Jigyt1z7PJv_MwWa2yEadLCrsR8/edit?tab=t.0">Admission Notice</a>.<br><br>
                </div>
                <!--
                <div class="ml-2 mb-2">請詳閱<a href="{{ url('downloads/$applicant->camp->table.$applicant->camp->year/【2025第58屆大專青年生命成長營】錄取通知單.pdf') }}">錄取/報到通知</a>，內含報到資訊、必帶物品，及交通資訊等等。</div>
                <div class="ml-2 mb-2"><a href="{{ url('downloads/$applicant->camp->table.$applicant->camp->year/【2025第58屆大專青年生命成長營】錄取通知單.pdf') }}" download class="btn btn-primary" target="_blank" style="margin-top: 10px">下載錄取/報到通知</a></div><br>
                -->

                @if(!isset($applicant->is_attend) || $applicant->is_attend)
                    <h4>Registration 活動費用</h4>
                    <form class="ml-2 mb-2" action="{{ route('modifyLodging', $batch_id) }}" method="POST" id="selectLodging">
                        @csrf
                        <div class="ml-0 mb-2">
                            Refer to <a href="{{ $camp_data->site_url }}">camp offical site</a>
                            for the Registration fee options.
                        <br>
                            活動費用，請參閱<a href="{{ $camp_data->site_url }}">營隊官網</a>說明。
                        </div>
                        <br>
                        <input type="hidden" name="id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                        <input type="hidden" name="camp" value="nycamp">
                        <input type="hidden" name="nights" value="{{ $applicant->lodging?->nights ?? 0}}">
                        <div class='row form-group required'>
                            <label for='inputRoomType' class='col-md-2 control-label text-md-right'>活動費用</label>
                            <div class="col-md-4">
                                <select required class='form-control' name='room_type' id='inputRoomType' onchange='changeRoom(this)'>
                                    <option value='' selected>- 請選擇 -</option>
                                    @foreach($fare_room as $key => $value)
                                    <option value='{{ $key }}' >{{ $key }}(USD{{ $value }})</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    請選擇活動費用
                                </div>
                            </div>
                        </div>
                        <div class='row form-group companion-sec required' style='display:none'>
                            <label for='inputCompanion' class='col-md-2 control-label text-md-right'>Friend's name 同行者姓名</label>
                            <div class="col-md-4">
                                @if(isset($applicant->companion_name))
                                <input type='text' class='form-control' name="companion_name" id='inputCompanion' value='{{ $applicant->companion_name }}' >
                                @else
                                <input type='text' class='form-control' name="companion_name" id='inputCompanion' value='' >
                                @endif
                                <div class="invalid-feedback">
                                    請提供同行者姓名
                                </div>
                            </div>
                        </div>
                        <input class="btn btn-success" type="submit" value="確認修改活動費用" id="confirmlodging" name="confirmlodging">
                    </form><br>
                    <h4>Shuttle Bus Service 接駁服務</h4>
                    <form class="ml-2 mb-2" action="{{ route('modifyTraffic', $batch_id) }}" method="POST" id="selecttraffic">
                        @csrf
                        <div class="ml-0 mb-2">
                        Shuttle bus service is available for $35 one way per person between Bliss and Wisdom New York Center and Honor's Haven.  Please make payment in advance with your registration fee.<br>
                        1/1 (Thu) @2:00pm Departs BW NY Center to Honor's Haven<br>
                        1/4 (Sun) @3:20PM Departs Honor's Haven to BW NY Center<br>
                        Bliss and Wisdom New York Center（25-10 Ulmer Street, Flushing, NY 11354）<br>
                        Honor's Haven Retreat & Conference（1195 Arrowhead Rd, Ellenville, NY 12428 USA）<br>
                        <br>
                        若需要搭乘紐約市區接駁至禪修莊園的遊覽車，費用每趟USD$35/人。<br>
                        1/1 (四) @2:00PM 巴士：紐約中心 → 禪修莊園<br>
                        1/4 (日) @3:20PM 巴士：禪修莊園 → 紐約中心<br>
                        紐約中心（25-10 Ulmer Street, Flushing, NY 11354）<br>
                        紐約禪修莊園 （1195 Arrowhead Rd, Ellenville, NY 12428 USA）<br>
                        </div>
                        <br>
                        <input type="hidden" name="id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                        <input type="hidden" name="camp" value="nycamp">
                        <div class='row form-group required'>
                            <label for='inputDepartFrom' class='col-md-2 control-label text-md-right'>去程交通</label>
                            <div class="col-md-4">
                                <select required class='form-control' name='depart_from' id='inputDepartFrom'>
                                    <option value='' selected>- 請選擇 -</option>
                                    @foreach($fare_depart_from as $key => $value)
                                    <option value='{{ $key }}' >{{ $key }}(USD{{ $value }})</option>
                                    @endforeach
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
                                    <option value='' selected>- 請選擇 -</option>
                                    @foreach($fare_back_to as $key => $value)
                                    <option value='{{ $key }}' >{{ $key }}(USD{{ $value }})</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    請選擇回程交通
                                </div>
                            </div>
                        </div>
                        <input class="btn btn-success" type="submit" value="確認修改交通" id="confirmtraffic" name="confirmtraffic">
                    </form><br>
                    @php
                        $fare_total = ($traffic?->fare ?? 0) + ($lodging?->fare ?? 0);
                        $sum_total = ($traffic?->sum ?? 0) + ($lodging?->sum ?? 0);
                    @endphp
                    <div class="ml-2 mb-2"><b>Payment Due 應交費用：USD{{ $fare_total }}<br>
                        Payment Received 已交費用：USD{{ $sum_total }}</b></div>
{{--                    
                    @if($traffic?->fare > 0)
                        <form action="{{ route('downloadPaymentForm', $batch_id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="applicant_id" value="{{ $applicant->applicant_id }}">
                            <input type="submit" class="btn btn-primary" value="下載繳費單">
                        </form>
                    @endif
--}}
                @endif
                    <h4>Cancellation 放棄參加</h4>
                    <form class="ml-2 mb-2" action="{{ route('toggleAttend', $batch_id) }}" method="POST" id="attendcancel">
                        @csrf
                        <input type="hidden" name="id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                        <input type="hidden" name="camp" value="nycamp">
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
                <br>
                <h4>Contact 聯絡我們</h4>
                <div class="ml-0 mb-2">If you have any question, feel free to contact</div>
                <div class="ml-2 mb-2">Jasmine Hu</div>
                <div class="ml-2 mb-2">Email: chunhu@blisswisdom.org</div>
                <div class="ml-2 mb-2">Phone: (902)808-0069</div>
                <div class="ml-2 mb-2">Online Service: https://lin.ee/8iOmovI</div>
                <br>
                <div class="ml-0 mb-2">如果您有任何問題，請聯絡</div>
                <div class="ml-2 mb-2">胡純</div>
                <div class="ml-2 mb-2">Email: chunhu@blisswisdom.org</div>
                <div class="ml-2 mb-2">洽詢電話(北美地區)：(902)808-0069</div>
                <div class="ml-2 mb-2">線上客服：https://lin.ee/8iOmovI</div>

                <p class="card-text text-right">The Oneness Truth Foundation</p>
                <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
            @elseif($applicant->created_at->gte(\Carbon\Carbon::parse('2025-06-11 00:00:00')))
                <!-----錄取中----->
                <p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
                <p class="card-text indent">感謝您報名「{{ $camp_data->fullName }}」，錄取作業正在進行中，請稍後再進行錄取查詢。感謝您的耐心等待！</p>
                <p class="card-text text-right">The Oneness Truth Foundation 敬啟</p>
                <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
            @elseif($applicant->deleted_at)
            @else
                <!--
                <p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
                <p class="card-text indent">感謝您報名「{{ $camp_data->fullName }}」，錄取作業正在進行中，請稍後再進行錄取查詢。感謝您的耐心等待！</p>
                <p class="card-text text-right">The Oneness Truth Foundation 敬啟</p>
                <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
                -->
                <!-----備取=不錄取----->
                <p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
                <p class="card-text indent">非常感謝您報名參加「{{ $camp_data->fullName }}」，由於本活動報名人數踴躍，且場地有限，非常抱歉未能在第一階段錄取您。我們已將您列入優先備取名單，若有遞補機會，基金會將儘速通知您!</p>
                <p class="card-text indent">開學後，各區福青學堂定期都有精彩的課程活動，竭誠歡迎您的參與!也祝福您學業順利，吉祥如意！</p>
                <h4>各區福青學堂資訊</h4>
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="card-text">
                            台北福青學堂<br>
                            02-2545-3788 #546<br>
                            台北市松山區南京東路四段161號9樓<br>
                            </p>
                            <p class="card-text">
                            桃園福青學堂<br>
                            03-275-6133 #1314<br>
                            桃園市中壢區強國路121號2樓<br>
                            </p>
                            <p class="card-text">
                            新竹福青學堂<br>
                            03-571-0968<br>
                            新竹市東區忠孝路43號2樓<br>
                            </p>
                            <p class="card-text">
                            台中福青學堂<br>
                            04-37069300 #621101<br>
                            台中市西屯區臺灣大道二段669號2樓<br>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="card-text">
                            雲嘉福青學堂<br>
                            05-5370133 #125<br>
                            雲林縣斗六市慶生路6號<br>
                            </p>
                            <p class="card-text">
                            台南福青學堂<br>
                            06-289-6558<br>
                            台南市東區崇明路405號4樓<br>
                            </p>
                            <p class="card-text">
                            高雄福青學堂<br>
                            07-974-1170<br>
                            高雄市新興區中正四路53號12樓之7<br>
                            </p>
                            <p class="card-text">
                            花蓮大專班籌備處<br>
                            03-831-6307<br>
                            花蓮市中華路243號2樓<br>
                            </p>
                        </div>
                    </div>
                </div>
                <!--
                <p class="card-text indent"><a href="http://bwfoce.org/web" target="_blank" rel="noopener noreferrer">http://bwfoce.org/web</a></p>
                <p class="card-text indent">祝福您身心健康，吉祥如意！</p>
                -->
                <p class="card-text text-right">The Oneness Truth Foundation 敬啟</p>
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
                if(confirm('確認修改交通？')){
                        return true;
                }
                event.preventDefault();
                return false;
            });

            {{-- 回填交通選項 --}}
            (function() {
                let traffic_data = JSON.parse('{!! $traffic ?? '{}' !!}');
                let selects = document.getElementsByTagName('select');
                for (var i = 0; i < selects.length; i++){
                    if(typeof traffic_data[selects[i].name] !== "undefined"){
                        selects[i].value = traffic_data[selects[i].name];
                    }
                }
            })();
        @endif
        @if(!isset($applicant->is_attend) || $applicant->is_attend)
            let confirmlodging = document.getElementById('confirmlodging');
            confirmlodging.addEventListener('click', function(event) {
                if(confirm('確認修改活動費？')){
                        return true;
                }
                event.preventDefault();
                return false;
            });

            {{-- 回填活動費選項 --}}
            (function() {
                let lodging_data = JSON.parse('{!! $lodging ?? '{}' !!}');
                let selects = document.getElementsByTagName('select');
                for (var i = 0; i < selects.length; i++){
                    if(typeof lodging_data[selects[i].name] !== "undefined"){
                        selects[i].value = lodging_data[selects[i].name];
                        changeRoom(selects[i]);
                    }
                }
            })();
        @endif
        function changeRoom(select_ele) {
            const companionSection = document.getElementsByClassName('companion-sec')[0];
            const companionInput = document.getElementById('inputCompanion');

            if (select_ele.value.includes("兩人同行")) {
                // 有同行者
                companionSection.style.display = '';
                companionInput.required = true;
            } else {
                // 一人報名
                companionSection.style.display = 'none';
                companionInput.required = false;
            }
        };
    </script>
@stop
