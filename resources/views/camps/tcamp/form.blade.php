{{-- 
    參考頁面：https://youth.blisswisdom.org/camp/winter/form/index_addto.php
    --}}
<?
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('camps.tcamp.layout')
@section('content')
    @include('partials.schools_script')
    @include('partials.counties_areas_script')
    <div class='alert alert-info' role='alert'>
        您在本網站所填寫的個人資料，僅用於此次教師營的報名及活動聯絡之用。
    </div>

    <div class='page-header form-group'>
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
            <span class='text-danger'>＊必填</span>
        </div>
    </div>
    <div class='row form-group'>
        <label for='inputBatch' class='col-md-2 control-label text-md-right'>營隊梯次</label>
        <div class='col-md-10'>
            <h3>{{ $camp_data->name . '梯' }} ({{ $camp_data->batch_start }} ~ {{ $camp_data->batch_end }})</h3>
            @if(isset($applicant_data))
                <input type='hidden' name='applicant_id' value='{{ $applicant_id }}'>
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

{{--
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
--}}

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
        <label for='inputID' class='col-md-2 control-label text-md-right'>身份證字號</label>
        <div class='col-md-10'>
            <input type='text' name='idno' value='' class='form-control' id='inputID' placeholder='僅作為申請研習時數或研習證書用' required @if(isset($isModify) && $isModify) disabled @endif>
        </div>
        <div class="invalid-feedback">
            請填寫身份證字號；
        </div>
    </div>
{{--
    <div class='row form-group required'>
        <label for='inputIsForeigner' class='col-md-2 control-label text-md-right'>海外身份</label>
        <div class='col-md-10'>
            <input type='checkbox' name='is_foreigner' value='' class='form-control' id='inputIsForeigner' placeholder='' required @if(isset($isModify) && $isModify) disabled @endif>
            （若勾選「海外身份」，「身份證字號」請填護照號碼）
        </div>
    </div>
--}}

    <div class='row form-group required'>
        <label for='inputEducation' class='col-md-2 control-label text-md-right'>學歷</label>
        <div class='col-md-10'>
                <select name="education" class="form-control"> 
                        <option value=''>- 請選擇 -</option>
                        <option value='高中職'>高中職</option>
                        <option value='大專'>大專</option>
                        <option value='研究所'>研究所</option>
                        <option value='博士'>博士</option>
                        <option value='其他'>其他</option>
                </select>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputHasLicense' class='col-md-2 control-label text-md-right'>是否有教師證</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='has_license' value=1 > 有
                <div class="invalid-feedback">
                    請勾選是否有教師證
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='has_license' value=0 > 無
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputIsEducating' class='col-md-2 control-label text-md-right'>是否在學校或教育單位任職</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='is_educating' value=1 id="is_educating_y"> 是（請續填下方任職資料）
                <div class="invalid-feedback">
                    請勾選是否在學校或教育單位任職
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='is_educating' value=0 id="is_educating_n"> 否
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
        </div>
    </div>
    <span id="rowIsEducating">
    <div class='row form-group required'>
        <label for='inputSchoolOrCourse' class='col-md-2 control-label text-md-right'>任職機關/任教學程</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='school_or_course' value=教育部 class="officials"> 教育部
                <div class="invalid-feedback crumb">
                    請勾選任職機關/任教學程
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='school_or_course' value=教育局/處 class="officials"> 教育局/處
                <div class="invalid-feedback crumb">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='school_or_course' value=大專校院 class="universities"> 大專校院
                <div class="invalid-feedback crumb">
                    &nbsp;
                </div>
            </label> <label class=radio-inline>
                <input type=radio required name='school_or_course' value=高中職 class="compulsories"> 高中職
                <div class="invalid-feedback crumb">
                    &nbsp;
                </div>
            </label> <label class=radio-inline>
                <input type=radio required name='school_or_course' value=國中 class="compulsories"> 國中
                <div class="invalid-feedback crumb">
                    &nbsp;
                </div>
            </label> <label class=radio-inline>
                <input type=radio required name='school_or_course' value=國小 class="compulsories"> 國小
                <div class="invalid-feedback crumb">
                    &nbsp;
                </div>
            </label> <label class=radio-inline>
                <input type=radio required name='school_or_course' value=幼教 class="compulsories"> 幼教
                <div class="invalid-feedback crumb">
                    &nbsp;
                </div>
            </label> 
        </div>
    </div>

    <div class='row form-group required'> 
    <label for='inputSubjectTeaches' class='col-md-2 control-label text-md-right'>任教科系/任教科目</label>
        <div class='col-md-10'>
            <input type=text required  name='subject_teaches' value='' class='form-control' id='inputSubjectTeaches'>
            <div class="invalid-feedback crumb">
                請填寫任教科系/任教科目
            </div>
        </div>
    </div>
    </span>

    <div class='row form-group required'> 
    <label for='inputSubjectTeaches' class='col-md-2 control-label text-md-right'>職稱</label>
        <div class='col-md-10'>
            <div id='tip' style='color: red; font-weight: bold;'>請先選擇任教機關/任教學程</div>
            <div style="display: none;" class="titles officials">
                <input type="radio" name="data[12]" class="officials" value="行政人員">行政人員
                <input type="radio" name="data[12]" class="officials" value="其他">其他
            </div>
            <div style="display: none;" class="titles universities">
                <input type="radio" name="data[12]" class="universities" value="校長">校長
                <input type="radio" name="data[12]" class="universities" value="教授">教授
                <input type="radio" name="data[12]" class="universities" value="副教授">副教授
                <input type="radio" name="data[12]" class="universities" value="助理教授">助理教授
                <input type="radio" name="data[12]" class="universities" value="講師">講師<br>
                <input type="radio" name="data[12]" class="universities" value="職員">職員
                <input type="radio" name="data[12]" class="universities" value="其他">其他
            </div>
            <div style="display: none;" class="titles compulsories">
                <input type="radio" name="data[12]" class="compulsories" value="校長">校長
                <input type="radio" name="data[12]" class="compulsories" value="主任">主任
                <input type="radio" name="data[12]" class="compulsories" value="教師">教師
                <input type="radio" name="data[12]" class="compulsories" value="教師兼行政">教師兼行政
                <input type="radio" name="data[12]" class="compulsories" value="代理教師">代理教師<br>
                <input type="radio" name="data[12]" class="compulsories" value="兼課老師">兼課老師
                <input type="radio" name="data[12]" class="compulsories" value="職員">職員
                <input type="radio" name="data[12]" class="compulsories" value="其他">其他
            </div>
            <input type=text required name='title' value='' class='form-control' id='title'>
            <div class="invalid-feedback crumb">
                請填寫職稱
            </div>
        </div>
    </div>

    <div class='row form-group required'> 
    <label for='inputUnit' class='col-md-2 control-label text-md-right'>服務單位名稱/校名</label>
        <div class='col-md-10'>
            <input type=text required name='unit' value='' class='form-control' id='inputUnit'>
            <div class="invalid-feedback crumb">
                請填寫服務單位名稱/校名
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputUnitCounty' class='col-md-2 control-label text-md-right'>服務單位所在縣市</label>
        <div class='col-md-10'>
            <select required class='form-control' name='unit_county'' onChange='SchooList(this.options[this.options.selectedIndex].value);'>
                <option value='' selected>- 請先選縣市 -</option>
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
    </div>
    <p class='form-control-static text-danger'>連絡方式</p>
    <div class='row form-group required'>
        <label for='inputCell' class='col-md-2 control-label text-md-right'>行動電話</label>
        <div class='col-md-10'>
            <input type=tel required name='mobile' value='' class='form-control' id='inputCell' placeholder='格式：0912345678'>
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
            <input type='email' required name='emailConfirm' value='' class='form-control' id='inputEmailConfirm'>
            {{-- data-match='#inputEmail' data-match-error='郵件不符合' placeholder='請再次填寫確認郵件填寫正確' --}}
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>願意收到福智文教基金會相關活動資訊</label>
        <div class="col-md-10">
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="1">
                    <input type="radio" name="is_allow_notified" value="1" required>
                    是
                    <div class="invalid-feedback">
                        請選擇一項
                    </div>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="0">
                    <input type="radio" name="is_allow_notified" value="0" required>
                    否
                    <div class="invalid-feedback">
                        &nbsp;
                    </div>
                </label>
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputAddress' class='col-md-2 control-label text-md-right'>通訊地址</label>
        <div class='col-md-2'>
            <select name="county" class="form-control" onChange="Address(this.options[this.options.selectedIndex].value);"> 
                <option value=''>- 請先選縣市 -</option>
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
                <option value=''>- 再選區鄉鎮 -</option>
            </select>
        </div>
        <div class='col-md-1'>
            <input readonly type=text name=zipcode value='' class='form-control'>
        </div>
        <div class='col-md-3'>
            <input type=text required name='address' value='' maxlength=80 class='form-control' placeholder='請填寫通訊地址'>
            <div class="invalid-feedback">
                請填寫通訊地址
            </div>
        </div>
    </div>
    
    <div class='row form-group'>
        <label for='inputExpect' class='col-md-2 control-label text-md-right'>您對這次活動的期望？</label>
        <div class='col-md-10'>
            <textarea class='form-control' rows=2 name='expectation' id=inputExpect></textarea>
            {{-- <div class="invalid-feedback">
                請填寫本欄位
            </div> --}}
        </div>
    </div>

    <!--- 福智活動 -->
    <div class='row form-group'>
        <label for='inputFuzhi' class='col-md-2 control-label text-md-right'>請問您曾參加福智文教基金會所舉辦的哪些活動（可複選）</label>
        <div class='col-md-10'>
            <label><input type="checkbox" name=blisswisdom_type[] value='研習或講座' > 研習或講座（如：暑期生命教育研習、淨塑、健康講座...）</label> <br/>
            <label><input type="checkbox" name=blisswisdom_type[] value='廣論研討班' > 廣論研討班</label> <br/>
            <div class='row form-group'>
                <div class='col-md-2'>
                    <label>班別：</label>
                </div>
                <div class='col-md-10'>
                    <input type=text class='form-control' name=blisswisdom_type_complement[] value=''>
                </div>
            </div>    
            <label><input type="checkbox" name=blisswisdom_type[] value='其它' > 其它</label> <br/>
            <div class='row form-group'>
                <div class='col-md-2'>
                    <label>活動名稱：</label>
                </div>
                <div class='col-md-10'>
                    <input type=text class='form-control' name=blisswisdom_type_complement[] value=''>
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
                    <select name="emergency_relationship" class="form-control"> 
                        <option value=''>- 請選擇 -</option>
                        <option value='配偶'>配偶</option>
                        <option value='父母'>父母</option>
                        <option value='兄弟姊妹'>兄弟姊妹</option>
                        <option value='朋友'>朋友</option>
                        <option value='同事'>同事</option>
                        <option value='子女'>子女</option>
                        <option value='其他'>其他</option>
                    </select>
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
            <label>
                <p class='form-control-static text-danger'>
                <input type='radio' required name="portrait_agree" value='1'> 我同意主辦單位在營隊期間拍照、錄影之活動記錄，使用於營隊及主辦單位的非營利教育推廣使用，並以網路方式推播。</p>
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
                <p class='form-control-static text-danger'>
                <input type='radio' required name='profile_agree' value='1'> 我同意主辦單位於本次營隊取得我的個人資料，於營隊期間及後續主辦單位舉辦之活動，作為訊息通知、行政處理等非營利目的之使用，不會提供給無關之其他私人單位使用。</p>
                <div class="invalid-feedback">
                    請圈選本欄位
                </div>
            </label> 
            <input type='radio' class='d-none' name='profile_agree' value='0' >
            <br/>
        </div>
    </div>

    <div class='row form-group'>
        <label class='col-md-2 control-label text-md-right'>推薦人</label>
        <div class='col-md-10'>
            <div class='row form-group'>
                <div class='col-md-2'>
                    姓名：
                </div>
                <div class='col-md-10'>
                    <input type='text'class='form-control' name="introducer_name" value=''>
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
                    <select name="introducer_relationship" class="form-control"> 
                        <option value=''>- 請選擇 -</option>
                        <option value='配偶'>配偶</option>
                        <option value='學生'>學生</option>
                        <option value='父母'>父母</option>
                        <option value='兄弟姊妹'>兄弟姊妹</option>
                        <option value='朋友'>朋友</option>
                        <option value='同事'>同事</option>
                        <option value='子女'>子女</option>
                        <option value='其他'>其他</option>
                    </select>
                </div>
            </div>   
            <div class='row form-group'>
                <div class='col-md-2'>
                    聯絡電話：
                </div>
                <div class='col-md-10'>
                    <input type='tel' class='form-control' name="introducer_phone" value=''>
                </div>
                <div class="invalid-feedback">
                    請填寫本欄位
                </div>
            </div>   
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
{{-- 
    參考頁面：https://youth.blisswisdom.org/camp/winter/form/index_addto.php
    --}}
