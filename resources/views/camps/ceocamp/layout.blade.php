<html lang='zh-Hant'>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name='description' content='邀請您推薦報名參加菁英營。' />
    <meta name='author' content='福智文教基金會'>
    <meta property='og:url' content='http://bwfoce.org/ceocamp'/>
    <meta property='og:title' content='{{ $camp_data->abbreviation }}'/>
    <meta property='og:description' content='邀請您推薦報名參加菁英營。' />
    <meta property="og:image" content="https://static.wixstatic.com/media/53b3d5_0cdf79a7c81a422ea7f9fd51467a4c05~mv2.jpg/v1/fill/w_2500,h_1406,al_c/53b3d5_0cdf79a7c81a422ea7f9fd51467a4c05~mv2.jpg"/>
    <meta property="og:image:width" content="2500"/>
    <meta property="og:image:height" content="1406"/>
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
    <script
        src="https://browser.sentry-cdn.com/8.20.0/bundle.tracing.replay.min.js"
        integrity="sha384-e4DRKCQjGj8HoVTcv07HyAm3g1wDECvRclj9gsw2d06z1aLh+78iJ21phn6RhkJD"
        crossorigin="anonymous"
        ></script>
    <script>
        Sentry.onLoad(function() {
            Sentry.init({
            integrations: [
                Sentry.replayIntegration({
                maskAllText: false,
                blockAllMedia: false,
                }),
            ],
            // Session Replay
            replaysSessionSampleRate: 0.5, // This sets the sample rate at 10%. You may want to change it to 100% while in development and then sample at a lower rate in production.
            replaysOnErrorSampleRate: 1.0, // If you're not already sampling the entire session, change the sample rate to 100% when sampling sessions where errors occur.
            });
        });
    </script>
    <script type="text/javascript">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "ne7yzyapsk");
    </script>
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
                    <!--
                    <li class="nav-item">
                        <a class="nav-link" href="http://bwfoce.org/ceocamp">營隊資訊</a>
                    </li>
                    -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('registration', $batch_id) }}">報名表單</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('query', $batch_id) }}">報名查詢/修改</a>
                    </li>
                    {{-- @if(\Carbon\Carbon::now() >= \Carbon\Carbon::createFromFormat("Y-m-d", $camp_data->admission_announcing_date))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("queryadmitGET", $batch_id) }}">錄取查詢</a>
                        </li>
                    @endif --}}
                    <!--
                    <li class="nav-item">
                    <a class="nav-link" href="https://bwfoceec.wixsite.com/ecamp/課程介紹">課程介紹</a>
                    </li>
                    -->
                    @if ((isset($batch) && str_contains($batch->name, "開南")) || (str_contains(\App\Models\Batch::find($batch_id)->name, "開南")))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url("downloads/ceocamp2024/2024菁英營學員推薦表_開南.docx") }}">學員推薦表WORD檔下載</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url("downloads/ceocamp2024/2024菁英營學員推薦表_開南.pdf") }}">學員推薦表PDF檔下載</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url("downloads/ceocamp2024/2024菁英營學員推薦表_勤益.docx") }}">學員推薦表WORD檔下載</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url("downloads/ceocamp2024/2024菁英營學員推薦表_勤益.pdf") }}">學員推薦表PDF檔下載</a>
                        </li>
                    @endif
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
