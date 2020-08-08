<html lang='zh-Hant'>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name='description' content='擁抱世界，成為帶給世界溫暖與希望的人。邀請你報名參加大專青年生命成長營，1/16-19@大仁科技大學。' />
    <meta name='author' content='福智文教基金會'>
    {{-- <link rel='icon' href='/camp/favicon.ico'> --}}

    <title> 2020第52屆大專青年生命成長營 </title>

    <!-- Bootstrap core CSS -->
    <link href='{{ asset('css/bootstrap.min.css') }}' rel='stylesheet'>

    <!-- Custom styles for this template -->
    <link href='{{ asset('css/camp.css') }}' rel='stylesheet'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src='https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js'></script>
    <script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
    <![endif]-->

    <!-- jQuery library-->
    <script src='{{ asset('js/jquery-3.5.1.min.js') }}'></script>

    <!-- Bootstrap core JS -->
    <script src='{{ asset('js/bootstrap.min.js') }}'></script>
    <!-- Bootstrap confirmation JS -->
    <script src='{{ asset('js/bootstrap-confirmation.min.js') }}'></script>
    <script src='{{ asset('js/bootstrap-validate.js') }}'></script>

    <meta property='og:url' content='http://youth.blisswisdom.org/camp/winter/'/>
    <meta property='og:title' content='大專青年生命成長營'/>
    <meta property='og:description' content='擁抱世界，成為帶給世界溫暖與希望的人。邀請你報名參加大專青年生命成長營，1/16-19@大仁科技大學。' />
    <meta property='og:image' content='http://youth.blisswisdom.org/camp/media/2016Wbanner.png'/>
</head>

<BODY>


<div id='fb-root'></div>

<nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #ebfbeb;">
    <div class="container">
        <a class='navbar-brand' href=''>大專青年生命成長營</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                {{-- <li class="nav-item active"> --}}
                {{-- <span class="sr-only">(current)</span> --}}
                <li class="nav-item">
                    <a class="nav-link" href="#">營隊資訊</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">報名表單</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">報名查詢</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">課程表</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">報名簡章下載</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- <nav class='navbar fixed-top navbar-light bg-light'>
    <div class='container'>
        <div class='navbar-header'>
        <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>
            <span class='sr-only'>行動選單</span>
            <span class='icon-bar'></span>
            <span class='icon-bar'></span>
            <span class='icon-bar'></span>
        </button>
        <a class='navbar-brand' href='{{ app('request')->input('batch_id') }}'>大專青年生命成長營</a>
        </div>


        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Dropdown
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="#">Action</a>
                  <a class="dropdown-item" href="#">Another action</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Something else here</a>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
              </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
              <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
          </div>


        <div id='navbar' class='collapse navbar-collapse'>
        <ul class='nav navbar-nav'>
            <li><a href='/camp/winter'>營隊資訊</a></li>
            <li><a href='/camp/winter'> 報名結束 </a></li>
            <li><a href='/camp/winter/query/'>報名查詢</a></li>
            <li><a href='/camp/winter/curriculum'>課程表</a></li>
            <li><a href='/camp/winter/download/W2020intro.pdf' target=_blank>報名簡章下載</a></li>
        <!--
            <li><a href='/camp/winteren'>English Version</a></li>
        -->
        </ul>
        <!--
        <p class='navbar-text navbar-right'><a href='/camp/winteren' class='navbar-link'>English Version</a></p>
        -->
        </div><!-- /.nav-collapse -->
    </div><!-- /.container -->
</nav><!-- /.navbar --> --}}
    @yield('content')
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src='{{ asset('js/bootstrap.min.js') }}'></script>

<!-- Site footer -->
<footer class='footer'>
    <p class=text-center>&copy; 財團法人福智文教基金會 <br> 福智青年聯誼會</p>
</footer>

</div>
</body></html>
