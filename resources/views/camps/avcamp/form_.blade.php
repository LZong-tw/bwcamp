<?
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('camps.avcamp.layout')
@section('content')
    @include('partials.counties_areas_script')
    <div class='alert alert-info' role='alert'>
        您在本網站所填寫的個人資料，僅用於此次{{ $camp_data->abbreviation }}及後續主辦單位舉辦之活動聯絡之用。
        @if(now()->gt(\Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $batch->camp->registration_end . "23:59:59")) && (!isset($isModify) || !$isModify))
            <br><span class="text-danger">報名時間({{ $batch->camp->registration_end }})已經截止，您的報名將列為備取名單（若錄取將另外通知）。</span>
        @endif
    </div>

    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}線上報名表</h4>
    </div>
{{-- !isset($isModify): 沒有 $isModify 變數，即為報名狀態、 $isModify: 修改資料狀態 --}}
@if(!isset($isModify) || $isModify)
    <form method='post' action='{{ route('formSubmit', [$batch_id]) }}' id='Camp' name='Camp' class='form-horizontal needs-validation' role='form'>
{{-- 以上皆非: 檢視資料狀態 --}}
@else
    <form action="{{ route("queryupdate", $applicant_batch_id) }}" method="post" class="d-inline" name='Camp' >
@endif
    @csrf
    <div class='row form-group'>
        <div class='col-md-2'></div>
        <div class='col-md-10'>
            <span class='text-danger'>＊必填</span>
        </div>
    </div>
    <div class='row form-group'>
        <label for='inputBatch' class='col-md-2 control-label text-md-right'>營隊日期</label>
        <div class='col-md-10'>
            @if(isset($applicant_data))
                <h3>{{ $applicant_raw_data->batch->batch_start }} ~ {{ $applicant_raw_data->batch->batch_end }} </h3>
                <input type='hidden' name='applicant_id' value='{{ $applicant_id }}'>
            @else
                <h3>{{ $batch->batch_start }} ~ {{ $batch->batch_end }} </h3>
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
        <label for='inputName' class='col-md-2 control-label text-md-right'>姓名</label>
        <div class='col-md-10'>
            <input type='text' name='name' value='' class='form-control' id='inputName' placeholder='請填寫全名' required @if(isset($isModify) && $isModify) disabled @endif>
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
                    <input class="form-check-input" type="radio" name="gender" value="M" required @if(isset($isModify) && $isModify) disabled @endif>
                    男
                    <div class="invalid-feedback">
                        未選擇性別
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
        <label for='inputCell' class='col-md-2 control-label text-md-right'>行動電話</label>
        <div class='col-md-10'>
            <input type=tel required name='mobile' value='' class='form-control' id='inputCell' placeholder='格式：0912345678'>
            <div class="invalid-feedback">
                請填寫行動電話
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>電子郵件</label>
        <div class='col-md-10'>
            <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請務必填寫正確，以利營隊相關訊息通知'>
            <div class="invalid-feedback">
                郵件不正確
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
            <input type='email' required name='emailConfirm' value='' class='form-control' id='inputEmailConfirm' placeholder='請再次填寫確認'>
            {{-- data-match='#inputEmail' data-match-error='郵件不符合' placeholder='請再次填寫確認郵件填寫正確' --}}
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

    <input type='hidden' name="profile_agree" value='1'>
    <input type='hidden' name="portrait_agree" value='1'>

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
        function sleep (time) {
            return new Promise((resolve) => setTimeout(resolve, time));
        }
        $('[data-toggle="confirmation"]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            title: "敬請再次確認資料填寫無誤。",
            btnOkLabel: "正確無誤，送出",
            btnCancelLabel: "再檢查一下",
            popout: true,
            onConfirm: function() {
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

        function setMotivationOther(checkbox_ele) {
            // 檢查 checkbox_ele 是否被勾選
            //console.log(checkbox_ele.checked);
            if(checkbox_ele.checked) {
            // 被勾選: 把 language_other_text required = true
                document.getElementById("motivation_other_text").required = true;
            }
            else {
            // 否則:把 language_other_text required = false
                document.getElementById("motivation_other_text").required = false;
            }
        }

        function setBlisswisdomTypeOther(checkbox_ele) {
            // 檢查 checkbox_ele 是否被勾選
            //console.log(checkbox_ele.checked);
            if(checkbox_ele.checked) {
            // 被勾選: 把 language_other_text required = true
                document.getElementById("blisswisdom_type_other_text").required = true;
            }
            else {
            // 否則:把 language_other_text required = false
                document.getElementById("blisswisdom_type_other_text").required = false;
            }
        }

        @if(isset($applicant_data))
            {{-- 回填報名資料 --}}
            sleep(10).then(() => {
                (function() {
                    let applicant_data = JSON.parse('{!! $applicant_data !!}');
                    let inputs = document.getElementsByTagName('input');
                    let selects = document.getElementsByTagName('select');
                    let textareas = document.getElementsByTagName('textarea');
                    let complementPivot = 0;
                    let complementData = applicant_data["blisswisdom_type_complement"] ? applicant_data["blisswisdom_type_complement"].split("||/") : null;
                    // console.log(inputs);
                    for (var i = 0; i < selects.length; i++){
                        if(typeof applicant_data[selects[i].name] !== "undefined"){
                            if (selects[i].name == 'unit_subarea'){
                                continue;
                            }
                            selects[i].value = applicant_data[selects[i].name];
                            if (selects[i].name == 'unit_county'){
                                Address(applicant_data[selects[i].name], 'unit');
                                var selectElement = document.getElementById("unit_subarea");

                                // Get the options of the select element
                                var options = selectElement.options;

                                // Iterate through the options
                                for (var j = 0; j < options.length; j++) {

                                    // Get the text of the option
                                    var optionText = options[j].text;

                                    // Check if the text equals to the specific text
                                    if (optionText == applicant_data['unit_subarea']) {
                                        options[j].selected = true;
                                    }
                                }
                            }
                        }
                        if (selects[i].name == 'county'){
                            // Split the string into an array of characters.
                            var characters = applicant_data["address"].split('');

                            // Create an empty array to store the two elements.
                            var elements = [];

                            // Add the first three characters to the first element.
                            elements.push(characters.slice(0, 3).join(''));

                            // Add the last three characters to the second element.
                            elements.push(characters.slice(3).join(''));
                            selects[i].value = elements[0];
                            Address(elements[0]);
                            var selectElement = document.getElementById("subarea");
                            // Get the options of the select element
                            var options = selectElement.options;

                            // Iterate through the options
                            for (var j = 0; j < options.length; j++) {

                                // Get the text of the option
                                var optionText = options[j].text;

                                // Check if the text equals to the specific text
                                if (optionText == elements[1]) {
                                    options[j].selected = true;
                                }
                            }
                        }
                    }
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
                        else if(inputs[i].type == "hidden" && (inputs[i].name == 'address' || inputs[i].name == 'unit_address')){
                            inputs[i].value = applicant_data[inputs[i].name];
                        }
                        if(inputs[i].name == 'emailConfirm'){
                            inputs[i].value = applicant_data['email'];
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
            });

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
