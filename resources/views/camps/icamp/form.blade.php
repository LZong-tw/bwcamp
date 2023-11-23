{{--
    參考頁面：http://youngone.bwyouth.org/2020/form/index.php
--}}
<?
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('camps.icamp.layout')
@section('content')
    @include('partials.counties_areas_script')
    <div class='alert alert-info' role='alert'>
        您在本網站所填寫的個人資料，僅用於此次{{ $camp_data->abbreviation }}及後續主辦單位舉辦之活動聯絡之用。
        @if(now()->gt(\Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $batch->camp->registration_end . "23:59:59")) && (!isset($isModify) || !$isModify))
            <br><span class="text-danger">報名時間({{ $batch->camp->registration_end }})已經截止，您的報名將列為備取名單（若錄取將另外通知）。</span>
        @endif
    </div>

    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}線上報名表</h4>
    </div>
{{-- !isset($isModify): 沒有 $isModify 變數，即為報名狀態、 $isModify: 修改資料狀態 --}}
@if(!isset($isModify) || $isModify)
    <form method='post' action='{{ route('formSubmit', [$batch_id]) }}' id='Camp' name='Camp' class='form-horizontal needs-validation' role='form'>
{{-- 以上皆非: 檢視資料狀態 --}}
@else
    <form action="{{ route("queryupdate", $applicant_batch_id) }}" method="post" class="d-inline" name='Camp' >
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
        <label for='inputNationality' class='col-md-2 control-label text-md-right'>區域</label>
        <div class='col-md-2'>
        <select class='form-control' name='nationality' id='inputNationality'>
            <option value=''>- 請選擇 -</option>
            <option value='新加坡' >新加坡</option>
            <option value='馬來西亞' >馬來西亞</option>
            <option value='香港' >香港</option>
            <option value='南加' >南加</option>
            <option value='北加' >北加</option>
            <option value='紐約' >紐約</option>
            <option value='溫哥華' >溫哥華</option>
            <option value='多倫多' >多倫多</option>
            <option value='PEI' >PEI</option>
            <option value='紐西蘭' >紐西蘭</option>
            <option value='日本' >日本</option>
            <option value='韓國' >韓國</option>
            <option value='澳洲' >澳洲</option>
            <option value='歐洲' >歐洲</option>
            <option value='汶萊' >汶萊</option>
            <option value='南非' >南非</option>
            <option value='深圳' >深圳</option>
            <option value='廈門' >廈門</option>
            <option value='東南亞' >東南亞</option>
            <option value='其它' >其它</option>
        </select>
        </div>
    </div>

    <div class='row form-group required'> 
    <label for='inputLRClass' class='col-md-2 control-label text-md-right'>廣論研討班別</label>
        <div class='col-md-10'>
            <input type='text' required name='lrclass' value='' class='form-control' id='inputLRClass'>
            <div class="invalid-feedback">
                請填寫廣論研討班別
            </div>
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

    <div class='row form-group'>
        <label for='inputPhoneHome' class='col-md-2 control-label text-md-right'>在台電話</label>
        <div class='col-md-10'>
            <input type=tel name='phone_home' value='' class='form-control' id='inputCell' placeholder='格式：0912345678 或 0212345678'>
            <div class="invalid-feedback">
                請填寫在台電話
            </div>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputBirth' class='col-md-2 control-label text-md-right'>出生年月日（保險用）</label>
        <div class='date col-md-10' id='inputBirth'>
            <div class='row form-group required'>
                <div class="col-md-1">
                    西元
                </div>
                <div class="col-md-3">
                    <input type='number' required class='form-control' name='birthyear' min=1923 max='{{ \Carbon\Carbon::now()->subYears(16)->year }}' value='' placeholder=''>
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

    <div class='row form-group required' style="display: none;">
        <label for='inputID' class='col-md-2 control-label text-md-right'>護照號碼(台商請填身分證字號)</label>
        <div class='col-md-10'>
            <input type='text' name='idno' value='' class='form-control' id='inputID' placeholder='' @if(isset($isModify) && $isModify) disabled @endif>
            <div class="invalid-feedback">
                未填寫護照號碼/身份證字號
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
        <label for='inputEmailConfirm' class='col-md-2 control-label text-md-right'>確認電子郵件</label>
        <div class='col-md-10'>
            <input type='email' required name='emailConfirm' value='' class='form-control' id='inputEmailConfirm' placeholder='請再次填寫確認'>
            {{-- data-match='#inputEmail' data-match-error='郵件不符合' placeholder='請再次填寫確認郵件填寫正確' --}}
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputPaticipationMode' class='col-md-2 control-label text-md-right'>正行報名</label>
        <div class='col-md-10'>
            <p class='form-control-static text-info'>
            【全程】2/19報到，2/20~2/25，其中2/21唐卡展、大自然莊園，2/23參訪根本道場。團費 6000元<br>
            【後半程】2/22報到，2/23參訪根本道場，2/24祈願法會＋上師薈供，2/25迎慈尊、下午唐卡展。團費3500元<br>
            </p>
            <label class=radio-inline>
                <input type=radio required name='participation_mode' value='全程' > 全程
                <div class="invalid-feedback">
                    請選擇報名全程或後半程
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='participation_mode' value='後半程' > 後半程
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputTransportationDepart' class='col-md-2 control-label text-md-right'>去程交通調查</label>
        <div class='col-md-10'>
            <p class='form-control-static text-info'>
            【團進團出】跟著新、馬、港 大團團進團出<br>
            【自往】報名全程：2/19傍晚17:00前在園區報到<br>
            　　　　報名後半程：2/22傍晚17:00前在園區報到<br>
            </p>
            <label class=radio-inline>
                <input type=radio required name='transportation_depart' value='團進團出' > 團進團出
                <div class="invalid-feedback">
                    請選擇去程交通
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='transportation_depart' value='自往' > 自往
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputTransportationBack' class='col-md-2 control-label text-md-right'>回程交通調查</label>
        <div class='col-md-10'>
            <p class='form-control-static text-info'>
            【團進團出】跟著新、馬、港 大團團進團出<br>
            【希求大會安排搭車】<br>
            　　下車點選項：園區、台北學苑、高雄左營<br>
            　　車資自付，依終點及搭車人數不同另行收費，人數未達最低搭車人數，則不另行安排，將改為自行離開<br>
            </p>
            <label class=radio-inline>
                <input type=radio required name='transportation_back' value='團進團出' > 團進團出
                <div class="invalid-feedback">
                    請選擇回程交通
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='transportation_back' value='自回' > 自回
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='transportation_back' value='回園區' > 回園區
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='transportation_back' value='回台北學苑' > 回台北學苑
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
            <label class=radio-inline>
                <input type=radio required name='transportation_back' value='回高雄左營' > 回高雄左營
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputSpecialNeeds' class='col-md-2 control-label text-md-right'>備註</label>
        <div class='col-md-10'>
            <textarea class=form-control rows=2 name='special_needs' id=inputGoal placeholder='如有特殊需求ex:下床鋪需求、行動不便請備註原因，特殊飲食需求等'></textarea>
            <div class="invalid-feedback">
                請填寫本欄位
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputQuestions' class='col-md-2 control-label text-md-right'>其它</label>
        <div class='col-md-10'>
            <textarea class=form-control rows=2 name='questions' id=inputGoal placeholder='若有問題，請在此提出'></textarea>
            <div class="invalid-feedback">
                請填寫本欄位
            </div>
        </div>
    </div>

    <input type='hidden' name="profile_agree" value='0'>
    <input type='hidden' name="portrait_agree" value='0'>

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
        function sleep (time) {
            return new Promise((resolve) => setTimeout(resolve, time));
        }
        $('[data-toggle="confirmation"]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            title: "敬請再次確認資料填寫無誤。",
            btnOkLabel: "正確無誤，送出",
            btnCancelLabel: "再檢查一下",
            popout: true,
            onConfirm: function() {
                /*console.log($('.motivation').filter(':checked').length);
                console.log($('.blisswisdom_type').filter(':checked').length);
                if($('.motivation').filter(':checked').length < 1) {
                    document.Camp.checkValidity();
                    event.preventDefault();
                    event.stopPropagation();
                    $(".tips").removeClass('d-none');
                    $('#motivation-invalid').show();
                }
                else{
                    document.Camp.checkValidity();
                    event.preventDefault();
                    event.stopPropagation();
                    $(".tips").removeClass('d-none');
                    $('#motivation-invalid').hide();
                }
                if($('.blisswisdom_type').filter(':checked').length < 1) {
                    document.Camp.checkValidity();
                    event.preventDefault();
                    event.stopPropagation();
                    $(".tips").removeClass('d-none');
                    $('#blisswisdom_type-invalid').show();
                }
                else{
                    document.Camp.checkValidity();
                    event.preventDefault();
                    event.stopPropagation();
                    $(".tips").removeClass('d-none');
                    $('#blisswisdom_type-invalid').hide();
                }*/
                if (document.Camp.checkValidity() === false
                    //|| ($('.motivation').filter(':checked').length < 1)
                    //|| ($('.blisswisdom_type').filter(':checked').length < 1 )
                    ) {
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
{{--
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
--}}
        function setMotivationOther(checkbox_ele) {
            // 檢查 checkbox_ele 是否被勾選
            //console.log(checkbox_ele.checked);
            if(checkbox_ele.checked) {
            // 被勾選: 把 language_other_text required = true
                document.getElementById("motivation_other_text").required = true;
            }
            else {
            // 否則:把 language_other_text required = false
                document.getElementById("motivation_other_text").required = false;
            }
        }

        function setBlisswisdomTypeOther(checkbox_ele) {
            // 檢查 checkbox_ele 是否被勾選
            //console.log(checkbox_ele.checked);
            if(checkbox_ele.checked) {
            // 被勾選: 把 language_other_text required = true
                document.getElementById("blisswisdom_type_other_text").required = true;
            }
            else {
            // 否則:把 language_other_text required = false
                document.getElementById("blisswisdom_type_other_text").required = false;
            }
        }

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
            sleep(10).then(() => {
                (function() {
                    let applicant_data = JSON.parse('{!! $applicant_data !!}');
                    let inputs = document.getElementsByTagName('input');
                    let selects = document.getElementsByTagName('select');
                    let textareas = document.getElementsByTagName('textarea');
                    let complementPivot = 0;
                    let complementData = applicant_data["blisswisdom_type_complement"] ? applicant_data["blisswisdom_type_complement"].split("||/") : null;
                    // console.log(inputs);
                    for (var i = 0; i < selects.length; i++){
                        if(typeof applicant_data[selects[i].name] !== "undefined"){
                            if (selects[i].name == 'unit_subarea'){
                                continue;
                            }
                            selects[i].value = applicant_data[selects[i].name];
                            if (selects[i].name == 'unit_county'){
                                Address(applicant_data[selects[i].name], 'unit');
                                if (applicant_data[selects[i].name].includes('海外')) {
                                    document.getElementsByName('unit_address')[0].value = applicant_data["unit_county"] + applicant_data["unit_subarea"];
                                    continue;
                                }
                                var selectElement = document.getElementById("unit_subarea");

                                // Get the options of the select element
                                var options = selectElement.options;

                                // Iterate through the options
                                for (var j = 0; j < options.length; j++) {

                                    // Get the text of the option
                                    var optionText = options[j].text;

                                    // Check if the text equals to the specific text
                                    if (optionText == applicant_data['unit_subarea']) {
                                        options[j].selected = true;
                                    }
                                }
                            }
                            if (selects[i].name == 'transportation') {
                                var selectElement = document.getElementsByName("transportation")[0];
                                // Get the options of the select element
                                var options = selectElement.options;
                                // Iterate through the options
                                for (var j = 0; j < options.length; j++) {
                                    // Check if the text equals to the specific text
                                    if (options[j].text == applicant_data['transportation']) {
                                        options[j].selected = true;
                                    }
                                }
                            }
                        }
                        if (selects[i].name == 'county'){
                            // Split the string into an array of characters.
                            if(!applicant_data["address"]){
                                for (var j = 0; j < document.getElementsByName('county')[0].options.length; j++){
                                    if (document.getElementsByName('county')[0].options[j].value == '其他'){
                                        document.getElementsByName('county')[0].options[j].selected = true;
                                        Address('其他');
                                        handleHiddenCustomField('show');
                                    }
                                }
                                continue;
                            }

                            if(applicant_data["address"].includes("海外")){
                                for (var j = 0; j < document.getElementsByName('county')[0].options.length; j++){
                                    if (document.getElementsByName('county')[0].options[j].value == '海外'){
                                        document.getElementsByName('county')[0].options[j].selected = true;
                                        Address('海外');
                                        handleOverseas('set');
                                    }
                                }
                                continue;
                            }

                            if(applicant_data["address"].includes("新竹市")){
                                for (var j = 0; j < document.getElementsByName('county')[0].options.length; j++){
                                    if (document.getElementsByName('county')[0].options[j].value == '新竹市'){
                                        document.getElementsByName('county')[0].options[j].selected = true;
                                        Address('新竹市');
                                    }
                                }
                                for (var j = 0; j < document.getElementsByName('subarea')[0].options.length; j++){
                                    if (document.getElementsByName('subarea')[0].options[j].text == '新竹市'){
                                        document.getElementsByName('subarea')[0].options[j].selected = true;
                                    }
                                }
                                continue;
                            }

                            var characters = applicant_data["address"].split('');

                            // Create an empty array to store the two elements.
                            var elements = [];

                            // Add the first three characters to the first element.
                            elements.push(characters.slice(0, 3).join(''));

                            // Add the last three characters to the second element.
                            elements.push(characters.slice(3).join(''));

                            selects[i].value = elements[0];
                            Address(elements[0]);
                            var selectElement = document.getElementById("subarea");
                            // Get the options of the select element
                            var options = selectElement.options;

                            // Iterate through the options
                            let set = false;
                            for (var j = 0; j < options.length; j++) {

                                // Get the text of the option
                                var optionText = options[j].text;

                                // Check if the text equals to the specific text
                                if (optionText == elements[1]) {
                                    options[j].selected = true;
                                    set = true;
                                }
                            }
                            // if(!set) {
                            //     var option = document.getElementsByName(");
                            //     option.text = elements[1];
                            //     option.value = elements[1];
                            //     selectElement.add(option);
                            //     option.selected = true;
                            // }
                        }
                    }
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
                        else if(inputs[i].type == "hidden" && inputs[i].name == 'address'){
                            if (applicant_data["address"].includes("新竹市")) {
                                Address("新竹市");
                                document.getElementById('subarea').options[1].selected = true;
                            }
                            inputs[i].value = applicant_data[inputs[i].name] + applicant_data["subarea"];
                        }
                        else if(inputs[i].type == "hidden" && (inputs[i].name == 'unit_address' && !applicant_data["unit_county"].includes("海外"))){
                            inputs[i].value = applicant_data["unit_county"] + applicant_data["unit_subarea"];
                        }
                        if(inputs[i].name == 'emailConfirm'){
                            inputs[i].value = applicant_data['email'];
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
            });

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
