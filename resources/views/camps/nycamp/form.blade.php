{{-- 
    參考頁面：https://dc1006.wixstudio.com/2026nycamp/signup
--}}
<?php
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('camps.nycamp.layout')
@section('content')
    @include('partials.counties_areas_script')
    @if(!isset($isBackend))
        <div class='alert alert-info' role='alert'>
            The information you provide in this page is only for the registration and contact of this camp.<br>
            您在本網站所填寫的個人資料，僅用於此次大專營的報名及活動聯絡之用。
        </div>
    @endif

    <div class='page-header form-group'>
        <h4>REGISTRATION</h4>
        <h4>{{ $camp_data->fullName }}線上報名表</h4>
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
            <span class='text-danger'>＊Required 必填</span>
        </div>
    </div>


    @if(isset($applicant_data))
    <!--div class='row form-group'>
        <label for='inputBatch' class='col-md-2 control-label text-md-right'>營隊梯次</label>
        <div class='col-md-10'>
            <h3>{{ $applicant_raw_data->batch->name . '梯' }} {{ $applicant_raw_data->batch->batch_start }} ~ {{ $applicant_raw_data->batch->batch_end }} </h3>
            {{-- <h3>線上 {{ $applicant_raw_data->batch->batch_start }} ~ {{ $applicant_raw_data->batch->batch_end }} </h3> --}}
        </div>
    </div-->
    <input type='hidden' name='applicant_id' value='{{ $applicant_id }}'>
    @endif


    @if(isset($isModify))
        <div class='row form-group'>
            <label for='inputRegistrationDate' class='col-md-2 control-label text-md-right'>報名日期</label>
            <div class='col-md-10'>
                {{ $applicant_raw_data->created_at }}
            </div>
        </div>
    @endif

    <div class='row form-group required'>
        <label for='inputEngName' class='col-md-2 control-label text-md-right'>Name<br>英文姓名</label>
        <div class='col-md-5'>
            <input required type='text' name='english_name' value='' class='form-control' id='inputEngFirstName' placeholder='English First Name 英文名字'>
        </div>
        <div class="invalid-feedback">
            This field is required. 請填寫英文名字
        </div>
        <div class='col-md-5'>
            <input required type='text' name='english_last_name' value='' class='form-control' id='inputEngLastName' placeholder='English Last Name 英文姓氏'>
        </div>
        <div class="invalid-feedback">
            This field is required. 請填寫英文姓氏
        </div>
    </div>
    
    <div class='row form-group'>
        <div class='col-md-2'>
        </div>
        <div class='col-md-10'>
            <label class='text-info'>Please fill in your Chinese name if applicable.
            如果您有中文姓名，請填寫，若無則免填。</label>
        </div>
        <label for='inputChnName' class='col-md-2 control-label text-md-right'>Chinese Name<br>中文姓名</label>
        <div class='col-md-5'>
            <input type='text' name='chinese_first_name' value='' class='form-control' id='inputChnFirstName' placeholder='Chinese First Name 名字'>
        </div>
        <!--div class="invalid-feedback">
            This field is required. 請填寫中文名字
        </div-->
        <div class='col-md-5'>
            <input type='text' name='chinese_last_name' value='' class='form-control' id='inputChnLastName' placeholder='Chinese Last Name 姓氏'>
        </div>
        <!--div class="invalid-feedback">
            This field is required. 請填寫英文姓氏
        </div-->
    </div>

    <div class="row form-group required">
        <label for='inputGender' class='col-md-2 control-label text-md-right'>Gender<br>生理性別</label>
        <div class='col-md-10'>
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="M">
                    <!--
                    <input class="form-check-input" type="radio" name="gender" value="M" required @if(isset($isModify) && $isModify) disabled @endif>
                    -->
                    <input required class="form-check-input" type="radio" name="gender" value="M" > Male 男&nbsp;&nbsp;&nbsp;
                    <div class="invalid-feedback">
                        This field is required. 請選擇生理性別
                    </div>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="F">
                    <!--
                    <input class="form-check-input" type="radio" name="gender" value="F" required @if(isset($isModify) && $isModify) disabled @endif>
                    -->
                    <input required class="form-check-input" type="radio" name="gender" value="F" > Female 女
                    <div class="invalid-feedback">
                        &nbsp;
                    </div>
                </label>
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputBirthYear' class='col-md-2 control-label text-md-right'>Birth Year<br>出生年</label>
        <div class='date col-md-10' id='inputBirthYear'>
            <div class='row form-group'>
                <!--div class="col-md-1">
                    西元
                </div-->
                <div class="col-md-3">
                    <input type='number' required class='form-control' name='birthyear' min='{{ \Carbon\Carbon::now()->subYears(36)->year }}' max='{{ \Carbon\Carbon::now()->subYears(18)->year }}' value='' placeholder=''>
                    <div class="invalid-feedback">
                        This field is blank or the format is incorrect. 未填寫或日期不正確
                    </div>
                </div>
                <!--div class="col-md-1">
                    年
                </div-->
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputLanguage' class='col-md-2 control-label text-md-right'>Language Preference<br>語言偏好</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='language' value=Mandarin > Mandarin 中文&nbsp;&nbsp;&nbsp;
                <div class="invalid-feedback">
                    This field is required. 請選擇語言偏好
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='language' value=English > English 英文&nbsp;&nbsp;&nbsp;
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='language' value=Both > Both 中英皆可
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
        </div>
    </div>


    <div class='row form-group required'>
        <label for='inputResidence' class='col-md-2 control-label text-md-right'>Residence<br>居住地</label>
        <div class='col-md-3'>
            <input required type=text name='addr_city' value='' class='form-control' id='inputCity' placeholder='City 城市'>
            <div class="invalid-feedback crumb">
                This field is required. 請填寫居住城市
            </div>
        </div>
        <div class='col-md-2'>
            <input required type=text name='addr_state' value='' class='form-control' id='inputState' placeholder='State 州'>
            <div class="invalid-feedback crumb">
                This field is required. 請填寫居住州
            </div>
        </div>
        <div class='col-md-2'>
            <input required type=text name='zipcode' value='' class='form-control' id='inputZipcode' placeholder='Zipcode 郵遞區號'>
            <div class="invalid-feedback crumb">
                This field is blank or the format is incorrect. 請填寫郵遞區號
            </div>
        </div>
        <div class='col-md-3'>
            <input required type=text name='addr_country' value='' class='form-control' id='inputCountry' placeholder='Country 國家'>
            <div class="invalid-feedback">
                This field is required. 請填寫居住國家
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputCell' class='col-md-2 control-label text-md-right'>Cell Phone<br>行動電話</label>
        <div class='col-md-10'>
            <input type=tel required name='mobile' value='' class='form-control' id='inputCell' placeholder=''>
            <div class="invalid-feedback">
                This field is blank or the format is incorrect. 請填寫行動電話
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>Email<br>電子郵件</label>
        <div class='col-md-10'>
            <!--
            <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請務必填寫正確，以利營隊相關訊息通知' @if(isset($isModify) && $isModify) disabled @endif>
            -->
            <input type='email' required name='email' value='' class='form-control' id='inputEmail'>
            <div class="invalid-feedback">
                This field is blank or the format is incorrect. 未填電子信箱或格式不正確
            </div>
        </div>
    </div>

    <script language='javascript'>
        $('#inputEmail').bind("cut copy paste",function(e) {
        e.preventDefault();
        });
    </script>
    
    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>Confirm Email<br>確認電子郵件</label>
        <div class='col-md-10'>
            <!--
            <input type='email' required  name='emailConfirm' value='' class='form-control' id='inputEmailConfirm' placeholder='請再次填寫(勿複製貼上)，確認電子信箱正確' @if(isset($isModify) && $isModify) disabled @endif>
            -->
            <input type='email' required name='emailConfirm' value='' class='form-control' id='inputEmailConfirm' placeholder='Please type email again (no copy-past). 請再次填寫(勿複製貼上)'>
            {{-- data-match='#inputEmail' data-match-error='郵件不符合' placeholder='請再次填寫確認郵件填寫正確' --}}
            <div class="invalid-feedback">
                This field is blank or the format is incorrect. 未填電子信箱或格式不正確
            </div>
        </div>
    </div>

    <!--div class='row form-group required'>
        <label for='inputIsStudent' class='col-md-2 control-label text-md-right'>Status 身分</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='is_student' value='0' > 已就業或待業中&nbsp;&nbsp;&nbsp;
                <div class="invalid-feedback">
                    請選擇身分
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='is_student' value='1' > 在學中
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div-->

    <div class='row form-group required'>
        <div class='col-md-2'>
        </div>
        <div class='col-md-10'>
            <label class='text-info'>If you have graduated, please fill in the highest level of education, including the school and department from which you graduated. 若已畢業，請填寫最高學歷；年級請填寫「畢」</label>
        </div>

        <label for='inputSchoolName' class='col-md-2 control-label text-md-right'>School<br>學校</label>
        <div class='col-md-10'>
            <input type=text required name='school' value='' class='form-control' id='inputSchoolName' placeholder='School 學校名稱'>
            <div class="invalid-feedback crumb">
                This field is required. 請填寫學校
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputDeptGrade' class='col-md-2 control-label text-md-right'>Major and year<br>系所年級</label>
        <div class='col-md-6'>
            <input type=text required name='department' value='' class='form-control' id='inputDept' placeholder='Major 系所科'>
            <div class="invalid-feedback">
                This field is required. 請填寫系所科
            </div>
        </div>
        <div class='col-md-4'>
            <input type=text required name='grade' value='' class='form-control' id='inputGrade' placeholder='Year 年級'>
            <div class="invalid-feedback">
                This field is required. 請填寫年級
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <div class='col-md-2'>
        </div>
        <div class='col-md-10'>
            <label class='text-info'>If you are currently employed, please provide your job title and company. <br>
            公司名稱及職稱：已在職者填寫即可，在校生打工不必填寫。</label>
        </div>

        <label for='inputUnit' class='col-md-2 control-label text-md-right'>Company<br>公司名稱</label>
        <div class='col-md-10'>
            <input type=text name='unit' value='' class='form-control' id='inputUnit'>
            <!--div class="invalid-feedback crumb">
                請填寫公司名稱
            </div-->
        </div>
    </div>

    <div class='row form-group'>
    <label for='inputTitle' class='col-md-2 control-label text-md-right'>Job Title<br>職稱</label>
        <div class='col-md-10'>
            <input type=text name='title' value='' class='form-control' id='inputTitle'>
            <!--div class="invalid-feedback">
                請填寫職稱
            </div-->
        </div>
    </div>
    <br>
    <div class='row form-group'>
        <label for='inputDietaryNeeds' class='col-md-2 control-label text-md-right'>Dietary Needs<br>飲食特殊需求</label>
        <div class='col-md-10'>
            <textarea class=form-control rows=2 name='dietary_needs' id=inputDietaryNeeds placeholder=''></textarea>
            <!--div class="invalid-feedback">
                請填寫本欄位
            </div-->
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputOtherNeeds' class='col-md-2 control-label text-md-right'>Other Needs<br>其它特殊需求</label>
        <div class='col-md-10'>
            <textarea class=form-control rows=2 name='other_needs' id=inputAccessNeeds placeholder='For example: access needs, allergy information, and so on. 如：無障礙需求，過敏反應等。請簡要說明。'></textarea>
            <!--div class="invalid-feedback">
                請填寫本欄位
            </div-->
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputAccomodationNeeds' class='col-md-2 control-label text-md-right'>Roommate Preference<br>住宿需求</label>
        <div class='col-md-10'>
            <label class='text-info'>
                Example:<br>
                I would like to room with someone I know: (Please provide name(s))<br>
                I do not have preference: (Please assign me a roommate)<br>
                填寫說明：<br>
                我想和我認識的人住一間房：(請提供他們的姓名)<br>
                我沒有偏好：(請分配一個室友給我)<br>
            </label>
            <textarea class=form-control rows=2 name='accommodation_needs' id=inputAcommodationNeeds ></textarea>
            <!--div class="invalid-feedback">
                請填寫本欄位
            </div-->
        </div>
    </div>

    <br>

    <div class='row form-group required'>
        <label for='inputMotivation' class='col-md-2 control-label text-md-right'>Why do you want to attend?<br>為何想報名此營隊</label>
        <div class='col-md-10'>
            <textarea required class='form-control' rows=2 name='motivation' id=inputMotivation></textarea>
            <div class="invalid-feedback">
                請填寫為何想報名此營隊
            </div>
        </div>
    </div>


    <div class='row form-group required'>
        <label for='inputInfoSource' class='col-md-2 control-label text-md-right'>How did you hear about this event?<br>如何得知此營隊？</label>
        <div class='col-md-10'>
            <label class='text-info'>Single Choice. Choose the major source.<br>
            單選，請選最主要管道。</label>
            <br>
            <label class=radio-inline>
                <input type=radio required name='info_source' value=網站 > Website 網站&nbsp;&nbsp;&nbsp;
                <div class="invalid-feedback">
                    This field is required. 請選擇得知管道
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='info_source' value=Email > Email&nbsp;&nbsp;&nbsp;
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='info_source' value=親友師長 > Relatives and Friends 親友介紹
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputIntroducerName' class='col-md-2 control-label text-md-right'>Name of Relative/Friend<br>介紹人姓名</label>
        <div class='col-md-10'>
            <input type='text'class='form-control' name="introducer_name" value=''>
            <!--div class="invalid-feedback">
                This field is required. 請填寫本欄位
            </div-->
        </div>
    </div>

    <h5 class='form-control-static'>＊EMERGENCY CONTACT 緊急聯絡人資料＊</h5><br>

    <div class='row form-group required'>
        <label for='inputEmergencyName' class='col-md-2 control-label text-md-right'>Name Contact<br>緊急聯絡人姓名</label>
        <div class='col-md-10'>
            <input type='text' required class='form-control' name="emergency_name" value='' >
            <div class="invalid-feedback">
                請填寫本欄位
            </div>
        </div>
    </div>
    <div class='row form-group required'>
        <label for='inputEmergencyRelationship' class='col-md-2 control-label text-md-right'>Relationship<br>與學員關係</label>
        <div class='col-md-10'>
            <input type='text' required class='form-control' name="emergency_relationship" value='' >
            <div class="invalid-feedback">
                請填寫本欄位
            </div>
        </div>
    </div>
    <div class='row form-group required'>
        <label for='inputEmergencyMobile' class='col-md-2 control-label text-md-right'>Cell Phone<br>行動電話</label>
        <div class='col-md-10'>
            <input type='text' required class='form-control' name="emergency_mobile" value='' >
            <div class="invalid-feedback">
                This field is required. 請填寫本欄位
            </div>
        </div>
    </div>
    <div class='row form-group '>
        <label for='inputEmergencyPhoneHome' class='col-md-2 control-label text-md-right'>Home Phone<br>室內電話</label>
        <div class='col-md-10'>
            <input type='text'class='form-control' name="emergency_phone_home" value='' >
            <div class="invalid-feedback">
                This field is required. 請填寫本欄位
            </div>
        </div>
    </div>

    <h5 class='form-control-static'>＊CONSENT 同意聲明＊</h5>
    <br>

    <div class='row form-group required'>
        <div class='col-md-2'>
        </div>
        <div class='col-md-10'>
            <p class='form-control-static text-danger'>
            We will be capturing photos and videos throughout the camp for educational and informational purposes that highlight our commitment to humanitarian initiatives and community involvement. By attending, you acknowledge that you may appear in these images and videos. Please give your consent for the Bliss & Wisdom Foundation of Culture and Education to use and share these materials.<br>
            活動中將有針對大眾的攝影或錄影，以作記錄與報導之用。您有可能被鏡頭所攝取，敬請同意與授權福智文教基金會使用與傳播，感恩您的護持參與。
            </p>
        </div>

        <label for='inputTerm' class='col-md-2 control-label text-md-right'>同意聲明</label>
        <div class='col-md-10 form-check'>
            <label class=radio-inline>
                <input type='radio' required name="portrait_agree" value='1' > 我同意
                <div class="invalid-feedback">
                    Please check. 請圈選本欄位
                </div>
            </label>
            <!--label class=radio-inline>
                <input type=radio required name="portrait_agree" value='0' > 我不同意
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label-->
        </div>
    </div>
    
    <input type=hidden name='is_attend' value='1' class='form-control'>



    <script language='javascript'>

    function referer_field(show) {
        var show_q = ' <label> 若無介紹人，不需填寫資料 <input type=button class="btn btn-info" value="清除資料" onClick="referer_field(0);"> </label> ';

        var show_field1 = ' <div class="row form-group"> <label for="introducer_name" class="col-md-2 control-label">介紹人姓名</label> <div class="col-md-4"> <input type=text  name="introducer_name" id="introducer_name" value="" class=form-control > </div> <label for="introducer_relationship" class="col-md-2 control-label">關係</label> <div class="col-md-4"> <input type=text  name="introducer_relationship" id="introducer_relationship" value="" class=form-control placeholder="與介紹人的關係"> </div> </div>' ;

        var show_field2 = ' <div class="row form-group"> <label for="introducer_participated" class="col-md-2 control-label">福智活動</label> <div class="col-md-4"> <input type=text  name="introducer_participated" id="introducer_participated" value="" class=form-control placeholder="曾參加福智舉辦的活動" > </div> <label for="introducer_phone" class="col-md-2 control-label">介紹人聯絡電話</label> <div class="col-md-4"> <input type=tel  name="introducer_phone" id="introducer_phone" value="" class=form-control > </div> </div>' ;

        hidden_field = ' <label> 若有介紹人，請填寫資料 <input type=button class="btn btn-info" value="填寫介紹人資料" onClick="referer_field(1);"> </label> ';

        if (show == 0) { 
        document.getElementById('referer').innerHTML = hidden_field ; 
        } else { 
        document.getElementById('referer').innerHTML = show_q + show_field1 + show_field2; 
        }
    }
    </script>

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
                        if (document.Camp.checkValidity() === false) {
                            $(".tips").removeClass('d-none');
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        else{
                            $(".tips").addClass('d-none');
                            if(typeof document.getElementsByName("father_name")[0] === "undefined"){
                                //parent_field(1);
                            }
                            if(typeof document.getElementsByName("introducer_name")[0] === "undefined"){
                                //referer_field(1);
                            }
                            document.Camp.submit();
                        }
                        document.Camp.classList.add('was-validated');
                    }
        });

        /**
        * Ready functions.
        * Executes commands after the web page is loaded. 
        */
        /*document.onreadystatechange = () => {
            if (document.readyState === 'complete') {
                document.getElementById('blisswisdom_type_other_checkbox').addEventListener("change", function(){
                    document.Camp.blisswisdom_type_other.required = document.getElementById('blisswisdom_type_other_checkbox').checked;
                });
            }
        };*/

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
                //{{-- 填完資料，檢查是否有父母或介紹人資料，若無則關閉 --}}
                //let father_name = document.getElementsByName("father_name")[0].value;
                //let father_lamrim = document.getElementsByName("father_lamrim")[0].value;
                //let father_phone = document.getElementsByName("father_phone")[0].value;
                //let mother_name = document.getElementsByName("mother_name")[0].value;
                //let mother_lamrim = document.getElementsByName("mother_lamrim")[0].value;
                //let mother_phone = document.getElementsByName("mother_phone")[0].value;
                //let parents = [father_name, father_lamrim, father_phone, mother_name, mother_lamrim, mother_phone];
                //if(parents.every(checkIfNull)){
                //    parent_field(0);
                //}
                //let introducer_name = document.getElementsByName("introducer_name")[0].value;
                //let introducer_relationship = document.getElementsByName("introducer_relationship")[0].value;
                //let introducer_participated = document.getElementsByName("introducer_participated")[0].value;
                //let introducer_phone = document.getElementsByName("introducer_phone")[0].value;
                //let introducer = [introducer_name, introducer_relationship, introducer_participated, introducer_phone];
                //if(introducer.every(checkIfNull)){
                //   referer_field(0);
                //}

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
