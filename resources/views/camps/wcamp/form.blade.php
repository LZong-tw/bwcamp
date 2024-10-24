@php
    header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
@endphp
@extends('camps.wcamp.layout')
@section('content')
    @include('partials.counties_areas_script')
    <div class='alert alert-info' role='alert'>
        您在本網站所填寫的個人資料，僅用於此次「{{ $camp_data->fullName }}」的報名及活動聯絡之用。
    </div>

    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }} 線上報名表</h4>
    </div>
    <div class="alert alert-warning">
        <h5>正行時間 2024/12/7 ~ 12/8 全程參加</h5>
        <h5>11/28前繳交1份個人故事,郵寄於2025bwtrain@gmail.com</h5>
    </div>

{{-- !isset($isModify): 沒有 $isModify 變數，即為報名狀態、 $isModify: 修改資料狀態 --}}
@if(!isset($isModify) || $isModify)
    <form method='post' action='{{ route('formSubmit', [$batch_id]) }}' id='Camp' name='Camp' class='form-horizontal needs-validation' role='form'>
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
        <label for='inputBatch' class='col-md-2 control-label text-md-right'>梯次及日期</label>
        <div class='col-md-10'>
            @if(isset($applicant_data))
                <h3>{{ $applicant_raw_data->batch->name }} {{ $applicant_raw_data->batch->batch_start }} ~ {{ $applicant_raw_data->batch->batch_end }} </h3>
                <input type='hidden' name='applicant_id' value='{{ $applicant_id }}'>
            @else
                <h3>{{ $batch->name }} {{ $batch->batch_start }} ~ {{ $batch->batch_end }} </h3>
            @endif
        </div>
    </div>

    @if(isset($isModify))
        <div class='row form-group'>
            <label for='inputBatch' class='col-md-2 control-label text-md-right'>報名日期</label>
            <div class='col-md-10'>
                {{ $applicant_raw_data->created_at }}
            </div>
        </div>
    @endif

    <div class='row form-group required'>
        <label for='inputRegion' class='col-md-2 control-label text-md-right'>區域</label>
        <div class='col-md-10'>
        <select class='form-control' name='region' id='inputRegion'>
            <option value='台北' >台北</option>
            <option value='桃園' >桃園</option>
            <option value='新竹' >新竹</option>
            <option value='台中' >台中</option>
            <option value='雲嘉' >雲嘉</option>
            <option value='台南' >台南</option>
            <option value='高雄' >高雄</option>
            <option value='海外' >海外</option>
        </select>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputNationality' class='col-md-2 control-label text-md-right'>海外(請註明國家)</label>
        <div class='col-md-10'>
            <input type='text' name='nationality' value='' class='form-control' id='inputNationality'>
        </div>
        <div class="invalid-feedback">
            請填寫國家
        </div>
    </div>

    <hr>
    <h5 class='form-control-static'>推薦人基本資料：</h5>
    <br>

    <div class='row form-group required' >
        <label for='inputIntroducerName' class='col-md-2 control-label text-md-right text-info'>推薦人姓名</label>
        <div class='col-md-10'>
            <input type='text' required class='form-control' name='introducer_name' value='' id='inputIntroducerName'>
            <div class="invalid-feedback">
                請填寫推薦人姓名
            </div>
        </div>
    </div>

    <div class='row form-group required' >
        <label for='inputIntroducerParticipated' class='col-md-2 control-label text-md-right text-info '>推薦人相關訊息<br>(ex :班長, 幹部... )
        </label>
        <div class='col-md-10'>
            <input type='text' required class='form-control' name='introducer_participated' value='' id='inputIntroducerParticipated'>
            <div class="invalid-feedback">
            請填寫推薦人相關訊息
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputIntroducerPhone' class='col-md-2 control-label text-md-right text-info'>推薦人電話</label>
        <div class='col-md-10'>
            <input type='tel' required class='form-control' name='introducer_phone' value='' id='inputIntroducerPhone'>
            <div class="invalid-feedback">
                請填寫推薦人電話
            </div>
        </div>
    </div>

    <div class='row form-group  required'>
        <label for='inputIntroducerEmail' class='col-md-2 control-label text-md-right text-info'>推薦人<br>電子信箱</label>
        <div class='col-md-10'>
            <input type='email' required class='form-control' name='introducer_email' value='' id='inputIntroducerEmail'>
            <div class="invalid-feedback">
                未填電子信箱或格式不正確
            </div>
        </div>
    </div>

    <hr>
    <h5 class='form-control-static'>被推薦人(講師)基本資料：</h5>

    <div class='row form-group required'>
        <label for='inputName' class='col-md-2 control-label text-md-right'>姓名</label>
        <div class='col-md-10'>
            <input type='text' name='name' value='' class='form-control' id='inputName' placeholder='請填寫全名' required>
        </div>
        <div class="invalid-feedback">
            請填寫姓名
        </div>
    </div>

    <div class="row form-group required">
        <label for='inputGender' class='col-md-2 control-label text-md-right'>性別</label>
        <div class='col-md-10'>
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="M">
                    <input class="form-check-input" type='radio' name="gender" value="M" required @if(isset($isModify) && $isModify) disabled @endif>
                    男
                    <div class="invalid-feedback">
                        未選擇性別
                    </div>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="F">
                    <input class="form-check-input" type='radio' name="gender" value="F" required @if(isset($isModify) && $isModify) disabled @endif>
                    女
                    <div class="invalid-feedback">
                        &nbsp;
                    </div>
                </label>
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputMobile' class='col-md-2 control-label text-md-right'>聯絡電話</label>
        <div class='col-md-10'>
            <input type=tel required name='mobile' value='' class='form-control' id='inputMobile'>
            <div class="invalid-feedback">
                請填寫聯絡電話
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>電子信箱</label>
        <div class='col-md-10'>
            <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請務必填寫正確，以利培訓相關訊息通知'>
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

    <div class='row form-group required' >
    <label for='inputUnit' class='col-md-2 control-label text-md-right itemreg_long'>服務單位</label>
        <div class='col-md-10'>
            <input type=text class='form-control itemreq_long' name='unit' id='inputUnit' value=''  placeholder='若已退休，請填寫退休前資料'>
            <div class="invalid-feedback crumb">
                請填寫服務單位
            </div>
        </div>
    </div>

    <div class='row form-group required' >
    <label for='inputTitle' class='col-md-2 control-label text-md-right'>職稱</label>
        <div class='col-md-10'>
            <input type='text' class='form-control itemreq_long' name='title' id='inputTitle' value='' maxlength="40"  placeholder='若已退休，請填寫退休前資料'>
            <div class="invalid-feedback">
                請填寫職稱
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputLearningExperiences' class='col-md-2 control-label text-md-right'>學習狀況</label>
        <div class='col-md-10'>
            <textarea required class='form-control' rows=2 name='learning_experiences' id=inputLearningExperiences></textarea>
            <div class="invalid-feedback">
                請填寫學習狀況
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputVolunteerExpereiences' class='col-md-2 control-label text-md-right'>護持狀況</label>
        <div class='col-md-10'>
            <textarea required class='form-control' rows=2 name='volunteer_experiences' id=inputVolunteerExpereiences></textarea>
            <div class="invalid-feedback">
                請填寫護持狀況
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputSpeakExpereiences' class='col-md-2 control-label text-md-right'>講說經驗</label>
        <div class='col-md-10'>
            <textarea required class='form-control' rows=2 name='speak_experiences' id=inputSpeakExpereiences></textarea>
            <div class="invalid-feedback">
                請填寫講說經驗
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputCharacter' class='col-md-2 control-label text-md-right'>講說特質</label>
        <div class='col-md-10'>
            <textarea required class='form-control' rows=2 name='character' id=inputCharacter></textarea>
            <div class="invalid-feedback">
                請填寫講說特質
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputPotential' class='col-md-2 control-label text-md-right'>潛力特質<br>(ex:司儀,主持人,生命故事)</label>
        <div class='col-md-10'>
            <textarea required class='form-control' rows=2 name='potential' id=inputPotential></textarea>
            <div class="invalid-feedback">
                請填寫潛力特質
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputComments' class='col-md-2 control-label text-md-right'>備註說明</label>
        <div class='col-md-10'>
            <textarea required class='form-control' rows=2 name='comments' id=inputComments></textarea>
            <div class="invalid-feedback">
                請填寫備註說明
            </div>
        </div>
    </div>
    
    <!--- 同意書 -->
    <input type='hidden' name="profile_agree" value='0'>
    <input type='hidden' name="portrait_agree" value='0'>

    <!--- 預設參加 -->
    <input type='hidden' name="is_attend" value='1'>

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
                        if($('.favored_event :checkbox:checked').length < 0) {
                            event.preventDefault();
                            event.stopPropagation();
                            console.log('yes');
                            {{-- $('.favored_event .invalid-feedback').prop('display') = 1; --}}
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
{{--
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
--}}
        
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
                    else if(inputs[i].type == "text"){
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
