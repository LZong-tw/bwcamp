<html lang='zh-Hant'>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name='description' content='「光點無距愛無懼」邀請您報名參加卓越青年生命探索營。' />
    <meta name='author' content='福智文教基金會'>
    <meta property='og:url' content='http://www.youngone.org.tw/camp/'/>
    <meta property='og:title' content='{{ $camp_data->abbreviation }}'/>
    <meta property='og:description' content='「光點無距愛無懼」邀請您報名參加卓越青年生命探索營。' />
    <meta property='og:image' content='http://www.youngone.org.tw/camp/img/header/slide1.png'/>
    {{-- <link rel='icon' href='/camp/favicon.ico'> --}}
    <title> {{ $camp_data->fullName }} </title>
    <!-- Bootstrap core CSS -->
    <link href='{{ asset('css/bootstrap.min.css') }}' rel='stylesheet'>
    <!-- Custom styles for this template -->
    <link href='{{ asset('css/camp.css') }}' rel='stylesheet'>
    <!-- jQuery library-->
    <script src='{{ asset('js/jquery-3.5.1.min.js') }}'></script>
    <script src="{{ asset('js/popper_1.12.9.min.js') }}"></script>
    <!-- Bootstrap core JS -->
    <script src='{{ asset('js/bootstrap.bundle.min.js') }}'></script>
    <!-- Bootstrap confirmation JS -->
    <script src='{{ asset('js/bootstrap-confirmation.min.js') }}'></script>
    <script src='{{ asset('js/bootstrap-validate.js') }}'></script>
</head>

<BODY>
<div id='fb-root'></div>
@if(isset($isBackend))
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #f8d7da;">
        <div class="container" style="color: #721c24">
            {{ $isBackend }}
        </div>
    </nav>
@else
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #ebfbeb;">
        <div class="container">
            <a class='navbar-brand' href=''>{{ $camp_data->abbreviation }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    {{-- <li class="nav-item active"> --}}
                    {{-- <span class="sr-only">(current)</span> --}}
                    <li class="nav-item">
                        <a class="nav-link" href="http://www.youngone.org.tw/camp/">營隊資訊</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('registration', $batch_id) }}">報名表單</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('query', $batch_id) }}">報名查詢/修改</a>
                    </li>
                    @if(\Carbon\Carbon::now() >= \Carbon\Carbon::createFromFormat("Y-m-d", $camp_data->admission_announcing_date))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("queryadmitGET", $batch_id) }}">錄取查詢</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="http://www.youngone.org.tw/camp/">課程表</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url("downloads/acamp2023/2023卓越青年生命探索營報名簡章20230408R4.pdf") }}">報名簡章下載</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endif
<div class=container>
    @yield('content')
    <!-- Site footer -->
    <footer class='footer'>
        <p class="text-center">洽詢電話：<br>
        北區(含宜蘭、花蓮)：02-775-16788#610301、#613091、#610408<br>
        基隆：02-242-31289#15<br>
        桃區：03-275-6133#1305<br>
        竹區：03-532-5566<br>
        中區：04-370-69300#620202<br>
        雲嘉：05-283-3940#203<br>
        台南：06-264-6831#351<br>
        高屏(含台東)：07-974-3280#68104<br>
        </p>
        <p class="text-center">洽詢時間：<br>週一至週五10:00~20:00、週六10:00~16:00</p>
        <p class="text-center">&copy; 財團法人福智文教基金會</p>
    </footer>
</div>
</body></html>
