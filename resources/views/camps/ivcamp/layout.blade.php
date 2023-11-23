<html lang='zh-Hant'>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name='description' content='向生命更深的維度啟航，成為帶給世界溫暖與希望的人。' />
    <meta name='author' content='福智文教基金會'>
    <meta property='og:url' content='http://bwfoce.org/ycamp/'/>
    <meta property='og:title' content='{{ $camp_data->abbreviation }}'/>
    <meta property='og:description' content='向生命更深的維度啟航，成為帶給世界溫暖與希望的人。' />
    {{-- <meta property='og:image' content='http://youth.blisswisdom.org/camp/media/2016Wbanner.png'/> --}}
    {{-- <link rel='icon' href='/camp/favicon.ico'> --}}
    <title> {{ $camp_data->fullName }} </title>
    <!-- Bootstrap core CSS -->
    <link href='{{ asset('css/bootstrap.min.css') }}' rel='stylesheet'>
    <!-- Custom styles for this template -->
    <link href='{{ asset('css/camp.css') }}' rel='stylesheet'>
    <!-- jQuery library-->
    <script src='{{ asset('js/jquery-3.5.1.min.js') }}'></script>
    <script src="{{ asset('js/popper.2.11.6.min.js') }}"></script>
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
                        <a class="nav-link" href="{{ $camp_data->site_url }}">營隊資訊</a>
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
                    {{--
                    <li class="nav-item">
                        <a class="nav-link" href="#">課程表</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">報名簡章下載</a>
                    </li>
                    --}}
                </ul>
            </div>
        </div>
    </nav>
@endif
<div class=container>
    @yield('content')
    <!-- Site footer -->
    <footer class='footer'>
        <p class=text-center>&copy; 財團法人福智文教基金會</p>
    </footer>
</div>
</body></html>
