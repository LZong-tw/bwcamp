<style>
    u{
        color: red;
    }
</style>
@extends('camps.utcamp.layout')
@section('content')
    @if(Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get("error") }}
        </div>
    @endif
    <br>
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
{{--
    @if($applicant->is_admitted)
        <div class="card">
            <div class="card-header">
                <h5>研習證明下載</h5>
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
            <h5>錄取查詢</h5>
        </div>
        <div class="card-body">
            @if($applicant->is_admitted && !$applicant->deleted_at)
                <p class="card-text">{{ $applicant->name }} 您好！</p>
				<p class="card-text text-indent">恭喜您錄取「{{ $camp_data->fullName }}」，竭誠歡迎您的到來。<br>
				我們將於營隊三周前寄發「報到通知單」，提供營隊相關訊息，請記得到電子信箱收信。<br>
				期待與您共享這場心靈的饗宴，預祝您歡喜學習，收穫滿滿。<br>
				&emsp;&emsp;敬祝<br>
				教安<br>
                </p>
                <p class="card-text text-indent">
                您的報名序號：{{ $applicant->applicant_id }}<br>
                您的錄取編號：{{ $applicant->group }}{{ $applicant->number }}<br>
                營隊期間：{{ $applicant->batch->batch_start }} ({{ $applicant->batch->batch_start_weekday }}) ~ {{ $applicant->batch->batch_end }} ({{ $applicant->batch->batch_end_weekday }})，共4天<br>
                營隊地點：{{ $applicant->batch->locationName }} ({{ $applicant->batch->location }})<br>
                </p>

                <h5>錄取/報到通知</h5>
                <div class="ml-0 mb-2">
                請詳閱 <a href="{{ $camp_data->content_link_chn }}" target="_blank">錄取通知</a>，內含報到資訊、必帶物品，及交通資訊等等。<br>
                </div>
                <br>
@if(!isset($applicant->is_attend) || $applicant->is_attend)
<div class="container alert alert-warning">
                <form class="ml-2 mb-2" action="{{ route('modifyAfterAdmitted', $batch_id) }}" method="POST" id="modifyafteradmitted">
                    @csrf
                    <input type="hidden" name="applicant_id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                    <input type="hidden" name="camp_table" value="utcamp">
                    <input type="hidden" name="nights" value="{{ $applicant->lodging?->nights ?? 0}}">

                    <h5>活動費用/住宿選項</h5>
                        <div class="ml-0 mb-2">
                        請參閱您的 <a href="https://docs.google.com/document/d/1t56h4BsWBqC_r38rtGekn24GQDW8_2oA/edit" target="_blank">錄取通知</a>。其中有關於活動費用的詳細說明。
                        </div>
                        <br>
                        <div class='row form-group required'>
                            <label for='inputRoomType' class='col-md-2 control-label text-md-right'>活動費用</label>
                            <div class="col-md-4">
                                <select required class='form-control' name='room_type' id='inputRoomType' onchange='changeRoom(this)'>
                                    <option value='' selected>- 請選擇 -</option>
                                    @foreach($fare_room as $key => $value)
                                    <option value='{{ $key }}' >{{ $key }}({{ $value }})</option>
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
                                    Please provide your companion's name. 請提供同行者姓名
                                </div>
                            </div>
                        </div>
                    <h5>接駁服務</h5>
                        <div class="ml-0 mb-2">
                        若需要 {{ $applicant->batch->batch_start }} 新竹高鐵接駁，接駁車會由12:30發車
                        </div>
                        <br>
                        <div class='row form-group required'>
                            <label for='inputDepartFrom' class='col-md-2 control-label text-md-right'>去程交通</label>
                            <div class="col-md-4">
                                <select required class='form-control' name='depart_from' id='inputDepartFrom'>
                                    <option value='' selected>- 請選擇 -</option>
                                    @foreach($fare_depart_from as $key => $value)
                                    <option value='{{ $key }}' >{{ $key }}</option>
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
                                    <option value='{{ $key }}' >{{ $key }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    請選擇回程交通
                                </div>
                            </div>
                        </div>
                    @php
                        $fare_total = ($traffic?->fare ?? 0) + ($lodging?->fare ?? 0);
                        $sum_total = ($traffic?->sum ?? 0) + ($lodging?->sum ?? 0);
                    @endphp
                    <div class="ml-2 mb-2 alert alert-info" role='alert'>
                        <b>
                        =====&nbsp;&nbsp;&nbsp;應交費用：{{ $fare_total }}&nbsp;&nbsp;&nbsp;=====<br>
                        =====&nbsp;&nbsp;&nbsp;已交費用：{{ $sum_total }}&nbsp;&nbsp;&nbsp;=====<br>
                        </b>
                    </div><br>
                    <h5>研習證明&活動發票</h5>
                        <div class='row form-group required'>
                            <label for='inputIsCivilCertificate' class='col-md-2 control-label text-md-right'>是否申請公務員研習時數</label>
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" name="is_civil_certificate" value="1" required id='inputIsCivilCertificate' onchange="id_setRequired(this)">
                                        是
                                        <div class="invalid-feedback">
                                            請選擇一項
                                        </div>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" name="is_civil_certificate" value="0" required id='inputIsNotCivilCertificate' onchange="id_setRequired(this)">
                                        否
                                        <div class="invalid-feedback">
                                            &nbsp;
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class='row form-group idno-sec required' style="display: none;">
                            <label for='inputID' class='col-md-2 control-label text-md-right'>身份證字號</label>
                            <div class='col-md-10'>
                                <input type='text' name='idno' value='' class='form-control' id='inputID' placeholder='僅作為申請研習時數用'>
                                <div class="invalid-feedback">
                                    未填寫身份證字號（申請時數或研習證明用）
                                </div>
                            </div>
                        </div>
                        <div class='row form-group required'>
                            <label for='inputIsBwfoceCertificate' class='col-md-2 control-label text-md-right'>是否申請基金會研習數位證明書</label>
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" name="is_bwfoce_certificate" value="1" required>
                                        是
                                        <div class="invalid-feedback">
                                            請選擇一項
                                        </div>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" name="is_bwfoce_certificate" value="0" required>
                                        否
                                        <div class="invalid-feedback">
                                            &nbsp;
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class='row form-group required'>
                            <label for='inputInvoiceType' class='col-md-2 control-label text-md-right'>活動費發票開立</label>
                            <div class='col-md-10'>
                                <select required class='form-control' name='invoice_type' id='inputInvoiceType' onchange="taxid_setRequired(this)">
                                    <option value="">- 請選擇 -</option>
                                    <option value="單位發票">單位發票</option>
                                    <option value="個人發票">個人發票</option>
                                    <option value="捐贈福智文教基金會">捐贈福智文教基金會</option>
                                </select>
                                <div class="invalid-feedback">
                                    未選擇活動費發票開立
                                </div>
                            </div>
                        </div>

                        <div class='row form-group taxid-sec required' style="display: none;">
                            <label for='inputTaxID' class='col-md-2 control-label text-md-right'>統一編號</label>
                            <div class='col-md-10'>
                                <input type='text' name='taxid' value='' class='form-control' id='inputTaxID' placeholder='開立活動費發票用'>
                                <div class="invalid-feedback">
                                    未填寫統一編號
                                </div>
                            </div>
                        </div>

                        <div class='row form-group invoice-title-sec required' style="display: none;">
                            <label for='inputInvoiceTitle' class='col-md-2 control-label text-md-right'>抬頭</label>
                            <div class='col-md-10'>
                                <input type='text' name='invoice_title' value='' class='form-control' id='inputInvoiceTitle' placeholder='開立活動費發票用'>
                                <div class="invalid-feedback">
                                    未填寫抬頭
                                </div>
                            </div>
                        </div>

                    <input class="btn btn-success" type="submit" value="確認修改" id="confirmafteradmitted" name="confirmafteradmitted">
                    </form>
</div>
@endif

                    <h5>確認參加</h5>
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
                            <div class="ml-0 mb-2 text-danger">您目前的狀態是「放棄參加」。</div>
                            <div class="ml-0 mb-2">如您可以參加了，請按恢復參加，謝謝！</div>
                            <div>
                            <input class="btn btn-success" type="submit" value="恢復參加" id="confirmattend" name="confirmattend">
                            </div>
                        @endif
                    </form><br>
                <h5>Contact 聯絡我們</h5>
                <div class="ml-0 mb-2">洽詢專線<br>
+               {!! nl2br(e(str_replace('\n', "\n", $applicant->batch->contact_card))) !!}
                </div>
                <p class="card-text text-right">財團法人福智文教基金會　謹此&emsp;&emsp;<br>
                {{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}&emsp;&emsp;</p>
            @elseif($applicant->created_at->gte($pplicant->batch->camp->admission_announcing_date)))
                <!-----錄取中----->
                <p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
                <p class="card-text indent">感謝您報名「{{ $camp_data->fullName }}」，錄取作業正在進行中，請稍後再進行錄取查詢。感謝您的耐心等待！</p>
                <p class="card-text text-right">財團法人福智文教基金會　謹此<br>
                {{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}</p>
            @elseif($applicant->deleted_at)
            @else
                <!-----備取=不錄取----->
                <p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
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
                <!--
                <p class="card-text indent"><a href="http://bwfoce.org/web" target="_blank" rel="noopener noreferrer">http://bwfoce.org/web</a></p>
                <p class="card-text indent">祝福您身心健康，吉祥如意！</p>
                -->
                <p class="card-text text-right">The Oneness Truth Foundation 敬啟</p>
                <p class="card-text text-right">{{ \Carbon\Carbon::now()->format('Y 年 n 日 j 日') }}</p>
            @endif
            <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">home 回營隊首頁</a>
        </div>
    </div>

    <script>
        @if(!isset($applicant->is_attend) || $applicant->is_attend)
            {{-- 修改確認 --}}
            let cancel = document.getElementById('cancel');
            if (cancel)
                cancel.addEventListener('click', function(event) {
                    if(confirm('confirm cancellation 確認放棄參加？')){
                        return true;
                    }
                    event.preventDefault();
                    return false;
                });
            let confirmtraffic = document.getElementById('confirmtraffic');
            if (confirmtraffic) {
                confirmtraffic.addEventListener('click', function(event) {
                    if(confirm('confirm 確認修改交通？')){
                            return true;
                    }
                    event.preventDefault();
                    return false;
                });
            }
            let confirmlodging = document.getElementById('confirmlodging');
            if (confirmlodging) {
                confirmlodging.addEventListener('click', function(event) {
                    if(confirm('confirm 確認修改活動費？')){
                            return true;
                    }
                    event.preventDefault();
                    return false;
                });
            }
            {{-- 回填 --}}
            (function() {
                let traffic_data = JSON.parse('{!! $traffic ?? '{}' !!}');
                let lodging_data = JSON.parse('{!! $lodging ?? '{}' !!}');
                let applicant_data = JSON.parse('{!! $applicant ?? '{}' !!}');

                let inputs = document.getElementsByTagName('input');
                let selects = document.getElementsByTagName('select');
                if (selects) {
                    for (var i = 0; i < selects.length; i++) {
                        if(typeof lodging_data[selects[i].name] !== "undefined"){
                            selects[i].value = lodging_data[selects[i].name];
                            changeRoom(selects[i]);
                        } else if (typeof traffic_data[selects[i].name] !== "undefined"){
                            selects[i].value = traffic_data[selects[i].name];
                        } else if (typeof applicant_data[selects[i].name] !== "undefined"){
                            selects[i].value = applicant_data[selects[i].name];
                            selects[i].dispatchEvent(new Event('change'));
                            //if (selects[i].id == "inputInvoiceType") {
                            //    taxid_setRequired(selects[i]);
                            //}
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
                                        radios[j].dispatchEvent(new Event('change'));
                                        //if (radios[j].id == "inputIsCivilCertificate" || radios[j].id == "inputIsNotCivilCertificate") {
                                        //    id_setRequired(radios[j]);
                                        //}
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

        //----- 處理欄位出現與否 -----
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
        function taxid_setRequired(ele) {
            const taxidSection = document.getElementsByClassName('taxid-sec')[0];
            const invoiceTitleSection = document.getElementsByClassName('invoice-title-sec')[0];
            const taxidInput = document.getElementById('inputTaxID');
            const invoiceTitleInput = document.getElementById('inputInvoiceTitle');

            if(ele.value == "單位發票") {
                taxidSection.style.display = '';
                invoiceTitleSection.style.display = '';
                taxidInput.required = true;
                invoiceTitleInput.required = true;
            }
            else {
                taxidSection.style.display = 'none';
                invoiceTitleSection.style.display = 'none';
                taxidInput.required = false;
                invoiceTitleInput.required = false;
            }
        }
        function id_setRequired(ele) {
            const idnoSection = document.getElementsByClassName('idno-sec')[0];
            const idnoInput = document.getElementById('inputID');

            //if(ele.value == "一般教師研習時數" || ele.value == "公務員研習時數") {
            if(ele.value == "1") {
                idnoSection.style.display = '';
                idnoInput.required = true;
            }
            else {
                idnoSection.style.display = 'none';
                idnoInput.required = false;
            }
        }
    </script>
@stop
