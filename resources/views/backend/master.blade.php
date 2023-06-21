
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
