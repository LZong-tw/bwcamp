<?php
header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
?>
@extends('camps.utcamp.layout')
@section('content')
    @include('partials.schools_script')
    @include('partials.counties_areas_script')
    <div class='alert alert-info' role='alert'>
        您在本網站所填寫的個人資料，僅用於此次教師營的報名及活動聯絡之用。
    </div>

    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }} {{ $batch->name }} 線上報名表</h4>
    </div>
<span id="utcamp-layout">
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
{{--
    <div class='row form-group required'>
        <label for='inputBirth' class='col-md-2 control-label text-md-right'>研習時數申請</label>
        <div class='col-md-10'>
            <select required class='form-control' name='workshop_credit_type' placeholder='' onchange="id_setRequired(this)">
                <option value="">- 請選擇 -</option>
                <option value="不申請">不申請</option>
                <!--<option value="一般教師研習時數">一般教師研習時數</option>-->
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
--}}
    <div class='row form-group'>
        <label class = 'col-md-2'></label>
        <label class = 'col-md-10 text-primary'>若您的服務學校/單位不在下面選單中，請在「行政區」選「其他」，即可自填服務學校/單位。</label>
    </div>

    {{-- 大專教師營 --}}
        <div id="utcamp-unit-and-batch-section" class="m-0 p-0">
            <utcamp-unit-and-batch-section></utcamp-unit-and-batch-section>
        </div>

        <div class="row form-group required">
            <label for="inputSchoolOrCourse" class="col-md-2 control-label text-md-right">
                服務系所/部門
            </label>
            <div class="col-md-10">
                <input type="text" required name="department" class='form-control' value="">
                <div class="invalid-feedback crumb">
                    請輸入服務系所/部門
                </div>
            </div>
        </div>
        <span id="utcamp-title" class="m-0 p-0">
            <utcamp-title></utcamp-title>
        </span>
    {{-- 大專教師營 --}}

    <h5 class='form-control-static text-primary'>聯絡方式</h5>
    <!--<p class="form-control-static text-danger">＊因需寄發教材資料及通知，請務必填寫正確</p>-->
    <div class='row form-group required'>
        <label for='inputCell' class='col-md-2 control-label text-md-right'>聯絡電話(手機)</label>
        <div class='col-md-10'>
            <input type=tel required name='mobile' value='' class='form-control' id='inputCell' pattern='09\d{8}' placeholder='格式：0912345678'>
            <div class="invalid-feedback">
                未填寫行動電話，或格式不正確
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputTelWork' class='col-md-2 control-label text-md-right'>聯絡電話(O)</label>
        <div class='col-md-10'>
            <input type=tel  name='phone_work' value='' class='form-control' id='inputTelWork' placeholder='格式：0225452546'>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputTelHome' class='col-md-2 control-label text-md-right'>聯絡電話(H)</label>
        <div class='col-md-10'>
            <input type=tel  name='phone_home' value='' class='form-control' id='inputTelHome' placeholder='格式：0225452546'>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>電子信箱</label>
        <div class='col-md-10'>
            <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請務必填寫正確，以利營隊相關訊息通知'>
            <div class="invalid-feedback">
                未填寫電子郵件，或格式不正確
            </div>
        </div>
    </div>

    <script type="module">
        window.addEventListener("load", function() {
            $('#inputEmail').bind("cut copy paste",function(e) {
                e.preventDefault();
            });
        });
    </script>

    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>確認電子信箱</label>
        <div class='col-md-10'>
            <input type='email' required name='emailConfirm' value='' class='form-control' id='inputEmailConfirm' placeholder='請再次填寫，確認郵件正確'>
            {{-- data-match='#inputEmail' data-match-error='郵件不符合' placeholder='請再次填寫確認郵件填寫正確' --}}
            <div class="invalid-feedback">
                未確認電子郵件
            </div>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputLineID' class='col-md-2 control-label text-md-right'>LINE ID</label>
        <div class='col-md-10'>
            <input type=text name='line' value='' class='form-control' id='inputLineID'>
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
        <label for='inputFuzhi' class='col-md-2 control-label text-md-right'>如何得知報名訊息(可複選)</label>
        <div class='col-md-10'>
            <label><input type="checkbox" name=info_source[] value='親友/同事推薦' > 親友/同事推薦</label> <br/>
            <label><input type="checkbox" name=info_source[] value='學校公文' > 學校公文</label> <br/>
            <label><input type="checkbox" name=info_source[] value='宣傳海報/小卡' > 宣傳海報/小卡</label> <br/>
            <label><input type="checkbox" name=info_source[] value='福智文教基金會官網' > 福智文教基金會官網</label> <br/>
            <label><input type="checkbox" name=info_source[] value='自行搜尋' > 自行搜尋</label> <br/>
            <label><input type="checkbox" name=info_source[] value='其他' onchange="toggleISOrequired()"> 其它</label> <br>
                <input type=text class='form-control' name="info_source_other" value='' id="info_source_other">
                <div class="invalid-feedback">
                    請填寫其它管道
                </div>
        </div>
    </div>

    <div class='row form-group'>
        <label class='col-md-2 control-label text-md-right'>介紹人</label>
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
                        <option value='朋友'>朋友</option>
                        <option value='同事'>同事</option>
                        <option value='子女'>子女</option>
                        <option value='其他'>其他</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-----是否參加過福智舉辦活動-----
    <span id="utcamp-is-blisswisdom">
            <utcamp-is-blisswisdom></utcamp-is-blisswisdom>
    </span>
    -----是否參加過福智舉辦活動----->

    <div class='row form-group'>
        <label for='inputExpect' class='col-md-2 control-label text-md-right'>您對營隊的期許</label>
        <div class='col-md-10'>
            <textarea class='form-control' rows=2 name='expectation' id=inputExpect></textarea>
            {{-- <div class="invalid-feedback">
                請填寫本欄位
            </div> --}}
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
                    手機<label class='text-danger'>＊</label>
                </div>
                <div class='col-md-10'>
                    <input type='tel' class='form-control' name="emergency_mobile" value='' required>
                    <div class="invalid-feedback">
                    未填寫緊急聯絡人手機
                    </div>
                </div>
            </div>
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

    <script type="module">
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
            });
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
        //toggle info_source_other required or not
        function toggleISOrequired() {
            document.getElementById('info_source_other').required = !document.getElementById('info_source_other').required ? true : false;
        }
        //toggle blisswisdom_type_complement required or not
        function toggleBTCrequired() {
            document.getElementById('blisswisdom_type_complement').required = !document.getElementById('blisswisdom_type_complement').required ? true : false;
        }
        //??
        function toggleComplement(val) {
            let blisswisdom_type_complements =
                    $('input').filter(function() {
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
