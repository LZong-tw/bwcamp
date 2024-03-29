@php
    header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
    $regions = ['北區', '竹區', '中區', '高區'];
@endphp
@extends('camps.evcamp.layout')
@section('content')
    @include('partials.counties_areas_script')
{{--
    <div class='alert alert-info' role='alert'>
        您在本網站所填寫的個人資料，僅用於此次企業營的報名及活動聯絡之用。
    </div>
--}}
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}線上報名表</h4>
    </div>

{{-- !isset($isModify): 沒有 $isModify 變數，即為報名狀態；只在報名時提供載入舊資料選項--}}
    @if(!isset($isModify) && !isset($batch_id_from))
    <hr>
    <h5 class='form-control-static text-warning bg-secondary'>若您曾報名2023年企業營義工，請點選下面連結，查詢並使用2023年企業營義工報名資料<br>
    <a href="{{ route('query', 103) }}?batch_id_from={{ $batch_id }}" class="text-warning bg-secondary"><u>台北</u>　</a>
    <a href="{{ route('query', 124) }}?batch_id_from={{ $batch_id }}" class="text-warning bg-secondary"><u>桃園</u>　</a>
    <a href="{{ route('query', 125) }}?batch_id_from={{ $batch_id }}" class="text-warning bg-secondary"><u>新竹</u>　</a>
    <a href="{{ route('query', 126) }}?batch_id_from={{ $batch_id }}" class="text-warning bg-secondary"><u>中區</u>　</a>
    <a href="{{ route('query', 127) }}?batch_id_from={{ $batch_id }}" class="text-warning bg-secondary"><u>雲嘉</u>　</a>
    <a href="{{ route('query', 122) }}?batch_id_from={{ $batch_id }}" class="text-warning bg-secondary"><u>台南</u>　</a>
    <a href="{{ route('query', 128) }}?batch_id_from={{ $batch_id }}" class="text-warning bg-secondary"><u>高雄</u></a>
    </h5>
    <hr>
    @endif

    {{-- 使用舊資料報名：如果有batch_id_from參數的話 --}}
    @if(isset($batch_id_from))
    <hr>
    <form action="{{ route('formCopy', $batch_id_from) }}" method="POST">
        @csrf
        <input type="hidden" name="batch_id_ori" value="{{ $batch_id }}">
        <input type="hidden" name="batch_id_copy" value="{{ $batch_id_from }}">
        <input type="hidden" name="applicant_id_ori" value="{{ $applicant_id }}">
        <input type="submit" class="btn btn-success" value="使用此資料報名{{ $camp_abbr_from }}">
    </form>
    <hr>
    @endif

{{-- !isset($isModify): 沒有 $isModify 變數，即為報名狀態、 $isModify: 修改資料狀態 --}}
@if(!isset($isModify) || $isModify)
    <form method='post' action='{{ route('formSubmit', [$batch_id]) }}' id='Camp' name='Camp' class='form-horizontal needs-validation' role='form' enctype="multipart/form-data">
{{-- 以上皆非: 檢視資料狀態 --}}
@else
    <form action="{{ route("queryupdate", $applicant_batch_id) }}" method="post" class="d-inline">
@endif
    @csrf
    <div class='row form-group'>
        <div class='col-md-2'></div>
        <div class='col-md-10'>
            <span class='text-danger'>＊必填</span>
        </div>
    </div>
    <div class='row form-group'>
        <label for='inputBatch' class='col-md-2 control-label text-md-right'>營隊梯次</label>
        <div class='col-md-10'>
            @if(isset($applicant_data) && !isset($useOldData2Register))
                <h3>{{ $applicant_raw_data->batch->name }} {{ $applicant_raw_data->batch->batch_start }} ~ {{ $applicant_raw_data->batch->batch_end }} </h3>
                <input type='hidden' name='applicant_id' value='{{ $applicant_id }}'>
                <input type="hidden" name="region" value="@foreach($regions as $r) @if(\Str::contains($applicant_raw_data->batch->name, $r)){{ $r }} @break @endif @endforeach">
            @else
                <h3>{{ $batch->name }} {{ $batch->batch_start }} ~ {{ $batch->batch_end }} </h3>
                <input type="hidden" name="region" value="@foreach($regions as $r) @if(\Str::contains($batch->name, $r)){{ $r }} @break @endif @endforeach">
            @endif
        </div>
    </div>
    {{-- 如果有useOldData2Register 變數，表示用舊資料新報名，不顯示報名日期 --}}
    @if(isset($isModify) && !isset($useOldData2Register))
        <div class='row form-group'>
            <label for='inputBatch' class='col-md-2 control-label text-md-right'>報名日期</label>
            <div class='col-md-10'>
                {{ $applicant_raw_data->created_at }}
            </div>
        </div>
    @endif

    <hr>
    <h5 class='form-control-static text-info'>說明：我們非常重視您的志願選擇，但也會考量營隊人力需求來分配組別，尚請多多理解。感恩！</h5>

    <div class='row form-group required'>
        <label for='inputGroupPriority1' class='col-md-2 control-label text-md-right'>報名組別第1志願</label>
        <div class='col-md-10'>
            <select required class='form-control' name='group_priority1' id='inputGroupPriority1' onChange='showFields();'>
                <option value='' selected>- 請選擇 -</option>
                @if(str_contains($batch->name, "第一梯"))
                {{-- 總部企劃、秘書組、教務組、關懷組、總務組、公關組、餐飲組、福青組、依營隊需求安排 --}}
                    <option value='總部企劃' >總部企劃</option>
                    <option value='秘書組' >秘書組</option>
                    <option value='教務組' >教務組</option>
                    <option value='關懷組' >關懷組</option>
                    <option value='總務組' >總務組</option>
                    <option value='公關組' >公關組</option>
                    <option value='餐飲組' >餐飲組</option>
                    <option value='福青組' >福青組</option>
                @elseif(\Str::contains($batch->name, "桃園"))
                    <option value='秘書：義工' >秘書：義工</option>
                    <option value='秘書：財務' >秘書：財務</option>
                    <option value='秘書：行政' >秘書：行政</option>
                    <option value='秘書：資訊' >秘書：資訊</option>
                    <option value='文宣採訪：文字採訪' >文宣採訪：文字採訪</option>
                    <option value='文宣採訪：活動記錄' >文宣採訪：活動記錄</option>
                    <option value='教務：課務教材' >教務：課務教材</option>
                    <option value='教務：課務行政' >教務：課務行政</option>
                    <option value='教務：展覽' >教務：展覽</option>
                    <option value='教務：視聽' >教務：視聽</option>
                    <option value='教務：主持人' >教務：主持人</option>
                    <option value='關懷：報名報到' >關懷：報名報到</option>
                    <option value='關懷：關懷行政' >關懷：關懷行政</option>
                    <option value='關懷：關懷服務' >關懷：關懷服務</option>
                    <option value='關懷：關懷員' >關懷：關懷員</option>
                    <option value='總務：場地' >總務：場地</option>
                    <option value='總務：交通' >總務：交通</option>
                    <option value='總務：資材' >總務：資材</option>
                    <option value='總務：餐飲' >總務：餐飲</option>
                    <option value='總務：環保' >總務：環保</option>
                    <option value='總務：保健' >總務：保健</option>
                    <option value='總務：機動' >總務：機動</option>
                    <option value='公關：外接待' >公關：外接待</option>
                @else
                    <option value='總部企劃' >總部</option>
                    <option value='秘書組' >秘書組</option>
                    <option value='資訊組' >資訊組</option>
                    <option value='教務組' >教務組</option>
                    <option value='關懷組' >關懷組</option>
                    <option value='總務組' >總務組</option>
                    <option value='公關組' >公關組</option>
                    <option value='海外組' >海外組</option>
                    @if(\Str::contains($batch->name, "中區"))
                    <option value='餐飲組' >餐飲組</option>
                    @endif
                    @if(\Str::contains($batch->name, "高雄"))
                    <option value='節目組' >節目組</option>
                    @endif
                    <option value='報到組義工' >報到組義工</option>
                @endif
                <option value='依營隊需求安排' >依營隊需求安排</option>
            </select>
            <div class="invalid-feedback">
                請選擇報名組別第1志願
            </div>
        </div>
    </div>

    <input type='hidden' name='recruit_channel' id='inputRecruitCh' value='自招'>

{{--
    <div class='row form-group'>
        <label for='inputGroupPriority2' class='col-md-2 control-label text-md-right'>報名組別第2志願</label>
        <div class='col-md-10'>
            <select class='form-control' name='group_priority2' onChange=''>
                <option value='' selected>- 請選擇 -</option>
                @if(\Str::contains($batch->name, "桃園"))
                    <option value='秘書：義工' >秘書：義工</option>
                    <option value='秘書：財務' >秘書：財務</option>
                    <option value='秘書：行政' >秘書：行政</option>
                    <option value='秘書：資訊' >秘書：資訊</option>
                    <option value='文宣採訪：文字採訪' >文宣採訪：文字採訪</option>
                    <option value='文宣採訪：活動記錄' >文宣採訪：活動記錄</option>
                    <option value='教務：課務教材' >教務：課務教材</option>
                    <option value='教務：課務行政' >教務：課務行政</option>
                    <option value='教務：展覽' >教務：展覽</option>
                    <option value='教務：視聽' >教務：視聽</option>
                    <option value='教務：主持人' >教務：主持人</option>
                    <option value='關懷：報名報到' >關懷：報名報到</option>
                    <option value='關懷：關懷行政' >關懷：關懷行政</option>
                    <option value='關懷：關懷服務' >關懷：關懷服務</option>
                    <option value='關懷：關懷員' >關懷：關懷員</option>
                    <option value='總務：場地' >總務：場地</option>
                    <option value='總務：交通' >總務：交通</option>
                    <option value='總務：資材' >總務：資材</option>
                    <option value='總務：餐飲' >總務：餐飲</option>
                    <option value='總務：環保' >總務：環保</option>
                    <option value='總務：保健' >總務：保健</option>
                    <option value='總務：機動' >總務：機動</option>
                    <option value='公關：外接待' >公關：外接待</option>
                @else
                    <option value='總部' >總部</option>
                    <option value='資訊組' >資訊組</option>
                    <option value='秘書組' >秘書組</option>
                    <option value='教務組' >教務組</option>
                    <option value='關懷組' >關懷組</option>
                    <option value='總務組' >總務組</option>
                    <option value='公關組' >公關組</option>
                    <option value='海外組' >海外組</option>
                    @if(\Str::contains($batch->name, "中區"))
                    <option value='餐飲組' >餐飲組</option>
                    @endif
                    @if(\Str::contains($batch->name, "高雄"))
                    <option value='節目組' >節目組</option>
                    @endif
                    <option value='報到組義工' >報到組義工</option>
                @endif
                <option value='無' >無</option>
            </select>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputGroupPriority3' class='col-md-2 control-label text-md-right'>報名組別第3志願</label>
        <div class='col-md-10'>
            <select class='form-control' name='group_priority3' onChange=''>
            <option value='' selected>- 請選擇 -</option>
                @if(\Str::contains($batch->name, "桃園"))
                    <option value='秘書：義工' >秘書：義工</option>
                    <option value='秘書：財務' >秘書：財務</option>
                    <option value='秘書：行政' >秘書：行政</option>
                    <option value='秘書：資訊' >秘書：資訊</option>
                    <option value='文宣採訪：文字採訪' >文宣採訪：文字採訪</option>
                    <option value='文宣採訪：活動記錄' >文宣採訪：活動記錄</option>
                    <option value='教務：課務教材' >教務：課務教材</option>
                    <option value='教務：課務行政' >教務：課務行政</option>
                    <option value='教務：展覽' >教務：展覽</option>
                    <option value='教務：視聽' >教務：視聽</option>
                    <option value='教務：主持人' >教務：主持人</option>
                    <option value='關懷：報名報到' >關懷：報名報到</option>
                    <option value='關懷：關懷行政' >關懷：關懷行政</option>
                    <option value='關懷：關懷服務' >關懷：關懷服務</option>
                    <option value='關懷：關懷員' >關懷：關懷員</option>
                    <option value='總務：場地' >總務：場地</option>
                    <option value='總務：交通' >總務：交通</option>
                    <option value='總務：資材' >總務：資材</option>
                    <option value='總務：餐飲' >總務：餐飲</option>
                    <option value='總務：環保' >總務：環保</option>
                    <option value='總務：保健' >總務：保健</option>
                    <option value='總務：機動' >總務：機動</option>
                    <option value='公關：外接待' >公關：外接待</option>
                @else
                    <option value='總部' >總部</option>
                    <option value='資訊組' >資訊組</option>
                    <option value='秘書組' >秘書組</option>
                    <option value='教務組' >教務組</option>
                    <option value='關懷組' >關懷組</option>
                    <option value='總務組' >總務組</option>
                    <option value='公關組' >公關組</option>
                    <option value='海外組' >海外組</option>
                    @if(\Str::contains($batch->name, "中區"))
                    <option value='餐飲組' >餐飲組</option>
                    @endif
                    @if(\Str::contains($batch->name, "高雄"))
                    <option value='節目組' >節目組</option>
                    @endif
                    <option value='報到組義工' >報到組義工</option>
                @endif
                <option value='無' >無</option>
            </select>
        </div>
    </div>

    <div class='row form-group'>
    <label for='inputGroupPriorityOther' class='col-md-2 control-label text-md-right'>報名組別其它需求</label>
        <div class='col-md-10'>
            <input type='text' name='group_priority_other' value='' class='form-control' id='inputGroupPriorityOther' placeholder='若對報名組別有其它需求請在此填寫'>
            <div class="invalid-feedback">
                請填寫報名組別其它需求
            </div>
        </div>
    </div>
--}}

    <div class='row form-group required'>
    <label for='inputLRClass' class='col-md-2 control-label text-md-right'>廣論研討班別</label>
        <div class='col-md-10 form-inline'>
            <div class='col-auto'>
                {{-- 區域別(北、桃、竹、中、嘉、南、高、園)；年度(10~24)；班階(宗、備、善、增、春、秋)；班號(自填) --}}
                <select required class='form-control' name='lrRegion' onchange="if(this.value == ''){ document.getElementById('inputLRClass').value = ''; } else {document.getElementById('inputLRClass').value = this.value;}">
                    <option value=''>- 區域別 -</option>
                    <option value='北'>北</option>
                    <option value='桃'>桃</option>
                    <option value='竹'>竹</option>
                    <option value='中'>中</option>
                    <option value='嘉'>嘉</option>
                    <option value='南'>南</option>
                    <option value='高'>高</option>
                    <option value='園'>園</option>
                </select>
            </div>
            <div class='col-auto'>
                <select required class='form-control' name='lrYear' onchange="if(this.value == ''){ document.getElementById('inputLRClass').value = ''; } else {document.getElementById('inputLRClass').value = document.Camp.lrRegion.value + this.value;}">
                    <option value=''>- 年度 -</option>
                    @for($i = 10; $i <= 24; $i++)
                    <option value='{{ $i }}'>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class='col-auto'>
                <select required class='form-control' name='lrRank' onchange="if(this.value == ''){ document.getElementById('inputLRClass').value = ''; } else {document.getElementById('inputLRClass').value = document.Camp.lrRegion.value + document.Camp.lrYear.value + this.value;}">
                    <option value=''>- 班階 -</option>
                    <option value='宗'>宗</option>
                    <option value='備'>備</option>
                    <option value='善'>善</option>
                    <option value='增'>增</option>
                    <option value='春'>春</option>
                    <option value='秋'>秋</option>
                </select>
            </div>
            <div class='col-auto'>
                <input type='tel' required name='lrclassNumber' placeholder='班號' class='form-control' id='inputLRClassNumber' onKeyUp="if(document.Camp.lrclassNumber.value == ''){ document.Camp.lrclass.value = ''; } else {document.getElementById('inputLRClass').value = document.Camp.lrRegion.value + document.Camp.lrYear.value + document.Camp.lrRank.value + document.Camp.lrclassNumber.value;}">
            </div>
            <div class='col-auto mt-3'>
                <label>班別預覽：
                <input type='text' required name='lrclass' value='' class='form-control' id='inputLRClass' readonly></label>
            </div>
            <div class="invalid-feedback">
                請填寫廣論研討班別
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputName' class='col-md-2 control-label text-md-right'>中文姓名</label>
        <div class='col-md-10'>
            <input type='text' required name='name' value='' class='form-control' id='inputName' placeholder='請填寫全名'>
            <div class="invalid-feedback">
                請填寫姓名
            </div>
        </div>
    </div>

    <div class="row form-group required">
        <label for='inputGender' class='col-md-2 control-label text-md-right'>性別</label>
        <div class='col-md-10'>
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="M">
                    <input class="form-check-input" type="radio" name="gender" value="M" required>
                    男
                    <div class="invalid-feedback">
                        請選擇性別
                    </div>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="F">
                    <input class="form-check-input" type="radio" name="gender" value="F" required>
                    女
                    <div class="invalid-feedback">
                        &nbsp;
                    </div>
                </label>
            </div>
        </div>
    </div>

    <div class='row form-group required field_long' style='display:none'>
        <label for='inputBirth' class='col-md-2 control-label text-md-right'>生日</label>
        <div class='date col-md-10' id='inputBirth'>
            <div class='row form-group required'>
                <div class="col-md-1">
                    西元
                </div>
                <div class="col-md-3">
                    <input type='number' required class='form-control' name='birthyear' min='{{ \Carbon\Carbon::now()->subYears(90)->year }}' max='{{ \Carbon\Carbon::now()->subYears(16)->year }}' value='' placeholder=''>
                    <div class="invalid-feedback">
                        未填寫或日期不正確
                    </div>
                </div>
                <div class="col-md-1">
                    年
                </div>
                <div class="col-md-2">
                    <input type='number' required class='form-control' name='birthmonth' min=1 max=12 value='' placeholder=''>
                    <div class="invalid-feedback">
                        未填寫或日期不正確
                    </div>
                </div>
                <div class="col-md-1">
                    月
                </div>
                <div class="col-md-3">
                    <input type='number' required class='form-control' name='birthday' min=1 max=31 value='' placeholder=''>
                    <div class="invalid-feedback">
                        未填寫或日期不正確
                    </div>
                </div>
                <div class="col-md-1">
                    日
                </div>
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputMobile' class='col-md-2 control-label text-md-right'>手機號碼</label>
        <div class='col-md-10'>
            <input type=tel required name='mobile' value='' class='form-control' id='inputMobile' placeholder='格式：0912345678'>
            <div class="invalid-feedback">
                請填寫手機號碼
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>電子信箱</label>
        <div class='col-md-10'>
            <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請務必填寫正確，以利營隊相關訊息通知'>
            <div class="invalid-feedback">
                未填電子信箱或格式不正確
            </div>
        </div>
    </div>

    <script language='javascript'>
        $('#inputEmail').bind("cut copy paste",function(e) {
        e.preventDefault();
        });
    </script>

    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>確認電子信箱</label>
        <div class='col-md-10'>
            <input type='email' required name='emailConfirm' value='' class='form-control' id='inputEmailConfirm' placeholder='請再次填寫(勿複製貼上)，確認電子信箱正確'>
            {{-- data-match='#inputEmail' data-match-error='郵件不符合' placeholder='請再次填寫確認郵件填寫正確' --}}
            <div class="invalid-feedback">
                未填電子信箱或格式不正確
            </div>
        </div>
    </div>

    <hr>

    {{-- 護持日期 --}}
    <div class='row form-group required' >
        <label for='inputParticipationDates' class='col-md-2 control-label text-md-right'>護持日期(多選)</label>
        <div class='col-md-10'>
            @if(str_contains($batch->name, "北區"))
                <label><input type="checkbox" class='participation_dates' name=participation_dates[] value='0710(三)' > 0710(三)</label> <br/>
                <label><input type="checkbox" class='participation_dates' name=participation_dates[] value='0711(四)' > 0711(四)</label> <br/>
                <label><input type="checkbox" class='participation_dates' name=participation_dates[] value='0712(五)' > 0712(五)</label> <br/>
                <label><input type="checkbox" class='participation_dates' name=participation_dates[] value='0713(六)' > 0713(六)</label> <br/>
                <label><input type="checkbox" class='participation_dates' name=participation_dates[] value='0714(日)' > 0714(日)</label> <br/>
            @else
                <label><input type="checkbox" class='participation_dates' name=participation_dates[] value='0724(三)' > 0724(三)</label> <br/>
                <label><input type="checkbox" class='participation_dates' name=participation_dates[] value='0725(四)' > 0725(四)</label> <br/>
                <label><input type="checkbox" class='participation_dates' name=participation_dates[] value='0726(五)' > 0726(五)</label> <br/>
                <label><input type="checkbox" class='participation_dates' name=participation_dates[] value='0727(六)' > 0727(六)</label> <br/>
                <label><input type="checkbox" class='participation_dates' name=participation_dates[] value='0728(日)' > 0728(日)</label> <br/>
            @endif
            <div class="invalid-feedback" id="participation_dates-invalid">
                請選擇護持日期。
            </div>
        </div>
    </div>

    {{-- 住宿日期 --}}
    <div class='row form-group required' >
        <label for='inputStayDates' class='col-md-2 control-label text-md-right'>住宿日期(多選)</label>
        <div class='col-md-10'>
            <label><input type="checkbox" class='stay_dates' name=stay_dates[] value='0710(三)' > 0710(三)</label> <br/>
            <label><input type="checkbox" class='stay_dates' name=stay_dates[] value='0711(四)' > 0711(四)</label> <br/>
            <label><input type="checkbox" class='stay_dates' name=stay_dates[] value='0712(五)' > 0712(五)</label> <br/>
            <label><input type="checkbox" class='stay_dates' name=stay_dates[] value='0713(六)' > 0713(六)</label> <br/>
            <div class="invalid-feedback" id="stay_dates-invalid">
                請選擇住宿日期。
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputPreliminaries' class='col-md-2 control-label text-md-right'>參加全體義工提升</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='is_preliminaries' value='1' > 是
                <div class="invalid-feedback">
                    請選擇是否參加全體義工提升
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='is_preliminaries' value='0' > 否
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputCleanUp' class='col-md-2 control-label text-md-right'>參加打掃法會</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='is_cleanup' value='1' > 是
                <div class="invalid-feedback">
                    請選擇是否參加打掃法會
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='is_cleanup' value='0' > 否
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputDepartFrom' class='col-md-2 control-label text-md-right'>正行交通</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='depart_from' value='自往' id='inputDepartFromSelf' onclick="setDepartFrom(this)"> 自往
                <div class="invalid-feedback">
                    請選擇正行交通
                </div>
            </label>
            <br>
            <label class=radio-inline>
                <input type=radio required name='depart_from' value='搭車' id='inputDepartFromBus' onclick="setDepartFrom(this)"> 搭車
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <br>
            <label>
                <input type="text" name="depart_from_location" id="inputDepartFromLocation" class="form-control" onclick="inputDepartFromBus.checked = true; this.required = true;" placeholder='請填寫搭車地點'>
                <div class="invalid-feedback">
                    請填寫搭車地點
                </div>
            </label>
        </div>
    </div>

    <hr>

    <div class='row form-group field_long' style='display:none'>
    <label for='inputLineID' class='col-md-2 control-label text-md-right'>LINE ID</label>
        <div class='col-md-10'>
            <input type='text' name='line' value='' class='form-control' id='inputLineID'>
            <div class="invalid-feedback crumb">
                請填寫LINE ID
            </div>
        </div>
    </div>

    <div class='row form-group field_long' style='display:none'>
        <label for='inputCadreExperiences' class='col-md-2 control-label text-md-right'>班級護持記錄</label>
        <div class='col-md-10'>
            <textarea class='form-control' rows=2 name='cadre_experiences' id=inputCadreExperiences placeholder='例如：18春991班副班長'></textarea>
            <div class="invalid-feedback">
                請填寫班級護持記錄
            </div>
        </div>
    </div>

    <div class='row form-group field_long' style='display:none'>
        <label for='inputVolunteerExpereiences' class='col-md-2 control-label text-md-right'>義工護持記錄</label>
        <div class='col-md-10'>
            <textarea class='form-control' rows=2 name='volunteer_experiences' id=inputVolunteerExpereiences placeholder='例如：2021年菁英營關懷組小組長、月光共學字幕校對義工'></textarea>
            <div class="invalid-feedback">
                請填寫義工護持記錄
            </div>
        </div>
    </div>

    {{-- 日常交通方式 --}}
    <div class='row form-group field_long' style='display:none'>
        <label for='inputTransport' class='col-md-2 control-label text-md-right'>日常交通方式<br>(多選)</label>
        <div class='col-md-10'>
            <label><input type="checkbox" name=transport[] value='開車' > 開車</label> <br/>
            <label><input type="checkbox" name=transport[] value='摩托車' > 摩托車</label> <br/>
            <label><input type="checkbox" name=transport[] value='大眾運輸' > 大眾運輸</label> <br/>
            <label><input type="checkbox" name=transport[] value='走路' > 走路</label> <br/>
            {{-- 其它 --}}
            <label>
                <input type="checkbox" name=transport[] value='其它' id="transport_other_checkbox" onclick="setTransportOther(this)"> 其它：
                <input type="text" name="transport_other" id="transport_other_text" class="form-control" onclick="transport_other_checkbox.checked = true; this.required = true;">
                <div class="invalid-feedback">
                    請選擇日常交通方式，若選其它請填寫何種交通方式。
                </div>
            </label>
        </div>
    </div>

{{--
    <div class='row form-group field_long' style='display:none'>
    <label for='inputExpertise' class='col-md-2 control-label text-md-right'>專長</label>
        <div class='col-md-10'>
            <input type='text' name='expertise' value='' class='form-control' id='inputExpertise'>
            <div class="invalid-feedback crumb">
                請填寫專長
            </div>
        </div>
    </div>
--}}

    {{-- 專長 --}}
    <div class='row form-group field_long' style='display:none'>
        <label for='inputExpertise' class='col-md-2 control-label text-md-right'>專長(多選)</label>
        <div class='col-md-10'>
            <label><input type="checkbox" name=expertise[] value='插花/花藝' > 插花/花藝</label> <br/>
            <label><input type="checkbox" name=expertise[] value='攝影' > 攝影</label> <br/>
            <label><input type="checkbox" name=expertise[] value='視覺設計' > 視覺設計</label> <br/>
            <label><input type="checkbox" name=expertise[] value='電腦文書處理' > 電腦文書處理</label> <br/>
            <label><input type="checkbox" name=expertise[] value='影音多媒體' > 影音多媒體</label> <br/>
            <label><input type="checkbox" name=expertise[] value='程式開發/網頁設計' > 程式開發/網頁設計</label> <br/>
            <label><input type="checkbox" name=expertise[] value='美髮/美容' > 美髮/美容</label> <br/>     <label>
            {{-- 其它 --}}
                <input type="checkbox" name=expertise[] value='其它' id="expertise_other_checkbox" onclick="setExpertiseOther(this)"> 其它：
                <input type="text" name="expertise_other" id="expertise_other_text" class="form-control" onclick="expertise_other_checkbox.checked = true; this.required = true;">
                <div class="invalid-feedback">
                    請選擇專長，若選其它請填寫何種專長。
                </div>
            </label>
        </div>
    </div>

    {{-- 語言 --}}
    <div class='row form-group field_long' style='display:none'>
        <label for='inputLanguage' class='col-md-2 control-label text-md-right'>語言(多選)</label>
        <div class='col-md-10'>
            <label><input type="checkbox" name=language[] value='中文' > 中文</label> <br/>
            <label><input type="checkbox" name=language[] value='英語' > 英語</label> <br/>
            <label><input type="checkbox" name=language[] value='日語' > 日語</label> <br/>
            <label><input type="checkbox" name=language[] value='韓語' > 韓語</label> <br/>
            <label><input type="checkbox" name=language[] value='馬來語' > 馬來語</label> <br/>
            <label><input type="checkbox" name=language[] value='印尼語' > 印尼語</label> <br/>
            <label><input type="checkbox" name=language[] value='越南語' > 越南語</label> <br/>
            <label><input type="checkbox" name=language[] value='台語' > 台語</label> <br/>
            <label><input type="checkbox" name=language[] value='客語' > 客語</label> <br/>
            {{-- 其它 --}}
            <label>
                <input type="checkbox" name=language[] value='其它' id="language_other_checkbox" onclick="setLanguageOther(this)"> 其它：
                <input type="text" name="language_other" id="language_other_text" class="form-control" onclick="language_other_checkbox.checked = true; this.required = true;">
                <div class="invalid-feedback">
                    請選擇語言，若選其它請填寫何種語言。
                </div>
            </label>
        </div>
    </div>

    <hr>
    <h5 class='form-control-static text-info field_long' style='display:none'>說明：底下公司及職務相關欄位，若已退休，請填寫退休前資料</h5>
    <br>

    <div class='row form-group required field_long' style='display:none'>
    <label for='inputUnit' class='col-md-2 control-label text-md-right itemreg_long'>公司名稱</label>
        <div class='col-md-10'>
            <input type=text required class='form-control itemreq_long' name='unit' id='inputUnit' value=''  placeholder='若已退休，請填寫退休前資料'>
            <div class="invalid-feedback crumb">
                請填寫公司名稱
            </div>
        </div>
    </div>

    <div class='row form-group required field_long' style='display:none'>
        <label for='inputIndustry' class='col-md-2 control-label text-md-right'>產業別</label>
        <div class='col-md-10'>
            <select required class='form-control itemreg_long' name='industry' id='inputIndustry' onChange=''>
                <option value='' selected>- 請選擇 -</option>
                <option value='製造業' >製造業</option>
                <option value='公共事業' >公共事業</option>
                <option value='營建工程相關產業' >營建工程相關產業</option>
                <option value='批發、零售及貿易相關產業' >批發、零售及貿易相關產業</option>
                <option value='運輸及倉儲相關產業' >運輸及倉儲相關產業</option>
                <option value='住宿及餐飲相關產業' >住宿及餐飲相關產業</option>
                <option value='大眾傳播通訊相關產業' >大眾傳播通訊相關產業</option>
                <option value='金融及保險業相關產業' >金融及保險業相關產業</option>
                <option value='專業、科學及技術相關服務業(會計師、律師、顧問等）' >專業、科學及技術相關服務業(會計師、律師、顧問等）</option>
                <option value='教育學術相關產業' >教育學術相關產業</option>
                <option value='醫療保健及社會工作服務相關' >醫療保健及社會工作服務相關</option>
                <option value='藝術、娛樂及休閒服務相關' >藝術、娛樂及休閒服務相關</option>
                <option value='公家機關' >公家機關</option>
                <option value='其它' >其它</option>
            </select>
            <div class="invalid-feedback crumb">
                請選擇產業別
            </div>
        </div>
    </div>

{{--
    <div class='row form-group field_long' style='display:none'>
    <label for='inputIndustryOther' class='col-md-2 control-label text-md-right'>產業別:自填</label>
        <div class='col-md-10'>
            <input type='text' name='industry_other' value='' class='form-control' id='inputIndustryOther' placeholder='產業別若選「其它」請自填'>
            <div class="invalid-feedback">
                產業別若選「其它」請自填
            </div>
        </div>
    </div>
--}}

    <div class='row form-group required field_long' style='display:none'>
    <label for='inputTitle' class='col-md-2 control-label text-md-right'>職稱</label>
        <div class='col-md-10'>
            <input type='text' required class='form-control itemreq_long' name='title' id='inputTitle' value='' maxlength="40"  placeholder='若已退休，請填寫退休前資料'>
            <div class="invalid-feedback">
                請填寫職稱
            </div>
        </div>
    </div>

    <div class='row form-group required field_long' style='display:none'>
        <label for='inputJobProperty' class='col-md-2 control-label text-md-right'>職務類型</label>
        <div class='col-md-10'>
            <select required class='form-control itemreq_long' name='job_property' id='inputJobProperty' onChange=''>
                <option value='' selected>- 請選擇 -</option>
                <option value='負責人/公司經營管理' >負責人/公司經營管理</option>
                <option value='人資' >人資</option >
                <option value='行政/總務' >行政/總務</option>
                <option value='法務' >法務</option>
                <option value='財會/金融' >財會/金融</option>
                <option value='行銷/企劃' >行銷/企劃</option>
                <option value='專案管理' >專案管理</option>
                <option value='客服/門市' >客服/門市</option>
                <option value='業務/貿易' >業務/貿易</option>
                <option value='資訊軟體/研發' >資訊軟體/研發</option>
                <option value='生產製造/品管/環衛' >生產製造/品管/環衛</option>
                <option value='物流/運輸' >物流/運輸</option>
                <option value='建築/營建' >建築/營建</option>
                <option value='影視演藝/幕後製作' >影視演藝/幕後製作</option>
                <option value='藝術創作/視覺設計' >藝術創作/視覺設計</option>
                <option value='文字創作/傳媒工作' >文字創作/傳媒工作</option>
                <option value='醫療/保健服務' >醫療/保健服務</option>
                <option value='學術/教育輔導' >學術/教育輔導</option>
                <option value='軍警消/保全' >軍警消/保全</option>
                <option value='其它' >其它</option>
            </select>
            <div class="invalid-feedback crumb">
                請選擇職務類型
            </div>
        </div>
    </div>

{{--
    <div class='row form-group field_long' style='display:none'>
    <label for='inputJobPropertyOther' class='col-md-2 control-label text-md-right'>職務類型:自填</label>
        <div class='col-md-10'>
            <input type='text' name='job_property_other' value='' class='form-control' id='inputJobPropertyOther' placeholder='職務類型若選「其它」請自填'>
            <div class="invalid-feedback">
                職務類型若選「其它」請自填
            </div>
        </div>
    </div>

    <div class='row form-group field_long' style='display:none'>
        <label for='inputTelWork' class='col-md-2 control-label text-md-right'>公司電話</label>
        <div class='col-md-10'>
            <input type='tel' name='phone_work' value='' class='form-control' id='inputTelWork' placeholder='格式：0225452546#520'>
            <div class="invalid-feedback crumb">
                請填寫公司電話
            </div>
        </div>
    </div>
--}}

    <div class='row form-group field_long' style='display:none'>
    <label for='inputEmployees' class='col-md-2 control-label text-md-right'>公司員工總數</label>
        <div class='col-md-10'>
            <input type='number' name='employees' value='' class='form-control' id='inputEmployees' placeholder='請填寫數字，勿填「非數字」'>
            <div class="invalid-feedback crumb">
                請填寫數字，勿填「非數字」，如不確定可填大約人數
            </div>
        </div>
    </div>

    <div class='row form-group field_long' style='display:none'>
    <label for='inputDirectManagedEmployees' class='col-md-2 control-label text-md-right'>所轄員工人數</label>
        <div class='col-md-10'>
            <input type='number' name='direct_managed_employees' value='' class='form-control' id='inputDirectManagedEmployees' placeholder='請填寫數字，勿填「非數字」'>
            <div class="invalid-feedback crumb">
                請填寫數字，勿填「非數字」，如不確定可填大約人數
            </div>
        </div>
    </div>
{{--
    <div class='row form-group field_long' style='display:none'>
    <label for='inputCapital' class='col-md-2 control-label text-md-right'>資本額(新臺幣:元)</label>
        <div class='col-md-10'>
            <input type='number' name='capital' value='' maxlength="40" class='form-control' id='inputTitle' placeholder='請填寫數字'>
            <div class="invalid-feedback crumb">
                請填寫資本額
            </div>
        </div>
    </div>

    <div class='row form-group field_long' style='display:none'>
        <label for='inputOrgType' class='col-md-2 control-label text-md-right'>公司/組織形式</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio name='org_type' value='私人公司' > 私人公司
                <div class="invalid-feedback">
                    請選擇公司/組織形式
                </div>
            </label>
            <label class=radio-inline>
                <input type='radio' name='org_type' value='專業領域(例醫生、作家⋯)' > 專業領域(例醫生、作家⋯)
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type='radio' name='org_type' value='政府部門/公營事業' > 政府部門/公營事業
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type='radio' name='org_type' value='非政府/非營利組織' > 非政府/非營利組織
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type='radio' name='org_type' value='其它' > 其它
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <div class='row form-group field_long' style='display:none'>
    <label for='inputOrgTypeOther' class='col-md-2 control-label text-md-right'>公司/組織形式:自填</label>
        <div class='col-md-10'>
            <input type='text' name='org_type_other' value='' class='form-control' id='inputOrgTypeOther' placeholder='公司/組織形式若選「其它」請自填'>
            <div class="invalid-feedback">
                公司/組織形式若選「其它」請自填
            </div>
        </div>
    </div>

    <div class='row form-group field_long' style='display:none'>
        <label for='inputYearsOperation' class='col-md-2 control-label text-md-right'>公司成立幾年</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type='radio' name='years_operation' value='10年以上' > 10年以上
                <div class="invalid-feedback">
                    請選擇公司成立幾年
                </div>
            </label>
            <label class=radio-inline>
                <input type='radio' name='years_operation' value='5年~10年' > 5年~10年
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type='radio' name='years_operation' value='5年以下' > 5年以下
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>
--}}

    <!--- 同意書 -->
    <div class='row form-group required'>
        <label for='inputTerm' class='col-md-2 control-label text-md-right'>個人資料</label>
        <div class='col-md-10 form-check'>
            <p class='form-control-static text-danger'>
            主辦單位於本次營隊取得之個人資料，於營隊期間及後續主辦單位舉辦之活動，作為訊息通知、行政處理等非營利目的之使用，不會提供給無關之其他私人單位使用。
            </p>
            <label class=radio-inline>
                <input type='radio' required name="profile_agree" value='1' checked> 我同意
                <div class="invalid-feedback">
                    請圈選本欄位
                </div>
            </label>
            <label class=radio-inline>
                <input type='radio' required name="profile_agree" value='0' > 我不同意
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputTerm' class='col-md-2 control-label text-md-right'>肖像權</label>
        <div class='col-md-10 form-check'>
            <p class='form-control-static text-danger'>
            主辦單位在營隊期間拍照/錄影之活動記錄，可使用於營隊及主辦單位的非營利教育推廣使用，並以網路方式推播。
            </p>
            <label class=radio-inline>
                <input type='radio' required name="portrait_agree" value='1' checked> 我同意
                <div class="invalid-feedback">
                    請圈選本欄位
                </div>
            </label>
            <label class=radio-inline>
                <input type='radio' required name="portrait_agree" value='0' > 我不同意
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputIntroducerName' class='col-md-2 control-label text-md-right'>邀請人或班級聯絡人(若無免填)</label>
        <div class='col-md-10'>
            <input type='text' name='introducer_name' value='' class='form-control' id='inputIntroducerName' placeholder='若您今年是透過某位師兄師姊的邀請加入義工，請填寫他/她的姓名'>
            <div class="invalid-feedback">
                請填寫邀請人姓名
            </div>
        </div>
    </div>

    <hr>

    @if(isset($applicant_data))
        @if($isModify)
            @if(!$applicant_raw_data->avatar)
                <h5 class='form-control-static text-primary field_long'>請選擇正面、清楚、不戴帽、不戴墨鏡、不戴口罩的大頭照上傳</h5>
                <div class='row form-group field_long'>
                    <label for='inputAvatar' class='col-md-2 control-label text-md-right'>大頭照</label>
                    <div class='col-md-10'>
                        <input type='file' name='avatar' value='' class='form-control' id='inputAvatar'>
                        <div class="invalid-feedback">
                            請上傳大頭照
                        </div>
                    </div>
                </div>
            @else
                <h6 class='form-control-static text-info field_long'>您已於報名時成功上傳大頭照，為隱私考量，故不在此顯示。</h6>
                <h5 class="text-warning field_long">若需更換，請在此重新選擇新照片。</h5>
                <div class='row form-group field_long'>
                    <label for='inputAvatar' class='col-md-2 control-label text-md-right'>重新上傳大頭照</label>
                    <div class='col-md-10'>
                        <input type='file' name='avatar_re' value='' class='form-control' id='inputAvatar'>
                    </div>
                </div>
            @endif
        @else
            @if(!$applicant_raw_data->avatar)
                <h6 class='form-control-static text-info field_long' style='display:none'>未上傳大頭照。</h6>
            @else
                <h6 class='form-control-static text-info field_long' style='display:none'>您已於報名時成功上傳大頭照，為隱私考量，故不在此顯示。</h6>
            @endif
        @endif
    @else
        <h5 class='form-control-static text-primary field_long' style='display:none'>請選擇正面、清楚、不戴帽、不戴墨鏡、不戴口罩的大頭照上傳</h5>
        <div class='row form-group field_long'>
            <label for='inputAvatar' class='col-md-2 control-label text-md-right'>大頭照</label>
            <div class='col-md-10'>
                <input type='file' name='avatar' value='' class='form-control' id='inputAvatar'>
                <div class="invalid-feedback">
                    請上傳大頭照
                </div>
            </div>
        </div>
    @endif

    <div class="row form-group text-danger tips d-none">
        <div class='col-md-2'></div>
        <div class='col-md-10'>
            請檢查是否有未填寫或格式錯誤的欄位。
        </div>
    </div>

    <!--- 確認送出 -->
    <div class='row form-group'>
        <div class='col-md-2'></div>
        <div class='col-md-10'>
            {{-- !isset($isModify): 沒有 $isModify 變數，即為報名狀態、 $isModify: 修改資料狀態--}}
            @if(!isset($isModify) || $isModify)
                @if(isset($useOldData2Register))
                <input type="hidden" name="useOldData2Register" value="1">
                @endif
                <input type='button' class='btn btn-success' value='確認送出' data-toggle="confirmation">
                {{--
                <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                <input type='reset' class='btn btn-danger' value='清除再來'>
                --}}
            {{-- 以上皆非: 檢視資料狀態 --}}
            @else
                <input type="hidden" name="sn" value="{{ $applicant_id }}">
                <input type="hidden" name="isModify" value="1">
                <button class="btn btn-primary">修改報名資料</button>
            @endif
        </div>
    </div>
    </form>

    <script>
        $('[data-toggle="confirmation"]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            title: "敬請再次確認資料填寫無誤。",
            btnOkLabel: "正確無誤，送出",
            btnCancelLabel: "再檢查一下",
            popout: true,
            onConfirm: function() {
                {{-- 檢查「必填＋多選」至少選一個 ---}}
                console.log($('.participation_dates').filter(':checked').length);
                console.log($('.stay_dates').filter(':checked').length);
                if($('.participation_dates').filter(':checked').length < 1) {
                    document.Camp.checkValidity();
                    event.preventDefault();
                    event.stopPropagation();
                    $(".tips").removeClass('d-none');
                    $('#participation_dates-invalid').show();
                }
                else{
                    document.Camp.checkValidity();
                    event.preventDefault();
                    event.stopPropagation();
                    $(".tips").removeClass('d-none');
                    $('#participation_dates-invalid').hide();
                }
                {{-- 檢查「必填＋多選」至少選一個 ---}}
                if($('.stay_dates').filter(':checked').length < 1) {
                    document.Camp.checkValidity();
                    event.preventDefault();
                    event.stopPropagation();
                    $(".tips").removeClass('d-none');
                    $('#stay_dates-invalid').show();
                }
                else{
                    document.Camp.checkValidity();
                    event.preventDefault();
                    event.stopPropagation();
                    $(".tips").removeClass('d-none');
                    $('#stay_dates-invalid').hide();
                }

                if (document.Camp.checkValidity() === false) {
                    $(".tips").removeClass('d-none');
                    event.preventDefault();
                    event.stopPropagation();
                }
                else{
                    $(".tips").addClass('d-none');
                    document.Camp.submit();
                }
                document.Camp.classList.add('was-validated');
            }
        });
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if($('.transport :checkbox:checked').length < 1) {
                            event.preventDefault();
                            event.stopPropagation();
                            console.log('yes');
                            {{-- $('.transport .invalid-feedback').prop('display') = 1; --}}
                        }
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        let categories = null;
        let rowIsEducating = null;

        /**
        * Ready functions.
        * Executes commands after the web page is loaded.
        */

        function setDepartFrom(radio_ele) {
            // 檢查 radio_ele.id == "inputDepartFromBus" 是否被勾選
            // console.log(radio_ele.id);
            if(radio_ele.id == "inputDepartFromBus") {
                // 被勾選: 把 depart_from_location required = true
                document.getElementById("inputDepartFromLocation").required = true;
            }
            else {
                // 否則: 把 depart_from_location required = false
                document.getElementById("inputDepartFromLocation").required = false;
            }
        }

        function setTransportOther(checkbox_ele) {
            // 檢查 checkbox_ele 是否被勾選
            //console.log(checkbox_ele.checked);
            if(checkbox_ele.checked) {
                // 被勾選: 把 transport_other_text required = true
                document.getElementById("transport_other_text").required = true;
            }
            else {
                // 否則:把 transport_other_text required = false
                document.getElementById("transport_other_text").required = false;
            }
        }

        function setExpertiseOther(checkbox_ele) {
            // 檢查 checkbox_ele 是否被勾選
            //console.log(checkbox_ele.checked);
            if(checkbox_ele.checked) {
                // 被勾選: 把 transport_other_text required = true
                document.getElementById("expertise_other_text").required = true;
            }
            else {
                // 否則:把 transport_other_text required = false
                document.getElementById("expertise_other_text").required = false;
            }
        }

        function setLanguageOther(checkbox_ele) {
            // 檢查 checkbox_ele 是否被勾選
            //console.log(checkbox_ele.checked);
            if(checkbox_ele.checked) {
            // 被勾選: 把 language_other_text required = true
                document.getElementById("language_other_text").required = true;
            }
            else {
            // 否則:把 language_other_text required = false
                document.getElementById("language_other_text").required = false;
            }
        }

        function showFields(){
            sel = document.getElementById('inputGroupPriority1').value;
            console.log(sel);
            fields_long = document.getElementsByClassName('field_long');
            fields_short = document.getElementsByClassName('field_short');
            itemsreq_long = document.getElementsByClassName('itemreq_long');
            itemsreq_short = document.getElementsByClassName('itemreq_short');
            recruit_ch = document.getElementById('inputRecruitCh');
            console.log(fields_long.length);
            console.log(itemsreq_long.length);
            console.log(itemsreq_long);
            if (sel == '關懷組') {
                for (i=0;i<fields_long.length;i++) fields_long[i].style.display = '';
                for (i=0;i<fields_short.length;i++) fields_short[i].style.display = 'none';
                for (i=0;i<itemsreq_long.length;i++) itemsreq_long[i].required = true;
                for (i=0;i<itemsreq_short.length;i++) itemsreq_long[i].required = false;
            }
            else {
                for (i=0;i<fields_long.length;i++) fields_long[i].style.display = 'none';
                for (i=0;i<fields_short.length;i++) fields_short[i].style.display = '';
                for (i=0;i<itemsreq_long.length;i++) itemsreq_long[i].required = false;
                for (i=0;i<itemsreq_short.length;i++) itemsreq_long[i].required = true;
            }

            if (sel == '依營隊需求安排')
                recruit_ch.value = '統招';
            else
                recruit_ch.value = '自招';
        }

        function hideFields(){
            fields_long = document.getElementsByClassName('field_long');
            fields_short = document.getElementsByClassName('field_short');
            itemsreq_long = document.getElementsByClassName('itemreq_long');
            itemsreq_short = document.getElementsByClassName('itemreq_short');
            for (i=0;i<fields_long.length;i++) fields_long[i].style.display = 'none';
            for (i=0;i<fields_short.length;i++) fields_short[i].style.display = 'none';
            for (i=0;i<itemsreq_long.length;i++) itemsreq_long[i].required = false;
            for (i=0;i<itemsreq_short.length;i++) itemsreq_long[i].required = false;
        }

        function setUnrequired(elements){
            for(let i = 0; i < elements.length; i++){
                elements[i].required = false;
            }
        }

        function setRequired(elements){
            for(let i = 0; i < elements.length; i++){
                elements[i].required = true;
            }
        }

        function changeJobTitleList(){
            if(this.checked){
                document.getElementById('tip').style.display = 'none';
                document.getElementById('title').value = '';
                titleSets = document.getElementsByClassName("titles");
                for(let i = 0 ; i < titleSets.length ; i++){
                    if(titleSets[i].className.includes(this.className)){
                        titleSets[i].style.display = "";
                    }
                    else{
                        inputs = titleSets[i].getElementsByTagName('input');
                        for(let j = 0 ; j < inputs.length ; j++){
                            inputs[j].checked = false;
                        }
                        titleSets[i].style.display = "none";
                    }
                }
            }
        }

        function fillTheTitle(){
            if(this.value == '其他'){
                document.getElementById('title').value = '請在此處自行輸入職稱';
            }
            else if(this.value == '兼課老師'){
                document.getElementById('title').value = '兼課老師(兼課時數: 小時)';
            }
            else if(this.value != null){
                document.getElementById('title').value = this.value;
            }
        }

        @if(isset($applicant_data))
            {{-- 回填報名資料 --}}
            (function() {
                let applicant_data = JSON.parse('{!! $applicant_data !!}');
                let inputs = document.getElementsByTagName('input');
                let selects = document.getElementsByTagName('select');
                let textareas = document.getElementsByTagName('textarea');
                let complementPivot = 0;
                let complementData = applicant_data["blisswisdom_type_complement"] ? applicant_data["blisswisdom_type_complement"].split("||/") : null;
                // console.log(inputs);
                for (var i = 0; i < inputs.length; i++){
                    if(typeof applicant_data[inputs[i].name] !== "undefined" || inputs[i].type == "checkbox"){
                        if(inputs[i].type == "radio"){
                            let radios = document.getElementsByName(inputs[i].name);
                            for( j = 0; j < radios.length; j++ ) {
                                if( radios[j].value == applicant_data[inputs[i].name] ) {
                                    radios[j].checked = true;
                                }
                            }
                        }
                        else if(inputs[i].type == "checkbox"){
                            let checkboxes = document.getElementsByName(inputs[i].name);
                            let deArray = inputs[i].name.slice(0, -2);
                            if(applicant_data[deArray]){
                                let checkedValues = applicant_data[deArray].split("||/");
                                for( j = 0; j < checkboxes.length; j++ ) {
                                    for( k = 0; k < checkboxes.length; k++ ) {
                                        if( checkboxes[j].value == checkedValues[k] ) {
                                            checkboxes[j].checked = true;
                                        }
                                    }
                                }
                            }
                        }
                        else if(applicant_data[inputs[i].name]){
                            inputs[i].value = applicant_data[inputs[i].name];
                        }
                    }
                    else if(inputs[i].type == "text" && inputs[i].name == 'blisswisdom_type_complement[]'){
                        inputs[i].value = complementData ? complementData[complementPivot] : null;
                        complementPivot++;
                    }
                    else if(inputs[i].type == "text" && typeof applicant_data[inputs[i].name] !== "undefined"){
                        inputs[i].value = applicant_data[inputs[i].name];
                    }
                    if(inputs[i].name == 'emailConfirm'){
                        inputs[i].value = applicant_data['email'];
                    }
                }
                for (var i = 0; i < selects.length; i++){
                    if(typeof applicant_data[selects[i].name] !== "undefined"){
                        selects[i].value = applicant_data[selects[i].name];
                    }
                }
                for (var i = 0; i < textareas.length; i++){
                    if(typeof applicant_data[textareas[i].name] !== "undefined"){
                        textareas[i].value = applicant_data[textareas[i].name];
                    }
                }

                @if(!$isModify)
                    for (var i = 0; i < inputs.length; i++){
                        if(typeof applicant_data[inputs[i].name] !== "undefined" || inputs[i].type == "checkbox" || inputs[i].name == 'emailConfirm' || inputs[i].name == "blisswisdom_type[]" || inputs[i].name == "blisswisdom_type_complement[]"){
                            inputs[i].disabled = true;
                        }
                    }
                    for (var i = 0; i < selects.length; i++){
                        selects[i].disabled = true;
                    }
                    for (var i = 0; i < textareas.length; i++){
                        textareas[i].disabled = true;
                    }
                @endif
            })();

            function checkIfNull(val) {
                return val == "";
            }
        @endif

    </script>
    <style>
        .required .control-label::after {
            content: "＊";
            color: red;
        }
    </style>
@stop

