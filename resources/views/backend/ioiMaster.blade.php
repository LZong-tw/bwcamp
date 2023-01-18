
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ config('app.name', 'Laravel') }}</title>

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
    <link rel="stylesheet" href="{{ asset('bootstrap-table/bootstrap-table.min.css') }}">
    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script defer src="{{ asset('bootstrap-table/bootstrap-table.min.js') }}"></script>
    <script defer src="{{ asset('bootstrap-table/locale/bootstrap-table-zh-TW.min.js') }}"></script>
    <style>
        .card-link{
            color: #3F86FB!important;
        }
        .card-link:hover{
            color: #33B2FF!important;
        }
        li > a {
            color: white;
        }
    </style>
</head>
<body>
    @include('backend.panel')
    <script type="text/javascript">
        (function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $(this).toggleClass('active');
            });

            $('#sidebarCollapse').toggleClass('active');
            $('#sidebar').toggleClass('active');
        })();
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
    </script>
</body>

</html>
