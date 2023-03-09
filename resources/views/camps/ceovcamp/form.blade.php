@php
    header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
    $regions = ['北區', '竹區', '中區', '高區'];
@endphp
@php
    $is_north = FALSE;
    if(isset($applicant_data)) {
        if(\Str::contains($applicant_raw_data->batch->name, "北區") || \Str::contains($applicant_raw_data->batch->name, "台北")) {$is_north = TRUE;}
    } else {
        if(\Str::contains($batch->name, "北區") || \Str::contains($batch->name, "台北")) {$is_north = TRUE;}
    }
@endphp
@extends('camps.ceovcamp.layout')
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

    {{-- !isset($isModify): 沒有 $isModify 變數，即為報名狀態；只在[報名狀態]時提供載入舊資料選項 --}}
    @if(!isset($isModify) && !isset($batch_id_from) && $is_north)
    <hr>
    <h5 class='form-control-static text-warning bg-secondary'>若您曾報名2022年菁英營義工，請點選->
    <a href="{{ route('query', 50) }}?batch_id_from={{ $batch_id }}" class="text-warning bg-secondary">查詢並使用 *2022年菁英營義工* 報名資料</a>
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
    <form action="{{ route('queryupdate', $applicant_batch_id) }}" method="post" class="d-inline">
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
            <select required class='form-control' name='group_priority1' onChange=''>
                <option value='' selected>- 請選擇 -</option>
                <option value='關懷組' >關懷組</option>
                <option value='行政組' >行政組</option>
                <option value='資訊組' >資訊組</option>
                <option value='秘書組' >秘書組</option>
                <option value='教務組' >教務組</option>
                <option value='依營隊需求安排' >依營隊需求安排</option>
            </select>
        {{--
            <div class="invalid-feedback">
                請選擇報名組別第1志願
            </div>
        --}}
        </div>  
    </div>

    <div class='row form-group'>
        <label for='inputGroupPriority2' class='col-md-2 control-label text-md-right'>報名組別第2志願</label>
        <div class='col-md-10'>
            <select class='form-control' name='group_priority2' onChange=''>
                <option value='' selected>- 請選擇 -</option>
                <option value='關懷組' >關懷組</option>
                <option value='行政組' >行政組</option>
                <option value='資訊組' >資訊組</option>
                <option value='秘書組' >秘書組</option>
                <option value='教務組' >教務組</option>
                <option value='無' >無</option>
            </select>
        </div>  
    </div>

    <div class='row form-group'>
        <label for='inputGroupPriority3' class='col-md-2 control-label text-md-right'>報名組別第3志願</label>
        <div class='col-md-10'>
            <select class='form-control' name='group_priority3' onChange=''>
                <option value='' selected>- 請選擇 -</option>
                <option value='關懷組' >關懷組</option>
                <option value='行政組' >行政組</option>
                <option value='資訊組' >資訊組</option>
                <option value='秘書組' >秘書組</option>
                <option value='教務組' >教務組</option>
                <option value='無' >無</option>
            </select>
        </div>  
    </div>

{{--
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
        <label for='inputLRClassLevel' class='col-md-2 control-label text-md-right'>廣論研討班別</label>
        <div class='col-md-10'>
            <select required class='form-control' name='lrclass_level' onChange=''>
                <option value='' selected>- 請選擇 -</option>
                <option value='一輪班' >一輪班</option>
                <option value='增上班' >增上班</option>
                <option value='善行班' >善行班</option>
                <option value='備覽班' >備覽班</option>
                <option value='宗行班' >宗行班</option>
                <option value='其它' >其它</option>
            </select>
        </div>  
    </div>

    <div class='row form-group required'> 
    <label for='inputLRClass' class='col-md-2 control-label text-md-right'>廣論研討班別(詳)</label>
        <div class='col-md-10'>
            <input type='text' required name='lrclass' value='' class='form-control' id='inputLRClass' placeholder='請詳填廣論研討班別，例：北14宗001班'>
            <div class="invalid-feedback">
                請詳填廣論研討班別，例：北14宗001班
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputName' class='col-md-2 control-label text-md-right'>中文姓名</label>
        <div class='col-md-10'>
            <input type='text' name='name' value='' class='form-control' id='inputName' placeholder='請填寫全名'>
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

    <div class='row form-group required'>
        <label for='inputBirth' class='col-md-2 control-label text-md-right'>生日</label>
        <div class='date col-md-10' id='inputBirth'>
            <div class='row form-group required'>
                <div class="col-md-1">
                    西元
                </div>
                <div class="col-md-3">
                    <input type='number' required class='form-control' name='birthyear' min=1900 max='{{ \Carbon\Carbon::now()->subYears(16)->year }}' value='' placeholder=''>
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
        <label for='inputCell' class='col-md-2 control-label text-md-right'>手機號碼</label>
        <div class='col-md-10'>
            <input type=tel required name='mobile' value='' class='form-control' id='inputCell' placeholder='格式：0912345678'>
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

    <div class='row form-group'> 
    <label for='inputLineID' class='col-md-2 control-label text-md-right'>LINE ID</label>
        <div class='col-md-10'>
            <input type='text' name='line' value='' class='form-control' id='inputLineID'>
            <div class="invalid-feedback crumb">
                請填寫LINE ID
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputCadreExperiences' class='col-md-2 control-label text-md-right'>班級護持記錄</label>
        <div class='col-md-10'>
            <textarea class='form-control' rows=2 name='cadre_experiences' id=inputCadreExperiences placeholder='例如：18春991班副班長'></textarea>
            <div class="invalid-feedback">
                請填寫班級護持記錄
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputVolunteerExpereiences' class='col-md-2 control-label text-md-right'>義工護持記錄</label>
        <div class='col-md-10'>
            <textarea class='form-control' rows=2 name='volunteer_experiences' id=inputVolunteerExpereiences placeholder='例如：2021年菁英營關懷組小組長、月光共學字幕校對義工'></textarea>
            <div class="invalid-feedback">
                請填寫義工護持記錄
            </div>
        </div>
    </div>

    {{-- 日常交通方式 --}}
    <div class='row form-group'>
        <label for='inputTransport' class='col-md-2 control-label text-md-right'>日常交通方式<br>(多選)</label>
        <div class='col-md-10'>
            <label><input type="checkbox" name=transport[] value='開車' > 開車</label> <br/>
            <label><input type="checkbox" name=transport[] value='摩托車' > 摩托車</label> <br/>
            <label><input type="checkbox" name=transport[] value='大眾運輸' > 大眾運輸</label> <br/>
            <label><input type="checkbox" name=transport[] value='走路' > 走路</label> <br/>
            <label>
                <input type="checkbox" name=transport[] value='其它' id="transport_other_checkbox" onclick="setTransportOther(this)"> 其它：
                <input type="text" name="transport_other" id="transport_other_text" class="form-control" onclick="transport_other_checkbox.checked = true; this.required = true;">
                <div class="invalid-feedback">
                    請選擇日常交通方式，若選其它請填寫何種交通方式。
                </div>
            </label>
            {{-- 其它 --}}
        </div>
    </div>

{{--
    <div class='row form-group'> 
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
    <div class='row form-group'>
        <label for='inputExpertise' class='col-md-2 control-label text-md-right'>專長(多選)</label>
        <div class='col-md-10'>
            <label><input type="checkbox" name=expertise[] value='插花/花藝' > 插花/花藝</label> <br/>
            <label><input type="checkbox" name=expertise[] value='攝影' > 攝影</label> <br/>
            <label><input type="checkbox" name=expertise[] value='視覺設計' > 視覺設計</label> <br/>
            <label><input type="checkbox" name=expertise[] value='電腦文書處理' > 電腦文書處理</label> <br/>
            <label><input type="checkbox" name=expertise[] value='影音多媒體' > 影音多媒體</label> <br/>
            <label><input type="checkbox" name=expertise[] value='程式開發/網頁設計' > 程式開發/網頁設計</label> <br/>
            <label>
                <input type="checkbox" name=expertise[] value='其它' id="expertise_other_checkbox" onclick="setExpertiseOther(this)"> 其它：
                <input type="text" name="expertise_other" id="expertise_other_text" class="form-control" onclick="expertise_other_checkbox.checked = true; this.required = true;">
                <div class="invalid-feedback">
                    請選擇專長，若選其它請填寫何種專長。
                </div>
            </label>
            {{-- 其它 --}}
        </div>
    </div>

    {{-- 語言 --}}
    <div class='row form-group'>
        <label for='inputLanguage' class='col-md-2 control-label text-md-right'>語言(多選)</label>
        <div class='col-md-10'>
            <label><input type="checkbox" name=language[] value='英語' > 英語</label> <br/>
            <label><input type="checkbox" name=language[] value='日語' > 日語</label> <br/>
            <label><input type="checkbox" name=language[] value='台語' > 台語</label> <br/>
            <label><input type="checkbox" name=language[] value='客語' > 客語</label> <br/>
            <label><input type="checkbox" name=language[] value='越南語' > 越南語</label> <br/>
            <label>
                <input type="checkbox" name=language[] value='其它' id="language_other_checkbox" onclick="setLanguageOther(this)"> 其它：
                <input type="text" name="language_other" id="language_other_text" class="form-control" onclick="language_other_checkbox.checked = true; this.required = true;">
                <div class="invalid-feedback">
                    請選擇語言，若選其它請填寫何種語言。
                </div>
            </label>
            {{-- 其它 --}}
        </div>
    </div>

    <hr>
    <h5 class='form-control-static text-info'>說明：底下公司及職務相關欄位，若已退休，請填寫退休前資料</h5>
    <br>

    <div class='row form-group required'> 
    <label for='inputUnit' class='col-md-2 control-label text-md-right'>公司名稱</label>
        <div class='col-md-10'>
            <input type=text required name='unit' value='' class='form-control' id='inputUnit' placeholder='若已退休，請填寫退休前資料'>
            <div class="invalid-feedback crumb">
                請填寫公司名稱
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputIndustry' class='col-md-2 control-label text-md-right'>產業別</label>
        <div class='col-md-10'>
            <select required class='form-control' name='industry' onChange=''>
                <option value='' selected>- 請選擇 -</option>
                <option value='電子科技/資訊/軟體/半導體' >電子科技/資訊/軟體/半導體</option>
                <option value='傳產製造' >傳產製造</option>
                <option value='金融/保險/貿易' >金融/保險/貿易</option>
                <option value='法律/會計/顧問' >法律/會計/顧問</option>
                <option value='政治/宗教/社福' >政治/宗教/社福</option>
                <option value='建築/營造/不動產' >建築/營造/不動產</option>
                <option value='醫師/藥師/藥廠/醫療照護' >醫師/藥師/藥廠/醫療照護</option>
                <option value='民生服務業' >民生服務業</option>
                <option value='廣告/傳播/出版' >廣告/傳播/出版</option>
                <option value='教育' >教育</option>
                <option value='設計/藝術/文創' >設計/藝術/文創</option>
                <option value='非營利組織' >非營利組織</option>
                <option value='其它' >其它</option>
            </select>
            <div class="invalid-feedback crumb">
                請選擇產業別
            </div>
        </div>  
    </div>
    
{{--
    <div class='row form-group'> 
    <label for='inputIndustryOther' class='col-md-2 control-label text-md-right'>產業別:自填</label>
        <div class='col-md-10'>
            <input type='text' name='industry_other' value='' class='form-control' id='inputIndustryOther' placeholder='產業別若選「其它」請自填'>
            <div class="invalid-feedback">
                產業別若選「其它」請自填
            </div>
        </div>
    </div>
--}}

    <div class='row form-group required'> 
    <label for='inputTitle' class='col-md-2 control-label text-md-right'>職稱</label>
        <div class='col-md-10'>
            <input type='text' required name='title' value='' maxlength="40" class='form-control' id='inputTitle' placeholder='若已退休，請填寫退休前資料'>
            <div class="invalid-feedback">
                請填寫職稱
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputJobProperty' class='col-md-2 control-label text-md-right'>職務類型</label>
        <div class='col-md-10'>
            <select required class='form-control' name='job_property' onChange=''>
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
    <div class='row form-group'> 
    <label for='inputJobPropertyOther' class='col-md-2 control-label text-md-right'>職務類型:自填</label>
        <div class='col-md-10'>
            <input type='text' name='job_property_other' value='' class='form-control' id='inputJobPropertyOther' placeholder='職務類型若選「其它」請自填'>
            <div class="invalid-feedback">
                職務類型若選「其它」請自填
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputTelWork' class='col-md-2 control-label text-md-right'>公司電話</label>
        <div class='col-md-10'>
            <input type='tel' name='phone_work' value='' class='form-control' id='inputTelWork' placeholder='格式：0225452546#520'>
            <div class="invalid-feedback crumb">
                請填寫公司電話
            </div>
        </div>
    </div>
--}}

    <div class='row form-group'> 
    <label for='inputEmployees' class='col-md-2 control-label text-md-right'>公司員工總數</label>
        <div class='col-md-10'>
            <input type='number' name='employees' value='' class='form-control' id='inputEmployees' placeholder='請填寫數字，勿填「非數字」'>
            <div class="invalid-feedback crumb">
                請填寫數字，勿填「非數字」，如不確定可填大約人數
            </div>
        </div>
    </div>

    <div class='row form-group'> 
    <label for='inputDirectManagedEmployees' class='col-md-2 control-label text-md-right'>所轄員工人數</label>
        <div class='col-md-10'>
            <input type='number' name='direct_managed_employees' value='' class='form-control' id='inputDirectManagedEmployees' placeholder='請填寫數字，勿填「非數字」'>
            <div class="invalid-feedback crumb">
                請填寫數字，勿填「非數字」，如不確定可填大約人數
            </div>
        </div>
    </div>
{{--
    <div class='row form-group'> 
    <label for='inputCapital' class='col-md-2 control-label text-md-right'>資本額(新臺幣:元)</label>
        <div class='col-md-10'>
            <input type='number' name='capital' value='' maxlength="40" class='form-control' id='inputTitle' placeholder='請填寫數字'>
            <div class="invalid-feedback crumb">
                請填寫資本額
            </div>
        </div>
    </div>

    <div class='row form-group'>
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

    <div class='row form-group'> 
    <label for='inputOrgTypeOther' class='col-md-2 control-label text-md-right'>公司/組織形式:自填</label>
        <div class='col-md-10'>
            <input type='text' name='org_type_other' value='' class='form-control' id='inputOrgTypeOther' placeholder='公司/組織形式若選「其它」請自填'>
            <div class="invalid-feedback">
                公司/組織形式若選「其它」請自填
            </div>
        </div>
    </div>

    <div class='row form-group'>
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
        <label for='inputIntroducerName' class='col-md-2 control-label text-md-right'>邀請人<br>(若無免填)</label>
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
                <h5 class='form-control-static text-primary'>請選擇正面、清楚、不戴帽、不戴墨鏡、不戴口罩的大頭照上傳</h5>
                <div class='row form-group'>
                    <label for='inputAvatar' class='col-md-2 control-label text-md-right'>大頭照</label>
                    <div class='col-md-10'>
                        <input type='file' name='avatar' value='' class='form-control' id='inputAvatar'>
                        <div class="invalid-feedback">
                            請上傳大頭照
                        </div>
                    </div>
                </div>
            @else
                <h6 class='form-control-static text-info'>您已於報名時成功上傳大頭照，為隱私考量，故不在此顯示。</h6>
                <h5 class="text-warning">若需更換，請在此重新選擇新照片。</h5>
                <div class='row form-group'>
                    <label for='inputAvatar' class='col-md-2 control-label text-md-right'>重新上傳大頭照</label>
                    <div class='col-md-10'>
                        <input type='file' name='avatar_re' value='' class='form-control' id='inputAvatar'>
                    </div>
                </div>
            @endif
        @else
            @if(!$applicant_raw_data->avatar)
                <h6 class='form-control-static text-info'>未上傳大頭照。</h6>
            @else
                <h6 class='form-control-static text-info'>您已於報名時成功上傳大頭照，為隱私考量，故不在此顯示。</h6>
            @endif
        @endif
    @else
        <h5 class='form-control-static text-primary'>請選擇正面、清楚、不戴帽、不戴墨鏡、不戴口罩的大頭照上傳</h5>
        <div class='row form-group'>
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
                @if(isset($applicant_raw_data->avatar))
                <input type="hidden" name="avatar" value="{{ $applicant_raw_data->avatar }}">
                @endif
                <input type='button' class='btn btn-success' value='確認送出' data-toggle="confirmation">
                {{--
                <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                <input type='reset' class='btn btn-danger' value='清除再來'>
                --}}
            {{-- 以上皆非: 檢視資料狀態 --}}
            @else
                @if(isset($camp_data->modifying_deadline) && \Carbon\Carbon::now() <= \Carbon\Carbon::createFromFormat("Y-m-d", $camp_data->modifying_deadline))
                <input type="hidden" name="sn" value="{{ $applicant_id }}">
                <input type="hidden" name="isModify" value="1">
                <button class="btn btn-primary">修改報名資料</button>
                @endif
            @endif
        </div>
    </div>
    </form>
    
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

    <script>        
        $('[data-toggle="confirmation"]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            title: "敬請再次確認資料填寫無誤。",
            btnOkLabel: "正確無誤，送出",
            btnCancelLabel: "再檢查一下",
            popout: true,
            onConfirm: function() {
                {{--
                        console.log($('.transport :checkbox:checked').length);
                        if($('.transport :checkbox:checked').length < 1) {
                            document.Camp.checkValidity();
                            event.preventDefault();
                            event.stopPropagation();
                            $(".tips").removeClass('d-none');
                            $('#transport-invalid').show();
                        }
                        else{
                            document.Camp.checkValidity();
                            event.preventDefault();
                            event.stopPropagation();
                            $(".tips").removeClass('d-none');
                            $('#transport-invalid').hide();
                        }
                        console.log($('.language :checkbox:checked').length);
                        if($('.language :checkbox:checked').length < 1) {
                            document.Camp.checkValidity();
                            event.preventDefault();
                            event.stopPropagation();
                            $(".tips").removeClass('d-none');
                            $('#language-invalid').show();
                        }
                        else{
                            document.Camp.checkValidity();
                            event.preventDefault();
                            event.stopPropagation();
                            $(".tips").removeClass('d-none');
                            $('#language-invalid').hide();
                        }
                --}}
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
{{--
        document.onreadystatechange = () => {
            if (document.readyState === 'complete') {
                /**
                * 是否在學校或教育單位任職，勾選後顯示/隱藏任職單位相關欄位。
                */
                rowIsEducating = document.getElementById("rowIsEducating");
                document.getElementById("is_educating_y").addEventListener("change", showFields);
                document.getElementById("is_educating_n").addEventListener("change", hideFields);
                if(document.getElementById("is_educating_n").checked){
                    hideFields();
                }
                /**
                * 任職機關/任教學程，勾選後顯示對應職稱。
                */
                categories = document.getElementsByName("school_or_course");
                for(let i = 0; i < categories.length; i++){
                    categories[i].addEventListener("click", changeJobTitleList);
                    categories[i].addEventListener("change", changeJobTitleList);
                }
                            
                /**
                * 選擇職稱後，將職稱填至欄位中。
                */
                titles = document.getElementsByName("data[12]");
                for(let i = 0; i < titles.length; i++){
                    titles[i].addEventListener("click", fillTheTitle);
                    titles[i].addEventListener("change", fillTheTitle);
                }
            }
        };
--}}
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
            rowIsEducating.innerHTML = "<div class='row form-group required'>" +
                "    <label for='inputSchoolOrCourse' class='col-md-2 control-label text-md-right'>任職機關/任教學程</label>" +
                "    <div class='col-md-10'>" +
                "        <label class=radio-inline>" +
                "            <input type=radio required name='school_or_course' value=教育部 class='officials'> 教育部" +
                "            <div class='invalid-feedback crumb'>" +
                "                請勾選任職機關/任教學程" +
                "            </div>" +
                "        </label> " +
                "        <label class=radio-inline>" +
                "            <input type=radio required name='school_or_course' value=教育局/處 class='officials'> 教育局/處" +
                "            <div class='invalid-feedback crumb'>" +
                "                &nbsp;" +
                "            </div>" +
                "        </label> " +
                "        <label class=radio-inline>" +
                "            <input type=radio required name='school_or_course' value=大專校院 class='universities'> 大專校院" +
                "            <div class='invalid-feedback crumb'>" +
                "                &nbsp;" +
                "            </div>" +
                "        </label> <label class=radio-inline>" +
                "            <input type=radio required name='school_or_course' value=高中職 class='compulsories'> 高中職" +
                "            <div class='invalid-feedback crumb'>" +
                "                &nbsp;" +
                "            </div>" +
                "        </label> <label class=radio-inline>" +
                "            <input type=radio required name='school_or_course' value=國中 class='compulsories'> 國中" +
                "            <div class='invalid-feedback crumb'>" +
                "                &nbsp;" +
                "            </div>" +
                "        </label> <label class=radio-inline>" +
                "            <input type=radio required name='school_or_course' value=國小 class='compulsories'> 國小" +
                "            <div class='invalid-feedback crumb'>" +
                "                &nbsp;" +
                "            </div>" +
                "        </label> <label class=radio-inline>" +
                "            <input type=radio required name='school_or_course' value=幼教 class='compulsories'> 幼教" +
                "            <div class='invalid-feedback crumb'>" +
                "                &nbsp;" +
                "            </div>" +
                "        </label> " +
                "    </div>" +
                "</div>" +
                "<div class='row form-group required'> " +
                "<label for='inputSubjectTeaches' class='col-md-2 control-label text-md-right'>任教科系/任教科目</label>" +
                "    <div class='col-md-10'>" +
                "        <input type=text required  name='subject_teaches' value='' class='form-control' id='inputSubjectTeaches'>" +
                "        <div class='invalid-feedback crumb'>" +
                "            請填寫任教科系/任教科目" +
                "        </div>" +
                "    </div>" +
                "</div>";

            document.getElementById("tip").innerHTML = '請先選擇任教機關/任教學程';
                
            /*************************************
             * 物件重建後需重新設定 event listener
             *************************************/
            categories = document.getElementsByName("school_or_course");
            for(let i = 0; i < categories.length; i++){
                categories[i].addEventListener("click", changeJobTitleList);
                categories[i].addEventListener("change", changeJobTitleList);
            }

            titles = document.getElementsByName("data[12]");
            for(let i = 0; i < titles.length; i++){
                titles[i].addEventListener("click", fillTheTitle);
                titles[i].addEventListener("change", fillTheTitle);
            }
        }

        function hideFields(){        
            rowIsEducating.innerHTML = '';
            document.getElementById("tip").innerHTML = '';
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

