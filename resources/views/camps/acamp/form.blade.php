{{-- 
    參考頁面：http://youngone.bwyouth.org/2020/form/index.php
    --}}
<?
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('camps.acamp.layout')
@section('content')
    @include('partials.schools_script')
    @include('partials.counties_areas_script')
    <div class='alert alert-info' role='alert'>
        您在本網站所填寫的個人資料，僅用於此次{{ $camp_data->abbreviation }}的報名及活動聯絡之用。
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
        <label for='inputBirth' class='col-md-2 control-label text-md-right'>生日年月</label>
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
                {{--
                <div class="col-md-3">
                    <input type='number' required class='form-control' name='birthday' min=1 max=31 value='' placeholder=''>
                    <div class="invalid-feedback">
                        未填寫或日期不正確
                    </div>
                </div>
                <div class="col-md-1">
                    日
                </div>
                --}}
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
            <div class="invalid-feedback">
                請選擇最高學歷
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
                <option value='其他'>其他</option>
                <option value='無'>無</option>
            </select>
        </div>
    </div>

{{--
    <div class='row form-group required'>
        <label for='inputNationName' class='col-md-2 control-label text-md-right'>國家地區</label>
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
            <option value='其他' >其他</option>
        </select>
        </div>
    </div>
--}}
    <div class='row form-group required'>
        <label for='inputAddress' class='col-md-2 control-label text-md-right'>現居住地點</label>
        <div class='col-md-2'>
            <select name="county" class="form-control" onChange="Address(this.options[this.options.selectedIndex].value);" required> 
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
                <option value='其他'>其他</option>
            </select>
        </div>
        <div class='col-md-2'>
            <select name=subarea class='form-control' onChange='document.Camp.zipcode.value=this.options[this.options.selectedIndex].value; document.Camp.address.value=MyAddress(document.Camp.county.value, this.options[this.options.selectedIndex].text);' required>
                <option value=''>- 再選區鄉鎮 -</option>
            </select>
        </div>
        <div class='col-md-1'>
            <input readonly type=hidden name=zipcode value='' class='form-control'>
        </div>
        <div class='col-md-3'>
            <input type=hidden name=address value='' maxlength=80 class='form-control' placeholder='請填寫通訊地址'>
            <div class="invalid-feedback">
                請填寫通訊地址
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

    <div class='row form-group'>
        <label for='inputTelWork' class='col-md-2 control-label text-md-right'>公司電話</label>
        <div class='col-md-10'>
            <input type=tel name='phone_work' value='' class='form-control' id='inputTelWork' placeholder='格式：0225452546#520'>
            {{-- <div class="invalid-feedback">
                請填寫公司電話
            </div> --}}
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputTelHome' class='col-md-2 control-label text-md-right'>住家電話</label>
        <div class='col-md-10'>
            <input type=tel  name='phone_home' value='' class='form-control' id='inputTelHome' placeholder='格式：0225452546'>
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



    <div class='row form-group required'> 
    <label for='inputUnit' class='col-md-2 control-label text-md-right'>服務單位</label>
        <div class='col-md-10'>
            <input type=text required name='unit' value='' class='form-control' id='inputUnit' placeholder='公司名稱'>
            <div class="invalid-feedback crumb">
                請填寫服務單位(公司名稱)
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputUnitCounty' class='col-md-2 control-label text-md-right'>服務地點</label>
        <div class='col-md-2'>
            <select name="unit_county" class="form-control" onChange="Address(this.options[this.options.selectedIndex].value, 'unit');"> 
                <option value=''>- 請選擇縣市 -</option>
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
                <option value='其他'>其他</option>
            </select>
        </div>
        <div class='col-md-2'>
            <select name='unit_subarea' class='form-control' onChange='document.Camp.unit_zipcode.value=this.options[this.options.selectedIndex].value; document.Camp.unit_address.value=MyAddress(document.Camp.unit_county.value, this.options[this.options.selectedIndex].text);'>
                <option value=''>- 再選區鄉鎮 -</option>
            </select>
        </div>
        <div class='col-md-1'>
            <input readonly type=hidden name=unit_zipcode value='' class='form-control'>
        </div>
        <div class='col-md-3'>
            <input type=hidden name=unit_address value='' maxlength=80 class='form-control' placeholder='請填寫通訊地址'>
            <div class="invalid-feedback">
                請填寫通訊地址
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputIndustry' class='col-md-2 control-label text-md-right'>產業別</label>
        <div class='col-md-10'>
            <select required class='form-control' name='industry' onChange=''>
                <option value='' selected>- 請選擇 -</option>
                <option value='科技業' >科技業</option>
                <option value='製造業' >製造業</option>
                <option value='金融保險業' >金融保險業</option>
                <option value='大眾傳播業' >大眾傳播業</option>
                <option value='農林漁牧業' >農林漁牧業</option>
                <option value='自由業' >自由業</option>
                <option value='營造業' >營造業</option>
                <option value='軍公教' >軍公教</option>
                <option value='觀光餐旅業' >觀光餐旅業</option>
                <option value='批發零售業' >批發零售業</option>
                <option value='醫療及社工服務業' >醫療及社工服務業</option>
                <option value='待業中' >待業中</option>
                <option value='其他' >其他</option>
            </select>
        </div>  
    </div>

    <div class='row form-group required'> 
    <label for='inputTitle' class='col-md-2 control-label text-md-right'>職稱</label>
        <div class='col-md-10'>
            <input type=text required name='title' value='' class='form-control' id='inputTitle'>
            <div class="invalid-feedback crumb">
                請填寫職稱
            </div>
        </div>
    </div>


    <div class='row form-group required'>
        <label for='inputJobProperty' class='col-md-2 control-label text-md-right'>目前工作屬性</label>
        <div class='col-md-10'>
            <select required class='form-control' name='job_property' onChange=''>
                <option value='' selected>- 請選擇 -</option>
                <option value='經營/管理' >經營/管理</option>
                <option value='一般行政' >一般行政</option>
                <option value='行銷企劃/媒體' >行銷企劃/媒體</option>
                <option value='業務/通路' >業務/通路</option>
                <option value='財務會計' >財務會計</option>
                <option value='教育師資' >教育師資</option>
                <option value='工程研發' >工程研發</option>
                <option value='產品開發' >產品開發</option>
                <option value='視覺設計' >視覺設計</option>
                <option value='電腦資訊' >電腦資訊</option>
                <option value='醫療護理' >醫療護理</option>
                <option value='其他' >其他</option>
            </select>
        </div>  
    </div>    

    <div class='row form-group required'>
        <label for='inputStuType' class='col-md-2 control-label text-md-right'>是否為主管</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='is_manager' value='1' > 是
                <div class="invalid-feedback">
                    請選擇是否為主管
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='is_manager' value='0' > 否
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputStuType' class='col-md-2 control-label text-md-right'>是否為儲訓幹部</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='is_cadre' value='1' > 是
                <div class="invalid-feedback">
                    請選擇是否為儲訓幹部
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='is_cadre' value='0' > 否
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputStuType' class='col-md-2 control-label text-md-right'>是否為專門技術人員</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='is_technical_staff' value='1' > 是
                <div class="invalid-feedback">
                    請選擇是否為專門技術人員
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='is_technical_staff' value='0' > 否
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputSource' class='col-md-2 control-label text-md-right'>您如何得知此營隊？(單選，最主要管道)</label>
        <div class='col-md-10'>
                <select name="way" class="form-control"> 
                        <option value=''>- 請選擇 -</option>
                        <option value='網路、fb'>網路、fb</option>
                        <option value='朋友同事'>朋友同事</option>
                        <option value='家人親戚'>家人親戚</option>
                        <option value='活動海報'>活動海報</option>
                        <option value='公司推薦'>公司推薦</option>
                        <option value='其他'>其他</option>
                </select>
        </div>
    {{--
        <div class='col-md-10'>
            <p class='form-control-static text-danger'>單選，請選最主要管道。</p>
            <label class=radio-inline>
                <input type=radio required name='way' value='網路、fb' > 網路、fb
                <div class="invalid-feedback">
                    請選擇得知管道
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value='朋友同事' > 朋友同事
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value='家人親戚' > 家人親戚
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value='活動海報' > 活動海報
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value='公司推薦' > 公司推薦
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='way' value=其他 > 其他
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    --}}
    </div>

    {{-- 動機 --}}
    <div class='row form-group required'>
        <label for='inputFuzhi' class='col-md-2 control-label text-md-right'>報名探索營的動機（可複選）</label>
        <div class='col-md-10'>
            <label><input type="checkbox" name=motivation[] value='自我提升' > 自我提升</label> <br/>
            <label><input type="checkbox" name=motivation[] value='紓壓釋放' > 紓壓釋放</label> <br/>
            <label><input type="checkbox" name=motivation[] value='交朋友' > 交朋友</label> <br/>
            <label><input type="checkbox" name=motivation[] value='認識福智' > 認識福智</label> <br/>
            <label>
                <input type="checkbox" name=motivation[] value='其他' id="motivation_other_checkbox"> 其他：<input type="text" name="motivation_other" class="form-control">
                <div class="invalid-feedback">
                    請填寫本欄位
                </div>
            </label> 
            {{-- 其他 --}}
        </div>
    </div>
    {{-- 曾參與 --}}
    <div class='row form-group required'>
        <label for='inputFuzhi' class='col-md-2 control-label text-md-right'>曾參與福智文教基金會所舉辦的營隊或課程（可複選）</label>
        <div class='col-md-10'>
            <label><input type="checkbox" name=blisswisdom_type[] value='否' > 否</label> <br/>
            <label><input type="checkbox" name=blisswisdom_type[] value='大專營' > 大專營</label> <br/>
            <label><input type="checkbox" name=blisswisdom_type[] value='教師營' > 教師營</label> <br/>
            <label><input type="checkbox" name=blisswisdom_type[] value='企業營' > 企業營</label> <br/>
            <label><input type="checkbox" name=blisswisdom_type[] value='卓越青年營' > 卓越青年營(卓青營)</label> <br/>
            <label><input type="checkbox" name=blisswisdom_type[] value='廣論研討班' > 廣論研討班</label> <br/>
            <label>
                <input type="checkbox" name=blisswisdom_type[] value='其他' id="blisswisdom_type_other_checkbox"> 其他：
                <input type="text" name="blisswisdom_type_other" class="form-control">
                <div class="invalid-feedback">
                    請填寫本欄位
                </div>
            </label>
            {{-- 其他 --}}
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputStuType' class='col-md-2 control-label text-md-right'>若營隊後續有平日晚間課程，您希望在哪裡參加？</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='class_location' value='上班附近' > 上班附近
                <div class="invalid-feedback">
                    請選擇課程地點
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='class_location' value='住家附近' > 住家附近
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='class_location' value='皆可' > 皆可
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputIndustry' class='col-md-2 control-label text-md-right'>交通需求</label>
        <div class='col-md-10'>
            <select required class='form-control' name='transportation' onChange=''>
                <option value='' selected>- 請選擇 -</option>
                <option value='捷運士林站接駁' >捷運士林站接駁</option>
                <option value='捷運劍南路站(美麗華)接駁' >捷運劍南路站(美麗華)接駁</option>
                <option value='校園機車停車位' >校園機車停車位</option>
                <option value='無以上需求' >無以上需求</option>
            </select>
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
                    聯絡電話：
                </div>
                <div class='col-md-10'>
                    <input type='tel' class='form-control' name="emergency_mobile" value='' required>
                </div>
                <div class="invalid-feedback">
                    請填寫本欄位
                </div>
            </div>   
            <div class='row form-group'>
                <div class='col-md-2'>
                    與報名者關係：
                </div>
                <div class='col-md-10'>
                    <select name="emergency_relationship" class="form-control"> 
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
        </div>
    </div>

    <div class='row form-group'>
        <label class='col-md-2 control-label text-md-right'>若有介紹人<br>請填寫介紹人資料</label>
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
                    與報名者關係：
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
        <label for='inputTerm' class='col-md-2 control-label text-md-right'>請同意</label>
        <div class='col-md-10'>
            <label>
                <p class='form-control-static text-danger'>
                <input type='radio' required name='profile_agree' value='1'> 我同意，本報名表所填個人資料，僅提供此次營隊及福智課程通知使用。主辦單位有權將此次活動的錄影、
照片，於刊物及網路上播放、展出。(同意將肖像用於相關活動的宣傳與播放使用）</p>
                <div class="invalid-feedback">
                    請圈選本欄位
                </div>
            </label> 
            <input type='radio' class='d-none' name='profile_agree' value='0' >
            <br/>
        </div>
    </div>

    <input type='hidden' name="portrait_agree" value='1'>

    {{--
    <div class='row form-group required'>
        <label for='inputTerm' class='col-md-2 control-label text-md-right'>肖像權</label>
        <div class='col-md-10 form-check'>
            <label>
                <p class='form-control-static text-danger'>
                <input type='radio' required name="portrait_agree" value='1'> 我同意，主辦單位有權將此次活動的錄影、照片，於刊物及網路上撥放、展出。(同意將肖像用於相關活動的宣傳與播放使用）</p>
                <div class="invalid-feedback">
                    請圈選本欄位
                </div>
            </label>  
            <input type='radio' class='d-none' name="portrait_agree" value='0'>  
            <br/>
        </div>
    </div>
    --}}
    <!--- 填寫表單之人 -->
    <div class='row form-group required'>
        <label class='col-md-2 control-label text-md-right'>是否為本人親自報名</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name="is_inperson" value="1" > 是
                <div class="invalid-feedback">
                    請選擇是否本人親自報名
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name="is_inperson" value="0" > 否
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>
    <div class='row form-group'>
        <label class='col-md-2 control-label text-md-right'>如非本人報名<br>請填寫填表人資料</label>
        <div class='col-md-10'>
            <div class='row form-group'>
                <div class='col-md-2'>
                    姓名： 
                </div>
                <div class='col-md-10'>
                    <input type=text class='form-control' name="agent_name" value=''>
                </div>
            </div>
            <div class='row form-group'>
                <div class='col-md-2'>
                    聯絡電話：
                </div>
                <div class='col-md-10'>
                    <input type=tel class='form-control' name="agent_phone" value=''>
                </div>
            </div>
            <div class='row form-group'>
                <div class='col-md-2'>
                    與報名者關係：
                </div>
                <div class='col-md-10'>
                    <select name="agent_relationship" class="form-control"> 
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
                document.getElementById('motivation_other_checkbox').addEventListener("change", function(){
                    document.Camp.motivation_other.required = document.getElementById('motivation_other_checkbox').checked;
                });
                document.getElementById('blisswisdom_type_other_checkbox').addEventListener("change", function(){
                    document.Camp.blisswisdom_type_other.required = document.getElementById('blisswisdom_type_other_checkbox').checked;
                });

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
