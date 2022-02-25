{{-- 
    參考頁面：https://bwfoce.org/ecamp/form/2020ep01.php
    --}}
@php
    header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
    $regions = ['台北', '桃園', '新竹', '台中', '雲嘉', '台南', '高雄'];
@endphp
@extends('camps.ceocamp.layout')
@section('content')
    @include('partials.schools_script')
    @include('partials.counties_areas_script')
    <div class='alert alert-info' role='alert'>
        您在本網站所填寫的個人資料，僅用於此次企業營的報名及活動聯絡之用。
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
            <label for='inputBatch' class='col-md-2 control-label text-md-right'>報名日期</label>
            <div class='col-md-10'>
                {{ $applicant_raw_data->created_at }}
            </div>
        </div>
    @endif

    <hr>
    <h5 class='form-control-static'>推薦人(填表者)基本資料：</h5>
    <br>

    <div class='row form-group required'>
        <label class='col-md-2 control-label text-md-right text-info'>推薦人</label>
        <div class='col-md-10'>
            <div class='row form-group required'>
                <div class='col-md-2 text-info'>
                    推薦人姓名：
                </div>
                <div class='col-md-10'>
                    <input type='text' class='form-control' name="introducer_name" value=''>
                </div>
                <div class="invalid-feedback">
                    請填寫本欄位
                </div>
            </div>
            <div class='row form-group required'>
                <div class='col-md-2 text-info'>
                    推薦人廣論研討班別：
                </div>
                <div class='col-md-10'>
                    <input type='text' class='form-control' name="introducer_participated" value=''>
                </div>
                <div class="invalid-feedback">
                    請填寫本欄位
                </div>
            </div>
            <div class='row form-group required'>
                <div class='col-md-2 text-info'>
                    推薦人手機號碼：
                </div>
                <div class='col-md-10'>
                    <input type='tel' class='form-control' name="introducer_phone" value='' placeholder='格式：0912345678'>
                </div>
                <div class="invalid-feedback">
                    請填寫手機號碼
                </div>
            </div>
            <div class='row form-group required'>
                <div class='col-md-2 text-info'>
                    推薦人電子信箱：
                </div>
                <div class='col-md-10'>
                    <input type='email' class='form-control' name="introducer_email" value='' placeholder='請務必填寫正確，以利營隊相關訊息通知'>
                </div>
                <div class="invalid-feedback">
                    電子信箱格式不正確
                </div>
            </div>
            <div class='row form-group required'>
                <div class='col-md-2 text-info'>
                    與被推薦人關係：
                </div>
                <div class='col-md-10'>
                    <select name="introducer_relationship" class="form-control"> 
                        <option value=''>- 請選擇 -</option>
                        <option value='配偶'>親戚</option>
                        <option value='父親'>同學</option>
                        <option value='母親'>同事</option>
                        <option value='兄弟'>朋友</option>
                        <option value='姊妹'>工作相關</option>
                        <option value='朋友'>社團</option>
                        <option value='其他'>其他</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <h5 class='form-control-static'>被推薦人(營隊學員)基本資料：</h5>
    <br>

    <div class='row form-group required'>
        <label for='inputName' class='col-md-2 control-label text-md-right'>中文姓名</label>
        <div class='col-md-10'>
            <input type='text' name='name' value='' class='form-control' id='inputName' placeholder='請填寫全名' required @if(isset($isModify) && $isModify) disabled @endif>
        </div>
        <div class="invalid-feedback">
            請填寫姓名
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputEngName' class='col-md-2 control-label text-md-right'>英文慣用名</label>
        <div class='col-md-10'>
            <input type='text' name='english_name' value='' class='form-control' id='inputEngName' placeholder='請填寫英文慣用名，如James、Michelle等，若無免填'>
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
                電子信箱格式不正確
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
        <label for='inputContactTime' class='col-md-2 control-label text-md-right'>適合聯絡時間<br>(可複選)</label>
        <div class='col-md-10'>
            <label><input type="checkbox" class="contact_time" name=contact_time[] value='上午0800-1200' > 上午0800-1200</label> <br/>
            <label><input type="checkbox" class="contact_time" name=contact_time[] value='中午1200-1400' > 中午1200-1400</label> <br/>
            <label><input type="checkbox" class="contact_time" name=contact_time[] value='下午1400-1700' > 下午1400-1700</label> <br/>
            <label><input type="checkbox" class="contact_time" name=contact_time[] value='晚上1700-2100' > 晚上1700-2100</label> <br/>
            <div class="invalid-feedback" id="contact_time-invalid">
                請勾選適合聯絡時間
            </div>
        </div>
    </div>
    
    <div class='row form-group'>
        <label class='col-md-2 control-label text-md-right text-info'>代理人(秘書/特助)<br>(若無免填)</label>
        <div class='col-md-10'>
            <div class='row form-group'>
                <div class='col-md-2 text-info'>
                    代理人姓名：
                </div>
                <div class='col-md-10'>
                    <input type='text'class='form-control' name="substitute_name" value='' required>
                </div>
                <div class="invalid-feedback">
                    請填寫本欄位
                </div>
            </div>

            <div class='row form-group'>
                <div class='col-md-2 text-info'>
                    代理人聯絡電話：
                </div>
                <div class='col-md-10'>
                    <input type='tel' class='form-control' name="substitute_mobile" value='' placeholder='手機格式：0912345678；市話格式：0225452546#520' required>
                </div>
                <div class="invalid-feedback">
                    請填寫本欄位
                </div>
            </div>

            <div class='row form-group'>
                <div class='col-md-2 text-info'>
                    代理人電子信箱：
                </div>
                <div class='col-md-10'>
                    <input type='email' class='form-control' name="substitute_email" value='' placeholder='請務必填寫正確，以利營隊相關訊息通知'>
                </div>
                <div class="invalid-feedback">
                    電子信箱格式不正確
                </div>
            </div>   
   
        </div>
    </div>

    <div class='row form-group'> 
    <label for='inputMaritalStatus' class='col-md-2 control-label text-md-right'>婚姻狀況</label>
        <div class='col-md-10'>
            <input type=text required name='marital_status' value='' class='form-control' id='inputMaritalStatus'>
            <div class="invalid-feedback">
                請填寫婚姻狀況
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputExceptionalConditions' class='col-md-2 control-label text-md-right'>被推薦人需要<br>特別關懷事項</label>
        <div class='col-md-10'>
            <textarea class='form-control' rows=2 name='exceptional_conditions' id=inputExceptionalConditions placeholder='例如：家庭狀況、是否有宗教信仰'></textarea>
            <div class="invalid-feedback">
                請填寫需要特別關懷事項
            </div>
        </div>
    </div>


    <div class='row form-group required'>
        <label for='inputParticipationMode' class='col-md-2 control-label text-md-right'>參加營隊形式</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='participation_mode' value=實體營隊 > 實體營隊
                <div class="invalid-feedback">
                    請選擇參加營隊形式
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='participation_mode' value=線上營隊 > 線上營隊
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
        </div>
    </div>

    <div class='row form-group'> 
    <label for='inputReasonsOnline' class='col-md-2 control-label text-md-right'>選擇線上營隊原因</label>
        <div class='col-md-10'>
            <input type=text required name='reasons_online' value='' class='form-control' id='inputReasonsOnline' placeholder='若選線上營隊請簡述不參加實體營隊原因'>
            <div class="invalid-feedback">
                請填寫選擇線上營隊原因
            </div>
        </div>
    </div>

    <hr>
    <h5 class='form-control-static'>被推薦人(營隊學員)其它資訊及推薦理由：</h5>
    <br>

    <div class='row form-group required'> 
    <label for='inputUnit' class='col-md-2 control-label text-md-right'>被推薦人公司名稱</label>
        <div class='col-md-10'>
            <input type=text required name='unit' value='' class='form-control' id='inputUnit'>
            <div class="invalid-feedback crumb">
                請填寫被推薦人公司名稱
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
                <option value='醫療照護' >醫療照護</option>
                <option value='民生服務業' >民生服務業</option>
                <option value='廣告/傳播/出版' >廣告/傳播/出版</option>
                <option value='其它' >其它</option>
            </select>
        </div>  
    </div>

    <div class='row form-group'> 
    <label for='inputIndustryOther' class='col-md-2 control-label text-md-right'>產業別:自填</label>
        <div class='col-md-10'>
            <input type=text required name='industry_other' value='' class='form-control' id='inputIndustryOther' placeholder='產業別若選「其它」請自填'>
            <div class="invalid-feedback">
                產業別若選「其它」請自填
            </div>
        </div>
    </div>

    <div class='row form-group required'> 
    <label for='inputTitle' class='col-md-2 control-label text-md-right'>職稱</label>
        <div class='col-md-10'>
            <input type=text required name='title' value='' maxlength="40" class='form-control' id='inputTitle'>
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
                <option value='經營/人資' >經營/人資</option>
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
        </div>  
    </div>

    <div class='row form-group'> 
    <label for='inputJobPropertyOther' class='col-md-2 control-label text-md-right'>職務類型:自填</label>
        <div class='col-md-10'>
            <input type=text required name='job_property_other' value='' class='form-control' id='inputJobPropertyOther' placeholder='職務類型若選「其它」請自填'>
            <div class="invalid-feedback">
                職務類型若選「其它」請自填
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputTelWork' class='col-md-2 control-label text-md-right'>公司電話</label>
        <div class='col-md-10'>
            <input type=tel required name='phone_work' value='' class='form-control' id='inputTelWork' placeholder='格式：0225452546#520'>
            <div class="invalid-feedback crumb">
                請填寫公司電話
            </div>
        </div>
    </div>

    <div class='row form-group'> 
    <label for='inputEmployees' class='col-md-2 control-label text-md-right'>公司員工總數</label>
        <div class='col-md-10'>
            <input type=number required name='employees' value='' class='form-control' id='inputEmployees'>
            <div class="invalid-feedback crumb">
                請填寫公司員工總數
            </div>
        </div>
    </div>

    <div class='row form-group'> 
    <label for='inputDirectManagedEmployees' class='col-md-2 control-label text-md-right'>所轄員工人數</label>
        <div class='col-md-10'>
            <input type=number required name='direct_managed_employees' value='' class='form-control' id='inputDirectManagedEmployees'>
            <div class="invalid-feedback crumb">
                請填寫所轄員工人數
            </div>
        </div>
    </div>

    <div class='row form-group'> 
    <label for='inputCapital' class='col-md-2 control-label text-md-right'>資本額(新臺幣:元)</label>
        <div class='col-md-10'>
            <input type=number required name='capital' value='' maxlength="40" class='form-control' id='inputTitle' placeholder='請填寫數字'>
            <div class="invalid-feedback crumb">
                請填寫資本額
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputOrgType' class='col-md-2 control-label text-md-right'>公司/組織形式</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='org_type' value='私人公司' > 私人公司
                <div class="invalid-feedback">
                    請選擇公司/組織形式
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='org_type' value='專業領域(例醫生、作家⋯)' > 專業領域(例醫生、作家⋯)
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='org_type' value='政府部門/公營事業' > 政府部門/公營事業
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='org_type' value='非政府/非營利組織' > 非政府/非營利組織
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='org_type' value='其它' > 其它
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
        </div>
    </div>

    <div class='row form-group'> 
    <label for='inputOrgTypeOther' class='col-md-2 control-label text-md-right'>公司/組織形式:自填</label>
        <div class='col-md-10'>
            <input type=text required name='org_type_other' value='' class='form-control' id='inputOrgTypeOther' placeholder='公司/組織形式若選「其它」請自填'>
            <div class="invalid-feedback">
                公司/組織形式若選「其它」請自填
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputYearsOperation' class='col-md-2 control-label text-md-right'>公司經營年限</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='years_operation' value='10年以上' > 10年以上
                <div class="invalid-feedback">
                    請選擇公司經營年限
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='years_operation' value='5年~10年' > 5年~10年
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='years_operation' value='5年以下' > 5年以下
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputReasonsRecommend' class='col-md-2 control-label text-md-right'>特別推薦理由或社會影響力說明</label>
        <div class='col-md-10'>
            <textarea class='form-control' rows=2 name='reasons_recommend' id=inputReasonsRecommend></textarea>
            <div class="invalid-feedback">
                請填寫特別推薦理由或社會影響力說明
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
                        console.log($('.contact_time :checkbox:checked').length);
                        if($('.contact_time :checkbox:checked').length < 1) {
                            document.Camp.checkValidity();
                            event.preventDefault();
                            event.stopPropagation();
                            $(".tips").removeClass('d-none');
                            $('#contact_time-invalid').show();
                        }
                        else{
                            document.Camp.checkValidity();
                            event.preventDefault();
                            event.stopPropagation();
                            $(".tips").removeClass('d-none');
                            $('#contact_time-invalid').hide();
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
                        if($('.contact_time :checkbox:checked').length < 1) {
                            event.preventDefault();
                            event.stopPropagation();
                            console.log('yes');
                            {{-- $('.contact_time .invalid-feedback').prop('display') = 1; --}}
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
    參考頁面：https://bwfoce.org/ecamp/form/2020ep01.php
    --}}
