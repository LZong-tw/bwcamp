@php
    header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
    $regions = ['台北', '桃園', '新竹', '中區', '雲嘉', '台南', '高區'];
@endphp
@extends('camps.scamp.layout')
@section('content')
    @include('partials.counties_areas_script')
    <div class='alert alert-info' role='alert'>
        您在本網站所填寫的個人資料，僅用於此次「{{ $camp_data->fullName }}」的報名及活動聯絡之用。
    </div>

    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }} 線上報名表</h4>
        課程資訊聯絡人：0918627969 許小姐<br>
        　　　　　　　　0918031509 李小姐<br>
        　　　　　　　　0939349349 陳小姐<br>
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
        <label for='inputName' class='col-md-2 control-label text-md-right'>報名者姓名</label>
        <div class='col-md-10'>
            <input required type='text' name='name' value='' class='form-control' id='inputName' placeholder='請填寫全名' >
        </div>
        <div class="invalid-feedback">
            請填寫姓名
        </div>
    </div>
    <div class='row form-group required'>
        <label for='inputEngName' class='col-md-2 control-label text-md-right'>英文姓名</label>
        <div class='col-md-10'>
            <input required type='text' name='english_name' value='' class='form-control' id='inputEngName' placeholder='證書需要，請同護照'>
        </div>
        <div class="invalid-feedback">
            請填寫英文姓名
        </div>
    </div>
    <div class='row form-group required'>
        <label for='inputID' class='col-md-2 control-label text-md-right'>身份證字號</label>
        <div class='col-md-10'>
            <input type='text' name='idno' value='' class='form-control' id='inputID' placeholder='證書需要，敬請確實填寫'>
            <div class="invalid-feedback">
                未填寫身份證字號（申請證書用）
            </div>
        </div>
    </div>
    <div class="row form-group required">
        <label for='inputGender' class='col-md-2 control-label text-md-right'>生理性別</label>
        <div class='col-md-10'>
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="M">
                    <!--
                    <input class="form-check-input" type="radio" name="gender" value="M" required @if(isset($isModify) && $isModify) disabled @endif>
                    -->
                    <input class="form-check-input" type="radio" name="gender" value="M" required>
                    男
                    <div class="invalid-feedback">
                        未選擇生理性別
                    </div>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="F">
                    <!--
                    <input class="form-check-input" type="radio" name="gender" value="F" required @if(isset($isModify) && $isModify) disabled @endif>
                    -->
                    <input class="form-check-input" type="radio" name="gender" value="F" required>
                    女
                    <div class="invalid-feedback">
                        &nbsp;
                    </div>
                </label>
            </div>
        </div>
    </div>
    <div class='row form-group'>
        <label for='inputBirth' class='col-md-2 control-label text-md-right'>出生年</label>
        <div class='date col-md-10' id='inputBirth'>
            <div class='row form-group'>
                <div class="col-md-1">
                    西元
                </div>
                <div class="col-md-3">
                    <input type='number' class='form-control' name='birthyear' min='{{ \Carbon\Carbon::now()->subYears(80)->year }}' max='{{ \Carbon\Carbon::now()->subYears(16)->year }}' value='' placeholder=''>
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
    <div class='row form-group'>
        <label for='inputTelHome' class='col-md-2 control-label text-md-right'>聯絡電話</label>
        <div class='col-md-10'>
            <input type=tel name='phone_home' value='' class='form-control' id='inputTelHome' placeholder='格式：0225452546#520'>
            <div class="invalid-feedback">
                請填寫聯絡電話
            </div>
        </div>
    </div>
    <div class='row form-group required'>
        <label for='inputCell' class='col-md-2 control-label text-md-right'>行動電話</label>
        <div class='col-md-10'>
            <input required type=tel name='mobile' value='' class='form-control' id='inputCell' placeholder='格式：0912345678'>
            <div class="invalid-feedback">
                請填寫行動電話
            </div>
        </div>
    </div>

    <div class='row form-group'>
    <label for='inputLineID' class='col-md-2 control-label text-md-right'>LINE ID</label>
        <div class='col-md-10'>
            <input type=text name='line' value='' class='form-control' id='inputLineID'>
            <div class="invalid-feedback crumb">
                請填寫LINE ID
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputAddress' class='col-md-2 control-label text-md-right'>紙本證書郵寄地址</label>
    </div>
    <div class='row form-group'>
        <div class='col-md-2'></div>
        <div class='col-md-2'>
            <select name="county" class="form-control" onChange="Address(this.options[this.options.selectedIndex].value);"> 
                <option value=''>請選縣市...</option>
                <option value='臺北市'>臺北市</option>
                <option value='新北市'>新北市</option>
                <option value='基隆市'>基隆市</option>
                <option value='宜蘭縣'>宜蘭縣</option>
                <option value='花蓮縣'>花蓮縣</option>
                <option value='桃園市'>桃園市</option>
                <option value='新竹市'>新竹市</option>
                <option value='新竹縣'>新竹縣</option>
                <option value='苗栗縣'>苗栗縣</option>
                <option value='臺中市'>臺中市</option>
                <option value='彰化縣'>彰化縣</option>
                <option value='南投縣'>南投縣</option>
                <option value='雲林縣'>雲林縣</option>
                <option value='嘉義市'>嘉義市</option>
                <option value='嘉義縣'>嘉義縣</option>
                <option value='臺南市'>臺南市</option>
                <option value='高雄市'>高雄市</option>
                <option value='屏東縣'>屏東縣</option>
                <option value='臺東縣'>臺東縣</option>
                <option value='澎湖縣'>澎湖縣</option>
                <option value='金門縣'>金門縣</option>
                <option value='連江縣'>連江縣</option>
                <option value='南海諸島'>南海諸島</option>
                <option value='海外'>海外</option>
            </select>
        </div>
        <div class='col-md-2'>
            <select name=subarea class='form-control' onChange='document.Camp.zipcode.value=this.options[this.options.selectedIndex].value; document.Camp.address.value=MyAddress(document.Camp.county.value, this.options[this.options.selectedIndex].text);'>
                <option value=''>區鄉鎮...</option>
            </select>
        </div>
        <div class='col-md-1'>
            <input readonly type=text name=zipcode value='' class='form-control'>
        </div>
        <div class='col-md-3'>
            <input required type=text name='address' value='' pattern=".{10,80}" class='form-control' placeholder='海外請自行填寫國家及區域'>
            <div class="invalid-feedback">
                請填寫通訊地址或檢查輸入的地址是否不齊全
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>電子郵件</label>
        <div class='col-md-10'>
            <!--
            <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請務必填寫正確，以利相關訊息通知' @if(isset($isModify) && $isModify) disabled @endif>
            -->
            <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請務必填寫正確，以利相關訊息通知'>
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
        <label for='inputEmailConfirm' class='col-md-2 control-label text-md-right'>確認電子郵件</label>
        <div class='col-md-10'>
            <!--
            <input type='email' required  name='emailConfirm' value='' class='form-control' id='inputEmailConfirm' placeholder='請再次填寫(勿複製貼上)，確認電子信箱正確' @if(isset($isModify) && $isModify) disabled @endif>
            -->
            <input type='email' required  name='emailConfirm' value='' class='form-control' id='inputEmailConfirm' placeholder='請再次填寫(勿複製貼上)，確認電子信箱正確'>
            {{-- data-match='#inputEmail' data-match-error='郵件不符合' placeholder='請再次填寫確認郵件填寫正確' --}}
            <div class="invalid-feedback">
                未填電子信箱或格式不正確
            </div>
        </div>
    </div>

    <div class='row form-group required'>
    <label for='inputUnit' class='col-md-2 control-label text-md-right'>服務單位(全名)</label>
        <div class='col-md-10'>
            <input type=text required name='unit' value='' class='form-control' id='inputUnit'>
            <div class="invalid-feedback crumb">
                請填寫服務單位
            </div>
        </div>
    </div>

    <div class='row form-group'>
    <label for='inputAddressWork' class='col-md-2 control-label text-md-right'>服務單位地址</label>
        <div class='col-md-10'>
            <input type=text name='address_work' value='' class='form-control' id='inputAddressWork'>
            <div class="invalid-feedback crumb">
                請填寫服務單位地址
            </div>
        </div>
    </div>

    <div class='row form-group required'>
    <label for='inputDepartment' class='col-md-2 control-label text-md-right'>服務部門</label>
        <div class='col-md-10'>
            <input type=text required name='department' value='' class='form-control' id='inputDepartment'>
            <div class="invalid-feedback crumb">
                請填寫服務部門
            </div>
        </div>
    </div>

    <div class='row form-group'>
    <label for='inputTitle' class='col-md-2 control-label text-md-right'>職稱</label>
        <div class='col-md-10'>
            <input type=text name='title' value='' class='form-control' id='inputTitle'>
            <div class="invalid-feedback crumb">
                請填寫職稱
            </div>
        </div>
    </div>

    <div class='row form-group'>
    <label for='inputSeniority' class='col-md-2 control-label text-md-right'>服務年資</label>
        <div class='col-md-10'>
            <input type=number class='form-control' name='seniority' min=0 max=80 value='' placeholder=''>
            <div class="invalid-feedback crumb">
                請填寫服務年資
            </div>
        </div>
    </div>
    
    <div class='row form-group required'>
        <label for='inputWay' class='col-md-2 control-label text-md-right'>如何得知本課程訊息</label>
        <div class='col-md-10'>
            <p class='form-control-static text-info'>單選，請選最主要管道。</p>
            <label class=radio-inline>
                <input type=radio required name='way' value=Line宣傳 onClick='select_source(0);'> Line宣傳
                <div class="invalid-feedback">
                    請選擇如何得知管道
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=臉書等社群網站 onClick='select_source(1);'> 臉書等社群網站
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=電子郵件 onClick='select_source(2);'> 電子郵件
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=書面文宣 onClick='select_source(3);'> 書面文宣
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=親友介紹 onClick='select_source(4);'> 親友介紹
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=其它 onClick='select_source(5);'> 其它
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <div class='row form-group required introducer_name' style="display:none">
        <label for='inputIntroducerName' class='col-md-2 control-label text-md-right'>課程介紹人</label>
        <div class='col-md-10'>
            <input type='text' required name='introducer_name' value='' class='form-control' id='inputIntroducerName'>
            <div class="invalid-feedback">
                請填寫介紹人姓名
            </div>
        </div>
    </div>

    <div class='row form-group required way_other' style="display:none">
        <label for='inputWayOther' class='col-md-2 control-label text-md-right'>得知管道：自填</label>
        <div class='col-md-10'>
            <input type='text' required name='way_other' value='' class='form-control' id='inputWayOther'>
            <div class="invalid-feedback">
                請自填得知管道
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputExpectation' class='col-md-2 control-label text-md-right'>對本次課程的期待</label>
        <div class='col-md-10'>
            <textarea class='form-control' rows=2 name='expectation' id=inputExpect></textarea>
            <div class="invalid-feedback">
                請填寫對本次課程的期待
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputAllowInformed' class='col-md-2 control-label text-md-right'>是否願意收到後續相關課程資訊</label>
        <div class="col-md-10">
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" name="is_allow_informed" value="1">
                    是
                    <div class="invalid-feedback">
                        請選擇一項
                    </div>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" name="is_allow_informed" value="0">
                    否
                    <div class="invalid-feedback">
                        &nbsp;
                    </div>
                </label>
            </div>
        </div>
    </div>
    
    <div class='row form-group required'>
        <label for='inputExamFormat' class='col-md-2 control-label text-md-right'>考證方式(單選)</label>
        <div class="col-md-10">
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" required name="exam_format" value="筆電">
                    筆電(裝置自備)
                    <div class="invalid-feedback">
                        請選擇一項
                    </div>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" required name="exam_format" value="平板">
                    平板(裝置自備)
                    <div class="invalid-feedback">
                        &nbsp;
                    </div>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" required name="exam_format" value="手機">
                    手機(裝置自備)
                    <div class="invalid-feedback">
                        &nbsp;
                    </div>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" required name="exam_format" value="紙本">
                    紙本
                    <div class="invalid-feedback">
                        &nbsp;
                    </div>
                </label>
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputLast5' class='col-md-2 control-label text-md-right'>匯款帳號後五碼</label>
        <div class='col-md-10'>
            <p class='form-control-static text-info'>
            說明：考證費用為台幣 $500，請將費用匯至以下匯款帳號，並填寫您繳款的帳號後五碼。<br>板信商業銀行(118)<br>匯款帳號：0914-5-00000510-7</p>
            <input type=number required class='form-control' name='last5' min=0 max=99999 value='' placeholder=''>
            <div class="invalid-feedback crumb">
                請填寫匯款帳號後五碼
            </div>
        </div>
    </div>

    <input type='hidden' name="portrait_agree" value='0'>
    <input type='hidden' name="profile_agree" value='0'>

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
        
        function select_source(way){        
            tg_intro = document.getElementsByClassName('introducer_name');
            tg_way = document.getElementsByClassName('way_other');
            console.log(tg_intro);
            console.log(tg_way);
            if (way==4) {
                document.getElementById("inputIntroducerName").required = true;
                document.getElementById("inputWayOther").required = false;
                tg_intro[0].style.display = '';
                tg_way[0].style.display = 'none';
            } else if (way==5) {
                document.getElementById("inputIntroducerName").required = false;
                document.getElementById("inputWayOther").required = true;
                inputWayOther
                tg_intro[0].style.display = 'none';
                tg_way[0].style.display = '';
            } else {
                document.getElementById("inputIntroducerName").required = false;
                document.getElementById("inputWayOther").required = false;
                tg_intro[0].style.display = 'none';
                tg_way[0].style.display = 'none';
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
