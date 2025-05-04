{{--
    參考頁面：https://bwfoce.org/ecamp/form/2020ep01.php
--}}
@php
    header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
    $regions = ['台北', '桃園', '新竹', '中區', '雲嘉', '台南', '高區'];
@endphp
@extends('camps.ecamp.layout')
@section('content')
    @include('partials.schools_script')
    @include('partials.counties_areas_script')
    <div class='alert alert-info' role='alert'>
        您在本網站所填寫的個人資料，僅用於此次企業營的報名及活動聯絡之用。
    </div>

    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}線上報名表</h4>
    </div>

{{-- 使用舊資料報名：如果有batch_id_from參數的話(今年限2021&2022企業營，但沒有寫這個條件) --}}
@if(isset($batch_id_from))
<hr>
    <form action="{{ route('formCopy', $batch_id_from) }}" method="POST">
        @csrf
        <input type="hidden" name="batch_id_ori" value="{{ $batch_id }}">
        <input type="hidden" name="batch_id_copy" value="{{ $batch_id_from }}">
        <input type="hidden" name="applicant_id_ori" value="{{ $applicant_id }}">
        <input type="submit" class="btn btn-success" value="使用此資料報名{{ $camp_abbr_from }}">
    </form>
<hr>
@endif

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
                @if(\Str::contains($batch->name, "中區"))
                <h3>(本場課程期間-住宿3天2夜)</h3>
                @endif
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
                    <input class="form-check-input" type="radio" name="gender" value="M">
                    男
                    <div class="invalid-feedback">
                        未選擇性別
                    </div>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="F">
                    <input class="form-check-input" type="radio" name="gender" value="F">
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

    <div class='row form-group'>
        <label for='inputBelief' class='col-md-2 control-label text-md-right'>宗教信仰</label>
        <div class='col-md-10'>
                <select name="belief" class="form-control">
                        <option value=''>- 請選擇 -</option>
                        <option value='佛教'>佛教</option>
                        <option value='道教'>道教</option>
                        <option value='天主教'>天主教</option>
                        <option value='基督教'>基督教</option>
                        <option value='一貫道'>一貫道</option>
                        <option value='民間信仰'>民間信仰</option>
                        <option value='佛道'>佛道</option>
                        <option value='其它'>其它</option>
                        <option value='無'>無</option>
                </select>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputEducation' class='col-md-2 control-label text-md-right'>最高學歷</label>
        <div class='col-md-10'>
                <select name="education" class="form-control">
                        <option value=''>- 請選擇 -</option>
                        <option value='高中職'>高中職</option>
                        <option value='專科'>專科</option>
                        <option value='大學'>大學</option>
                        <option value='碩士'>碩士</option>
                        <option value='博士'>博士</option>
                        <option value='其它'>其它</option>
                </select>
        </div>
    </div>

    <div class='row form-group required'>
    <label for='inputUnit' class='col-md-2 control-label text-md-right'>服務單位</label>
        <div class='col-md-10'>
            <input type=text required name='unit' value='' class='form-control' id='inputUnit'>
            <div class="invalid-feedback crumb">
                請填寫服務單位
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputIndustry' class='col-md-2 control-label text-md-right'>產業別</label>
        <div class='col-md-10'>
            <select required class='form-control' name='industry' onChange=''>
                <option value='' selected>- 請選擇 -</option>
                <option value='傳產營造' >傳產營造(食衣住行傳統製造 / 石化製造／營建／工程／不動產)</option>
                <option value='資訊科技' >資訊科技(電子／資訊／科技／通訊／半導體／軟體)</option>
                <option value='教育文化' >教育文化(學術／研究／出版／傳播／文化／藝術／娛樂)</option>
                <option value='醫療保健' >醫療保健(醫療／保健／藥廠)</option>
                <option value='金融諮詢' >金融諮詢(金融 / 保險／會計／法律／諮商)</option>
                <option value='民生服務' >民生服務(水電天然氣供應 / 交通運輸／批發 / 零售 / 倉儲／住宿／餐飲)</option>
                <option value='政府機關' >政府機關(公家機關／政府單位)</option>
                <option value='非營利組織' >非營利組織(公益單位 / 非營利組織／社會服務)</option>
                <option value='永續產業' >永續產業(再生能源／能源管理／永續發展／企業社會責任部門／環境工程)</option>
                <option value='其它' >其它</option>
            </select>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputUnitLocation' class='col-md-2 control-label text-md-right'>服務單位<br>所在地</label>
        <div class='col-md-10'>
            <input type=text required name='unit_location' value='' class='form-control' id='inputUnitLocation'>
        </div>
    </div>

    <div class='row form-group required'>
    <label for='inputTitle' class='col-md-2 control-label text-md-right'>職稱</label>
        <div class='col-md-10'>
            <input type=text required name='title' value='' maxlength="40" class='form-control' id='inputTitle'>
            <div class="invalid-feedback crumb">
                請填寫職稱
            </div>
        </div>
    </div>

    <div class='row form-group required'>
    <label for='inputEmployees' class='col-md-2 control-label text-md-right'>公司員工人數</label>
        <div class='col-md-10'>
            <input type=number required name='employees' value='' class='form-control' id='inputEmployees'>
            <div class="invalid-feedback crumb">
                請填寫公司員工人數
            </div>
        </div>
    </div>

    <div class='row form-group required'>
    <label for='inputDirectManagedEmployees' class='col-md-2 control-label text-md-right'>直屬管轄人數</label>
        <div class='col-md-10'>
            <input type=number required name='direct_managed_employees' value='' class='form-control' id='inputDirectManagedEmployees'>
            <div class="invalid-feedback crumb">
                請填寫直屬管轄人數
            </div>
        </div>
    </div>


    <hr>
    <h5 class='form-control-static'>聯絡方式</h5>
    <br>

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
        <label for='inputTelWork' class='col-md-2 control-label text-md-right'>公司電話</label>
        <div class='col-md-10'>
            <input type=tel required name='phone_work' value='' class='form-control' id='inputTelWork' placeholder='格式：0225452546#520'>
            <div class="invalid-feedback">
                請填寫公司電話
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputTelHome' class='col-md-2 control-label text-md-right'>住家電話</label>
        <div class='col-md-10'>
            <input type=tel  name='phone_home' value='' class='form-control' id='inputTelHome' placeholder='格式：0225452546#520'>
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

    <div class='row form-group'>
    <label for='inputWeChatID' class='col-md-2 control-label text-md-right'>微信 ID</label>
        <div class='col-md-10'>
            <input type=text name='wechat' value='' class='form-control' id='inputWeChatID'>
            <div class="invalid-feedback crumb">
                請填寫微信 ID
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
            <input type='email' required name='emailConfirm' value='' class='form-control' id='inputEmailConfirm'>
            {{-- data-match='#inputEmail' data-match-error='郵件不符合' placeholder='請再次填寫確認郵件填寫正確' --}}
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputAddress' class='col-md-2 control-label text-md-right'>通訊地址</label>
        <div class='col-md-2'>
            <select name="county" class="form-control" onChange="handlePersonalRegionChange(this)">
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
                <option value='上海地區' >上海地區</option>
                <option value='港澳深圳' >港澳深圳</option>
                <option value='南海諸島' >南海諸島</option>
                <option value='星馬地區' >星馬地區</option>
                <option value='其它海外' >其它海外</option>
            </select>
        </div>
        <div class='col-md-2'>
            <select name=subarea class='form-control' onChange='document.Camp.zipcode.value=this.options[this.options.selectedIndex].value; document.Camp.address.value=MyAddress(document.Camp.county.value, this.options[this.options.selectedIndex].text);' id='inputSubarea'>
                <option value=''>- 再選區鄉鎮 -</option>
            </select>
            <input type="hidden" name='' value='' id='inputSubarea2' disabled="true" type="text" style="background: var(--bs-table-bg);border-radius: 10px;width: 140px;border-style: none;" onkeydown='document.Camp.address.value=MyAddress(document.Camp.county.value, this.value);' placeholder="請輸入行政區或地區">
        </div>
        <div class='col-md-1'>
            <input readonly type=text name=zipcode value='' class='form-control'>
        </div>
        <div class='col-md-3'>
            <input type='text' required name='address' value='' pattern=".{10,80}" class='form-control' placeholder='請填寫通訊地址'>
            <div class="invalid-feedback">
                請填寫通訊地址或檢查輸入的地址是否不齊全
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputExpect' class='col-md-2 control-label text-md-right'>期望成長營給您的幫助</label>
        <div class='col-md-10'>
            <textarea required class='form-control' rows=2 name='expectation' id=inputExpect></textarea>
            <div class="invalid-feedback">
                請填寫本欄位
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

    <div class='row form-group'>
        <label class='col-md-2 control-label text-md-right'>介紹人<br>(若無免填)</label>
        <div class='col-md-10'>
            <div class='row form-group'>
                <div class='col-md-2'>
                    姓名：
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
                    關係：
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
                    聯絡電話：
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
                    福智班別：
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


    <!--- 同意書 -->
    <div class='row form-group required'>
        <label for='inputTerm' class='col-md-2 control-label text-md-right'>肖像權</label>
        <div class='col-md-10 form-check'>
            <p class='form-control-static text-danger'>
            主辦單位在營隊期間拍照、錄影之活動記錄，可使用於營隊及主辦單位的非營利教育推廣使用，並以網路方式推播。
            </p>
            <label class=radio-inline>
                <input type='radio' required name="portrait_agree" value='1' checked> 我同意
                <div class="invalid-feedback">
                    請圈選本欄位
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name="portrait_agree" value='0' > 我不同意
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputTerm' class='col-md-2 control-label text-md-right'>個人資料</label>
        <div class='col-md-10 form-check'>
            <p class='form-control-static text-danger'>
            福智文教基金會（簡稱本基金會）及本基金會所屬福智團體於本次營隊取得我的個人資料，於營隊期間及後續本基金會及本基金會所屬福智團體舉辦之活動，作為訊息通知、行政處理等非營利目的之使用，不會提供給無關之其他私人單位使用。
            </p>
            <label class=radio-inline>
                <input type='radio' required name="profile_agree" value='1' checked> 我同意
                <div class="invalid-feedback">
                    請圈選本欄位
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name="profile_agree" value='0' > 我不同意
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <hr>
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
                @if(isset($camp_data->modifying_deadline) && \Carbon\Carbon::now() <= \Carbon\Carbon::createFromFormat("Y-m-d", $camp_data->modifying_deadline))
                <input type="hidden" name="sn" value="{{ $applicant_id }}">
                <input type="hidden" name="isModify" value="1">
                <button class="btn btn-primary">修改報名資料</button>
                @endif
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
                console.log($('.favored_event').filter(':checked').length);
                console.log($('.after_camp_available_day').filter(':checked').length);
                if($('.favored_event').filter(':checked').length < 1) {
                    document.Camp.checkValidity();
                    event.preventDefault();
                    event.stopPropagation();
                    $(".tips").removeClass('d-none');
                    $('#favored_event-invalid').show();
                }
                else{
                    document.Camp.checkValidity();
                    event.preventDefault();
                    event.stopPropagation();
                    $(".tips").removeClass('d-none');
                    $('#favored_event-invalid').hide();
                }
                if($('.after_camp_available_day').filter(':checked').length < 1) {
                    document.Camp.checkValidity();
                    event.preventDefault();
                    event.stopPropagation();
                    $(".tips").removeClass('d-none');
                    $('#after_camp_available_day-invalid').show();
                }
                else{
                    document.Camp.checkValidity();
                    event.preventDefault();
                    event.stopPropagation();
                    $(".tips").removeClass('d-none');
                    $('#after_camp_available_day-invalid').hide();
                }
                if ((document.Camp.checkValidity() === false)
                    || ($('.favored_event').filter(':checked').length < 1)
                    || ($('.after_camp_available_day').filter(':checked').length < 1)) {
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
{{--
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
--}}
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
        function handleRegionChange(ele) {
            let subarea = document.getElementById("inputUnitSubarea");
            let subarea2 = document.getElementById("inputUnitSubarea2");
            if(ele.value == "上海地區" || ele.value == "港澳深圳" || ele.value == "南海諸島" || ele.value == "星馬地區" || ele.value == "其它海外") {
                subarea.disabled = true;
                subarea.style.display = "none";
                subarea.name = "";
                subarea.required = false;
                subarea2.disabled = false;
                subarea2.style.display = "block";
                subarea2.type = "text";
                subarea2.name = "unit_subarea";
                subarea2.required = true;
            }
            else {
                subarea.disabled = false;
                subarea.style.display = "block";
                subarea.value = "";
                subarea.name = "unit_subarea";
                subarea.required = true;
                subarea2.disabled = true;
                subarea2.style.display = "none";
                subarea2.name = "";
                subarea2.required = false;
                Address(ele.options[ele.options.selectedIndex].value, "unit");
            }
        }
        function handlePersonalRegionChange(ele) {
            let subarea = document.getElementById("inputSubarea");
            let subarea2 = document.getElementById("inputSubarea2");
            if(ele.value == "上海地區" || ele.value == "港澳深圳" || ele.value == "南海諸島" || ele.value == "星馬地區" || ele.value == "其它海外") {
                subarea.disabled = true;
                subarea.style.display = "none";
                subarea.name = "";
                subarea.required = false;
                subarea2.disabled = false;
                subarea2.style.display = "block";
                subarea2.type = "text";
                subarea2.name = "subarea";
                subarea2.required = true;
            }
            else {
                subarea.disabled = false;
                subarea.style.display = "block";
                subarea.value = "";
                subarea.name = "subarea";
                subarea.required = true;
                subarea2.disabled = true;
                subarea2.style.display = "none";
                subarea2.name = "";
                subarea2.required = false;
                Address(ele.options[ele.options.selectedIndex].value);
            }
        }
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
