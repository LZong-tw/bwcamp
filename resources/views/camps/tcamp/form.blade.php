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
    @if(!$camp_data->variant)
        <div class="alert alert-warning">
            <h5>報名期間：112年10月16日(一)起至112年11月19日(日)止</h5>
            <h5>研習時間：113年01月27日(六)起至113年01月30日(二)止</h5>
            <h5>研習時數：凡參加研習者依規定核發研習時數或數位研習證書</h5>
            <h6>指導單位：教育部生命教育中心</h6>
            <h6>主辦單位：財團法人福智文教基金會、大仁科技大學</h6>
            <h6>合辦單位：國立臺中教育大學通識教育中心、桃園市立內壢高級中等學校</h6>
            <h6>協辦單位：福智學校財團法人、基隆市瑪陵國民小學、屏東縣立大路關國民小學</h6>
        </div>
    @endif
    <div class='alert alert-info' role='alert'>
        您在本網站所填寫的個人資料，僅用於此次教師營的報名及活動聯絡之用。
    </div>

    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }} {{ $batch->name }}場 線上報名表</h4>
    </div>
<span id="tcamp-layout">
{{-- !isset($isModify): 沒有 $isModify 變數，即為報名狀態、 $isModify: 修改資料狀態 --}}
@if((!isset($isModify) && $batch->is_appliable) || (isset($isModify) && $isModify))
    <form method='post' action='{{ route('formSubmit', [$batch_id]) }}' id='Camp' name='Camp' class='form-horizontal needs-validation' role='form'>
{{-- 禁止前台報名 --}}
@elseif(!$batch->is_appliable)
    <script>window.location = "/";</script>
    @exit
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
    @if(isset($applicant_data))
        <div class='row form-group'>
            <label for='inputBatch' class='col-md-2 control-label text-md-right'>營隊梯次</label>
            <div class='col-md-10'>
                <h4>{{ $batch->name }} ({{ $batch->batch_start }} ~ {{ $batch->batch_end }})</h4>
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
    @if($camp_data->variant ?? null == 'utcamp')
        {{-- 大專教師營 --}}
        <div id="utcamp-unit-and-batch-section" class="m-0 p-0">
            <utcamp-unit-and-batch-section></utcamp-unit-and-batch-section>
        </div>
    @endif
    <div class='row form-group required'>
        <label for='inputName' class='col-md-2 control-label text-md-right'>姓名</label>
        <div class='col-md-10'>
            <input type='text' name='name' value='' class='form-control' id='inputName' placeholder='請填寫全名' required @if(isset($isModify) && $isModify) disabled @endif>
            <div class="invalid-feedback">
                未填寫姓名
            </div>
        </div>
    </div>

    <div class="row form-group required">
        <label for='inputGender' class='col-md-2 control-label text-md-right'>生理性別</label>
        <div class='col-md-10'>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="gender" value="M" required @if(isset($isModify) && $isModify) disabled @endif>
                    男
                    <div class="invalid-feedback">
                        未選擇生理性別
                    </div>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
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
        <label for='inputBirth' class='col-md-2 control-label text-md-right'>出生年(西元)</label>
        <div class='col-md-10' id='inputBirth'>
            <input required type='number' class='form-control' name='birthyear' min=1940 max='{{ \Carbon\Carbon::now()->subYears(16)->year }}' value='' placeholder=''>
            <div class="invalid-feedback">
                未填寫出生年，或格式不正確
            </div>
        </div>
    </div>

<!--
    <div class='row form-group required'>
        <label for='inputBirth' class='col-md-2 control-label text-md-right'>年齡層</label>
        <div class='col-md-10'>
            <select required class='form-control' name='age_range' placeholder=''>
                <option value="">- 請選擇 -</option>
                <option value="25 歲以下">25 歲以下</option>
                <option value="26 歲 - 35 歲">26 歲 - 35 歲</option>
                <option value="36 歲 - 45 歲">36 歲 - 45 歲</option>
                <option value="46 歲 - 55 歲">46 歲 - 55 歲</option>
                <option value="56 歲 - 65 歲">56 歲 - 65 歲</option>
                <option value="66 歲以上">66 歲以上</option>
            </select>
            <div class="invalid-feedback">
                未選擇
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputEducation' class='col-md-2 control-label text-md-right'>最高學歷</label>
        <div class='col-md-10'>
            <select name="education" class="form-control" required>
                <option value=''>- 請選擇 -</option>
                <option value='高中職'>高中職</option>
                <option value='大專'>大專</option>
                <option value='碩士'>碩士</option>
                <option value='博士'>博士</option>
                <option value='其他'>其他</option>
            </select>
        </div>
    </div>
-->
    @if(!$camp_data->variant)
        <div class='row form-group required'>
            <label for='inputHasLicense' class='col-md-2 control-label text-md-right'>是否有教師證</label>
            <div class='col-md-10'>
                <label class=radio-inline>
                    <input type=radio required name='has_license' value=1 > 有
                    <div class="invalid-feedback">
                        未勾選是否有教師證
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
    @endif

    <div class='row form-group required'>
        <label for='inputBirth' class='col-md-2 control-label text-md-right'>研習時數申請</label>
        <div class='col-md-10'>
            <select required class='form-control' name='workshop_credit_type' placeholder='' onchange="id_setRequired(this)">
                <option value="">- 請選擇 -</option>
                <option value="不申請">不申請</option>
                @if(!$camp_data->variant)
                    <option value="一般教師研習時數">一般教師研習時數</option>
                @endif
                <option value="公務員研習時數">公務員研習時數</option>
                <option value="基金會研習數位證明書">基金會研習數位證明書</option>
            </select>
            <div class="invalid-feedback">
                未選擇研習時數申請
            </div>
        </div>
    </div>

    <div class='row form-group required' style="display: none;">
        <label for='inputID' class='col-md-2 control-label text-md-right'>身份證字號</label>
        <div class='col-md-10'>
            <input type='text' name='idno' value='' class='form-control' id='inputID' placeholder='僅作為申請研習時數或研習證明用' @if(isset($isModify) && $isModify) disabled @endif>
            <div class="invalid-feedback">
                未填寫身份證字號（申請時數或研習證明用）
            </div>
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
    @if(!$camp_data->variant)
        <div id="is-educating-section" class="m-0 p-0">
            <Is-Educating-Section></Is-Educating-Section>
        </div>

        <div class='row form-group required'>
        <label for='inputUnit' class='col-md-2 control-label text-md-right'>服務單位名稱/校名</label>
            <div class='col-md-10'>
                <input type=text required name='unit' value='' class='form-control' id='inputUnit'>
                <div class="invalid-feedback crumb">
                    未填寫服務單位名稱/校名
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
                <div class="invalid-feedback crumb">
                    未選擇服務單位所在縣市
                </div>
            </div>
        </div>
    @elseif($camp_data->variant == "utcamp")
        <div class="row form-group required">
            <label
                for="inputSchoolOrCourse"
                class="col-md-2 control-label text-md-right"
                >服務系所/部門</label
            >
            <div class="col-md-10">
                <input
                    type="text"
                    required
                    name="school_or_course"
                    class='form-control'
                    value=""
                />
                <div class="invalid-feedback crumb">
                    請輸入服務系所/部門
                </div>
            </div>
        </div>
        <span id="utcamp-title" class="m-0 p-0">
            <utcamp-title></utcamp-title>
        </span>
    @endif
    <h5 class='form-control-static text-primary'>聯絡方式</h5>
    <!--<p class="form-control-static text-danger">＊因需寄發教材資料及通知，請務必填寫正確</p>-->
    <div class='row form-group required'>
        <label for='inputCell' class='col-md-2 control-label text-md-right'>行動電話</label>
        <div class='col-md-10'>
            <input type=tel required name='mobile' value='' class='form-control' id='inputCell' pattern='09\d{8}' placeholder='格式：0912345678'>
            <div class="invalid-feedback">
                未填寫行動電話，或格式不正確
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputTelHome' class='col-md-2 control-label text-md-right'>家中電話</label>
        <div class='col-md-10'>
            <input type=tel  name='phone_home' value='' class='form-control' id='inputTelHome' placeholder='格式：0225452546'>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputLineID' class='col-md-2 control-label text-md-right'>LINE ID</label>
        <div class='col-md-10'>
            <input type=text name='line' value='' class='form-control' id='inputLineID'>
        </div>
    </div>

    @if($camp_data->variant ?? null == 'utcamp')
        <div class='row form-group'>
            <label for='inputTelHome' class='col-md-2 control-label text-md-right'>工作場所電話</label>
            <div class='col-md-10'>
                <input type=tel  name='phone_work' value='' class='form-control' id='inputTelWork' placeholder='格式：0225452546#1234'>
            </div>
        </div>
    @endif
<!--
    <div class='row form-group m-0'>
        <div class="col-md-2 m-0"></div>
        <p class="form-control-static text-danger font-weight-bold m-0">＊因需寄發教材資料，請務必填寫詳細</p>
    </div>
-->
<!--
<div class='row form-group required'>
        <label for='inputAddress' class='col-md-2 control-label text-md-right'>通訊地址</label>
        <div class='col-md-2'>
            <select required name="county" class="form-control" onChange="Address(this.options[this.options.selectedIndex].value);">
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
            <div class="invalid-feedback crumb">
                未選擇縣市
            </div>
        </div>
        <div class='col-md-2'>
            <select required name=subarea class='form-control' onChange='document.Camp.zipcode.value=this.options[this.options.selectedIndex].value; document.Camp.address.value=MyAddress(document.Camp.county.value, this.options[this.options.selectedIndex].text);'>
                <option value=''>- 再選區鄉鎮 -</option>
            </select>
            <div class="invalid-feedback crumb">
                未選擇區鄉鎮
            </div>
        </div>
        <div class='col-md-1'>
            <input readonly type=text name=zipcode value='' class='form-control'>
        </div>
        <div class='col-md-5'>
            <input type=text required name='address' value='' pattern=".{10,80}" class='form-control' placeholder='請填寫通訊地址'>
            <div class="invalid-feedback">
                未填寫通訊地址
            </div>
        </div>
    </div>
-->
    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>電子郵件</label>
        <div class='col-md-10'>
            <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請務必填寫正確，以利營隊相關訊息通知'>
            <div class="invalid-feedback">
                未填寫電子郵件，或格式不正確
            </div>
        </div>
    </div>

    <script language='javascript'>
        window.addEventListener("load", function() {
            $('#inputEmail').bind("cut copy paste", function (e) {
                e.preventDefault();
            })
        });
    </script>

    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>確認電子郵件</label>
        <div class='col-md-10'>
            <input type='email' required name='emailConfirm' value='' class='form-control' id='inputEmailConfirm' placeholder='請再次填寫，確認郵件正確'>
            {{-- data-match='#inputEmail' data-match-error='郵件不符合' placeholder='請再次填寫確認郵件填寫正確' --}}
            <div class="invalid-feedback">
                未確認電子郵件
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>願意收到福智文教基金會電子報</label>
        <div class="col-md-10">
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" name="is_allow_notified" value="1" required>
                    是
                    <div class="invalid-feedback">
                        請選擇一項
                    </div>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" name="is_allow_notified" value="0" required>
                    否
                    <div class="invalid-feedback">
                        &nbsp;
                    </div>
                </label>
            </div>
        </div>
    </div>


    <div class='row form-group'>
        <label class='col-md-2 control-label text-md-right'>緊急聯絡人</label>
        <div class='col-md-10'>
            <div class='row form-group'>
                <div class='col-md-12 text-primary'>
                    ＊＊＊緊急聯絡人資訊僅作營隊活動中緊急聯絡所需＊＊＊
                </div>
            </div>
            <div class='row form-group'>
                <div class='col-md-2'>
                    姓名<label class='text-danger'>＊</label>
                </div>
                <div class='col-md-10'>
                    <input type='text' class='form-control' name="emergency_name" value='' required>
                    <div class="invalid-feedback">
                    未填寫緊急聯絡人姓名
                    </div>
                </div>
            </div>
            <div class='row form-group'>
                <div class='col-md-2 required'>
                    關係<label class='text-danger'>＊</label>
                </div>
                <div class='col-md-10'>
                    <select name="emergency_relationship" class="form-control" required>
                        <option value=''>- 請選擇 -</option>
                        <option value='配偶'>配偶</option>
                        <option value='父親'>父親</option>
                        <option value='母親'>母親</option>
                        <option value='兄弟'>兄弟</option>
                        <option value='姊妹'>姊妹</option>
                        <option value='朋友'>朋友</option>
                        <option value='同事'>同事</option>
                        <option value='子女'>子女</option>
                        <option value='其他'>其他</option>
                    </select>
                    <div class="invalid-feedback">
                    未填寫緊急聯絡人關係
                    </div>
                </div>
            </div>
            <div class='row form-group'>
                <div class='col-md-2 required'>
                    聯絡電話1<label class='text-danger'>＊</label>
                </div>
                <div class='col-md-10'>
                    <input type='tel' class='form-control' name="emergency_mobile" value='' required>
                    <div class="invalid-feedback">
                    未填寫緊急聯絡人電話
                    </div>
                </div>
            </div>
            <div class='row form-group'>
                <div class='col-md-2'>
                    聯絡電話2
                </div>
                <div class='col-md-10'>
                    <input type='tel' class='form-control' name="emergency_phone_home" value=''>
                </div>
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <label class='col-md-2 control-label text-md-right'>交通需求</label>
        <div class='col-md-10'>
            <div class='row form-group'>
                <div class='col-md-12 text-primary'>
                    ＊＊＊僅供大會接駁規劃用，活動前另有義工個別聯繫確認＊＊＊
                </div>
            </div>
            <div class='row form-group required'>
                <label for='inputTransportation' class='col-md-2 control-label text-md-right'>
                    去程
                </label>
                <div class='col-md-10'>
                    <select required class='form-control' name='transportation' onChange=''>
                        <option value='' selected>- 請選擇 -</option>
                        <option value='屏東火車站至大仁科大接駁' >屏東火車站至大仁科大接駁</option>
                        <option value='左營高鐵站至大仁科大接駁' >左營高鐵站至大仁科大接駁</option>
                        <option value='自行前往無以上需求' >自行前往無以上需求</option>
                    </select>
                </div>
            </div>
            <div class='row form-group required'>
                <label for='inputTransportation' class='col-md-2 control-label text-md-right'>
                    回程
                </label>
                <div class='col-md-10'>
                    <select required class='form-control' name='transportation' onChange=''>
                        <option value='' selected>- 請選擇 -</option>
                        <option value='大仁科大至屏東火車站接駁' >大仁科大至屏東火車站接駁</option>
                        <option value='大仁科大至左營高鐵站接駁' >大仁科大至左營高鐵站接駁</option>
                        <option value='自行返回無以上需求' >自行返回無以上需求</option>
                    </select>
                </div>
            </div>
        </div>
    </div>




    <div class='row form-group'>
        <label class='col-md-2 control-label text-md-right'>介紹人<br>(若無免填)</label>
        <div class='col-md-10'>
            <div class='row form-group'>
                <div class='col-md-2'>
                    姓名
                </div>
                <div class='col-md-10'>
                    <input type='text' class='form-control' name="introducer_name" value=''>
                </div>
                <div class="invalid-feedback">
                    請填寫本欄位
                </div>
            </div>

            <div class='row form-group'>
                <div class='col-md-2'>
                    關係
                </div>
                <div class='col-md-10'>
                    <select name="introducer_relationship" class="form-control">
                        <option value=''>- 請選擇 -</option>
                        <option value='配偶'>配偶</option>
                        <option value='父親'>父親</option>
                        <option value='母親'>母親</option>
                        <option value='兄弟'>兄弟</option>
                        <option value='姊妹'>姊妹</option>
                        <option value='朋友'>朋友</option>
                        <option value='同事'>同事</option>
                        <option value='子女'>子女</option>
                        <option value='其他'>其他</option>
                    </select>
                </div>
            </div>
            <div class='row form-group'>
                <div class='col-md-2'>
                    聯絡電話
                </div>
                <div class='col-md-10'>
                    <input type='tel' class='form-control' name="introducer_phone" value=''>
                </div>
                <div class="invalid-feedback">
                    請填寫本欄位
                </div>
            </div>
            <div class='row form-group'>
                <div class='col-md-2'>
                    福智班別
                </div>
                <div class='col-md-10'>
                    <input type='text'class='form-control' name="introducer_participated" value=''>
                </div>
                <div class="invalid-feedback">
                    請填寫本欄位
                </div>
            </div>
        </div>
    </div>

    <!-- 有點複雜的「是否參加過福智活動」的調查 -->
    @if($camp_data->variant ?? null == 'utcamp')
        <span id="utcamp-is-blisswisdom">
            <utcamp-is-blisswisdom></utcamp-is-blisswisdom>
        </span>
    @else
        <div class='row form-group'>
            <label for='inputFuzhi' class='col-md-2 control-label text-md-right'>之前是否曾參加過「福智教師生命成長營」</label>
            <div class='col-md-10'>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" name="is_attend_tcamp" value='1' onclick="toggleTcampYear(1)"> 是
                        <div class="invalid-feedback">
                            請選擇項目
                        </div>
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" name="is_attend_tcamp" value='0' onclick="toggleTcampYear(0)"> 否
                        <div class="invalid-feedback">
                            &nbsp;
                        </div>
                    </label>
                </div>
            </div>
        </div>
        <div class='row form-group required' style="display: none;" id="tcamp_year_row">
            <label for='inputTcampYear' class='col-md-2 control-label text-md-right'>參加年度(西元)</label>
            <div class='col-md-10' id='inputTcampYear'>
                <input type='number' class='form-control' name='tcamp_year' min=1993 max='{{ \Carbon\Carbon::now()->year }}' value='' placeholder='大約年度即可'>
                <div class="invalid-feedback">
                    未填寫參加年度，或格式不正確
                </div>
            </div>
        </div>

        <div class='row form-group'>
            <label for='inputFuzhi' class='col-md-2 control-label text-md-right'>是否參加過福智「其它」的活動</label>
            <div class='col-md-10'>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" name="is_blisswisdom" value='1' onclick="toggleComplement(1)"> 是
                        <div class="invalid-feedback">
                            請選擇項目
                        </div>
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" name="is_blisswisdom" value='0' onclick="toggleComplement(0)"> 否
                        <div class="invalid-feedback">
                            &nbsp;
                        </div>
                    </label>
                </div>
            </div>
        </div>


        <div class='row form-group required' style="display: none;" id="complement_row">
            <label for='inputFuzhi' class='col-md-2 control-label text-md-right'>參加過福智的活動</label>
            <div class='col-md-10'>
                <label><input type="checkbox" name=blisswisdom_type[] value='廣論班' > 廣論班</label> <br/>
                <label><input type="checkbox" name=blisswisdom_type[] value='校園減塑點亮計畫' > 校園減塑點亮計畫</label> <br/>
                <label><input type="checkbox" name=blisswisdom_type[] value='幸福教育學等研習課程' > 幸福教育學等研習課程</label> <br/>
                <label><input type="checkbox" name=blisswisdom_type[] value='其他' onchange="toggleBTCrequired()"> 其它</label> <br>
                <input type=text class='form-control' name="blisswisdom_type_complement" value='' id="blisswisdom_type_complement">
                <div class="invalid-feedback">
                    請填寫活動
                </div>
            </div>
        </div>
    @endif

    <div class='row form-group'>
        <label for='inputFuzhi' class='col-md-2 control-label text-md-right'>如何得知報名訊息(可複選)</label>
        <div class='col-md-10'>
            <label><input type="checkbox" name=info_source[] value='親友/同事推薦' > 親友/同事推薦</label> <br/>
            <label><input type="checkbox" name=info_source[] value='學校公文' > 學校公文</label> <br/>
            <label><input type="checkbox" name=info_source[] value='宣傳海報/小卡' > 宣傳海報/小卡</label> <br/>
            <label><input type="checkbox" name=info_source[] value='福智文教基金會官網' > 福智文教基金會官網</label> <br/>
            @if(!isset($camp_data->variant) || $camp_data->variant != 'utcamp')
                <label><input type="checkbox" name=info_source[] value='幸福心學堂 Online 臉書' > 幸福心學堂 Online 臉書</label> <br/>
                <!--<label><input type="checkbox" name=info_source[] value='哈特麥臉書' > 哈特麥臉書</label> <br/>-->
            @endif
            <label><input type="checkbox" name=info_source[] value='自行搜尋' > 自行搜尋</label>
        </div>
    </div>

    @if(!isset($camp_data->variant) || $camp_data->variant != 'utcamp')
        <div class='row form-group'>
            <label for='inputFuzhi' class='col-md-2 control-label text-md-right'>對哪些活動有興趣(可複選)</label>
            <div class='col-md-10'>
                <label><input type="checkbox" name=interesting[] value='親子教育' > 親子教育</label> <br/>
                <label><input type="checkbox" name=interesting[] value='婚姻經營' > 婚姻經營</label> <br/>
                <label><input type="checkbox" name=interesting[] value='班級經營' > 班級經營</label> <br/>
                <label><input type="checkbox" name=interesting[] value='情緒管理' > 情緒管理</label> <br/>
                <label><input type="checkbox" name=interesting[] value='環保淨灘' > 環保淨灘</label> <br/>
                <label><input type="checkbox" name=interesting[] value='農場體驗' > 農場體驗</label> <br/>
                <label><input type="checkbox" name=interesting[] value='種樹活動' > 種樹活動</label> <br/>
                <label><input type="checkbox" name=interesting[] value='藝文活動' > 藝文活動</label> <br/>
                <label><input type="checkbox" name=interesting[] value='樂齡活動' > 樂齡活動</label> <br/>
                <label><input type="checkbox" name=interesting[] value='正念靜心' > 正念靜心</label> <br/>
                <label><input type="checkbox" name=interesting[] value='儒學與生活' > 儒學與生活</label> <br/>
                <label><input type="checkbox" name=interesting[] value='其他' onchange="toggleICrequired()"> 其它</label> <br>
                <input type=text class='form-control' name="interesting_complement" value='' id="interesting_complement">
                <div class="invalid-feedback">
                    請填寫活動
                </div>
            </div>
        </div>

        <div class='row form-group'>
            <label for='inputAvailableDay' class='col-md-2 control-label text-md-right'>營隊結束後，若有後續課程開課，請問您比較方便參加的時段？(可複選)</label>
            <div class='col-md-10'>
                <label><input type="checkbox" class="after_camp_available_day" name=after_camp_available_day[] value='平日晚上' > 平日晚上</label> <br/>
                <label><input type="checkbox" class="after_camp_available_day" name=after_camp_available_day[] value='假日白天' > 假日白天</label> <br/>
                <label><input type="checkbox" class="after_camp_available_day" name=after_camp_available_day[] value='假日晚上' > 假日晚上</label> <br/>
                <label><input type="checkbox" class="after_camp_available_day" name=after_camp_available_day[] value='寒暑假' > 寒暑假</label> <br/>
            </div>
        </div>

    @else
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
                    {{-- <div class="invalid-feedback">
                        請填寫本欄位
                    </div> --}}
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
            </div>
        </div>
    @endif

    <div class='row form-group'>
        <label for='inputExpect' class='col-md-2 control-label text-md-right'>我對這次活動的期望</label>
        <div class='col-md-10'>
            <textarea class='form-control' rows=2 name='expectation' id=inputExpect></textarea>
            {{-- <div class="invalid-feedback">
                請填寫本欄位
            </div> --}}
        </div>
    </div>

    <!--- 同意書 -->
    <div class='row form-group required'>
        <label for='inputTerm' class='col-md-2 control-label text-md-right'>肖像權</label>
        <div class='col-md-10 form-check'>
            <p class='form-control-static text-primary mb-0'>
                <label>
                    <input type='radio' required name='portrait_agree' value='1'>
                    我同意主辦方就所報名之
                    <!-- Button trigger modal -->
                    <button type="button" class="text-primary" data-toggle="modal" data-target="#exampleModalCenter">
                    活動或課程進行期間內所採訪或拍攝或攝影
                    </button>
                    之文字與影像進行合理範圍內之招生或使用（官網活動花絮等）。
                </label>
            </p>
            <input type='radio' class='d-none' name="portrait_agree" value='0'>
            <div class="invalid-feedback mt-0">
                請圈選本欄位
            </div>
        </div>
    </div>

    <!-- Modal：同意書互動視窗 -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">肖像權使用同意書</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
本人 現謹同意並授權以下參與財團法人福智文教基金會（下稱「基金會」）所舉辦之「教師生命成長營」，活動期間以文字、拍照及錄影音方式記錄過程中含有 本人之文字、影音、圖像、資料、心得分享等（下稱「本標的」），並同意接受下列之方式轉讓基金會：<br>
1. 本人同意將本標的之完整著作財產權無償轉讓予基金會。<br>
2. 本人之肖像權無償授權給基金會、其相關單位及基金會轉授權之第三方於教育推廣或非營利目的使用。（詳福智文教基金會網站：https://bwfoce.org/）<br>
上述說明您皆瞭解後，請點選同意。
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">回報名頁</button>
                    <!--<button type="button" class="btn btn-secondary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputTerm' class='col-md-2 control-label text-md-right'>個人資料</label>
        <div class='col-md-10'>
            <p class='form-control-static text-primary mb-0'>
                <label>
                    <input type='radio' required name='profile_agree' value='1'>
                    我同意主辦單位於本次營隊取得我的個人資料，於營隊期間及後續主辦單位舉辦之活動，作為訊息通知、行政處理等非營利目的之使用，不會提供給無關之其他私人單位使用。
                </label>
            </p>
            <input type='radio' class='d-none' name='profile_agree' value='0'>
            <div class="invalid-feedback mt-0">
                請圈選本欄位
            </div>
        </div>
    </div>

    <div class="row form-group text-danger tips d-none">
        <div class='col-md-2'></div>
        <div class='col-md-10'>
            請檢查是否有未填寫或格式不正確的欄位。
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
</span>

    <script>
        window.addEventListener("load", function() {
            $('[data-toggle="confirmation"]').confirmation({
                rootSelector: '[data-toggle=confirmation]',
                title: "敬請再次確認資料填寫無誤。",
                btnOkLabel: "正確無誤，送出",
                btnCancelLabel: "再檢查一下",
                popout: true,
                onConfirm: function() {
                            let isValid = document.Camp.checkValidity();
                            console.log(123);
                            if(document.Camp.title) {
                                if(document.Camp.title.value == '' && document.Camp.title.disabled) {
                                    document.Camp.title.classList.add("is-invalid");
                                    isValid = false;
                                }
                            }

                            // let blisswisdom_type_complements = $('input').filter(function() {
                            //                                         return this.name.match(/blisswisdom_type_complement\[\d\]/);
                            //                                     });
                            // if(blisswisdom_type_complements) {
                            //     let totalFilled = 0;
                            //     for (var i = 0; i < blisswisdom_type_complements.length; i++) {
                            //         if(blisswisdom_type_complements[i].value) {
                            //             totalFilled++;
                            //         }
                            //     }
                            //     if(totalFilled == 0 && document.getElementById("complement_row").style.display != "none") {
                            //         console.log(blisswisdom_type_complements);
                            //         for (var i = 0; i < blisswisdom_type_complements.length; i++) {
                            //             blisswisdom_type_complements[i].classList.add("is-invalid");
                            //             console.log(blisswisdom_type_complements[i].value);
                            //         }
                            //         isValid = false;
                            //     }
                            // }
                            if (isValid === false) {
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
            })
        });
        (function() {
            'use strict';
            document.addEventListener('DOMContentLoaded', function () {
                const inputs = Array.from(
                    document.querySelectorAll('input[name="blisswisdom_type_complement[0]"], input[name="blisswisdom_type_complement[1]"]'));
                const inputListener = e => inputs.filter(i => i !== e.target).forEach(i => i.required = !e.target.value.length);

                inputs.forEach(i => i.addEventListener('input', inputListener));
            });
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        let isValid = form.checkValidity();
                        console.log(223);
                        if(form.title) {
                            if(form.title.value == '' && form.title.disabled) {
                                form.title.classList.add("is-invalid");
                            }
                        }
                        if(form.blisswisdom_type_complement) {
                            let totalFilled = 0;
                            form.blisswisdom_type_complement.forEach(element => {
                                if(element.value) { totalFilled++; }
                            });
                            if(totalFilled == 0 && document.getElementById("complement_row").style.display != "none") {
                                form.blisswisdom_type_complement.forEach(element => {
                                    element.classList.add("is-invalid");
                                });
                                isValid = false;
                            }
                        }
                        if (isValid === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        /**
        * Ready functions.
        * Executes commands after the web page is loaded.
        */
        document.onreadystatechange = () => {
            if (document.readyState === 'complete') {
            }
        };
        //toggle interesting_complement required or not
        function toggleICrequired() {
            document.getElementById('interesting_complement').required = !document.getElementById('interesting_complement').required ? true : false;
        }
        //toggle blisswisdom_type_complement required or not
        function toggleBTCrequired() {
            document.getElementById('blisswisdom_type_complement').required = !document.getElementById('blisswisdom_type_complement').required ? true : false;
        }
        //??
        function toggleComplement(val) {
            let blisswisdom_type_complements = $('input').filter(function() {
                                                            return this.name.match(/blisswisdom_type_complement\[\d\]/);
                                                        });
            for (var i = 0; i < blisswisdom_type_complements.length; i++) {
                blisswisdom_type_complements[i].required = val;
            }
            if(val) {
                $("#complement_row").show();
            }
            else {
                $("#complement_row").hide();
            }
        }
        function toggleTcampYear(val) {
            if(val) {
                $("#tcamp_year_row").show();
                document.getElementById('inputTcampYear').required = true;
            }
            else {
                $("#tcamp_year_row").hide();
                document.getElementById('inputTcampYear').required = false;
            }
        }

        function id_setRequired(ele) {
            if(ele.value == "一般教師研習時數" || ele.value == "公務員研習時數") {
                $("#inputID").parent().parent().show();
                $("#inputID").prop('required',true);
            }
            else {
                $("#inputID").prop('required',false);
                $("#inputID").parent().parent().hide();
            }
        }

        function setRequired(elements){
            for(let i = 0; i < elements.length; i++){
                elements[i].required = true;
            }
        }

        @if(isset($applicant_data))
            {{-- 回填報名資料 --}}
            (function() {
                window.applicant_id = '{{ $applicant_id }}';
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
                    else if(inputs[i].type == "text" && (inputs[i].name == 'blisswisdom_type_complement[]' || inputs[i].name == 'blisswisdom_type_complement[0]' || inputs[i].name == 'blisswisdom_type_complement[1]')){
                        toggleComplement(applicant_data["is_blisswisdom"]);
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
                        if(typeof applicant_data[inputs[i].name] !== "undefined" || inputs[i].type == "checkbox" || inputs[i].name == 'emailConfirm' || inputs[i].name == "blisswisdom_type[]" || inputs[i].name == "blisswisdom_type_complement[]"
                        || inputs[i].name == "blisswisdom_type_complement[0]" || inputs[i].name == "blisswisdom_type_complement[1]"){
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
