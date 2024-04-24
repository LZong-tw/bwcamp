@php
    header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
    $regions = ['台北', '桃園', '新竹', '中區', '雲嘉', '台南', '高區'];
@endphp
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name='description' content='「翻轉人生從心出發」邀請您報名參加企業主管生命成長營。' />
    <meta name='author' content='福智文教基金會'>
    <meta property='og:url' content='https://bwfoce.org/ecamp/'/>
    <meta property='og:title' content='{{ $camp_data->abbreviation }}'/>
    <meta property='og:description' content='「翻轉人生從心出發」邀請您報名參加企業主管生命成長營。' />
    <meta property='og:image' content='https://static.wixstatic.com/media/8822b2_42442909881444a99904caa63bb7e659~mv2.png/v1/fill/w_2274,h_640,al_c,usm_0.66_1.00_0.01,enc_auto/8822b2_42442909881444a99904caa63bb7e659~mv2.png'/>
    {{-- <link rel='icon' href='/camp/favicon.ico'> --}}
    <title> {{ $camp_data->fullName }} </title>
    <link rel="stylesheet" href="{{ asset("mockup-assets/ecamp/bootstrap/css/bootstrap.min.css") }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abel&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Aboreto&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alegreya+Sans&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans+Chorasmian&amp;display=swap">
    @include('partials.counties_areas_script')
    <style>
        .required:before {
            content: "＊";
            color: red;
        }
    </style>
</head>

<body style="color: #343458;background: rgb(220,220,220);">
    <nav class="navbar navbar-expand-md fixed-top navbar-shrink py-3 navbar-light" id="mainNav" style="background: linear-gradient(rgba(104,163,193,0.4), rgba(255,255,255,0.4) 52%, rgb(208,225,234)), rgba(255,255,255,0.6);border-radius: 0px;height: 60px;box-shadow: 0px 0px 14px;">
        <div class="container"><a class="navbar-brand d-flex align-items-center" href="/"><span style="font-family: Abel, sans-serif;color: rgb(46,83,99);">2024 企業主管生命成長營</span></a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1" style="width: 43px;height: 40px;padding: 0px 0px;background: rgba(103,162,192,0.3);"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1" style="height: 50px;">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="https://bwfoce.org/ecamp">營隊資訊</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('registration', $batch_id) }}">報名表單</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('query', $batch_id) }}">查詢／修改</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="pricing.html">課程表</a></li>
                    <li class="nav-item"><a class="nav-link" href="contacts.html">報名簡章</a></li> --}}
                </ul>
            </div>
        </div>
    </nav>
    <header class="pt-5"></header>
    <section style="text-align: center;"><img src="{{ asset("img/2024ecampBANNER_1024x300.png") }}" style="width: 100%;margin: initial;padding: initial;">
    {{-- !isset($isModify): 沒有 $isModify 變數，即為報名狀態、 $isModify: 修改資料狀態 --}}
    @if(!isset($isModify) || $isModify)
        <form method='post' action='{{ route('formSubmit', [$batch_id]) }}' id='Camp' name='Camp' class='form-horizontal needs-validation' role='form'>
    {{-- 以上皆非: 檢視資料狀態 --}}
    @else
        <form action="{{ route("queryupdate", $applicant_batch_id) }}" method="post" class="d-inline">
    @endif
        @csrf
        <div class="container py-4 py-xl-5">
            <div class="row gy-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                <div class="col">
                    <div class="card border-light border-1 d-flex p-4" style="background: rgb(208,225,234);border-radius: 20px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);height: 100%;text-align: left;">
                        <h1 style="font-size: xx-large;">關於您</h1>
                        <div class="table-responsive" style="width: 100%;position: static;">
                            <table class="table">
                                <thead>
                                    <tr></tr>
                                </thead>
                                <tbody style="background: rgba(255,255,255,0);">
                                    <tr>
                                        <td style="width: 40%;border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(108, 166, 194);" class="required">姓　　名：</span></strong>&nbsp;<input type="text" style="background: var(--bs-table-bg);filter: brightness(100%);backdrop-filter: opacity(1) brightness(100%) saturate(100%);border-radius: 10px;border-width: 0.1px;border-style: none;border-top-style: none;width: 140px;" type='text' name='name' value='' id='inputName' placeholder='請填寫全名' required></td>
                                    </tr>
                                    <tr>
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(107, 165, 193);">性　　別</span><span style="color: rgb(107, 165, 193); background-color: rgba(105, 167, 190, 0);" class="required">：&nbsp;</span></strong>&nbsp;<input type="radio" style="border-style: none;border-color: rgb(105,167,190);" name="gender" value="M" required>&nbsp;男　　<input type="radio" style="border-style: none;border-color: rgb(105,167,190);opacity: 1;"  name="gender" value="F" required>&nbsp;女</td>
                                    </tr>
                                    <tr>
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(106, 165, 193);" class="required">生　　日：</span></strong>&nbsp;<input type="date" style="width: 140px;border-radius: 5px;padding: 3px;border-width: 1px;border-style: none;" required name="birthdate">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(105, 166, 194);">宗教信仰：&nbsp;</span></strong><select style="width: 140px;border-radius: 5px;padding: 3px;border-style: none;" name="belief">
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
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td style="border-width: 1px;border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(105, 167, 190);">最高學歷：&nbsp;</span></strong><select style="width: 140px;border-radius: 5px;padding: 3px;border-style: none;" name="education">
                                                <option value=''>- 請選擇 -</option>
                                                <option value='高中職'>高中職</option>
                                                <option value='專科'>專科</option>
                                                <option value='大學'>大學</option>
                                                <option value='碩士'>碩士</option>
                                                <option value='博士'>博士</option>
                                                <option value='其它'>其它</option>
                                            </select></td>
                                    </tr>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-light border-1 d-flex p-4" style="background: rgb(255,231,214);border-radius: 30px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);text-align: left;">
                        <h1 style="font-size: xx-large;">您的公司</h1>
                        <div class="table-responsive" style="width: 98%;">
                            <table class="table">
                                <thead>
                                    <tr></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width: 60%;border-style: none;background: rgba(255,255,255,0);padding: 8px;"><strong><span style="color: rgb(255, 109, 3);" class="required">服務單位：&nbsp;</span></strong><input type="text" style="width: 140px;border-style: none;border-color: rgba(255,152,77,0.24);background: var(--bs-table-bg);border-radius: 10px;" required name='unit' value='' id='inputUnit'></td>
                                    </tr>
                                    <tr style="background: rgba(255,255,255,0);">
                                        <td style="border-style: none;background: rgba(255,255,255,0);padding: 8px;"><strong><span style="color: rgb(255, 109, 3);" class="required">產&nbsp; 業&nbsp; 別：&nbsp;</span></strong><select style="width: 140px;border-radius: 5px;padding: 3px;background: var(--bs-table-bg);border-style: none;" required name='industry' onChange=''>
                                                <option value='' selected>- 請選擇 -</option>
                                                <option value='製造業' >製造業</option>
                                                <option value='公共事業' >公共事業</option>
                                                <option value='營建工程相關產業' >營建工程相關產業</option>
                                                <option value='批發、零售及貿易相關產業' >批發、零售及貿易相關產業</option>
                                                <option value='運輸及倉儲相關產業' >運輸及倉儲相關產業</option>
                                                <option value='住宿及餐飲相關產業' >住宿及餐飲相關產業</option>
                                                <option value='大眾傳播通訊相關產業' >大眾傳播通訊相關產業</option>
                                                <option value='金融及保險業相關產業' >金融及保險業相關產業</option>
                                                <option value='專業、科學及技術相關服務業(會計師、律師、顧問等）' >專業、科學及技術相關服務業(會計師、律師、顧問等）</option>
                                                <option value='教育學術相關產業' >教育學術相關產業</option>
                                                <option value='醫療保健及社會工作服務相關' >醫療保健及社會工作服務相關</option>
                                                <option value='藝術、娛樂及休閒服務相關' >藝術、娛樂及休閒服務相關</option>
                                                <option value='公家機關' >公家機關</option>
                                                <option value='其它' >其它</option>
                                            </select>&nbsp;</td>
                                    </tr>
                                    <tr style="background: rgba(255,255,255,0);">
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(255, 109, 3);" class="required">所&nbsp; 在&nbsp; 地：&nbsp;</span></strong><select style="width: 140px;border-radius: 5px;padding: 3px;border-style: none;background: var(--bs-table-bg);" required name='unit_county' onChange='handleRegionChange(this)' id='inputUnitCounty'> 
                                                <option value='' selected>- 先選縣市 -</option>
                                                <option value='' disabled>- 北區 -</option>
                                                <option value='臺北市' >臺北市</option>
                                                <option value='新北市' >新北市</option>
                                                <option value='基隆市' >基隆市</option>
                                                <option value='宜蘭縣' >宜蘭縣</option>
                                                <option value='花蓮縣' >花蓮縣</option>
                                                <option value='桃園市' >桃園市</option>
                                                <option value='新竹市' >新竹市</option>
                                                <option value='新竹縣' >新竹縣</option>
                                                <option value='' disabled>- 中區 -</option>
                                                <option value='苗栗縣' >苗栗縣</option>
                                                <option value='臺中市' >臺中市</option>
                                                <option value='彰化縣' >彰化縣</option>
                                                <option value='南投縣' >南投縣</option>
                                                <option value='雲林縣' >雲林縣</option>
                                                <option value='嘉義市' >嘉義市</option>
                                                <option value='嘉義縣' >嘉義縣</option>
                                                <option value='' disabled>- 南區 -</option>
                                                <option value='臺南市' >臺南市</option>
                                                <option value='高雄市' >高雄市</option>
                                                <option value='屏東縣' >屏東縣</option>
                                                <option value='臺東縣' >臺東縣</option>
                                                <option value='澎湖縣' >澎湖縣</option>
                                                <option value='金門縣' >金門縣</option>
                                                <option value='連江縣' >連江縣</option>
                                                <option value='' disabled>- 其它 -</option>
                                                <option value='上海地區' >上海地區</option>
                                                <option value='港澳深圳' >港澳深圳</option>
                                                <option value='南海諸島' >南海諸島</option>
                                                <option value='星馬地區' >星馬地區</option>
                                                <option value='其它海外' >其它海外</option>
                                            </select></td>
                                    </tr>
                                    <tr style="background: rgba(255,255,255,0);">
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(255, 109, 3);" class="required">行&nbsp; 政&nbsp; 區：&nbsp;</span></strong><select style="width: 140px;border-radius: 5px;padding: 3px;border-style: none;background: var(--bs-table-bg);" required name='unit_subarea' onChange='document.Camp.unit_location.value=MyAddress(document.Camp.unit_county.value, this.options[this.options.selectedIndex].text);' id='inputUnitSubarea'>
                                                <option value=''>- 再選區鄉鎮 -</option>
                                            </select>
                                            <input type='hidden' name='unit_zipcode' value=''>
                                            <input type='hidden' name='unit_address' value=''>
                                            <input type="hidden" name='unit_location' value='' id='inputUnitLocation'>
                                            <input type="hidden" name='' value='' id='inputUnitSubarea2' disabled="true" type="text" style="background: var(--bs-table-bg);border-radius: 10px;width: 140px;border-style: none;" placeholder="請輸入行政區或地區">
                                            </td>
                                    </tr>
                                    <tr style="border-style: none;background: rgba(255,255,255,0);">
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(255, 109, 3);" class="required">職　　稱：&nbsp;</span></strong><input type="text" style="background: var(--bs-table-bg);border-radius: 10px;width: 140px;border-style: none;" required name='title' value='' maxlength="40" id='inputTitle'>&nbsp;&nbsp;</td>
                                    </tr>
                                    <tr style="border-style: none;background: rgba(255,255,255,0);">
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(255, 109, 3);" class="required">工作屬性：&nbsp;</span></strong><select style="width: 143px;border-radius: 5px;padding: 3px;border-style: none;background: var(--bs-table-bg);"  required name='job_property' onChange=''>
                                                <option value='' selected>- 請選擇 -</option>
                                                <option value='經營/人資' >經營/人資</option>
                                                <option value='行政/總務' >行政/總務</option>
                                                <option value='法務' >法務</option>
                                                <option value='財會/金融' >財會/金融</option>
                                                <option value='行銷/企劃' >行銷/企劃</option>
                                                <option value='專案管理' >專案管理</option>
                                                <option value='客服/門市' >客服/門市</option>
                                                <option value='業務/貿易' >業務/貿易</option>
                                                <option value='餐飲/旅遊/美容美髮' >餐飲/旅遊/美容美髮</option>
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
                                    <tr style="border-style: none;background: rgba(255,255,255,0);">
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(255, 109, 3);" class="required">公司人數：&nbsp;</span></strong><input type="text" style="width: 120px;border-radius: 10px;background: var(--bs-table-bg);border-style: none;" required name='employees' value='' id='inputEmployees'>&nbsp;人&nbsp;</td>
                                    </tr>
                                    <tr style="border-style: none;background: rgba(255,255,255,0);">
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(255, 109, 3);" class="required">直屬管轄人數：&nbsp;</span></strong><input type="text" style="width: 85px;background: var(--bs-table-bg);border-style: none;border-radius: 10px;" required name='direct_managed_employees' value=''  id='inputDirectManagedEmployees'>&nbsp;人</td>
                                    </tr>
                                    <tr></tr>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-light border-1 d-flex p-4" style="background: rgb(255,231,214);border-radius: 30px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);height: 100%;text-align: left;">
                        <h1 style="font-size: xx-large;">您的經歷</h1>
                        <div class="" style="width: 98%;">
                            <table class="table">
                                <thead>
                                    <tr></tr>
                                </thead>
                                <tbody>
                                    <tr></tr>
                                    <tr></tr>
                                    <tr></tr>
                                    <tr></tr>
                                    <tr>
                                        <td style="filter: blur(0px);backdrop-filter: opacity(1);display: flex;border-style: none;background: #12164300;"><textarea style="width: 100%;border-style: none;background: var(--bs-table-bg);border-radius: 10px;height: 240px;padding: 0px;" name='experience' id=inputExperience></textarea></td>
                                    </tr>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><img src="{{ asset("mockup-assets/ecamp/img/illustrations/eco.png") }}" style="width: 95%;margin: 20px;">
            <div class="row gy-4 row-cols-1 row-cols-md-2">
                <div class="col">
                    <div class="card border-light border-1 d-flex p-4" style="background: #d0e1ea;border-radius: 20px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);height: 100%;text-align: left;">
                        <h1 style="font-size: x-large;">more about you...</h1>
                        <div class="table-responsive" style="width: 98%;">
                            <table class="table">
                                <thead>
                                    <tr></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width: 40%;color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(108, 166, 194);" class="required">行動電話：</span></strong>&nbsp;<input type="tel" style="background: var(--bs-table-bg);border-radius: 10px;width: 185px;border-style: none;" required name='mobile' value='' id='inputCell' placeholder='格式：0912345678'></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(108, 166, 194);" class="required">公司電話：</span></strong>&nbsp;<input type="tel" style="background: var(--bs-table-bg);border-radius: 10px;width: 185px;border-style: none;"  required name='phone_work' value='' id='inputTelWork' placeholder='格式：0225452546#520'></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(108, 166, 194);" class="required">住家電話：</span></strong>&nbsp;<input type="tel" style="background: var(--bs-table-bg);border-radius: 10px;width: 185px;border-style: none;" name='phone_home' value='' id='inputTelHome' placeholder='格式：0225452546#520'></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(108, 166, 194);">LINE ID：&nbsp;&nbsp;</span></strong>&nbsp;<input type="text" style="background: var(--bs-table-bg);border-radius: 10px;width: 185px;border-style: none;" name='line' value='' id='inputLineID'></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(108, 166, 194);">微 信 ID：&nbsp;&nbsp;</span></strong>&nbsp;<input type="text" style="background: var(--bs-table-bg);border-radius: 10px;width: 185px;border-style: none;" name='wechat' value='' id='inputWeChatID'></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(108, 166, 194);" class="required">電子郵件信箱：&nbsp;</span></strong><input type="email" style="background: var(--bs-table-bg);border-radius: 10px;width: 100%;border-style: none;" required name='email' value=''id='inputEmail' placeholder='請務必填寫正確，以利營隊相關訊息通知'></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="width: 60%;color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(52, 99, 122);" class="required">確認電子郵件信箱：</span></strong><span style="color: rgb(187, 57, 49);">(請再輸入一次)&nbsp;</span><strong><span style="color: rgb(52, 99, 122);">&nbsp;</span></strong><input type="email" style="background: var(--bs-table-bg);border-style: none;border-radius: 10px;width: 100%;"  required name='emailConfirm' value='' id='inputEmailConfirm'></td>
                                    </tr>
                                    <tr>
                                        <td style="border-width: 1px;border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(105, 167, 190);" class="required">通訊地址縣市：&nbsp;</span></strong><select style="width: 140px;border-radius: 5px;padding: 3px;border-style: none;" name="county" required onChange="Address(this.options[this.options.selectedIndex].value);">
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
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td style="border-width: 1px;border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(105, 167, 190);" class="required">通訊地址行政區：&nbsp;</span></strong><select style="width: 140px;border-radius: 5px;padding: 3px;border-style: none;" name="subarea" onChange='document.Camp.zipcode.value=this.options[this.options.selectedIndex].value; document.Camp.address.value=MyAddress(document.Camp.county.value, this.options[this.options.selectedIndex].text);' required>
                                                <option value=''>- 再選區鄉鎮 -</option>
                                        </select></td>
                                    </tr>
                                    <tr>
                                        <td style="border-width: 1px;border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(105, 167, 190);" class="required">通訊地址：&nbsp;</span></strong> 
                                        <input type="hidden" name='zipcode' value=''>
                                        <input type="text" name='address' value='' pattern=".{10,80}" style="background: var(--bs-table-bg);border-radius: 10px;width: 100%;border-style: none;" required>
                                        </td>
                                    </tr>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-light border-1 d-flex p-4" style="background: #d0e1ea;border-radius: 30px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);height: 100%;text-align: left;">
                        <h1 style="font-size: x-large;" class="required">希望成長營給您的幫助...</h1><textarea style="width: 98%;height: 300px;border-style: none;border-radius: 10px;" name='expectation' id=inputExpect required></textarea>
                    </div>
                </div>
            </div>
            <section>
                <div class="container py-4 py-xl-5" style="padding: 0px 0px;height: initial;">
                    <div class="row gy-4 gy-md-0">
                        <div class="col-md-6 text-center text-md-start d-flex d-sm-flex d-md-flex justify-content-center align-items-center justify-content-md-start align-items-md-center justify-content-xl-center">
                            <div><img class="rounded img-fluid fit-cover" style="min-height: 300px;width: 100%;height: auto;" src="{{ asset("mockup-assets/ecamp/img/illustrations/活動.png")}}" width="800"></div>
                        </div>
                        <div class="col" style="text-align: left;">
                            <div style="max-width: 450px;">
                                <h3 class="fw-bold pb-md-1 required" style="font-size: 18px;color: #ed5412;">有興趣參加活動的類別？(可複選)</h3>
                                <div class="row gy-4">
                                    <div class="col">
                                        <div>
                                            <p class="fw-normal text-muted" style="background: rgba(255,255,255,0);">
                                            <input type="checkbox" class="favored_event" name=favored_event[] value='企業參訪'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);" required>&nbsp;企業參訪　</span>
                                            <input type="checkbox" class="favored_event" name=favored_event[] value='種樹活動'><span style="background-color: rgba(220, 220, 220, 0);" required>&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">種樹活動　</span>
                                            <input type="checkbox" class="favored_event" name=favored_event[] value='環保淨灘'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);" required>&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">環保淨灘　</span><br>
                                            <input type="checkbox" class="favored_event" name=favored_event[] value='農場體驗'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);" required>&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">農場體驗　</span>
                                            <input type="checkbox" class="favored_event" name=favored_event[] value='禪修活動'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);" required>&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">禪修活動　</span>
                                            <input type="checkbox" class="favored_event" name=favored_event[] value='寺院參訪'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);" required>&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">寺院參訪　</span><br>
                                            <input type="checkbox" class="favored_event" name=favored_event[] value='儒學課程'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);" required>&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">儒學課程　</span>
                                            <input type="checkbox" class="favored_event" name=favored_event[] value='心靈講座'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);" required>&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">心靈講座　</span>
                                            <input type="checkbox" class="favored_event" name=favored_event[] value='藝文活動'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);" required>&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">藝文活動　</span><br>
                                            <input type="checkbox" class="favored_event" name=favored_event[] value='親子講座'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);" required>&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">親子講座　</span>
                                            <input type="checkbox" class="favored_event" name=favored_event[] value='樂齡活動'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);" required>&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">樂齡活動　</span></p>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="fw-bold pb-md-1" style="font-size: 18px;color: #ed5412;padding: 20px 0px 0px;">營隊結束後，若有後續課程開課，請問您比較方便參加的時段？(可複選)</h3>
                                <p class="fw-normal text-muted" style="background: rgba(203,164,164,0);">
                                <input type="checkbox" class="after_camp_available_day" name=after_camp_available_day[] value='週一'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;週一　</span>
                                <input type="checkbox" class="after_camp_available_day" name=after_camp_available_day[] value='週二'><span style="background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">週二　</span>
                                <input type="checkbox" class="after_camp_available_day" name=after_camp_available_day[] value='週三'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">週三　</span>
                                <input type="checkbox" class="after_camp_available_day" name=after_camp_available_day[] value='週四'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;週四</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">　　</span><br>
                                <input type="checkbox" class="after_camp_available_day" name=after_camp_available_day[] value='週五'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;週五</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">　</span>
                                <input type="checkbox" class="after_camp_available_day" name=after_camp_available_day[] value='週六'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">週六　</span>
                                <input type="checkbox" class="after_camp_available_day" name=after_camp_available_day[] value='週日'><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">週日</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="row gy-4 row-cols-1 row-cols-md-2" style="margin: -2px -12px 10px;">
                <div class="col">
                    <div class="card border-light border-1 d-flex p-4" style="background: var(--bs-body-bg);border-radius: 20px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);height: 100%;text-align: left;">
                        <p style="font-size: x-large;"><span style="color: rgb(253, 126, 20);">緊急聯絡人</span></p>
                        <div class="table-responsive" style="width: 98%;">
                            <table class="table">
                                <thead>
                                    <tr></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width: 40%;color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(253, 126, 20);" class="required">姓　　名：</span></strong><span style="color: rgb(253, 126, 20);">&nbsp;</span><input type="text" style="background: rgba(206,212,218,0.35);border-radius: 10px;width: 150px;border-style: none;border-color: rgb(221,221,221);" name="emergency_name" value='' required></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(253, 126, 20);" class="required">關　　係：</span></strong><span style="color: rgb(253, 126, 20);">&nbsp;</span><select style="border-radius: 5px;border-style: none;padding: 3px;background: rgba(206,212,218,0.35);width: 150px;" name="emergency_relationship" required>
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
                                            </select></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(253, 126, 20);" class="required">聯絡電話：</span></strong><span style="color: rgb(253, 126, 20);">&nbsp;</span><input type="tel" style="background: rgba(206,212,218,0.35);border-radius: 10px;width: 150px;border-style: none;" name="emergency_mobile" value='' required></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"></tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"></tr>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-light border-1 d-flex p-4" style="background: var(--bs-orange);border-radius: 30px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);height: 100%;text-align: left;">
                        <p style="font-size: x-large;">介紹人</p>
                        <div class="table-responsive" style="width: 98%;">
                            <table class="table">
                                <thead>
                                    <tr></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width: 40%;color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><span style="color: rgb(73, 80, 87);">姓　　名：</span>&nbsp;<input type="text" style="background: var(--bs-table-bg);border-radius: 10px;width: 150px;border-style: none;" name="introducer_name" value=''></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><span style="color: rgb(73, 80, 87);">關　　係：</span>&nbsp;<select style="padding: 3px;border-radius: 5px;border-style: none;width: 150px;" name="introducer_relationship">
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
                                            </select></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><span style="color: rgb(73, 80, 87);">聯絡電話：</span>&nbsp;<input type="tel" style="background: var(--bs-table-bg);border-radius: 10px;width: 150px;border-style: none;" name="introducer_phone"></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><span style="color: rgb(73, 80, 87);">福智班別：</span>&nbsp;<input type="text" style="background: var(--bs-table-bg);border-radius: 10px;width: 150px;border-style: none;" name="introducer_participated"></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"></tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"></tr>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-light border-1 d-flex p-4" style="background: rgba(255,255,255,0);border-radius: 30px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);height: 100%;padding: initial;margin: 10px 0px;">
                <p style="color: rgb(70,78,171);margin: 0px;font-size: initial;text-align: left;"><strong><span style="color: rgb(0, 0, 0);" class="required">肖像權</span></strong><br><span style="color: rgb(253, 126, 20);">主辦單位在營隊期間拍照、錄影之活動記錄，可使用於營隊及主辦單位的非營利教育推廣使用，並以網路方式推播。</span><br><input type="radio" required name="portrait_agree" value='1' checked>&nbsp;同意　　<input type="radio" required name="portrait_agree" value='0'>&nbsp;不同意</p>
            </div>
            <div class="card border-light border-1 d-flex p-4" style="background: rgba(255,255,255,0);border-radius: 30px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);height: 100%;padding: initial;margin: 10px 0px;">
                <p style="color: rgb(70,78,171);margin: 0px;font-size: initial;text-align: left;"><strong><span style="color: rgb(0, 0, 0);" class="required">個人資料</span></strong><br><span style="color: rgb(253, 126, 20);">福智文教基金會（簡稱本基金會）及本基金會所屬福智團體於本次營隊取得我的個人資料，於營隊期間及後續本基金會及本基金會所屬福智團體舉辦之活動，作為訊息通知、行政處理等非營利目的之使用，不會提供給無關之其他私人單位使用。</span><br><input type="radio" required name="profile_agree" value='1' checked>&nbsp;同意　　<input type="radio" required name="profile_agree" value='0'>&nbsp;不同意</p>
            </div>
            <div class="col" style="text-align: center;"><button class="btn btn-warning" type="reset" style="border-style: none;border-radius: 20px;box-shadow: 1px 1px 5px rgba(0,0,0,0.4);padding: 8px 20px;margin: 10px;background: rgba(255,210,0,0.59);"><span style="color: rgb(96, 96, 96);">清除重填 🤔</span></button><button class="btn btn-success" type="submit" style="text-align: center;border-radius: 20px;margin: 10px;border-style: none;box-shadow: 1px 1px 8px rgb(55,55,55);padding: 8px 60px;font-size: 20px;background: rgb(253,126,20);">確認送出 😊</button></div>
        </div>
    </form>
    </section>
    <footer></footer>
    <script src="{{ asset("mockup-assets/ecamp/bootstrap/js/bootstrap.min.js") }}"></script>
    <script src="{{ asset("mockup-assets/ecamp/js/bs-init.js") }}"></script>
    <script src="{{ asset("mockup-assets/ecamp/js/startup-modern.js") }}"></script>
    <script>

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
    </script>
</body>

</html>
