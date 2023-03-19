<div class="wrapper">
    <!-- Sidebar Holder -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>{{ config('app.name', 'Laravel') }}</h3>
        </div>
        <ul class="list-unstyled components">
            <p>功能列表 <a href="javascript: toggleAll();"><small>全部收合/展開</small></a></p>
            @if(isset($campFullData))
                @if(!str_contains($campFullData->table, "vcamp"))
                    <li>
                        <a href="#integratedOperatingInterface" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">綜合操作介面</a>
                        <ul class="collapse list-unstyled show" id="integratedOperatingInterface">
                            <li>
                                @if(str_contains($campFullData->table, "vcamp"))
                                    <a href="{{ route("showLearners", $vcamp_counterpart->mainCamp->id) }}">學員名單</a>
                                @else
                                    <a href="{{ route("showLearners", $campFullData->id) }}">學員名單</a>
                                @endif
                            </li>
                            <li>
                                @if(str_contains($campFullData->table, "vcamp"))
                                    <a href="{{ route("showVolunteers", $vcamp_counterpart->mainCamp->id) }}">義工名單</a>
                                @else
                                    <a href="{{ route("showVolunteers", $campFullData->id) }}">義工名單</a>
                                @endif
                            </li>
                        </ul>
                    </li>
                @endif
                <li>
                    <a href="#pageSubmenu3" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">統計資料</a>
                    <ul class="collapse list-unstyled show" id="pageSubmenu3">
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
                            <a href="{{ route("birthyearStat", $campFullData->id) }}">出生年次 / 歲數</a>
                        </li>
                        @if($campFullData->table == "ycamp")
                            <li>
                                <a href="{{ route("wayStat", $campFullData->id) }}">管道統計</a>
                            </li>
                            <li>
                                <a href="#">國籍(開發中)</a>
                            </li>
                        @endif
                        @if($campFullData->table == "ycamp" || $campFullData->table == "hcamp")
                            <li>
                                <a href="{{ route("educationStat", $campFullData->id) }}">就讀學程</a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route("batchesStat", $campFullData->id) }}">報名梯次</a>
                        </li>
                        <li>
                            <a href="{{ route("admissionStat", $campFullData->id) }}">錄取統計</a>
                        </li>
                        <li>
                            <a href="{{ route("checkinStat", $campFullData->id) }}">報到統計</a>
                        </li>
                        @if($campFullData->table == "ycamp")
                            <li>
                                <a href="{{ route("bwclubschoolStat", $campFullData->id) }}">福青社學校統計</a>
                            </li>
                        @endif
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
                <li>
                    <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">報名相關</a>
                    <ul class="collapse list-unstyled show" id="pageSubmenu2">
                        <li>
                            <a href="{{ route("showRegistration", $campFullData->id) }}">報名</a>
                        </li>
                        <li>
                            <a href="{{ route("showRegistrationList", $campFullData->id)}}">檢視及下載</a>
                        </li>
                        <li>
                            <a href="{{ route("changeBatchOrRegionGET", $campFullData->id) }}">修改梯次 / 區域</a>
                        </li>
                    </ul>
                </li>
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">錄取相關</a>
                    <ul class="collapse list-unstyled show" id="homeSubmenu">
                        <li>
                            <a href="{{ route("admissionGET", $campFullData->id) }}">單一錄取<br>查詢報名資料</a>
                        </li>
                        <li>
                            <a href="{{ route("batchAdmissionGET", $campFullData->id) }}">批次錄取</a>
                        </li>
                        <li>
                            <a href="{{ route("showGroupList", $campFullData->id) }}">組別名單查詢/下載<br>寄送錄取通知信<br>寄送報到通知信</a>
                        </li>
                        <li>
                            <a href="{{ route("showNotAdmitted", $campFullData->id) }}" class="text-warning">寄送未錄取通知信</a>
                        </li>
                        <li>
                            <a href="{{ route("modifyAccountingGET", $campFullData->id) }}">修改繳費資料</a>
                        </li>
                        <li>
                            <a href="{{ route("modifyAccountingGET", $campFullData->id) }}">設定取消參加</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">正行相關</a>
                    <ul class="collapse list-unstyled show" id="pageSubmenu">
                    @if($campFullData->vcamp)
                        <li>
                            <a href="{{route('showVolunteerPhoto', $campFullData->id) }}">義工名冊</a>
                        </li>
                    @endif
                        <li>
                            <a href="{{route('showGroupAttendList', $campFullData->id) }}">回覆參加名單</a>
                        </li>
                        <li>
                            <a href="{{route('showTrafficList', $campFullData->id) }}">交通名單</a>
                        </li>
                        <li>
                            <a href="#">輔導組表格</a>
                        </li>
                        <li>
                            <a href="{{ route('sign_back', $campFullData->id) }}">設定簽到退時間</a>
                        </li>
                        <li>
                            <a href="{{ route("modifyAccountingGET", $campFullData->id) }}">現場手動繳費<br>修改繳費資料</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#other" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">其　　他</a>
                    <ul class="collapse list-unstyled show" id="other">
                        <li>
                            <a href="{{ route("accounting", $campFullData->id) }}">銷帳資料</a>
                        </li>
                        <li>
                            <a href="{{ route("customMail", $campFullData->id) }}">寄送自定郵件</a>
                        </li>
                    </ul>
                </li>
                @if(!str_contains($campFullData->table, "vcamp"))
                    <li>
                        <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">營隊資訊</a>
                        <ul class="collapse list-unstyled show" id="pageSubmenu">
                            <li>
    {{--                                <a href="{{ route("roles", $campFullData->id) }}">職務表</a>--}}
                            </li>
                        </ul>
                        <ul class="collapse list-unstyled show" id="pageSubmenu">
                            <li>
    {{--                                <a href="{{ route("permissionScopes", $campFullData->id) }}">職務適用範圍</a>--}}
                            </li>
                        </ul>
                        <ul class="collapse list-unstyled show" id="pageSubmenu">
                            <li>
                                <a href="{{ route("laratrustCustom.roles-assignment.index", $campFullData->id) }}" target="_blank">職務設定</a>
                            </li>
                        </ul>
                    </li>
                @endif
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
                @if(isset($campFullData) && auth()->user()->getPermission(true, $campFullData->id))
                    <a href="">權限：{{ auth()->user()->getPermission(true, $campFullData->id)->name }}</a>
                    @if(auth()->user()->getPermission(true, $campFullData->id)->level > 2)
                        <a href="">地區：{{ auth()->user()->getPermission(true, $campFullData->id)->region }}</a>
                    @endif
                @else
                    <a href="">權限：{{ auth()->user()->getPermission(false)->name }}</a>
                @endif
            </li>
            @if(auth()->user()->getPermission()->level == 1)
                <li>
                    <a href="{{ route("userlist", $campFullData->id ?? "") }}">使用者列表</a>
                </li>
                <li>
                    <a href="{{ route("rolelist", $campFullData->id ?? "") }}">權限列表</a>
                </li>
            @endif
            <li>
                <a href="{{ route("jobs", $campFullData->id ?? "") }}">任務佇列</a>
            </li>
            @if(auth()->user()->getPermission()->level == 1)
                <li>
                    <a href="{{ route("logs", $campFullData->id ?? "0") }}" target="_blank">系統日誌</a>
                </li>
            @endif
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{-- {{ __('Logout') }} --}}
                    登出
                </a>
                <form id="logout-form" action="{{ route('logout') }}?rstr{{ \Str::random() }}={{ \Str::random() }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="rstr{{ \Str::random() }}" value="{{ \Str::random() }}">
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
                        @if(isset($campFullData))
                            @php
                                $vcamp_counterpart = \App\Models\Vcamp::find($campFullData->id);
                            @endphp
                            @if(!str_contains($campFullData->table, "vcamp") && $campFullData->vcamp)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route("campIndex", $campFullData->vcamp->id) }}">義工資料統計及操作</a>
                                </li>
                            @elseif($vcamp_counterpart->mainCamp)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route("campIndex", $vcamp_counterpart->mainCamp->id) }}">回主營隊</a>
                                </li>
                            @endif
                        @endif
                        <li class="nav-item">
                            <a class="nav-link card-link" href="/checkin?t={{ time() }}" target="_blank">報到系統</a>
                        </li>
                        @if(auth()->user()->getPermission()->level == 1)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route("campManagement") }}" target="_blank">營隊管理</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("backendIndex") }}">選擇營隊</a>
                        </li>
                        @if(isset($campFullData))
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ route("campIndex", $campFullData->id) }}">{{ $campFullData->fullName }}：後台首頁</a>
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
