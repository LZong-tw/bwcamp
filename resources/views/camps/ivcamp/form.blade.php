{{-- 
    參考頁面：https://youth.blisswisdom.org/camp/winter/form/index_addto.php
    --}}
<?
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('camps.ivcamp.layout')
@section('content')
    @include('partials.schools_script')
    @include('partials.counties_areas_script')
    @if(!isset($isBackend))
        <div class='alert alert-info' role='alert'>
            您在本網站所填寫的個人資料，僅用於此次法會的報名及活動聯絡之用。
        </div>
    @endif

    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }} 線上報名表</h4>
    </div>
{{-- !isset($isModify): 沒有 $isModify 變數，即為報名狀態、 $isModify: 修改資料狀態 --}}
@if(!isset($isModify) || $isModify)
    <form method='post' action='{{ route("formSubmit", [$batch_id]) }}' id='Camp' name='Camp' class='form-horizontal needs-validation' role='form'>
{{-- 以上皆非: 檢視資料狀態 --}}
@else
    <form action='{{ route("queryupdate", $applicant_batch_id) }}' method="post" class="d-inline">
@endif
    @csrf
    <div class='row form-group'>
        <div class='col-md-2'></div>
        <div class='col-md-10'>
            <span class='text-danger'>＊必填</span>
        </div>
    </div>

    @if(isset($applicant_data))
    <div class='row form-group'>
        <label for='inputBatch' class='col-md-2 control-label text-md-right'>營隊日期</label>
        <div class='col-md-10'>
                <h3>{{ $applicant_raw_data->batch->batch_start }} ~ {{ $applicant_raw_data->batch->batch_end }} </h3>
                {{-- <h3>線上 {{ $applicant_raw_data->batch->batch_start }} ~ {{ $applicant_raw_data->batch->batch_end }} </h3> --}}
                <input type='hidden' name='applicant_id' value='{{ $applicant_id }}'>
        </div>
    </div>
    @endif

    @if(isset($isModify))
        <div class='row form-group'>
            <label for='inputBatch' class='col-md-2 control-label text-md-right'>報名日期</label>
            <div class='col-md-10'>
                {{ $applicant_raw_data->created_at }}
            </div>
        </div>
    @endif
    <div class='row form-group required'>
        <label for='inputName' class='col-md-2 control-label text-md-right'>姓名</label>
        <div class='col-md-10'>
            <input type='text' name='name' value='' class='form-control' id='inputName' placeholder='請填寫全名' required @if(isset($isModify) && $isModify) disabled @endif>
            <div class="invalid-feedback">
                請填寫姓名
            </div>
       </div>
    </div>

    <div class='row form-group required'> 
    <label for='inputLRClass' class='col-md-2 control-label text-md-right'>廣論研討班別</label>
        <div class='col-md-10'>
            <input type='text' required name='lrclass' value='' class='form-control' id='inputLRClass' placeholder='請詳填廣論研討班別，例：北14宗001班'>
            <div class="invalid-feedback">
                請詳填廣論研討班別，例：北14宗001班
            </div>
        </div>
    </div>

    <div class="row form-group required">
        <label for='inputGender' class='col-md-2 control-label text-md-right'>生理性別</label>
        <div class='col-md-10'>
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="M">
                    <input class="form-check-input" type="radio" name="gender" value="M" required @if(isset($isModify) && $isModify) disabled @endif>
                    男
                    <div class="invalid-feedback">
                        未選擇生理性別
                    </div>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="F">
                    <input class="form-check-input" type="radio" name="gender" value="F" required @if(isset($isModify) && $isModify) disabled @endif>
                    女
                    <div class="invalid-feedback">
                        &nbsp;
                    </div>
                </label>
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inr>putBirth' class='col-md-2 control-label text-md-right'>出生年月日<br>(投保用)</label>
        <div class='date col-md-10' id='inputBirth'>
            <div class='row form-group required'>
                <div class="col-md-1">
                    西元
                </div>
                <div class="col-md-3">
                    <input type='number' required class='form-control' name='birthyear' min='{{ \Carbon\Carbon::now()->subYears(90)->year }}' max='{{ \Carbon\Carbon::now()->subYears(10)->year }}' value='' placeholder=''>
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
        <label for='inputID' class='col-md-2 control-label text-md-right'>身分證字號<br>(投保用)</label>
        <div class='col-md-10'>
            <input type='text' name='idno' value='' class='form-control' id='inputID' placeholder='' required>
            <div class="invalid-feedback">
                請填寫身份證字號
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

    <div class='row form-group'> 
    <label for='inputLineID' class='col-md-2 control-label text-md-right'>LINE ID</label>
        <div class='col-md-10'>
            <input type='text' name='line' value='' class='form-control' id='inputLineID'>
            <div class="invalid-feedback crumb">
                請填寫LINE ID
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>電子郵件</label>
        <div class='col-md-10'>
            <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請務必填寫正確，以利法會相關訊息通知' @if(isset($isModify) && $isModify) disabled @endif>
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
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>確認電子郵件</label>
        <div class='col-md-10'>
            <input type='email' required  name='emailConfirm' value='' class='form-control' id='inputEmailConfirm' placeholder='請再次填寫(勿複製貼上)，確認電子信箱正確' @if(isset($isModify) && $isModify) disabled @endif>
            {{-- data-match='#inputEmail' data-match-error='郵件不符合' placeholder='請再次填寫確認郵件填寫正確' --}}
            <div class="invalid-feedback">
                未填電子信箱或格式不正確
            </div>
        </div>
    </div>

    {{-- 專長 --}}
    <div class='row form-group required'>
        <label for='inputExpertise' class='col-md-2 control-label text-md-right'>專長(多選)</label>
        <div class='col-md-10 required'>
            <label><input type="checkbox" name=expertise[] value='電腦文書處理' > 電腦文書處理</label> <br/>
            <label><input type="checkbox" name=expertise[] value='視聽操作' > 視聽操作</label> <br/>
            <label><input type="checkbox" name=expertise[] value='影片剪輯' > 影片剪輯</label> <br/>
            <label><input type="checkbox" name=expertise[] value='拍照/攝影' > 拍照/攝影</label> <br/>
            <label><input type="checkbox" name=expertise[] value='主持/活動帶動' > 主持/活動帶動</label> <br/>
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

    <div class='row form-group required'>
        <label for='inputGroupPriority1' class='col-md-2 control-label text-md-right'>護持組別第1志願</label>
        <div class='col-md-10'>
            <p class='form-control-static text-info'>
            說明：尊重各位的志願選填，會優先考量，但最終分組會依各組需求人數安排，若無法如願，敬請見諒！</p>
            <select required class='form-control' name='group_priority1' onChange=''>
                <option value='' selected>- 請選擇 -</option>
                <option value='秘書組' >秘書組</option>
                <option value='教育組' >教育組</option>
                <option value='由大組安排即可' >由大組安排即可</option>
            </select>
            <div class="invalid-feedback">
                請選擇護持組別第1志願
            </div>
        </div>  
    </div>

    <div class='row form-group'>
        <label for='inputIntroducerName' class='col-md-2 control-label text-md-right'>介紹人<br>(若無免填)</label>
        <div class='col-md-10'>
            <input type='text' name='introducer_name' value='' class='form-control' id='inputIntroducerName' placeholder='若您今年是透過某位師兄師姊的邀請加入義工，請填寫他/她的姓名'>
            <div class="invalid-feedback">
                請填寫介紹人姓名
            </div>
        </div>
    </div>

    <input type='hidden' name="profile_agree" value='0'>
    <input type='hidden' name="portrait_agree" value='0'>

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
                <input type='button' class='btn btn-success' value='確認送出' data-toggle="confirmation">
                <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                <input type='reset' class='btn btn-danger' value='清除再來'>
            {{-- 以上皆非: 檢視資料狀態 --}}
            @elseif(!isset($isBackend))
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

        /**
        * Ready functions.
        * Executes commands after the web page is loaded. 
        */
        document.onreadystatechange = () => {
            if (document.readyState === 'complete') {
                document.getElementById('blisswisdom_type_other_checkbox').addEventListener("change", function(){
                    document.Camp.blisswisdom_type_other.required = document.getElementById('blisswisdom_type_other_checkbox').checked;
                });
            }
        };

        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();        
    
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

        @if(isset($applicant_data))
            {{-- 回填報名資料 --}}
            (function() {
                {{-- 開啟父母及介紹人資料欄位以免漏填 --}}
                //parent_field(1);
                //referer_field(1);
                let applicant_data = JSON.parse('{!! $applicant_data !!}');
                let inputs = document.getElementsByTagName('input');
                let selects = document.getElementsByTagName('select');
                let textareas = document.getElementsByTagName('textarea');
                console.log(inputs); 
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
                                    if( checkboxes[j].type == "text"){
                                        checkboxes[j].value = checkedValues[j - 1];  
                                    }
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
                    else if(inputs[i].type == "text" && inputs[i].name != 'blisswisdom_type[]'){
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
                {{-- 填完資料，檢查是否有父母或介紹人資料，若無則關閉 --}}
                let father_name = document.getElementsByName("father_name")[0].value;
                let father_lamrim = document.getElementsByName("father_lamrim")[0].value;
                let father_phone = document.getElementsByName("father_phone")[0].value;
                let mother_name = document.getElementsByName("mother_name")[0].value;
                let mother_lamrim = document.getElementsByName("mother_lamrim")[0].value;
                let mother_phone = document.getElementsByName("mother_phone")[0].value;
                let parents = [father_name, father_lamrim, father_phone, mother_name, mother_lamrim, mother_phone];
                if(parents.every(checkIfNull)){
                    parent_field(0);
                }
                let introducer_name = document.getElementsByName("introducer_name")[0].value;
                let introducer_relationship = document.getElementsByName("introducer_relationship")[0].value;
                let introducer_participated = document.getElementsByName("introducer_participated")[0].value;
                let introducer_phone = document.getElementsByName("introducer_phone")[0].value;
                let introducer = [introducer_name, introducer_relationship, introducer_participated, introducer_phone];
                if(introducer.every(checkIfNull)){
                    referer_field(0);
                }

                @if(!$isModify)
                    for (var i = 0; i < inputs.length; i++){
                        if(typeof applicant_data[inputs[i].name] !== "undefined" || inputs[i].type == "checkbox"){
                            inputs[i].disabled = true;
                        }
                        if(inputs[i].name == 'emailConfirm'){
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
{{-- 
    參考頁面：https://youth.blisswisdom.org/camp/winter/form/index_addto.php
    --}}
