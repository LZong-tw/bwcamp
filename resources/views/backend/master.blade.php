
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ env("APP_NAME") }}</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="{{ asset("css/backend.css") }}">

    <!-- Font Awesome JS -->
    <script defer src="{{ asset('js/solid.js') }}"></script>
    <script defer src="{{ asset('js/fontawesome.js') }}"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type='text/javascript'>
        google.charts.load('current', {'packages':['corechart','table'], 'language':'zh_TW'});
    </script>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar Holder -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>{{ env("APP_NAME") }}</h3>
            </div>
            <ul class="list-unstyled components">
                <p>功能列表</p>
                @if(isset($campFullData))
                    <li>
                        <a href="#pageSubmenu3" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">統計資料</a>
                        <ul class="collapse list-unstyled" id="pageSubmenu3">
                            <li>
                                <a href="{{ route("appliedDateStat", $campFullData->id) }}">報名日期</a>
                            </li>
                            <li>
                                <a href="{{ route("genderStat", $campFullData->id) }}">性別比率</a>
                            </li>
                            <li>
                                <a href="{{ route("countyStat", $campFullData->id) }}">區域縣市</a>
                            </li>
                            <li>
                                <a href="{{ route("birthyearStat", $campFullData->id) }}">出生年次</a>
                            </li>
                            @if($campFullData->table == "ycamp")
                                <li>
                                    <a href="#">就讀學程</a>
                                </li>
                                <li>
                                    <a href="#">管道統計</a>
                                </li>
                                <li>
                                    <a href="#">國籍</a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route("batchesStat", $campFullData->id) }}">報名梯次</a>
                            </li>
                            <li>
                                <a href="{{ route("admissionStat", $campFullData->id) }}">錄取統計</a>
                            </li>
                            @if($campFullData->table == "tcamp")
                                <li>
                                    <a href="{{ route("schoolOrCourseStat", $campFullData->id) }}">任教學程</a>
                                </li>
                                <li>
                                    ---開 發 中---
                                </li>
                                <li>
                                    <a href="#">有無教師證</a>
                                </li>
                                <li>
                                    <a href="#">職稱</a>
                                </li>
                                <li>
                                    <a href="#">服務縣市</a>
                                </li>
                                <li>
                                    <a href="#">參加意願</a>
                                </li>
                                <li>
                                    ---開 發 中---
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li class="active">
                        <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">錄取相關</a>
                        <ul class="collapse list-unstyled" id="homeSubmenu">
                            <li>
                                <a href="{{ route("admission", $campFullData->id) }}">單一錄取<br>查詢報名資料</a>
                            </li>
                            <li>
                                <a href="{{ route("batchAdmission", $campFullData->id) }}">批次錄取</a>
                            </li>
                            <li>
                                <a href="{{ route("showGroupList", $campFullData->id) }}">組別名單查詢/下載<br>寄送錄取通知信</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">報名相關</a>
                        <ul class="collapse list-unstyled" id="pageSubmenu2">
                            <li>
                                <a href="{{ route("showRegistration", $campFullData->id) }}">報名</a>
                            </li>
                            <li>
                                <a href="{{ route("showRegistrationList", $campFullData->id)}}">查詢及下載</a>
                            </li>
                            <li>
                                <a href="{{ route("changeBatchOrRegion", $campFullData->id) }}">修改梯次 / 區域</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">正行相關</a>
                        <ul class="collapse list-unstyled" id="pageSubmenu">
                            <li>
                                <a href="#">回覆參加名單</a>
                            </li>
                            <li>
                                <a href="#">交通名單</a>
                            </li>
                            <li>
                                <a href="#">輔導組表格</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route("accounting", $campFullData->id) }}">銷帳資料</a>
                    </li>
                @else
                    <li class="active">
                        <a class="" href="">未選擇營隊</a>
                    </li>
                @endif
            </ul>
            
            <ul class="list-unstyled CTAs">
                <li>
                    <a href="{{ route("home") }}">{{ Auth::user()->name }}</a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{-- {{ __('Logout') }} --}}
                        登出
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>

            {{-- <ul class="list-unstyled CTAs">
                <li>
                    <a href="https://bootstrapious.com/tutorial/files/sidebar.zip" class="download">Download source</a>
                </li>
                <li>
                    <a href="https://bootstrapious.com/p/bootstrap-sidebar" class="article">Back to article</a>
                </li>
            </ul> --}}
        </nav>

        <!-- Page Content Holder -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="navbar-btn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('backendIndex') }}">選擇營隊</a>
                            </li>
                            @if(isset($campFullData))
                                <li class="nav-item active">
                                    <a class="nav-link" href="">{{ $campFullData->fullName }}</a>
                                </li>
                            @else
                                <li class="nav-item active">
                                    <a class="nav-link" href="">未選擇營隊</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
            @yield('content')
        </div>
    </div>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
    <!-- Popper.JS -->
    <script src="{{ asset('js/popper_1.14.0.min.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $(this).toggleClass('active');
            });
        });
    </script>
</body>

</html>