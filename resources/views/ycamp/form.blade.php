{{-- 
    參考頁面：https://youth.blisswisdom.org/camp/winter/form/index_addto.php
    --}}
<?
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('layouts.ycamp')
@section('content')
    @include('partials.schools_script')
    @include('partials.counties_areas_script')
    @if(!isset($isBackend))
        <div class='alert alert-info' role='alert'>
            您在本網站所填寫的個人資料，僅用於此次大專營的報名及活動聯絡之用。
        </div>
    @endif

    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}線上報名表</h4>
    </div>
{{-- !isset($isModify): 沒有 $isModify 變數，即為報名狀態、 $isModify: 修改資料狀態--}}
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
    {{-- <div class='row form-group'>
        <label for='inputDate' class='col-md-2 control-label text-md-right'>報名日期</label>
        <div class='col-md-10'>
            {{ \Carbon\Carbon::now()->toDateString() }}
            <input type=hidden name=item[50] value='{{ \Carbon\Carbon::now()->toDateString() }}'>
        </div>
    </div> --}}
    <div class='row form-group'>
        <label for='inputDate' class='col-md-2 control-label text-md-right'>營隊梯次</label>
        <div class='col-md-10'>
            {{ $camp_data->fullName . $camp_data->name . '梯' }} ({{ $camp_data->batch_start }} ~ {{ $camp_data->batch_end }})
            @if(isset($applicant_data))
                <input type='hidden' name='applicant_id' value='{{ $applicant_id }}'>
            @endif
        </div>
    </div>
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
        <label for='inputNationName' class='col-md-2 control-label text-md-right'>國籍</label>
        <div class='col-md-2'>
        <select class='form-control' name='nationality' id='inputNationName'>
            <option value='美國' >美國</option>
            <option value='加拿大' >加拿大</option>
            <option value='澳大利亞' >澳大利亞</option>
            <option value='紐西蘭' >紐西蘭</option>
            <option value='中國' >中國</option>
            <option value='香港' >香港</option>
            <option value='澳門' >澳門</option>
            <option value='台灣' selected>台灣</option>
            <option value='韓國' >韓國</option>
            <option value='日本' >日本</option>
            <option value='蒙古' >蒙古</option>
            <option value='新加坡' >新加坡</option>
            <option value='馬來西亞' >馬來西亞</option>
            <option value='菲律賓' >菲律賓</option>
            <option value='印尼' >印尼</option>
            <option value='泰國' >泰國</option>
            <option value='越南' >越南</option>
            <option value='其它' >其它</option>
        </select>
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
                    <input type='number' required class='form-control' name='birthyear' min=1985 max='{{ \Carbon\Carbon::now()->subYears(16)->year }}' value='' placeholder=''>
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


    <div class='row form-group'>
        <label for='inputInterest' class='col-md-2 control-label text-md-right'>興趣</label>
        <div class='col-md-10'>
            <input type='text' name='habbit' value='' class='form-control' id='inputInterest' placeholder=''>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>電子郵件</label>
        <div class='col-md-10'>
            <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請務必填寫正確，方便系統發送確認信' @if(isset($isModify) && $isModify) disabled @endif>
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
            <input type='email' required  name='emailConfirm' value='' class='form-control' id='inputEmailConfirm' @if(isset($isModify) && $isModify) disabled @endif>
            {{-- data-match='#inputEmail' data-match-error='郵件不符合' placeholder='請再次填寫確認郵件填寫正確' --}}
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputProgramType' class='col-md-2 control-label text-md-right'>課程學制</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='system' value=博士 > 博士班
                <div class="invalid-feedback">
                    請選擇學制
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='system' value=碩士 > 碩士班
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='system' value=大學 > 大學
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='system' value=四技 > 四技
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='system' value=二技 > 二技
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='system' value=二專 > 二專
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='system' value=五專 > 五專
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputStuType' class='col-md-2 control-label text-md-right'>部別</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='day_night' value=日間部 > 日間部
                <div class="invalid-feedback">
                    請選擇部別
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='day_night' value=進修部 > 進修部（夜間部）
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputSchoolName' class='col-md-2 control-label text-md-right'>就讀學校</label>

        <div class='col-md-2'>
            <select required  class='form-control' name='school_location' onChange='SchooList(this.options[this.options.selectedIndex].value);'>
                <option value='' selected>請選擇所在縣市...</option>
                <option value='臺北市' >臺北市</option>
                <option value='新北市' >新北市</option>
                <option value='基隆市' >基隆市</option>
                <option value='宜蘭縣' >宜蘭縣</option>
                <option value='花蓮縣' >花蓮縣</option>
                <option value='桃園市' >桃園市</option>
                <option value='新竹市' >新竹市</option>
                <option value='新竹縣' >新竹縣</option>
                <option value='苗栗縣' >苗栗縣</option>
                <option value='臺中市' >臺中市</option>
                <option value='彰化縣' >彰化縣</option>
                <option value='南投縣' >南投縣</option>
                <option value='雲林縣' >雲林縣</option>
                <option value='嘉義市' >嘉義市</option>
                <option value='嘉義縣' >嘉義縣</option>
                <option value='臺南市' >臺南市</option>
                <option value='高雄市' >高雄市</option>
                <option value='屏東縣' >屏東縣</option>
                <option value='臺東縣' >臺東縣</option>
                <option value='澎湖縣' >澎湖縣</option>
                <option value='金門縣' >金門縣</option>
                <option value='連江縣' >連江縣</option>
                <option value='南海諸島' >南海諸島</option>
                <option value='海外' >海外</option>
            </select>
        </div>
        
        <div class='col-md-2'>
            <select class='form-control' name=sname onChange='document.Camp.school.value=this.options[this.options.selectedIndex].value;'>
                <option value=''>請選擇學校</option>
            </select>
        </div>
        <div class='col-md-4'>
            <input type=text name='school' required  value='' class='form-control' readonly='readonly' id='inputSchoolName' placeholder='左方選擇您的學校。如無，請自行填寫'>
        </div>
        <div class="invalid-feedback">
            請填寫學校
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputSchoolGrade' class='col-md-2 control-label text-md-right'>系所年級</label>
        <div class='col-md-6'>
            <input type=text required  name='department' value='' class='form-control' id='inputSchoolDept' placeholder='系所科'>
            <div class="invalid-feedback">
                請填寫系所科別
            </div>
        </div>
        <div class='col-md-2'>
            <select required  class='form-control' name='grade' >
                <option value='' selected>請選擇年級...</option>
                <option value='一' >一</option>
                <option value='二' >二</option>
                <option value='三' >三</option>
                <option value='四' >四</option>
                <option value='五' >五</option>
                <option value='六' >六</option>
                <option value='延畢' >延畢</option>
                <option value='其它' >其它</option>
            </select>
            <div class="invalid-feedback">
                請選擇年級
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputCell' class='col-md-2 control-label text-md-right'>行動電話</label>
        <div class='col-md-10'>
            <input type=tel required  name='mobile' value='' class='form-control' id='inputCell' placeholder='格式：0912345678'>
            <div class="invalid-feedback">
                請填寫行動電話
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputTelHome' class='col-md-2 control-label text-md-right'>家中電話</label>
        <div class='col-md-10'>
            <input type=tel  name='phone_home' value='' class='form-control' id='inputTelHome' placeholder='格式：0225452546#520'>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputAddress' class='col-md-2 control-label text-md-right'>通訊地址</label>
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
            <input type=text required  name='address' value='' maxlength=80 class='form-control' placeholder='海外請自行填寫國家及區域'>
            <div class="invalid-feedback">
                請填寫通訊地址
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputSource' class='col-md-2 control-label text-md-right'>您如何得知此活動？</label>
        <div class='col-md-10'>
            <p class='form-control-static text-danger'>單選，請選最主要管道。</p>
            <label class=radio-inline>
                <input type=radio required name='way' value=FB > FB
                <div class="invalid-feedback">
                    請選擇得知管道
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=IG > IG
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=Line > Line
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=官網 > 官網
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=網路(其它) > 網路(其它)
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=班宣(有同學到班上宣傳) > 班宣(有同學到班上宣傳)
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=同學 > 同學
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=親友師長 > 親友師長
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=活動海報 > 活動海報
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=系所公告 > 系所公告
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=其它 > 其它
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputClub' class='col-md-2 control-label text-md-right'>您曾參與的學校社團活動及擔任職務？</label>
        <div class='col-md-10'>
            <textarea class=form-control rows=2 required  name='club' id=inputClub></textarea>
        </div>
        <div class="invalid-feedback">
            請填寫本欄位
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputGoal' class='col-md-2 control-label text-md-right'>你這一生最想追求或完成的目標是什麼？</label>
        <div class='col-md-10'>
            <textarea class=form-control rows=2 required  name='goal' id=inputGoal></textarea>
        </div>
        <div class="invalid-feedback">
            請填寫本欄位
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputExpect' class='col-md-2 control-label text-md-right'>您對這次活動的期望？</label>
        <div class='col-md-10'>
            <textarea class='form-control' rows=2 required  name='expectation' id=inputExpect></textarea>
        </div>
        <div class="invalid-feedback">
            請填寫本欄位
        </div>
    </div>

    <!--- 福智活動 -->
    <div class='row form-group'>
        <label for='inputFuzhi' class='col-md-2 control-label text-md-right'>曾參與過福智團體所舉辦之活動與課程（可複選）</label>
        <div class='col-md-10'>
            <label><input type="checkbox" name=blisswisdom_type[] value='福智高中' > 就讀福智高中</label> <br/>
            <label><input type="checkbox" name=blisswisdom_type[] value='福智中小學' > 就讀福智中小學</label> <br/>
            <label><input type="checkbox" name=blisswisdom_type[] value='青少年班' > 參加青少年班</label> <br/>
            <label><input type="checkbox" name=blisswisdom_type[] value='青少年營' > 參加青少年營</label> <br/>
            <div class='row form-group'>
                <div class='col-md-2'>
                    <label>其它：</label>
                </div>
                <div class='col-md-10'>
                    <input type=text class='form-control' name=blisswisdom_type[] value=''>
                </div>
            </div>
        </div>
    </div>

    <!--- 錄取通知  -->
    <div class='row form-group'>
        <label for='inputNotice' class='col-md-2 control-label text-md-right'>錄取通知方式</label>
        <div class='col-md-10'>
        <p class='form-control-static text-danger'>
        請於 {{ $camp_data->admission_announcing_date }} ({{ $admission_announcing_date_Weekday }}) 起自行上網查詢。<br>
        並於 {{ $camp_data->admission_confirming_end }} ({{ $admission_confirming_end_Weekday }}) 前上網回覆確認參加，倘未回覆，視同放棄。
        </p>
        </div>
    </div>

    <!--- 父母親資料  -->
    <div class='row form-group'>
        <div class='col-md-12' id=parent>
            <label> 父母親為福智學員，請填寫資料 
                <input type=button class='btn btn-info' value='填寫父母親資料' onClick='parent_field(1);'>
            </label>
        </div>
    </div>

    <script language='javascript'>

    function parent_field(show) {
        var show_q= '<label>父母親若不是福智學員，本欄不用填寫 <input type=reset class="btn btn-info" value="清除資料" onClick="parent_field(0);"></label>' ;

        var show_field1 = ' <div class="row form-group"> <label for="father_name" class="col-md-2 control-label">父親姓名</label> <div class="col-md-2"> <input type=text  name="father_name" id="father_name" value="" class=form-control > </div> <label for="father_lamrim" class="col-md-2 control-label">廣論班別</label>   <div class="col-md-2"> <input type=text  name="father_lamrim" id="father_lamrim" value="" placeholder="例：北12宗13班" class=form-control > </div>   <label for="father_phone" class="col-md-2 control-label">聯絡電話</label>  <div class="col-md-2"> <input type=tel  name="father_phone" id="father_phone" value="" class=form-control > </div>  </div>' ;
        var show_field2 = ' <div class="row form-group"> <label for="mother_name" class="col-md-2 control-label">母親姓名</label> <div class="col-md-2"> <input type=text  name="mother_name" id="mother_name" value="" class=form-control > </div>  <label for="mother_lamrim" class="col-md-2 control-label">廣論班別</label>  <div class="col-md-2"> <input type=text  name="mother_lamrim" id="mother_lamrim" value="" placeholder="例：北15增16班" class=form-control > </div>  <label for="mother_phone" class="col-md-2 control-label">聯絡電話</label>  <div class="col-md-2"> <input type=tel  name="mother_phone" id="mother_phone" value="" class=form-control > </div>    </div>' ;
        hidden_field = '<label>父母親為福智學員，請填寫資料<input type=button class="btn btn-info" value="填寫父母親資料" onClick="parent_field(1);"></label>' ;

    if (show == 0) { 
        document.getElementById('parent').innerHTML = hidden_field ; 
    } else { 
        document.getElementById('parent').innerHTML = show_q + show_field1 + show_field2 ; 
    }
    }
    </script>

    <!--- 介紹人資料  -->
    <div class='row form-group'>
        <div class='col-md-12' id=referer>
            <label> 若有介紹人，請填寫資料
                <input type=button class='btn btn-info' value='填寫介紹人資料' onClick='referer_field(1);'>
            </label>
        </div>
    </div>

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

    <!--- 填寫表單之人 -->
    <div class='row form-group required'>
        <label class='col-md-2 control-label text-md-right'>填寫人</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name="is_inperson" value="1" > 本表係本人填寫 <br/>
            </label> <br/>
            <label class=radio-inline>
                <input type=radio required name="is_inperson" value="0" > 本表由他人代填
                <div class="invalid-feedback">
                    請選擇其中一項
                </div>
            </label>
            <div class='row form-group'>
                <div class='col-md-2'>
                    代填人姓名： 
                </div>
                <div class='col-md-10'>
                    <input type=text class='form-control' name="agent_name" value=''>
                </div>
            </div>
            <div class='row form-group'>
                <div class='col-md-2'>
                    代填人聯絡電話：
                </div>
                <div class='col-md-10'>
                    <input type=tel class='form-control' name="agent_phone" value=''>
                </div>
            </div>     
        </div>
    </div>

    <div class='row form-group required'>
        <label class='col-md-2 control-label text-md-right'>緊急聯絡人</label>
        <div class='col-md-10'>
            <div class='row form-group'>
                <div class='col-md-2'>
                    姓名：
                </div>
                <div class='col-md-10'>
                    <input type='text'class='form-control' name="emergency_name" value='' required>
                </div>
                <div class="invalid-feedback">
                    請填寫本欄位
                </div>
            </div>   
            <div class='row form-group'>
                <div class='col-md-2'>
                    關係：
                </div>
                <div class='col-md-10'>
                    <input type='tel' class='form-control' name="emergency_relationship" value='' required>
                </div>
                <div class="invalid-feedback">
                    請填寫本欄位
                </div>
            </div>   
            <div class='row form-group'>
                <div class='col-md-2'>
                    聯絡電話：
                </div>
                <div class='col-md-10'>
                    <input type='tel' class='form-control' name="emergency_mobile" value='' required>
                </div>
                <div class="invalid-feedback">
                    請填寫本欄位
                </div>
            </div>   
        </div>
    </div>

    <!--- 同意書 -->
    <div class='row form-group required'>
        <label for='inputTerm' class='col-md-2 control-label text-md-right'>肖像權</label>
        <div class='col-md-10 form-check'>
            <label><p class='form-control-static text-danger'>本次營隊期間，主辦單位將剪輯營隊中學員影像為影片，用於營隊、主辦單位等非營利教育推廣使用，並以網路方式播出，報名並獲錄取之本次營隊的人同意將營隊中之影像用於主辦單位及獲主辦單位授權之非營利機構使用。</p></label> <br/>
            <label>
                <input type='radio' required name="portrait_agree" value='1'> 我同意
                <div class="invalid-feedback">
                    請圈選本欄位
                </div>
            </label>  
            <input type='radio' class='d-none' name="portrait_agree" value='0'>  
            <br/>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputTerm' class='col-md-2 control-label text-md-right'>個人資料</label>
        <div class='col-md-10'>
            <label>
                <p class='form-control-static text-danger'>福智文教基金會透由本次營隊取得您的聯繫通訊及個人資料，目的在於營隊期間及後續本基金會舉辦之活動，依所蒐集之資料做為訊息通知、行政處理等非營利目的之使用，不會提供給無關之其它私人單位使用。</p>
            </label> <br/>
            <label>
                <input type='radio' required name='profile_agree' value='1'> 我同意
                <div class="invalid-feedback">
                    請圈選本欄位
                </div>
            </label> 
            <input type='radio' class='d-none' name='profile_agree' value='0' >
            <br/>
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
                                parent_field(1);
                            }
                            if(typeof document.getElementsByName("introducer_name")[0] === "undefined"){
                                referer_field(1);
                            }
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
                parent_field(1);
                referer_field(1);
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
