
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ config('app.name', 'Laravel') }}@if(isset($campFullData)) - {{ $campFullData->abbreviation }}@endif</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/backend.css') }}">
    <link rel="stylesheet" href="{{ asset('css/media.css') }}">

    <!-- Font Awesome JS -->
    <script defer src="{{ asset('js/solid.js') }}"></script>
    <script defer src="{{ asset('js/fontawesome.js') }}"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type='text/javascript'>
        google.charts.load('current', {'packages':['corechart','table'], 'language':'zh_TW'});
    </script>
    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
    <!-- Popper.JS -->
    <script src="{{ asset('js/popper_1.14.0.min.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/livewire/livewire.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script
    src="https://js.sentry-cdn.com/f6b44d1c46934ae4876d372cf7b5863d.min.js"
    crossorigin="anonymous"
    ></script>
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
    {!! \Sentry\Laravel\Integration::sentryTracingMeta() !!}
    <script type="text/javascript">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "ne7yzyapsk");
    </script>
    <style>
        .card-link{
            color: #3F86FB!important;
        }
        .card-link:hover{
            color: #33B2FF!important;
        }
    </style>
</head>
<body>
    @include('backend.panel')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $(this).toggleClass('active');
            });
        });
        function toggleAll(){
            if($(".collapse.list-unstyled").hasClass("show")){
                $(".collapse.list-unstyled").removeClass("show");
            }
            else{
                console.log("YES");
                $(".collapse.list-unstyled").addClass("show");
            }
        }
    </script>
</body>

</html>
