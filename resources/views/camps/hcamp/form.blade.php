{{-- 
    參考頁面：https://youth.blisswisdom.org/camp/winter/form/index_addto.php
    --}}
<?
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('camps.hcamp.layout')
@section('content')
    @include('partials.schools_script')
    @include('partials.counties_areas_script')
    <div class='alert alert-info' role='alert'>
        您在本網站所填寫的個人資料，僅用於此次快樂營的報名及活動聯絡之用。
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
        <label for='inputBatch' class='col-md-2 control-label text-md-right'>營隊時間</label>
        <div class='col-md-10'>
            <h3>{{ $batch->batch_start }} ~ {{ $batch->batch_end }} (需全程住宿)</h3>
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
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>電子郵件</label>
        <div class='col-md-10'>
            <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請務必正確填寫，以便寄送錄取通知及繳費資訊。'>
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
    <!--- 同意書 -->
    <div class='row form-group required'>
        <label for='inputTerm' class='col-md-2 control-label text-md-right'>肖像權</label>
        <div class='col-md-10 form-check'>
            <label>
                <p class='form-control-static text-danger'>
                <input type='radio' required name="portrait_agree" value='1'> 我同意本次營隊期間，主辦單位將剪輯營隊中學員影像製做影片，用於營隊、主辦單位等教育推廣使用，並以網路方式播出，報名本次營隊之學員，即同意將營隊中之影像及照片用於主辦單位使用。</p>
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
                <input type='radio' required name='profile_agree' value='1'> 我同意本次營隊取得我的聯繫通訊及個人資料，目的在於營隊期間及後續主辦單位舉辦之活動，依所蒐集之資料做為訊息通知、行政處理等使用，不會提供給無關之其他私人單位使用。</p>
                <div class="invalid-feedback">
                    請圈選本欄位
                </div>
            </label> 
            <input type='radio' class='d-none' name='profile_agree' value='0' >
            <br/>
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

    <div class='row form-group required'>
        <label for='inputBirth' class='col-md-2 control-label text-md-right'>生日</label>
        <div class='date col-md-10' id='inputBirth'>
            <div class='row form-group required'>
                <div class="col-md-1">
                    西元
                </div>
                <div class="col-md-3">
                    <input type='number' required class='form-control' name='birthyear' min=1900 max='{{ \Carbon\Carbon::now()->subYears(4)->year }}' value='' placeholder='辦理保險用，'>
                    <div class="invalid-feedback">
                        未填寫或日期不正確
                    </div>
                </div>
                <div class="col-md-1">
                    年
                </div>
                <div class="col-md-2">
                    <input type='number' required class='form-control' name='birthmonth' min=1 max=12 value='' placeholder='請務必'>
                    <div class="invalid-feedback">
                        未填寫或日期不正確
                    </div>
                </div>
                <div class="col-md-1">
                    月
                </div>
                <div class="col-md-3">
                    <input type='number' required class='form-control' name='birthday' min=1 max=31 value='' placeholder='填寫正確'>
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
            <input type='text' name='idno' value='' class='form-control' id='inputID' placeholder='辦理保險用，請務必填寫正確' required @if(isset($isModify) && $isModify) disabled @endif>
        </div>
        <div class="invalid-feedback">
            請填寫身份證字號；
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputCell' class='col-md-2 control-label text-md-right'>行動電話</label>
        <div class='col-md-10'>
            <input type=tel required name='mobile' value='' class='form-control' id='inputCell' placeholder='若無則請填寫家中電話'>
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

    <div class='row form-group required'>
        <label for='inputEducation' class='col-md-2 control-label text-md-right'>報名者學程</label>
        <div class='col-md-10'>
            現為國小五年級 ~ 高中三年級(即 110 年 6 月前之年段)
            <select name="education" class="form-control" required> 
                <option value=''>- 請選擇 -</option>
                <option value='國小五年級'>國小五年級</option>
                <option value='國小六年級'>國小六年級</option>
                <option value='國中一年級'>國中一年級</option>
                <option value='國中二年級'>國中二年級</option>
                <option value='國中三年級'>國中三年級</option>
                <option value='高中一年級'>高中一年級</option>
                <option value='高中二年級'>高中二年級</option>
                <option value='高中三年級'>高中三年級</option>
            </select>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputHasLicense' class='col-md-2 control-label text-md-right'>特殊狀況告知</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name='sc' value=無 id="no_special_condition"> 無
                <div class="invalid-feedback">
                    請勾選有無特殊狀況
                </div>
            </label> 
            <label class='radio-inline d-inline'>
                <input type=radio required name='sc' value=1 id="has_special_condition"> 有
                <input type="text" name="special_condition" class="form-control d-inline" placeholder="如：特殊疾病.飲食注意...等，以便帶隊老師留意及關照" id="special_condition">
                <div class="invalid-feedback">
                    &nbsp;
                </div>
            </label> 
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
        </div>
    </div>

    <div class='row form-group required'>
        <label for='traffic' class='col-md-2 control-label text-md-right'>交通調查</label>
        <div class='col-md-10'>
            本會代訂遊覽車，遊覽車車資請於車上繳交；遊覽車費用會於行前通知時告知
            <div class='row form-group required'>
                <label for='traffic' class='col-md-2 control-label text-md-left'>去程</label>
                <div class='col-md-10'>
                    <select name="traffic_depart" class="form-control" required> 
                        <option value=''>- 請選擇 -</option>
                        <option value='自往'>自往</option>
                        <option value='北苑站'>北苑站 (台北學苑對面彰化銀行)</option>
                        <option value='板橋站'>板橋站 (民生路2段221號)</option>
                        <option value='林口站'>林口站 (文化二路與復興一路交接口--中油對面彩虹魚門口)</option>
                        <option value='南崁站'>南崁站 (桃園市蘆竹區新南路一段368號)</option>
                        <option value='新屋站'>新屋站 (新屋交流道錡鎂修車廠：桃園市中壢區民族路三段22號)</option>
                        <option value='竹北站'>竹北站 (竹北稅捐處)</option>
                        <option value='頭份站'>頭份站 (頭份麥當勞)</option>
                        <option value='台中站'>台中站 (忠明國小側門)</option>
                        <option value='彰化站'>彰化站 (向陽停車場)</option>
                        <option value='麻豆站'>麻豆站 (麻豆交流道-)</option>
                        <option value='永康站'>永康站 (永康交流道-情定大飯店)</option>
                        <option value='仁德站'>仁德站 (仁德交流道-隆美窗簾)</option>
                        <option value='高雄岡山站'>高雄岡山站 (岡山阿囉哈)</option>
                        <option value='高雄楠梓站'>高雄楠梓站 (楠梓阿囉哈)</option>
                        <option value='高雄建工站'>高雄建工站 (大順建工路口-高雄銀行處)</option>
                    </select>
                </div>
            </div>
            <div class='row form-group required'>
                <label for='traffic' class='col-md-2 control-label text-md-left'>回程</label>
                <div class='col-md-10'>
                    <select name="traffic_return" class="form-control" required> 
                        <option value=''>- 請選擇 -</option>
                        <option value='自往'>自往</option>
                        <option value='北苑站'>北苑站 (台北學苑對面彰化銀行)</option>
                        <option value='板橋站'>板橋站 (民生路2段221號)</option>
                        <option value='林口站'>林口站 (文化二路與復興一路交接口--中油對面彩虹魚門口)</option>
                        <option value='南崁站'>南崁站 (桃園市蘆竹區新南路一段368號)</option>
                        <option value='新屋站'>新屋站 (新屋交流道錡鎂修車廠：桃園市中壢區民族路三段22號)</option>
                        <option value='竹北站'>竹北站 (竹北稅捐處)</option>
                        <option value='頭份站'>頭份站 (頭份麥當勞)</option>
                        <option value='台中站'>台中站 (忠明國小側門)</option>
                        <option value='彰化站'>彰化站 (向陽停車場)</option>
                        <option value='麻豆站'>麻豆站 (麻豆交流道-)</option>
                        <option value='永康站'>永康站 (永康交流道-情定大飯店)</option>
                        <option value='仁德站'>仁德站 (仁德交流道-隆美窗簾)</option>
                        <option value='高雄岡山站'>高雄岡山站 (岡山阿囉哈)</option>
                        <option value='高雄楠梓站'>高雄楠梓站 (楠梓阿囉哈)</option>
                        <option value='高雄建工站'>高雄建工站 (大順建工路口-高雄銀行處)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class='row form-group required'>
        <label class='col-md-2 control-label text-md-right'>福智相關資訊調查<br>（家長資訊）</label>
        <div class='col-md-10'>
            <div class="row form-group required">
                <div class='col-md-2 control-label text-md-right'>請問您有參加福智廣論研討班嗎？</div>
                <div class='col-md-10'>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="M">
                            <input class="form-check-input" type="radio" name="is_lamrim" value="1" required>
                            是
                            <div class="invalid-feedback">
                                請選擇是否為福智廣論研討班學員
                            </div>
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="F">
                            <input class="form-check-input" type="radio" name="is_lamrim" value="0" required>
                            否
                            <div class="invalid-feedback">
                                &nbsp;
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div class='row form-group required'>
                <div class='col-md-2  control-label'>
                    您的孩子曾在福智文教系統的哪個班次學習？
                </div>
                <div class='col-md-10'>
                    <input type="checkbox" name="is_child_blisswisdommed[]" id="" value="孩子沒有在福智系統學習過">
                    孩子沒有在福智系統學習過 <br>
                    <input type="checkbox" name="is_child_blisswisdommed[]" id="" value="福幼班">
                    福幼班 <br>
                    <input type="checkbox" name="is_child_blisswisdommed[]" id="" value="妙慧幼兒園系列">
                    妙慧幼兒園系列 <br>
                    <input type="checkbox" name="is_child_blisswisdommed[]" id="" value="福智學校（國小、國中、高中）">
                    福智學校（國小、國中、高中） <br>
                    <input type="checkbox" name="is_child_blisswisdommed[]" id="" value="福智青少年班">
                    福智青少年班 <br>
                    <input type="checkbox" name="is_child_blisswisdommed[]" id="" value="福智讀經班">
                    福智讀經班 <br>
                    <input type="checkbox" name="is_child_blisswisdommed[]" id="" value="福智大專班">
                    福智大專班 <br>
                    <input type="checkbox" name="is_child_blisswisdommed[]" id="" value="其他">
                    其他 <br>
                    <input type="text" class="form-control" name="is_child_blisswisdommed[]">
                    <div class="invalid-feedback">
                        請勾選項目
                    </div>
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
        let no_special_condition = null;
        let has_special_condition = null;
        let special_condition = null;
        let special_conditionHTML = null;

        /**
        * Ready functions.
        * Executes commands after the web page is loaded. 
        */
        document.onreadystatechange = () => {
            if (document.readyState === 'complete') {
                no_special_condition = document.getElementById("no_special_condition");
                has_special_condition = document.getElementById("has_special_condition");
                special_condition = document.getElementById("special_condition");
                special_conditionHTML = special_condition;
                document.getElementById("no_special_condition").addEventListener("click", function() { 
                    if (this.checked) {
                        special_condition.remove();
                    } 
                });
                document.getElementById("has_special_condition").addEventListener("click", function() { 
                    if (this.checked) {
                        let parentElement = has_special_condition.parentNode;
                        parentElement.insertBefore(special_conditionHTML, parentElement.children[1]);
                    } 
                });
                if(!document.getElementById("has_special_condition").checked){
                    special_condition.remove();
                }
            }
        };

        function showFields(){        
            rowParentLamrim.innerHTML = rowParentLamrimInnerHTML;
        }

        function hideFields(){        
            rowParentLamrim.innerHTML = '';
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

        @if(isset($applicant_data))
            {{-- 回填報名資料 --}}
            (function() {
                let applicant_data = JSON.parse('{!! $applicant_data !!}');
                let inputs = document.getElementsByTagName('input');
                let selects = document.getElementsByTagName('select');
                let textareas = document.getElementsByTagName('textarea');
                let complementPivot = 0;                
                let complementData = applicant_data["blisswisdom_type_complement"] ? applicant_data["blisswisdom_type_complement"].split("||/") : null; 
                if(applicant_data['special_condition']){
                    document.getElementById("has_special_condition").checked = true;
                    document.getElementById("has_special_condition").disabled = true;
                    document.getElementById("no_special_condition").disabled = true;
                    document.getElementById("special_condition").value = applicant_data['special_condition']; 
                }
                else{
                    document.getElementById("no_special_condition").checked = true;
                    document.getElementById("no_special_condition").disabled = true;
                    document.getElementById("has_special_condition").disabled = true;
                }
                for (var i = 0; i < inputs.length; i++){
                    if(inputs[i].name == "sc") {
                        //inputs[i].type == "checkbox" && inputs[i].id == 'blisswisdom_type_complement[]'){
                        console.log();
                    }
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
                    else if(inputs[i].type == "text" && inputs[i].name == 'is_child_blisswisdommed[]'){
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
