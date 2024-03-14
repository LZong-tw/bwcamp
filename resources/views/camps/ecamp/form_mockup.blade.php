<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Home - Brand</title>
    <link rel="stylesheet" href="{{ asset("mockup-assets/ecamp/bootstrap/css/bootstrap.min.css") }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abel&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Aboreto&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alegreya+Sans&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans+Chorasmian&amp;display=swap">
</head>

<body style="color: #343458;background: rgb(220,220,220);">
    <nav class="navbar navbar-expand-md fixed-top navbar-shrink py-3 navbar-light" id="mainNav" style="background: linear-gradient(rgba(104,163,193,0.4), rgba(255,255,255,0.4) 52%, rgb(208,225,234)), rgba(255,255,255,0.6);border-radius: 0px;height: 60px;box-shadow: 0px 0px 14px;">
        <div class="container"><a class="navbar-brand d-flex align-items-center" href="/"><span style="font-family: Abel, sans-serif;color: rgb(46,83,99);">2024 企業主管生命成長營</span></a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1" style="width: 43px;height: 40px;padding: 0px 0px;background: rgba(103,162,192,0.3);"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1" style="height: 50px;">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.html">營隊資訊</a></li>
                    <li class="nav-item"><a class="nav-link" href="features.html">報名表單</a></li>
                    <li class="nav-item"><a class="nav-link" href="integrations.html">查詢／修改</a></li>
                    <li class="nav-item"><a class="nav-link" href="pricing.html">課程表</a></li>
                    <li class="nav-item"><a class="nav-link" href="contacts.html">報名簡章</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <header class="pt-5"></header>
    <section style="text-align: center;"><img src="{{ asset("mockup-assets/ecamp/img/illustrations/報名banner.png") }}" style="width: 100%;margin: initial;padding: initial;">
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
                                        <td style="width: 40%;border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(108, 166, 194);">姓　　名：</span></strong>&nbsp;<input type="text" style="background: var(--bs-table-bg);filter: brightness(100%);backdrop-filter: opacity(1) brightness(100%) saturate(100%);border-radius: 10px;border-width: 0.1px;border-style: none;border-top-style: none;width: 140px;"></td>
                                    </tr>
                                    <tr>
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(107, 165, 193);">性　　別</span><span style="color: rgb(107, 165, 193); background-color: rgba(105, 167, 190, 0);">：&nbsp;</span></strong>&nbsp;<input type="radio" style="border-style: none;border-color: rgb(105,167,190);">&nbsp;男　　<input type="radio" style="border-style: none;border-color: rgb(105,167,190);opacity: 1;">&nbsp;女</td>
                                    </tr>
                                    <tr>
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(106, 165, 193);">生　　日：</span></strong>&nbsp;<input type="date" style="width: 140px;border-radius: 5px;padding: 3px;border-width: 1px;border-style: none;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(105, 166, 194);">宗教信仰：&nbsp;</span></strong><select style="width: 140px;border-radius: 5px;padding: 3px;border-style: none;">
                                                <optgroup label="This is a group">
                                                    <option value="12" selected="">This is item 1</option>
                                                    <option value="13">This is item 2</option>
                                                    <option value="14">This is item 3</option>
                                                </optgroup>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td style="border-width: 1px;border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(105, 167, 190);">最高學歷：&nbsp;</span></strong><select style="width: 140px;border-radius: 5px;padding: 3px;border-style: none;">
                                                <optgroup label="This is a group">
                                                    <option value="12" selected="">This is item 1</option>
                                                    <option value="13">This is item 2</option>
                                                    <option value="14">This is item 3</option>
                                                </optgroup>
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
                                        <td style="width: 60%;border-style: none;background: rgba(255,255,255,0);padding: 8px;"><strong><span style="color: rgb(255, 109, 3);">服務單位：&nbsp;</span></strong><input type="text" style="width: 140px;border-style: none;border-color: rgba(255,152,77,0.24);background: var(--bs-table-bg);border-radius: 10px;"></td>
                                    </tr>
                                    <tr style="background: rgba(255,255,255,0);">
                                        <td style="border-style: none;background: rgba(255,255,255,0);padding: 8px;"><strong><span style="color: rgb(255, 109, 3);">產&nbsp; 業&nbsp; 別：&nbsp;</span></strong><select style="width: 140px;border-radius: 5px;padding: 3px;background: var(--bs-table-bg);border-style: none;">
                                                <optgroup label="This is a group">
                                                    <option value="12" selected="">This is item 1</option>
                                                    <option value="13">This is item 2</option>
                                                    <option value="14">This is item 3</option>
                                                </optgroup>
                                            </select>&nbsp;</td>
                                    </tr>
                                    <tr style="background: rgba(255,255,255,0);">
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(255, 109, 3);">所&nbsp; 在&nbsp; 地：&nbsp;</span></strong><select style="width: 140px;border-radius: 5px;padding: 3px;border-style: none;background: var(--bs-table-bg);">
                                                <optgroup label="This is a group">
                                                    <option value="12" selected="">This is item 1</option>
                                                    <option value="13">This is item 2</option>
                                                    <option value="14">This is item 3</option>
                                                </optgroup>
                                            </select></td>
                                    </tr>
                                    <tr style="border-style: none;background: rgba(255,255,255,0);">
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(255, 109, 3);">職　　稱：&nbsp;</span></strong><input type="text" style="background: var(--bs-table-bg);border-radius: 10px;width: 140px;border-style: none;">&nbsp;&nbsp;</td>
                                    </tr>
                                    <tr style="border-style: none;background: rgba(255,255,255,0);">
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(255, 109, 3);">工作屬性：&nbsp;</span></strong><select style="width: 143px;border-radius: 5px;padding: 3px;border-style: none;background: var(--bs-table-bg);">
                                                <optgroup label="This is a group">
                                                    <option value="12" selected="">This is item 1</option>
                                                    <option value="13">This is item 2</option>
                                                    <option value="14">This is item 3</option>
                                                </optgroup>
                                            </select></td>
                                    </tr>
                                    <tr style="border-style: none;background: rgba(255,255,255,0);">
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(255, 109, 3);">公司人數：&nbsp;</span></strong><input type="text" style="width: 120px;border-radius: 10px;background: var(--bs-table-bg);border-style: none;">&nbsp;人&nbsp;</td>
                                    </tr>
                                    <tr style="border-style: none;background: rgba(255,255,255,0);">
                                        <td style="border-style: none;background: rgba(255,255,255,0);"><strong><span style="color: rgb(255, 109, 3);">直屬管轄人數：&nbsp;</span></strong><input type="text" style="width: 85px;background: var(--bs-table-bg);border-style: none;border-radius: 10px;">&nbsp;人</td>
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
                        <div class="table-responsive" style="width: 98%;">
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
                                        <td style="filter: blur(0px);backdrop-filter: opacity(1);display: flex;border-style: none;background: #12164300;"><textarea style="width: 100%;border-style: none;background: var(--bs-table-bg);border-radius: 10px;height: 240px;padding: 0px;"></textarea></td>
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
                                        <td style="width: 40%;color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(108, 166, 194);">行動電話：</span></strong>&nbsp;<input type="tel" style="background: var(--bs-table-bg);border-radius: 10px;color: rgba(255,255,255,0);width: 150px;border-style: none;"></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(108, 166, 194);">公司電話：</span></strong>&nbsp;<input type="tel" style="background: var(--bs-table-bg);border-radius: 10px;color: rgba(255,255,255,0);width: 150px;border-style: none;"></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(108, 166, 194);">住家電話：</span></strong>&nbsp;<input type="tel" style="background: var(--bs-table-bg);border-radius: 10px;color: rgba(255,255,255,0);width: 150px;border-style: none;"></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(108, 166, 194);">LINE ID：&nbsp;</span></strong>&nbsp;<input type="text" style="background: var(--bs-table-bg);border-radius: 10px;color: rgba(255,255,255,0);width: 150px;border-style: none;"></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(108, 166, 194);">微 信 ID：&nbsp;</span></strong>&nbsp;<input type="text" style="background: var(--bs-table-bg);border-radius: 10px;color: rgba(255,255,255,0);width: 150px;border-style: none;"></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(108, 166, 194);">電子郵件信箱：&nbsp;</span></strong><input type="email" style="background: var(--bs-table-bg);border-radius: 10px;width: 100%;color: rgba(255,255,255,0);border-style: none;"></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="width: 60%;color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(52, 99, 122);">確認電子郵件信箱：</span></strong><span style="color: rgb(187, 57, 49);">(請再輸入一次)&nbsp;</span><strong><span style="color: rgb(52, 99, 122);">&nbsp;</span></strong><input type="email" style="background: var(--bs-table-bg);border-style: none;border-radius: 10px;width: 100%;"></td>
                                    </tr>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-light border-1 d-flex p-4" style="background: #d0e1ea;border-radius: 30px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);height: 100%;text-align: left;">
                        <h1 style="font-size: x-large;">希望成長營給您的幫助...</h1><textarea style="width: 98%;height: 300px;border-style: none;border-radius: 10px;"></textarea>
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
                                <h3 class="fw-bold pb-md-1" style="font-size: 18px;color: #ed5412;">有興趣參加活動的類別？(可複選)</h3>
                                <div class="row gy-4">
                                    <div class="col">
                                        <div>
                                            <p class="fw-normal text-muted" style="background: rgba(255,255,255,0);"><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;企業參訪　</span><input type="checkbox"><span style="background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">種樹活動　</span><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">環保淨灘　</span><br><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">農場體驗　</span><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">禪修活動　</span><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">寺院參訪　　</span><br><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">儒學課程　</span><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">心靈講座　</span><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">藝文活動</span><br><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">親子講座　</span><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">樂齡活動</span></p>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="fw-bold pb-md-1" style="font-size: 18px;color: #ed5412;padding: 20px 0px 0px;">營隊結束後，若有後續課程開課，請問您比較方便參加的時段？(可複選)</h3>
                                <p class="fw-normal text-muted" style="background: rgba(203,164,164,0);"><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;週一　</span><input type="checkbox"><span style="background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">週二　</span><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">週三　</span><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;週四</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">　　</span><br><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;週五</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">　</span><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">週六　</span><input type="checkbox"><span style="color: rgb(0, 0, 0); background-color: rgba(220, 220, 220, 0);">&nbsp;</span><span style="color: rgb(33, 37, 41); background-color: rgba(220, 220, 220, 0);">週日</span></p>
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
                                        <td style="width: 40%;color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(253, 126, 20);">姓　　名：</span></strong><span style="color: rgb(253, 126, 20);">&nbsp;</span><input type="tel" style="background: rgba(206,212,218,0.35);border-radius: 10px;color: rgba(255,255,255,0);width: 150px;border-style: none;border-color: rgb(221,221,221);"></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(253, 126, 20);">關　　係：</span></strong><span style="color: rgb(253, 126, 20);">&nbsp;</span><select style="border-radius: 5px;border-style: none;padding: 3px;background: rgba(206,212,218,0.35);width: 150px;">
                                                <optgroup label="This is a group">
                                                    <option value="12" selected="">道友</option>
                                                    <option value="13">This is item 2</option>
                                                    <option value="14">This is item 3</option>
                                                </optgroup>
                                            </select></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><strong><span style="color: rgb(253, 126, 20);">聯絡電話：</span></strong><span style="color: rgb(253, 126, 20);">&nbsp;</span><input type="tel" style="background: rgba(206,212,218,0.35);border-radius: 10px;color: rgba(255,255,255,0);width: 150px;border-style: none;"></td>
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
                                        <td style="width: 40%;color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><span style="color: rgb(73, 80, 87);">姓　　名：</span>&nbsp;<input type="tel" style="background: var(--bs-table-bg);border-radius: 10px;color: rgba(255,255,255,0);width: 150px;border-style: none;"></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><span style="color: rgb(73, 80, 87);">關　　係：</span>&nbsp;<select style="padding: 3px;border-radius: 5px;border-style: none;width: 150px;">
                                                <optgroup label="This is a group">
                                                    <option value="12" selected="">唐玉紅</option>
                                                    <option value="13">This is item 2</option>
                                                    <option value="14">This is item 3</option>
                                                </optgroup>
                                            </select></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><span style="color: rgb(73, 80, 87);">聯絡電話：</span>&nbsp;<input type="tel" style="background: var(--bs-table-bg);border-radius: 10px;color: rgba(255,255,255,0);width: 150px;border-style: none;"></td>
                                    </tr>
                                    <tr style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;">
                                        <td style="color: rgba(255,255,255,0);background: rgba(255,255,255,0);border-style: none;"><span style="color: rgb(73, 80, 87);">福智班別：</span>&nbsp;<input type="tel" style="background: var(--bs-table-bg);border-radius: 10px;color: rgba(255,255,255,0);width: 150px;border-style: none;"></td>
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
                <p style="color: rgb(70,78,171);margin: 0px;font-size: initial;text-align: left;"><strong><span style="color: rgb(0, 0, 0);">肖像權</span></strong><br><span style="color: rgb(253, 126, 20);">主辦單位在營隊期間拍照、錄影之活動記錄，可使用於營隊及主辦單位的非營利教育推廣使用，並以網路方式推播。</span><br><input type="radio">&nbsp;同意　　<input type="radio">&nbsp;不同意</p>
            </div>
            <div class="card border-light border-1 d-flex p-4" style="background: rgba(255,255,255,0);border-radius: 30px;border-style: none;box-shadow: 0px 0px 5px rgba(0,0,0,0.15);height: 100%;padding: initial;margin: 10px 0px;">
                <p style="color: rgb(70,78,171);margin: 0px;font-size: initial;text-align: left;"><strong><span style="color: rgb(0, 0, 0);">個人資料</span></strong><br><span style="color: rgb(253, 126, 20);">主辦單位於本次營隊取得我的個人資料，於營隊期間及後續主辦單位舉辦之活動，作為訊息通知、行政處理等非營利目的之使用，不會提供給無關之其他私人單位使用。</span><br><input type="radio">&nbsp;同意　　<input type="radio">&nbsp;不同意</p>
            </div>
            <div class="col" style="text-align: center;"><button class="btn btn-warning" type="reset" style="border-style: none;border-radius: 20px;box-shadow: 1px 1px 5px rgba(0,0,0,0.4);padding: 8px 20px;margin: 10px;background: rgba(255,210,0,0.59);"><span style="color: rgb(96, 96, 96);">清除重填 🤔</span></button><button class="btn btn-success" type="submit" style="text-align: center;border-radius: 20px;margin: 10px;border-style: none;box-shadow: 1px 1px 8px rgb(55,55,55);padding: 8px 60px;font-size: 20px;background: rgb(253,126,20);">確認送出 😊</button></div>
        </div>
    </section>
    <footer></footer>
    <script src="{{ asset("mockup-assets/ecamp/bootstrap/js/bootstrap.min.js") }}"></script>
    <script src="{{ asset("mockup-assets/ecamp/js/bs-init.js") }}"></script>
    <script src="{{ asset("mockup-assets/ecamp/js/startup-modern.js") }}"></script>
</body>

</html>