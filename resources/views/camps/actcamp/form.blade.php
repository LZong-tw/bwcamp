@php
    header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
    $regions = ['台北', '桃園', '新竹', '中區', '雲嘉', '台南', '高區'];
@endphp
@extends('camps.actcamp.layout')
@section('content')
    @include('partials.counties_areas_script')
    <div class='alert alert-info' role='alert'>
        您在本網站所填寫的個人資料，僅用於此次「{{ $camp_data->fullName }}」的報名及活動聯絡之用。
    </div>

    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }} 線上報名表</h4>
        參訪日期：2023/9/17(日)<br>
        遊覽車發車時間：早上7:50(8:00準時發車)<br>
        遊覽車搭車地點：福智台北學苑對面彰化銀行(南京東路四段126號)<br>
        活動費用：成人600元／國小300元／幼稚園免費(費用於活動當天繳交)<br>
        <br>
        【報名注意事項】<br>
        若代親友報名，請分開填寫報名表。<br>
        若代親友報名，聯絡人必須是一同參加之企業營學員或義工。<br>
        若您在填寫表格時遇到困難，請洽詢您企業營的關懷員<br>
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
            <label for='inputBatch' class='col-md-2 control-label text-md-right'>報名日期</label>
            <div class='col-md-10'>
                {{ $applicant_raw_data->created_at }}
            </div>
        </div>
    @endif

    <div class='row form-group required'>
        <label for='inputName' class='col-md-2 control-label text-md-right'>報名者姓名</label>
        <div class='col-md-10'>
            <input type='text' name='name' value='' class='form-control' id='inputName' placeholder='請填寫全名' required>
        </div>
        <div class="invalid-feedback">
            請填寫姓名
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputCategory' class='col-md-2 control-label text-md-right'>身份別</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input selected type=radio required name='category' value=企業營學員 onClick='contact_field(0);'> 企業營學員
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='category' value=企業營學員親友 onClick='contact_field(1);'> 企業營學員親友
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='category' value=義工 onClick='contact_field(0);'> 義工
                <div class="invalid-feedback">
                    請選擇身份別
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='category' value=義工親友 onClick='contact_field(1);'> 義工親友
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 

            <label class=radio-inline>
                <input type=radio required name='category' value=其它 onClick='contact_field(0);'> 其它
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <!-- 依「身份別」選項而顯示的部分 -->
    @if(isset($applicant_data) && \Str::contains($applicant_raw_data->category, "親友"))
    <div id=contact>
        <div class="row form-group required">
            <label for='inputEmergencyName' class="col-md-2 control-label text-md-right">聯絡人姓名</label>
            <div class="col-md-10">
                <input type="text" required class="form-control" name="emergency_name" value="" >
                <div class="invalid-feedback">請填寫本欄位</div>
            </div>
        </div>
        <div class="row form-group required">
            <label for='inputEmergencyMobile' class="col-md-2 control-label text-md-right">聯絡人電話</label>
            <div class="col-md-10">
                <input type="tel" required class="form-control" name="emergency_mobile" value="" placeholder='格式：0912345678'>
                <div class="invalid-feedback">請填寫本欄位</div>
            </div>
        </div>        
        <div class="row form-group required">
            <label for='inputEmergencyRelationship' class="col-md-2 control-label text-md-right">關係</label>
            <div class="col-md-10">
                <div class="text-info">報名者是聯絡人的</div>
                <select required name="emergency_relationship" class="form-control">
                    <option value="">- 請選擇 -</option>
                    <option value="配偶">配偶</option>
                    <option value="父親">父親</option>
                    <option value="母親">母親</option>
                    <option value="兄弟">兄弟</option>
                    <option value="姊妹">姊妹</option>
                    <option value="朋友">朋友</option>
                    <option value="同事">同事</option>
                    <option value="子女">子女</option>
                    <option value="其他">其他</option>
                </select>
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputEmail' class='col-md-2 control-label text-md-right'>電子郵件</label>
            <div class='col-md-10'>
                <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請提供報名者或聯絡人電子郵件，以利寄送報名確認及活動通知。'>
                <div class="invalid-feedback">
                    郵件不正確
                </div>
            </div>
        </div>
    </div>
    @else
    <div id=contact>
        <div class='row form-group required'>
            <label for='inputMobile' class='col-md-2 control-label text-md-right'>行動電話</label>
            <div class='col-md-10'>
                <input type=tel required name='mobile' value='' class='form-control' id='inputMobile' placeholder='格式：0912345678'>
                <div class="invalid-feedback">
                    請填寫行動電話
                </div>
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputEmail' class='col-md-2 control-label text-md-right'>電子郵件</label>
            <div class='col-md-10'>
                <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請務必填寫正確，以利寄送報名確認及活動通知'>
                <div class="invalid-feedback">
                    郵件不正確
                </div>
            </div>
        </div>
    </div>
    @endif

    <script language='javascript'>
    function contact_field(show) {

        var show_contact = `
        <div class="row form-group required">
            <label for='inputEmergencyName' class="col-md-2 control-label text-md-right">聯絡人姓名</label>
            <div class="col-md-10">
                <input type="text" required class="form-control" name="emergency_name" value="" >
                <div class="invalid-feedback">請填寫本欄位</div>
            </div>
        </div>
        <div class="row form-group required">
            <label for='inputEmergencyMobile' class="col-md-2 control-label text-md-right">聯絡人電話</label>
            <div class="col-md-10">
                <input type="tel" required class="form-control" name="emergency_mobile" value="" placeholder='格式：0912345678'>
                <div class="invalid-feedback">請填寫本欄位</div>
            </div>
        </div>        
        <div class="row form-group required">
            <label for='inputEmergencyRelationship' class="col-md-2 control-label text-md-right">關係</label>
            <div class="col-md-10">
                <div class="text-info">報名者是聯絡人的</div>
                <select required name="emergency_relationship" class="form-control">
                    <option value="">- 請選擇 -</option>
                    <option value="配偶">配偶</option>
                    <option value="父親">父親</option>
                    <option value="母親">母親</option>
                    <option value="兄弟">兄弟</option>
                    <option value="姊妹">姊妹</option>
                    <option value="朋友">朋友</option>
                    <option value="同事">同事</option>
                    <option value="子女">子女</option>
                    <option value="其他">其他</option>
                </select>
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputEmail' class='col-md-2 control-label text-md-right'>電子郵件</label>
            <div class='col-md-10'>
                <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請提供報名者或聯絡人電子郵件，以利寄送報名確認及活動通知。'>
                <div class="invalid-feedback">
                    郵件不正確
                </div>
            </div>
        </div>`;

        var show_self = `
        <div class='row form-group required'>
            <label for='inputMobile' class='col-md-2 control-label text-md-right'>行動電話</label>
            <div class='col-md-10'>
                <input type=tel required name='mobile' value='' class='form-control' id='inputMobile' placeholder='格式：0912345678'>
                <div class="invalid-feedback">
                    請填寫行動電話
                </div>
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputEmail' class='col-md-2 control-label text-md-right'>電子郵件</label>
            <div class='col-md-10'>
                <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請務必填寫正確，以利寄送報名確認及活動通知'>
                <div class="invalid-feedback">
                    郵件不正確
                </div>
            </div>
        </div>`;

        if (show == 0) { 
        document.getElementById('contact').innerHTML = show_self ; 
        } else { 
        document.getElementById('contact').innerHTML = show_contact; 
        }
    }
    </script>

{{--
    <script language='javascript'>
        $('#inputEmail').bind("cut copy paste",function(e) {
        e.preventDefault();
        });
    </script>
    
    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>確認電子郵件</label>
        <div class='col-md-10'>
            <input type='email' required name='emailConfirm' value='' class='form-control' id='inputEmailConfirm'>
--}}
            {{-- data-match='#inputEmail' data-match-error='郵件不符合' placeholder='請再次填寫確認郵件填寫正確' --}}
{{--
        </div>
    </div>
--}}
    <div class='row form-group required'>
        <label for='inputTransportation' class='col-md-2 control-label text-md-right'>交通方式</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='transportation' value=自往 > 自往
                <div class="invalid-feedback">
                    請選擇交通方式
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='transportation' value=搭遊覽車 > 搭遊覽車
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            {{--
            <label class=radio-inline>
                <input type=radio required name='transportation' value=其它 > 其它
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            --}}
        </div>
    </div>
    <div class='row form-group required'>
        <label for='inputTransportation' class='col-md-2 control-label text-md-right'>活動費用</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='fee' value=600 > 大人600
                <div class="invalid-feedback">
                    請選擇交通方式
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='fee' value=300 > 國小300
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='fee' value=0 > 幼稚園以下免費
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>
{{--
    <hr>
    <h5 class='form-control-static'>保險內容及投保資料</h5>
    <h6>保險內容：
        保額：意外險：XXX萬；意外醫療：XXX萬
        受益人：法定繼承人
    </h6>
    <h6>投保資料（報名者）：</h6>
    <br>
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
        <label for='inputID' class='col-md-2 control-label text-md-right'>身份證字號</label>
        <div class='col-md-10'>
            <input type='text' name='idno' value='' class='form-control' id='inputID' placeholder='僅作為申請保險用' @if(isset($isModify) && $isModify) disabled @endif>
            <div class="invalid-feedback">
                未填寫身份證字號（申請保險用）
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputSubstituteName' class='col-md-2 control-label text-md-right'>法定代理人<br>(若無免填)</label>
        <div class='col-md-10'>
            <input type='text' name='substitute_name' value='' class='form-control' id='inputName' placeholder='請填寫全名'>
        </div>
        <div class="invalid-feedback">
            請填寫姓名
        </div>
    </div>
    <hr>
--}}
    
    <!--- 同意書 -->
    <div class='row form-group required'>
        <label for='inputTerm' class='col-md-2 control-label text-md-right'>肖像權</label>
        <div class='col-md-10 form-check'>
            <p class='form-control-static text-danger'>
            主辦單位在活動期間拍照、錄影之活動記錄，可使用於活動及主辦單位的非營利教育推廣使用，並以網路方式推播。
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
            主辦單位於本次活動取得我的個人資料，於活動期間及後續主辦單位舉辦之活動，作為訊息通知、行政處理等非營利目的之使用，不會提供給無關之其他私人單位使用。
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
    <!--- 預設參加 -->
    @if(!isset($isModify))
    <input type='hidden' name="is_attend" value='1'>
    @else
    <div class='row form-group required'>
        <label for='inputIsAttend' class='col-md-2 control-label text-md-right'>取消或恢復參加</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='is_attend' value=1 > 參加/恢復參加
                <div class="invalid-feedback">
                    請選擇參加與否
                </div>
            </label> 
            <label class=radio-inline>
                <input type=radio required name='is_attend' value=0 > 取消參加
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>
    @endif

    <input type='hidden' name="gender" value='N'>

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
