@php
    header('Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Expires: Fri, 01 Jan 1990 00:00:00 GMT');
    $regions = ['北區', '竹區', '中區', '高區'];
@endphp
<!DOCTYPE html>
<html data-bs-theme="light" lang="zh-Hant">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='description' content='邀請您推薦報名參加菁英營。' />
    <meta name='author' content='福智文教基金會'>
    <meta property='og:url' content='http://bwfoce.org/ceocamp' />
    <meta property='og:title' content='{{ $camp_data->abbreviation }}' />
    <meta property='og:description' content='邀請您推薦報名參加菁英營。' />
    <meta property='og:image'
        content='https://static.wixstatic.com/media/8822b2_42442909881444a99904caa63bb7e659~mv2.png/v1/fill/w_2274,h_640,al_c,usm_0.66_1.00_0.01,enc_auto/8822b2_42442909881444a99904caa63bb7e659~mv2.png' />
    {{-- <link rel='icon' href='/camp/favicon.ico'> --}}
    <title> {{ $camp_data->fullName }} </title>
    <link rel="stylesheet" href="{{ asset('mockup-assets/ceocamp/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Raleway:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abel&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Aboreto&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alegreya+Sans&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans+Chorasmian&amp;display=swap">    
    @include('partials.counties_areas_script')
</head>

<body style="color: #343458;background: #fcf2ff;">
    <nav class="navbar navbar-expand-md fixed-top navbar-shrink py-3 navbar-light" id="mainNav"
        style="background: linear-gradient(rgb(233,186,239), rgba(255,255,255,0.4) 52%, rgb(222,192,225)), rgba(255,255,255,0.6);border-radius: 0px;height: 60px;box-shadow: 0px 0px 14px;">
        <div class="container"><a class="navbar-brand d-flex align-items-center" href="/"><span
                    style="font-family: Abel, sans-serif;color: rgb(46,83,99);"><span
                        style="color: rgb(105, 24, 137);">2024 企業菁英營推薦</span><span
                        style="color: rgb(154, 0, 0);">（{{ $batch->name }}梯）</span></span></a><button data-bs-toggle="collapse"
                class="navbar-toggler" data-bs-target="#navcol-1"
                style="width: 43px;height: 40px;padding: 0px 0px;background: rgba(255,255,255,0.3);"><span
                    class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1" style="height: 50px;">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active"
                            href="{{ route('registration', $batch_id) }}">報名表單</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('query', $batch_id) }}">查詢／修改</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="pricing.html">推薦表Word檔下載</a></li>
                    <li class="nav-item"><a class="nav-link" href="contacts.html">PDF下載</a></li> --}}
                </ul>
            </div>
        </div>
    </nav>
    <header class="pt-5"></header>
    <section style="text-align: center;"><img
            src="{{ asset('mockup-assets/ceocamp/img/illustrations/菁英營banner2.jpg') }}"
            style="width: 100%;margin: initial;padding: initial;">
        <div class="container">
            <p style="text-align: left;margin: 0px;"><span
                    style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">若您在填寫表格時遇到困難，請洽詢：</span>北區—陳美蒨
                0958367318、陳尚耀 0966891868、吳宜芯 0910123257｜竹區—邱雍凌 0922437236、陳沛安 0921625305</p>
        </div>
        <div class="container py-4 py-xl-5">
            {{-- !isset($isModify): 沒有 $isModify 變數，即為報名狀態、 $isModify: 修改資料狀態 --}}
            @if(!isset($isModify) || $isModify)
                <form method='post' action='{{ route('formSubmit', [$batch_id]) }}' id='Camp' name='Camp' class='form-horizontal needs-validation' role='form'>
                {{-- 以上皆非: 檢視資料狀態 --}}
            @else
                <form action="{{ route("queryupdate", $applicant_batch_id) }}" method="post" class="d-inline">
            @endif
            @csrf
            <div class="row gy-4 row-cols-1 row-cols-md-2">
                <div class="col">
                    <div class="card border-light border-1 d-flex p-4"
                        style="background: rgba(197,154,204,0.56);border-radius: 20px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);height: 100%;text-align: left;">
                        <h1 style="font-size: x-large;color: rgb(67,36,18);border-color: rgb(255,94,0);"><span
                                style="color: rgb(96, 18, 72);">推薦人基本資料</span></h1>
                        <div class="table-responsive" style="width: 98%;">
                            <table class="table">
                                <thead>
                                    <tr></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td
                                            style="width: 40%;color: rgb(67,36,18);background: rgba(255,255,255,0);border-style: none;font-size: large;">
                                            推薦人姓名：&nbsp;<input type="text"
                                                style="background: var(--bs-table-bg);border-radius: 10px;width: 240px;border-style: none;font-size: large;"
                                                placeholder="請填寫推薦人姓名" name="introducer_name" required></td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;font-size: large;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;font-size: large;">
                                            <span style="color: rgb(67, 36, 18);">廣 論 班 別：&nbsp;&nbsp;</span><input
                                                type="text"
                                                style="background: var(--bs-table-bg);border-radius: 10px;width: 240px;border-style: none;font-size: large;"
                                                name="introducer_relationship" placeholder="請填寫廣論班別" required></td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;font-size: large;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;font-size: large;">
                                            <span style="color: rgb(67, 36, 18);">手 機 號 碼：&nbsp;&nbsp;</span><input
                                                type="tel"
                                                style="background: var(--bs-table-bg);border-radius: 10px;width: 240px;border-style: none;font-size: large;"
                                                name="introducer_phone" placeholder="格式：0912345678" required></td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;font-size: large;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;font-size: large;">
                                            <span style="color: rgb(67, 36, 18);">與被推薦人關係：&nbsp;&nbsp;</span><select
                                                style="padding: 3px;border-radius: 5px;border-style: none;width: 200px;font-size: large;"
                                                name="introducer_participated">
                                                <optgroup label="- 請選擇 -">
                                                    <option value='親戚'>親戚</option>
                                                    <option value='同學'>同學</option>
                                                    <option value='同事'>同事</option>
                                                    <option value='朋友'>朋友</option>
                                                    <option value='工作相關'>工作相關</option>
                                                    <option value='社團'>社團</option>
                                                    <option value='其他'>其他</option>
                                                </optgroup>
                                            </select></td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;font-size: large;">
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;font-size: large;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;font-size: large;">
                                            <span style="color: rgb(67, 36, 18);">推薦人電子郵件信箱：&nbsp;</span><input
                                                type="email"
                                                style="background: var(--bs-table-bg);border-radius: 10px;width: 100%;border-style: none;"
                                                name='introducer_email'></td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                    </tr>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-light border-1 d-flex p-4"
                        style="background: rgba(197,154,204,0.56);border-radius: 30px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);height: 100%;text-align: left;">
                        <h1 style="font-size: x-large;color: rgb(67,36,18);"><span
                                style="color: rgb(96, 18, 72);">推薦理由</span></h1>
                        <textarea name='reasons_recommend' style="width: 98%;height: 300px;border-style: none;border-radius: 10px;" required></textarea>
                    </div>
                </div>
            </div>
            <section>
                <div class="container py-4 py-xl-5" style="padding: 0px 0px;height: auto;">
                    <div class="row gy-4 gy-md-0">
                        <div class="col" style="text-align: left;">
                            <div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th
                                                    style="font-size: larger;background: rgb(201,81,143);border-radius: 30px;padding: 10px 24px;border-style: none;">
                                                    <span
                                                        style="font-weight: normal !important; color: rgb(238, 238, 238); background-color: rgba(220, 220, 220, 0);">若繼續填寫下方資料，表示</span><strong><span
                                                            style="color: rgb(255, 255, 255); background-color: rgba(220, 220, 220, 0);">&nbsp;您已確認：</span></strong>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td
                                                    style="font-size: larger;background: rgba(255,255,255,0);padding: 5px 40px;border-color: rgb(255,255,255);">
                                                    <strong><span
                                                            style="color: rgb(105, 57, 62); background-color: rgba(220, 220, 220, 0);">1.
                                                        </span><span
                                                            style="color: rgb(96, 40, 117); background-color: rgba(220, 220, 220, 0);">被推薦人同意參加</span><span
                                                            style="color: rgb(255, 107, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span></strong><span
                                                        style="color: rgb(35, 35, 35); background-color: rgba(220, 220, 220, 0);">本次營隊活動，並且</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="font-size: larger;background: rgba(255,255,255,0);padding: 5px 40px;border-style: none;border-color: rgb(255,255,255);">
                                                    <strong><span
                                                            style="color: rgb(105, 57, 62); background-color: rgba(220, 220, 220, 0);">2.
                                                        </span><span
                                                            style="color: rgb(96, 40, 117); background-color: rgba(220, 220, 220, 0);">被推薦人同意</span><span
                                                            style="color: rgb(170, 0, 18); background-color: rgba(220, 220, 220, 0);">&nbsp;</span></strong><span
                                                        style="color: rgb(35, 35, 35); background-color: rgba(220, 220, 220, 0);">將營隊推薦報名表内相關資料</span><strong><span
                                                            style="background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span
                                                            style="color: rgb(96, 40, 117); background-color: rgba(220, 220, 220, 0);">提供給主辦單位</span><span
                                                            style="color: rgb(105, 57, 62); background-color: rgba(220, 220, 220, 0);">。</span></strong>
                                                </td>
                                            </tr>

                                            @if (str_contains($batch->name, "開南"))
                                                <tr>
                                                    <td
                                                        style="font-size: larger;background: rgba(255,255,255,0);padding: 5px 40px;border-style: none;border-color: rgb(255,255,255);">
                                                        <strong><span
                                                                style="color: rgb(105, 57, 62); background-color: rgba(220, 220, 220, 0);">3.
                                                            </span><span
                                                                style="color: rgb(96, 40, 117); background-color: rgba(220, 220, 220, 0);">知悉並告知被推薦人：本營隊費用為NT$6,000元（包含食宿，安排鄰近開南大學之飯店雙人房，欲住宿單人房者加收NT$1,650元）。</span></strong>
                                                    </td>
                                                </tr>
                                            @endif


                                            <tr>
                                                <td style="font-size: initial;background: rgba(201,81,143,0.3);padding: 5px 40px;border-style: none;border-color: rgb(255,255,255);border-radius: 30px;">
                                                    @if (str_contains($batch->name, "開南"))
                                                        <span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">若有需要，可下載&nbsp;</span><a href="{{ url("downloads/ceocamp2024/2024菁英營學員推薦表_開南.docx") }}" target="_blank"><strong><span style="color: rgb(150, 59, 106); background-color: rgba(220, 220, 220, 0);">學員推薦表WORD檔</span></strong></a>&nbsp;<span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">或&nbsp;</span><a href="{{ url("downloads/ceocamp2024/2024菁英營學員推薦表_開南.pdf") }}" target="_blank"><strong><span style="color: rgb(150, 59, 106); background-color: rgba(220, 220, 220, 0);">學員推薦表PDF檔</span></strong></a><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">， 請被推薦人提供資料，做為填寫此表單的依據。</span>
                                                    @else
                                                        <span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">若有需要，可下載&nbsp;</span><a href="{{ url("downloads/ceocamp2024/2024菁英營學員推薦表_勤益.docx") }}"><strong><span style="color: rgb(150, 59, 106); background-color: rgba(220, 220, 220, 0);">學員推薦表WORD檔</span></strong></a>&nbsp;<span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">或&nbsp;</span><a href="{{ url("downloads/ceocamp2024/2024菁英營學員推薦表_勤益.pdf") }}"><strong><span style="color: rgb(150, 59, 106); background-color: rgba(220, 220, 220, 0);">學員推薦表PDF檔</span></strong></a><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">， 請被推薦人提供資料，做為填寫此表單的依據。</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="row gy-4 row-cols-1 row-cols-md-2">
                <div class="col">
                    <div class="card border-light border-1 d-flex p-4"
                        style="background: #ffffff;border-radius: 20px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);height: 100%;text-align: left;">
                        <h1 style="font-size: x-large;"><span style="color: rgb(96, 18, 72);">被推薦人</span><sup><span
                                    style="color: rgb(96, 18, 72);">(營隊學員)</span></sup><span
                                style="color: rgb(96, 18, 72);">基本資料</span></h1>
                        <div class="table-responsive" style="width: 98%;">
                            <table class="table">
                                <thead>
                                    <tr></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td
                                            style="width: 40%;color: var(--bs-body-color);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">中文姓名：&nbsp;</span><input type="text"
                                                style="border-style: none;border-radius: 10px;background: rgba(206,212,218,0.35);padding: 3px 10px;"
                                                name="name" placeholder='請填寫全名' required></td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">英文慣用名：&nbsp;</span><input
                                                type="text"
                                                style="background: rgba(206,212,218,0.35);border-style: none;border-radius: 10px;padding: 3px 10px; width: 100%;"
                                                name="english_name" placeholder='請填寫英文慣用名，如James、Michelle等，若無免填'
                                                required></td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">區域別：&nbsp;</span>
                                            <input type="radio" value="" name="region_id" required><span
                                                style="color: rgb(0, 0, 0);"> 北區　</span>
                                            <input type="radio" value="" name="region_id" required><span
                                                style="color: rgb(0, 0, 0);"> 竹區</span><br>
                                            <sup><span style="color: rgb(96, 18, 72);">建議根據被推薦人的工作/生活地區選擇</span></sup>
                                        </td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">性別：&nbsp;</span><input type="radio"
                                                name="gender" value="M" required>&nbsp;<span
                                                style="color: rgb(0, 0, 0);">男　　</span><input
                                                type="radio">&nbsp;<span style="color: rgb(0, 0, 0);"
                                                name="gender" value="F" required>女</span></td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">生日： 西元 </span>
                                            <select
                                                style="width: 60px;background: rgba(206,212,218,0.35);border-style: none;border-radius: 3px;padding: 3px;"
                                                name='birthyear' required>
                                                <option value="">請選擇</option>
                                                @for ($i = 1900; $i <= 2006; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                            <span style="color: rgb(0, 0, 0);">年　</span>
                                            <select
                                                style="width: 50px;background: rgba(206,212,218,0.35);border-radius: 3px;border-style: none;padding: 3px;"
                                                required name='birthmonth'>
                                                <option value="">請選擇</option>
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                            <span style="color: rgb(0, 0, 0);">月</span>
                                        </td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">手機號碼：&nbsp;&nbsp;</span><input
                                                type="tel"
                                                style="background: rgba(206,212,218,0.35);border-style: none;border-radius: 10px;padding: 3px 10px;width: 150px;"
                                                required name='mobile' value='' id='inputCell' placeholder='格式：0912345678'>
                                        </td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">電子信箱：&nbsp;</span><input type="email"
                                                style="background: rgba(206,212,218,0.35);border-radius: 10px;width: 100%;border-style: none;padding: 3px 10px;"
                                                required name='email' value='' id='inputEmail' placeholder='請務必填寫正確，以利營隊相關訊息通知'>
                                        </td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="width: 60%;color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <strong><span style="color: rgb(0, 0, 0);">確認電子信箱：</span></strong><span
                                                style="color: rgb(96, 18, 72);">(請再輸入一次)&nbsp;</span><strong><span
                                                    style="color: rgb(96, 18, 72);">&nbsp;</span></strong><input
                                                type="email"
                                                style="background: rgba(206,212,218,0.35);border-style: none;border-radius: 10px;width: 100%;padding: 3px 10px;" required name='emailConfirm' value='' id='inputEmailConfirm'>
                                        </td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">通訊地址：&nbsp;</span>
                                                <select name="county" 
                                                    style="border-style: none;border-radius: 10px;width: 80px;background: rgba(206,212,218,0.35);padding: 3px;" onChange="Address(this.options[this.options.selectedIndex].value);">
                                                    <option value=''>先選縣市</option>
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
                                                    <option value='其它'>其它</option>
                                            </select>&nbsp;<select name=subarea
                                                style="width: 90px;background: rgba(206,212,218,0.35);border-style: none;border-radius: 10px;padding: 3px;" onChange='document.Camp.zipcode.value=this.options[this.options.selectedIndex].value; document.Camp.address.value=MyAddress(document.Camp.county.value, this.options[this.options.selectedIndex].text);'>
                                                <option value=''>再選區鄉鎮</option>
                                            </select><span
                                                style="color: rgb(0, 0, 0);">&nbsp;<input name=zipcode
                                                style="width: 40px;background: rgba(206,212,218,0.35);border-style: none;border-radius: 10px;padding: 2px;" disabled>&nbsp;</span><br><input
                                                type="text"
                                                name='address' value='' pattern=".{10,80}"
                                                style="border-style: none;background: rgba(206,212,218,0.35);border-radius: 10px;width: 100%;padding: 3px 10px;margin: 10px 0px;"
                                                placeholder="填寫通訊地址"><br></td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">LINE ID：&nbsp;&nbsp;</span><input
                                                type="text"
                                                name='line'
                                                style="background: rgba(206,212,218,0.35);border-radius: 10px;width: 150px;border-style: none;padding: 3px 10px;">
                                        </td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">適合聯絡時間</span><sub><span
                                                    style="color: rgb(0, 0, 0);">（可複選）</span></sub><span
                                                style="color: rgb(0, 0, 0);">：&nbsp;</span><br>
                                                <input type="checkbox" name=contact_time[] value='上午'>&nbsp;<span style="color: rgb(0, 0, 0);">上午　</span>
                                                <input type="checkbox" name=contact_time[] value='中午'><span style="color: rgb(0, 0, 0);"> 中午　</span>
                                                <input type="checkbox" name=contact_time[] value='下午'><span style="color: rgb(0, 0, 0);"> 下午　</span>
                                                <input type="checkbox" name=contact_time[] value='晚上'><span style="color: rgb(0, 0, 0);"> 晚上&nbsp;</span></td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgb(0,0,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(96, 18, 72);">被推薦人</span><span
                                                style="color: rgb(0, 0, 0);">是否已加入廣論班：&nbsp;</span><input
                                                type="radio" name='is_lrclass' value='0'><span
                                                style="color: rgb(0, 0, 0);">&nbsp;否　</span><input
                                                type="radio" name='is_lrclass' value='1'>&nbsp;是　廣論班別：&nbsp;<input type="text"
                                                style="width: 260px;padding: 3px 10px;border-style: none;border-radius: 10px;background: rgba(206,212,218,0.35);" name='lrclass' value='' id='inputLRClass' placeholder='請填寫 *被推薦人* 廣論研討班別'>
                                        </td>
                                    </tr>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-light border-1 d-flex p-4"
                        style="background: var(--bs-body-bg);border-radius: 30px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);height: 100%;text-align: left;">
                        <h1 style="font-size: x-large;"><span style="color: rgb(96, 18, 72);">被推薦人</span><sup><span
                                    style="color: rgb(96, 18, 72);">(營隊學員)</span></sup><span
                                style="color: rgb(96, 18, 72);">其他資料</span></h1>
                        <h1 style="font-size: small;"><span
                                style="color: rgb(201, 81, 143); background-color: rgba(255, 255, 255, 0);">＊公司及職務相關欄位，若被推薦人已退休，請填寫退休前資料＊</span>
                        </h1>
                        <div class="table-responsive" style="width: 98%;">
                            <table class="table">
                                <thead>
                                    <tr></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td
                                            style="width: 40%;color: var(--bs-body-color);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">公司名稱：&nbsp;</span><input type="text" required name='unit' placeholder='若已退休，請填寫退休前資料'
                                                style="border-style: none;border-radius: 10px;background: rgba(206,212,218,0.35);padding: 3px 10px; width: 80%;">
                                        </td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">產業別：&nbsp;</span>
                                            <select required name='industry' onChange=''
                                                style="border-style: none;border-radius: 3px;height: initial;padding: 3px;background: rgba(206,212,218,0.35);">
                                                <option value='' selected>- 請選擇 -</option>
                                                <option value='電子科技/資訊/軟體/半導體' >電子科技/資訊/軟體/半導體</option>
                                                <option value='傳產製造' >傳產製造</option>
                                                <option value='金融/保險/貿易' >金融/保險/貿易</option>
                                                <option value='法律/會計/顧問' >法律/會計/顧問</option>
                                                <option value='政治/宗教/社福' >政治/宗教/社福</option>
                                                <option value='建築/營造/不動產' >建築/營造/不動產</option>
                                                <option value='醫師/藥師/藥廠/醫療照護' >醫師/藥師/藥廠/醫療照護</option>
                                                <option value='民生服務業' >民生服務業</option>
                                                <option value='廣告/傳播/出版' >廣告/傳播/出版</option>
                                                <option value='教育' >教育</option>
                                                <option value='設計/藝術/文創' >設計/藝術/文創</option>
                                                <option value='非營利組織' >非營利組織</option>
                                                <option value='其它' >其它</option>
                                            </select></td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">職稱：&nbsp;</span><input type="text" placeholder='若已退休，請填寫退休前資料' required name='title' value='' maxlength="40" 
                                                style="padding: 3px 10px;border-radius: 10px;border-style: none;background: rgba(206,212,218,0.35); width: 80%;">
                                        </td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">職務類型：&nbsp;</span><select required  name='job_property' onChange=''
                                                style="border-radius: 3px;border-style: none;padding: 3px;background: rgba(206,212,218,0.35);">
                                                    <option value='' selected>- 請選擇 -</option>
                                                    <option value='負責人/公司經營管理' >負責人/公司經營管理</option>
                                                    <option value='人資' >人資</option>
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
                                                </select></td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">公司員工總數：&nbsp;</span><input
                                                type="number"  name='employees' value=''  id='inputEmployees' placeholder='請填寫數字，勿填「非數字」'
                                                style="border-radius: 10px;border-style: none;padding: 3px 10px;background: rgba(206,212,218,0.35); width: 80%;"><span
                                                style="color: rgb(0, 0, 0);">&nbsp;</span></td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">所轄員工人數：&nbsp;</span><input
                                                type="number" name='direct_managed_employees' value='' id='inputDirectManagedEmployees' placeholder='請填寫數字，勿填「非數字」'
                                                style="border-radius: 10px;border-style: none;padding: 3px 10px;background: rgba(206,212,218,0.35); width: 80%;">
                                        </td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">資本額（新台幣）：&nbsp;</span><input
                                                type="number"  name='capital' value='' maxlength="40"  id='inputTitle' placeholder='請填寫數字，勿填「非數字」。請記得選單位。'
                                                style="border-radius: 10px;border-style: none;padding: 3px 10px;background: rgba(206,212,218,0.35); width: 100%;">
                                        </td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="width: 60%;color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">資本額單位：&nbsp;</span>
                                                <input type="radio" name='capital_unit' value='元' checked>&nbsp;<span style="color: rgb(0, 0, 0);">元　</span>
                                                <input name='capital_unit' value='萬元' checked type="radio"><span style="color: rgb(0, 0, 0);">&nbsp;萬元　</span>
                                                <input name='capital_unit' value='億元' checked type="radio"><span style="color: rgb(0, 0, 0);"> 億元</span><span style="color: rgb(201, 81, 143);">〔資本額填寫說明〕如資本額為500萬元，請在資本額欄位填寫500，單位選「萬元」；如資本額為1000億元，請在資本額欄位填寫1000，單位選「億元」。</span>
                                        </td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span
                                                style="color: rgb(0, 0, 0); background-color: rgba(253, 126, 20, 0);">公司/組織形式：&nbsp;</span>
                                                <input  required name='org_type' value='私人公司'
                                                type="radio"><span
                                                style="color: rgb(0, 0, 0); background-color: rgba(253, 126, 20, 0);">&nbsp;私人公司　</span>
                                                <input  required name='org_type' value='專業領域(例醫生、作家⋯)'
                                                type="radio"><span
                                                style="color: rgb(0, 0, 0); background-color: rgba(253, 126, 20, 0);">&nbsp;專業領域(例醫生、作家⋯)&nbsp;&nbsp;　</span>
                                                <input required name='org_type' value='政府部門/公營事業'
                                                type="radio"><span
                                                style="color: rgb(0, 0, 0); background-color: rgba(253, 126, 20, 0);">
                                                政府部門/公營事業　</span>
                                                <input required name='org_type' value='非政府/非營利組織' type="radio"><span
                                                style="color: rgb(0, 0, 0); background-color: rgba(253, 126, 20, 0);">&nbsp;非政府/非營利組織　</span>
                                                <input required name='org_type' value='其它'
                                                type="radio"><span
                                                style="color: rgb(0, 0, 0); background-color: rgba(253, 126, 20, 0);">&nbsp;其它</span>
                                        </td>
                                    </tr>
                                    <tr
                                        style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td
                                            style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                            <span style="color: rgb(0, 0, 0);">公司成立幾年：&nbsp;</span><input  name='years_operation' value='10年以上' 
                                                type="radio"><span
                                                style="color: rgb(0, 0, 0); background-color: rgba(253, 126, 20, 0);">&nbsp;10年以上　&nbsp;</span>
                                                <input  name='years_operation' value='5年~10年'
                                                type="radio"><span
                                                style="color: rgb(0, 0, 0); background-color: rgba(253, 126, 20, 0);">&nbsp;5年~10年　</span>
                                                <input  name='years_operation' value='5年以下'
                                                type="radio"><span
                                                style="color: rgb(0, 0, 0); background-color: rgba(253, 126, 20, 0);">&nbsp;5年以下</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-light border-1 d-flex p-4"
                style="background: rgba(255,255,255,0);border-radius: 30px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);height: 100%;padding: initial;margin: 10px 0px;">
                <p style="color: rgb(70,78,171);margin: 0px;font-size: initial;text-align: left;"><strong><span
                            style="color: rgb(96, 18, 72);">個人資料：</span></strong><span
                        style="color: rgb(0, 0, 0); background-color: rgba(253, 126, 20, 0);">體將利用被推薦人所提供個人資料通知被推薦人本次營隊活動相關訊息，及日後福智團體相關課程、活動訊息通知之非營利目的使用。同意期間自被推薦人同意參加活動之日起，至被推薦人提出刪除日止。營隊活動期間由本基金會及本基金會所屬福智團體保存被推薦人的個人資料，以作為被推薦人、本基金會查詢、確認證明之用。<br></span><br><span
                        style="color: rgb(0, 0, 0); background-color: rgba(253, 126, 20, 0);">除上述情形外，本基金會於本次營隊取得之個人資料，不會未經被推薦人以言詞、書面、電話、簡訊、電子郵件、傳真、電子文件等方式同意提供給第三單位使用。&nbsp;</span><br><br><input
                        type="radio" required name="profile_agree" value='1' checked>&nbsp;<strong><span style="color: rgb(96, 18, 72);">被推薦人</span><span
                            style="color: rgb(96, 18, 72);">同意</span></strong>　　<input
                        type="radio" required name="profile_agree" value='0'>&nbsp;<strong><span style="color: rgb(96, 18, 72);">被推薦人不同意</span></strong></p>
            </div>
            <div class="col" style="text-align: center;"><button class="btn btn-warning" type="reset"
                    style="border-style: none;border-radius: 20px;box-shadow: 1px 1px 5px rgba(0,0,0,0.4);padding: 8px 20px;margin: 10px;background: rgb(238,194,222);"><span
                        style="color: rgb(96, 96, 96);">清除重填 🤔</span></button><button class="btn btn-success"
                    type="submit"
                    style="text-align: center;border-radius: 20px;margin: 10px;border-style: none;box-shadow: 1px 1px 8px rgb(55,55,55);padding: 8px 60px;font-size: 20px;background: rgb(201,81,143);">確認送出
                    😊</button></div>
        </form>
        </div>
    </section>
    <footer></footer>
    <script src="{{ asset('mockup-assets/ceocamp/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('mockup-assets/ceocamp/js/bs-init.js') }}"></script>
    <script src="{{ asset('mockup-assets/ceocamp/js/startup-modern.js') }}"></script>
</body>

</html>
