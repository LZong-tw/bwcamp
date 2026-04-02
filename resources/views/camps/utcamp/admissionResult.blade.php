<style>
    u{
        color: red;
    }
</style>
@extends('camps.' . $camp_info->table . '.layout')
@section('content')
    @php
        $now = \Carbon\Carbon::now();
    @endphp
    @if(Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get("error") }}
        </div>
    @endif
    <br>
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
<!--研習證明可供下載後，就隱藏錄取查詢-->
@if($camp_data->certificate_available_date && $now->gte($camp_data->certificate_available_date))
    <div class="card">
        <div class="card-header">
            <h5>研習證明下載</h5>
        </div>
        <div class="card-body">
        @if($applicant->is_admitted && $applicant->utcamp->is_bwfoce_certificate)
            <p><a href="https://bwcamp.bwfoce.org/downloads/{{ $camp_data->table }}{{ $camp_data->year }}/{{ $applicant->group }}{{ $applicant->number }}{{ $applicant->applicant_id }}.pdf" target="_blank" rel="noopener noreferrer" class="btn btn-light">下載福智文教基金會研習數位證明書</a></p>       
            <p>如下載顯示錯誤，請聯絡您的帶組老師，謝謝！</p>
        @else
            <p>您沒有研習證明可供下載（可能是未登記或未參加完全程）</p>
            <p>如有疑問，請聯絡您的帶組老師，謝謝！</p>
        @endif
            <br>
            <p class="card-text text-right">財團法人福智文教基金會　謹此&emsp;<br>
            {{ $now->format('Y 年 n 月 j 日') }}&emsp;</p>
            <p><input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a></p>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-header">
            <h5>錄取查詢</h5>
        </div>
        <div class="card-body">
            @if($applicant->is_admitted && !$applicant->deleted_at)
                <p class="card-text">親愛的 {{ $applicant->name }} 您好！</p>
				<p class="card-text text-indent">恭喜您錄取「{{ $camp_data->fullName }}」，我們竭誠歡迎您的到來，請留意以下資訊，期望您能留下最美好的回憶。</p>
                @if(!isset($applicant->is_attend) || $applicant->is_attend)
                    <p class="card-text text-indent">
                        您的報名序號：{{ $applicant->id }}<br>
                        您的錄取編號：{{ $applicant->group }}{{ $applicant->number }}<br>
                        營隊期間：{{ $applicant->batch->batch_start }} ({{ $applicant->batch->batch_start_weekday }}) ~ {{ $applicant->batch->batch_end }} ({{ $applicant->batch->batch_end_weekday }})，共三天兩夜<br>
                        營隊地點：{{ $applicant->batch->locationName }} ({{ $applicant->batch->location }})<br>
                    </p>
                    @php
                        $fare_total = ($applicant->traffic?->fare ?? 0) + ($applicant->lodging?->fare ?? 0);
                        $sum_total = 
                        ($applicant->traffic?->deposit ?? 0) + ($applicant->traffic?->cash ?? 0)
                        + ($applicant->lodging?->deposit ?? 0) + ($applicant->lodging?->cash ?? 0);
                    @endphp
                    <h5>錄取/報到通知</h5>
                    <div class="ml-0 mb-2">
                        依據您報名時的選項：<b>{{ $applicant->lodging->room_type }}</b>，您的應繳費用/已交費用為：
                        <div class="ml-2 mb-2 alert alert-info" role='alert'>
                        <b>
                        您的銷帳編號（個人專屬繳費帳號）：{{ $applicant->bank_second_barcode }}<br>
                        =====&nbsp;&nbsp;&nbsp;應交費用：{{ $fare_total }}&nbsp;&nbsp;&nbsp;=====<br>
                        =====&nbsp;&nbsp;&nbsp;已交費用：{{ $sum_total }}&nbsp;&nbsp;&nbsp;=====<br>
                        </b>
                        </div>
                        <div class="ml-2 mb-2 alert alert-secondary" role='alert'>
                        <b>【繳費方式】</b><br>
                        1.臨櫃匯款：<br>
                        &emsp;&emsp;收款行 = 上海商業儲蓄銀行南京東路分行<br>
                        &emsp;&emsp;銀行代碼 = 0110406<br>
                        &emsp;&emsp;戶名 = 財團法人福智文教基金會<br>
                        &emsp;&emsp;帳號 = 銷帳編號 <span style="color: green"><b>{{ $applicant->bank_second_barcode }}</b></span><br>
                        &emsp;&emsp;<u>手續費：全台上海商業儲蓄銀行免手續費；其他銀行須自付手續費</u><br>
                        2.ATM 轉帳：選擇「轉帳」或「繳費」<br>
                        &emsp;&emsp;→ 輸入上海銀行 代號011<br>
                        &emsp;&emsp;→ 輸入銷帳編號 <span style="color: green"><b>{{ $applicant->bank_second_barcode }}</b></span><br>
                        &emsp;&emsp;→ 輸入應繳金額 <span style="color: brown"><b>{{ $applicant->lodging->fare }}</b></span><br>
                        &emsp;&emsp;<u>跨行轉帳須支付手續費</u>。<br>
                        <br>
                        <span style="color: red">
                        ＊＊＊請勿使用他人的繳款帳號＊＊＊<br>
                        ＊＊＊請勿使用您的繳款帳號代替他人繳款＊＊＊<br>
                        </span>
                        </div>
                        請詳閱 <a href="{{ $camp_data->content_link_chn }}" target="_blank">錄取通知</a>，內含繳費、交通、住宿相關資訊。<br>
                        <button type="button" class="btn btn-light" onclick=toggleModifySection(this)>
                            按這裡修改研習證明/交通/住宿選項
                        </button>
                    </div>
                    <br>
                    <div class="container alert alert-warning modify-sec" style="display: none;">
                        <h5>修改 研習證明/交通/住宿 選項</h5>
                        <form class="ml-2 mb-2" action="{{ route('modifyAfterAdmitted', $batch_id) }}" method="POST" id="modifyafteradmitted">
                            @csrf
                            <input type="hidden" name="applicant_id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                            <input type="hidden" name="camp_table" value="utcamp">
                            <input type="hidden" name="nights" value="{{ $applicant->lodging?->nights ?? 0}}">

                            @include('camps.' . $camp_info->table . '.formSection')

                            <input class="btn btn-success" type="submit" value="確認修改" id="confirmafteradmitted" name="confirmafteradmitted">
                        </form>
                    </div>
                @endif

                <h5>確認/取消參加</h5>
                <form class="ml-2 mb-2" action="{{ route('toggleAttend', $batch_id) }}" method="POST" id="attendcancel">
                    @csrf
                    <input type="hidden" name="id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                    <input type="hidden" name="camp" value="utcamp">
                    @if(!isset($applicant->is_attend) || $applicant->is_attend )
                        <div class="ml-0 mb-2 text-primary">您目前的狀態是「參加」。</div>
                        <div class="ml-0 mb-2">如您因故無法參加，請按下面「放棄參加」通知我們，謝謝！</div>
                        <div>
                        <input class="btn btn-danger" type="submit" value="放棄參加" id="cancel" name="cancel">
                        </div>
                    @else
                        <div class="ml-0 mb-2 text-danger">很遺憾，您目前的狀態是「放棄參加」。</div>
                        <div class="ml-0 mb-2">如您可以參加了，請按恢復參加，謝謝！</div>
                        <div>
                        <input class="btn btn-success" type="submit" value="恢復參加" id="confirmattend" name="confirmattend">
                        </div>
                    @endif
                </form>
                <br>
            @elseif($now->gte($camp_data->rejection_showing_date))
                <!-----備取=不錄取----->
                <p class="card-text">親愛的 {{ $applicant->name }} 老師您好</p>
                <p class="card-text indent">非常感謝您報名參加「{{ $camp_data->fullName }}」，由於本活動報名人數踴躍，且場地有限，非常抱歉未能在第一階段錄取您。我們已將您列入優先備取名單，若有遞補機會，基金會將儘速通知您!</p>
                <p class="card-text indent">開學後，各區福青學堂定期都有精彩的課程活動，竭誠歡迎您的參與!也祝福您學業順利，吉祥如意！</p>
                <h5>各區福青學堂資訊</h5>
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
            @else
                <!-----錄取中----->
                <p class="card-text">親愛的 {{ $applicant->name }} 老師您好</p>
                <p class="card-text indent">感謝您報名「{{ $camp_data->fullName }}」，錄取作業正在進行中，請稍後再進行錄取查詢。感謝您的耐心等待！</p>
            @endif
            <p>財團法人福智文教基金會<br>
            {{ $now->format('Y 年 n 月 j 日') }}<br><br>
            {!! nl2br(e(str_replace('\n', "\n", $applicant->batch->contact_card))) !!}</p>

            <p><input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a></p>
        </div>
    </div>
@endif
    <script>
        function toggleModifySection(ele) {
            const modifySection = document.getElementsByClassName('modify-sec')[0];
            console.log(this);
            if (modifySection.style.display == 'none') {
                modifySection.style.display = '';
                ele.innerText = "按這裡隱藏「研習證明/交通/住宿選項」";
            } 
            else {
                modifySection.style.display = 'none';
                ele.innerText = "按這裡修改研習證明/交通/住宿選項";
            }
        }

        @if(!isset($applicant->is_attend) || $applicant->is_attend)
            {{-- 修改確認 --}}
            let cancel = document.getElementById('cancel');
            if (cancel)
                cancel.addEventListener('click', function(event) {
                    if(confirm('確認放棄參加？')){
                        return true;
                    }
                    event.preventDefault();
                    return false;
                });
            let confirmafteradmitted = document.getElementById('confirmafteradmitted');
            if (confirmafteradmitted) {
                confirmafteradmitted.addEventListener('click', function(event) {
                    if(confirm('確認修改？')){
                            return true;
                    }
                    event.preventDefault();
                    return false;
                });
            }
            {{-- 回填 --}}
            (function() {
                let applicant_data = JSON.parse('{!! $applicant_data ?? '{}' !!}');  //includes xcamp, lodging, traffic
                
                const form = document.getElementById('modifyafteradmitted');
                let inputs = form.getElementsByTagName('input');
                let selects = form.getElementsByTagName('select');
                
                if (selects) {
                    for (var i = 0; i < selects.length; i++) {
                        if (typeof applicant_data[selects[i].name] !== "undefined"){
                            selects[i].value = applicant_data[selects[i].name];
                            selects[i].dispatchEvent(new Event('change'));
                        }   
                    }
                }
                if (inputs) {
                    for (var i = 0; i < inputs.length; i++) {
                        if(typeof applicant_data[inputs[i].name] !== "undefined"){
                            if(inputs[i].type == "radio"){
                                let radios = document.getElementsByName(inputs[i].name);
                                for( j = 0; j < radios.length; j++ ) {
                                    if( radios[j].value == applicant_data[inputs[i].name] ) {
                                        radios[j].checked = true;
                                        radios[j].dispatchEvent(new Event('click'));
                                    }
                                }
                            }
                            else if(inputs[i].type == "text"){
                                inputs[i].value = applicant_data[inputs[i].name];
                            }
                        }
                    }
                }
            })();
        @else
            let confirmattend = document.getElementById('confirmattend');
            if (confirmattend) {
                confirmattend.addEventListener('click', function(event) {
                    if(confirm('confirm 確認恢復參加？')){
                        return true;
                    }
                    event.preventDefault();
                    return false;
                });
            }
        @endif
    </script>
@stop
