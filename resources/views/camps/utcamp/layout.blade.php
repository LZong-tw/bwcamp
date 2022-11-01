<html lang='zh-Hant'>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name='description' content='「教育是人類升沈的樞紐」邀請您報名參加教師生命成長營。' />
    <meta name='author' content='福智文教基金會'>
    <meta property='og:url' content='http://bwfoce.org/2023utcamp/'/>
    <meta property='og:title' content='{{ $camp_data->abbreviation }}'/>
    <meta property='og:description' content='「教育是人類升沈的樞紐」邀請您報名參加教師生命成長營。' />
    <meta property='og:image' content='http://bwfoce.org/2023utcamp/'/>
    {{-- <link rel='icon' href='/camp/favicon.ico'> --}}
    <title> {{ $camp_data->fullName }} </title>
    <!-- Bootstrap core CSS -->
    <script src='{{ mix('js/app.js') }}'></script>
    <link href='{{ asset('css/app.css') }}' rel='stylesheet'>
    {{-- <link href='{{ asset('css/bootstrap.min.css') }}' rel='stylesheet'> --}}
    <!-- Custom styles for this template -->
    <link href='{{ asset('css/camp.css') }}' rel='stylesheet'>
    <!-- jQuery library-->
    {{-- <script src='{{ asset('js/jquery-3.5.1.min.js') }}'></script> --}}
    <script src="{{ asset('js/popper_1.12.9.min.js') }}"></script>
    <!-- Bootstrap core JS -->
    {{-- <script src='{{ asset('js/bootstrap.bundle.min.js') }}'></script> --}}
    <!-- Bootstrap confirmation JS -->
    <script src='{{ asset('js/bootstrap-confirmation.min.js') }}'></script>
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
                        <a class="nav-link" href="https://bwfoce.org/2023utcamp">營隊資訊</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('registration', $batch_id) }}">報名表單</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('query', $batch_id) }}">報名查詢/修改</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('payment', $batch_id) }}">線上繳費</a>
                    </li> --}}
                    @if(\Carbon\Carbon::now() >= \Carbon\Carbon::createFromFormat("Y-m-d", $camp_data->admission_announcing_date))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("queryadmitGET", $batch_id) }}">錄取查詢</a>
                        </li>
                    @endif
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ url("downloads/utcamp2023/2022第30屆教師生命成長營大專教職員梯-活動簡章_含課表_R5.pdf") }}">課程表</a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url("downloads/utcamp2023/2023第31屆教師生命成長營大專教職員梯-活動簡章_含課表紙本報名表_R2.docx") }}">報名簡章(含課表)下載</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endif
<div class="container w-full">
    @yield('content')
    <!-- Site footer -->
    <footer class='footer'>
        <p class=text-center>&copy; 財團法人福智文教基金會</p>
    </footer>
</div>
</body></html>
