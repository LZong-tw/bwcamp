@php
    header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
    $regions = ['北區', '竹區', '中區', '高區'];
@endphp
@extends('camps.mcamp.layout')
@section('content')
    <div class='alert alert-info' role='alert'>
        您在本網站所填寫的個人資料，僅用於此次「{{ $camp_data->fullName }}」的報名及活動聯絡之用。
    </div>

    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }} 線上報名表</h4>
    </div>

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
        <label for='inputBatch' class='col-md-2 control-label text-md-right'>梯次及日期</label>
        <div class='col-md-10'>
            @if(isset($applicant_data))
                <h3>{{ $applicant_raw_data->batch->name }} {{ $applicant_raw_data->batch->batch_start }} ~ {{ $applicant_raw_data->batch->batch_end }} </h3>
                <input type='hidden' name='applicant_id' value='{{ $applicant_id }}'>
                <input type="hidden" name="region" value="@foreach($regions as $r) @if(\Str::contains($applicant_raw_data->batch->name, $r)){{ $r }} @break @endif @endforeach">
            @else
                <h3>{{ $batch->name }} {{ $batch->batch_start }} ~ {{ $batch->batch_end }} </h3>
                <input type="hidden" name="region" value="@foreach($regions as $r) @if(\Str::contains($batch->name, $r)){{ $r }} @break @endif @endforeach">
            @endif
        </div>
    </div>

    @if(isset($isModify))
        <div class='row form-group'>
            <label for='inputCreateAt' class='col-md-2 control-label text-md-right'>報名日期</label>
            <div class='col-md-10'>
                {{ $applicant_raw_data->created_at }}
            </div>
        </div>
    @endif

    <div class='row form-group required'>
        <label for='inputName' class='col-md-2 control-label text-md-right'>姓名</label>
        <div class='col-md-10'>
            <input required type='text' name='name' value='' class='form-control' id='inputName' placeholder='請填寫全名'>
	<div class="invalid-feedback">
            請填寫姓名
        </div>
	</div>
    </div>

    <div class="row form-group required">
        <label for='inputGender' class='col-md-2 control-label text-md-right'>生理性別</label>
        <div class='col-md-10'>
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="M">
                    <input required class="form-check-input" type="radio" name="gender" value="M">男
                    <div class="invalid-feedback">
                        請選擇生理性別
                    </div>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="F">
                    <input required class="form-check-input" type="radio" name="gender" value="F">女
                    <div class="invalid-feedback">
                        &nbsp;
                    </div>
                </label>
            </div>
        </div>
    </div>


    <div class='row form-group required'>
        <label for='inputBirthYear' class='col-md-2 control-label text-md-right'>出生年</label>
        <div class='date col-md-10' id='inputBirthYear'>
            <div class='row form-group'>
                <div class="col-md-1">
                    西元
                </div>
                <div class="col-md-3">
                    <input required type='number' class='form-control' name='birthyear' min='{{ \Carbon\Carbon::now()->subYears(80)->year }}' max='{{ \Carbon\Carbon::now()->subYears(16)->year }}' value='' placeholder=''>
                    <div class="invalid-feedback">
                        未填寫或日期不正確
                    </div>
                </div>
                <div class="col-md-1">
                    年
                </div>
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>Email</label>
        <div class='col-md-10'>
            <input required type='email' name='email' value='' class='form-control' id='inputEmail' placeholder='請務必填寫正確，以利相關訊息通知'>
            <div class="invalid-feedback">
                未填寫Email或格式不正確
            </div>
        </div>
    </div>

    <script language='javascript'>
        $('#inputEmail').bind("cut copy paste",function(e) {
        e.preventDefault();
        });
    </script>
    
    <div class='row form-group required'>
        <label for='inputEmailConfirm' class='col-md-2 control-label text-md-right'>確認Email</label>
        <div class='col-md-10'>
            <input required type='email' name='emailConfirm' value='' class='form-control' id='inputEmailConfirm' placeholder='請再次填寫(勿複製貼上)，確認電子信箱正確'>
            {{-- data-match='#inputEmail' data-match-error='郵件不符合' placeholder='請再次填寫確認郵件填寫正確' --}}
            <div class="invalid-feedback">
                未填Email或格式不正確
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputCell' class='col-md-2 control-label text-md-right'>手機號碼</label>
        <div class='col-md-10'>
            <input required type=tel name='mobile' value='' class='form-control' id='inputCell' placeholder='格式：0912345678'>
            <div class="invalid-feedback">
                請填寫手機號碼
            </div>
        </div>
    </div>

    <div class='row form-group required'>
    <label for='inputUnit' class='col-md-2 control-label text-md-right'>服務機構</label>
        <div class='col-md-10'>
            <input required type=text name='unit' value='' class='form-control' id='inputUnit'>
            <div class="invalid-feedback crumb">
                請填寫服務機構
            </div>
        </div>
    </div>

    <div class='row form-group required'>
    <label for='inputTitle' class='col-md-2 control-label text-md-right'>職稱</label>
        <div class='col-md-10'>
            <input required type=text name='title' value='' class='form-control' id='inputTitle'>
            <div class="invalid-feedback crumb">
                請填寫職稱
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputStatus' class='col-md-2 control-label text-md-right'>身分別</label>
        <div class='col-md-10'>
            <label class='radio-inline'>
                <input type=radio required name='status' value='第一次參加'> 第一次參加　
                <div class="invalid-feedback">
                    請選擇身分
                </div>
            </label>
            <label class='radio-inline'>
                <input type=radio required name='status' value='已參加過'> 已參加過　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class='radio-inline'>
                <input type=radio required name='status' value='福智學員'> 福智學員(福智學員請一律點選此項)
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputID' class='col-md-2 control-label text-md-right'>身份證字號</label>
        <div class='col-md-10'>
            <input type='text' name='idno' value='' class='form-control' id='inputID' placeholder='選填，若要申請教育積分務必填寫'>
            <div class="invalid-feedback">
                未填寫身份證字號（申請教育積分用）
            </div>
        </div>
    </div>
    
    <div class='row form-group required'>
        <label for='inputMedicalSpecialty' class='col-md-2 control-label text-md-right'>職種類別</label>
        <div class='col-md-10'>
            <p class='form-control-static text-info'>醫事人員積分申請用</p>
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=西醫師> 西醫師　
                <div class="invalid-feedback">
                    請選擇職種類別
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=中醫師> 中醫師　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=牙醫師> 牙醫師　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=藥師> 藥師　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=專科護理師> 專科護理師　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=護理師(士)> 護理師(士)　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=物理治療師(生)> 物理治療師(生)　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=職能治療師(生)> 職能治療師(生)　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=醫事檢驗師> 醫事檢驗師　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=醫事放射師> 醫事放射師　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=營養師> 營養師　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=助產師> 助產師　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=心理師> 心理師　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=呼吸治療師> 呼吸治療師　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=語言治療師> 語言治療師　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=聽力師> 聽力師　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='medical_specialty' value=不申請積分> 不申請積分或非醫事人員　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputWordCategory' class='col-md-2 control-label text-md-right'>職種類別</label>
        <div class='col-md-10'>
            <p class='form-control-static text-info'>長照人員積分申請用</p>
            <label class=radio-inline>
                <input type=radio required name='work_category' value=照顧服務人員 > 照顧服務人員　
                <div class="invalid-feedback">
                    請選擇職種類別
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='work_category' value=居家服務督導員 > 居家服務督導員　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='work_category' value=社會工作師、社會工作人員及醫事人員> 社會工作師、社會工作人員及醫事人員　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='work_category' value=照顧管理專員及照顧管理督導> 照顧管理專員及照顧管理督導　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='work_category' value=長照服務相關計畫之人員(個案管理師)> 長照服務相關計畫之人員(個案管理師)　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='work_category' value=不限(多重身份)> 不限(多重身份)　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='work_category' value=不申請積分> 不申請積分或非長照人員　
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <!--- 同意書 -->
    <div class='row form-group required'>
        <label for='inputProfile' class='col-md-2 control-label text-md-right'>個人資料</label>
        <div class='col-md-10 form-check'>
            <p class='form-control-static'>
            <b>本人同意以上個資提供此次營隊，作為訊息通知、行政處理等非營利目的之使用，不會提供給無關之其他私人單位：</b>
            </p>
            <label class=radio-inline>
                <input type=radio required name="profile_agree" value='1'> 我同意　
                <div class="invalid-feedback">
                    請圈選本欄位
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name="profile_agree" value='0'> 我不同意
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputPortrait' class='col-md-2 control-label text-md-right'>肖像權</label>
        <div class='col-md-10 form-check'>
            <p class='form-control-static'>
            <b>本人同意主辦單位在營隊期間拍攝之照片、錄影【肖像權】提供主辦單位非營利教育推廣使用，並以網路方式推播：</b>
            </p>
            <label class=radio-inline>
                <input type='radio' required name="portrait_agree" value='1'> 我同意　
                <div class="invalid-feedback">
                    請圈選本欄位
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name="portrait_agree" value='0'> 我不同意
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

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
