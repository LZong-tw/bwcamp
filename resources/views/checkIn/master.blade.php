
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">    

    <!-- Font Awesome JS -->
    <script defer src="{{ asset('js/solid.js') }}"></script>
    <script defer src="{{ asset('js/fontawesome.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/instascan-ioslized.min.js') }}"></script>
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
</head>
<body>
    @yield('content')
    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
    <!-- Popper.JS -->
    <script src="{{ asset('js/popper_1.14.0.min.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>