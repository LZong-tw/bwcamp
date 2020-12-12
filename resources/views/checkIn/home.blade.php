@extends('checkIn.master')
@section('content')
<style>
    .text-center {
        text-align: center;
    }
</style>
<div class="container">
    <h3 class="mt-5 text-center">福智營隊報到系統</h3>
    <h5 class="text-center">當前報到營隊：{{ $camp->fullName }}<br>報到日期：{{ \Carbon\Carbon::today()->format('Y-m-d') }}</h5>
    <form action="/checkin/query" id="query">
        <div class="form-group input-group">
            <input type="text" class="form-control" name="query_str" id="" placeholder="請輸入報名序號、組別、錄取編號、姓名、電話查詢 ..." value="{{ old("query_str") }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit" id="checkinsearch">
                    Go <i class="fa fa-search"></i>
                </button>
            </div>
            <a href="javascript: toggleCamera();" id="qr-switch" class="btn btn-success ml-1">使用 QR Code</a>
        </div>
    </form>
    @if($errors->any())
        @foreach ($errors->all() as $message)
            <div class='alert alert-danger' role='alert'>
                {{ $message }}
            </div>
        @endforeach
    @endif
    @if(\Session::has('message'))
        <div class='alert alert-success' role='alert'>
            {{ \Session::get('message') }}
        </div>
    @endif
    @if(isset($applicants) && $applicants->count() > 0)
        <table class="table table-bordered text-break">
            <tr class="table-active">
                <th style="width: 20%">組別</th>
                <th style="width: 20%">編號</th>
                <th style="width: 20%">姓名</th>
                <th style="width: 20%">手機</th>
                <th style="width: 15%">本日報到</th>
            </tr>
            @foreach ($applicants as $applicant)
                <tr id="{{ $applicant->id }}">
                    <td>{{ $applicant->group }}</td>
                    <td>{{ $applicant->number }}</td>
                    <td>{{ $applicant->name }}</td>
                    <td>{{ $applicant->mobile }}</td>
                    <td>
                        @php
                            $yes = 0;   
                        @endphp
                        @foreach($applicant->checkInData as $checkInData)
                            @if($checkInData->check_in_date == \Carbon\Carbon::today()->format('Y-m-d'))
                                @php
                                    $yes = 1;   
                                @endphp
                            @endif
                        @endforeach
                        @if($yes)
                            <form action="/checkin/un-checkin" method="POST" class="d-inline" name="uncheckIn{{ $applicant->id }}">
                                @csrf
                                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                                <input type="hidden" name="check_in_date" value="{{ $checkInData->check_in_date }} ">
                                <input type="hidden" name="query_str" value="{{ old("query_str") }}">
                                <input type="submit" value="取消" onclick="this.value = '取消中'; this.disabled = true; document.uncheckIn{{ $applicant->id }}.submit();" class="btn btn-danger">
                            </form> 
                        @else
                            <form action="/checkin/checkin" method="POST" name="checkIn{{ $applicant->id }}">
                                @csrf
                                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                                <input type="hidden" name="query_str" value="{{ old("query_str") }}">
                                <input type="submit" value="報到" onclick="this.value = '報到中'; this.disabled = true; document.checkIn{{ $applicant->id }}.submit();" class="btn btn-success" id="btn{{ $applicant->id }}">
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    @elseif(isset($applicants) && $applicants->count() == 0)
        <div class="alert alert-danger">查無資料。</div>
    @endif
</div>  
<script>
    @if(isset($applicants))
        (function() {
            @foreach ($applicants as $applicant)
                @foreach($applicant->checkInData as $checkInData)
                    @if($checkInData->check_in_date == \Carbon\Carbon::today()->format('Y-m-d'))
                        document.getElementById("{{ $applicant->id }}").classList.add("table-success");
                    @endif
                @endforeach
            @endforeach
        })();
    @endif
</script>
<script type="text/javascript">
    let scanner;

    function toggleCamera(){
        let element = '<center><video id="scanner"></video><br><a href="javascript: toggleForm();" id="form-switch" class="btn btn-primary mb-2">傳統表格</a></center>';
        document.getElementById("query").innerHTML = element;
        setCamera();
        document.getElementById("form-switch").classList.remove('d-none');
        document.getElementById("qr-switch").classList.add('d-none');
    }

    function toggleForm(){
        scanner.stop()
        delete Instascan.Scanner;
        let element = '<div class="form-group input-group"><input type="text" class="form-control" name="query_str" id="" placeholder="請輸入報名序號、組別、錄取編號、姓名、電話查詢 ..." value="{{ old("query_str") }}"><div class="input-group-append"><button class="btn btn-outline-secondary" type="submit" id="checkinsearch"><i class="fa fa-search"></i></button></div><a href="javascript: toggleCamera();" id="qr-switch" class="btn btn-success ml-1">使用 QR Code</a></div>';
        document.getElementById("query").innerHTML = element;
        document.getElementById("qr-switch").classList.remove('d-none');
        document.getElementById("form-switch").classList.add('d-none');
    }

    function setCamera(){
        scanner = new Instascan.Scanner({
            video: document.getElementById('scanner')
        });
        {{-- 開啟一個新的掃描
             宣告變數scanner，在html<video>標籤id為scanner的地方開啟相機預覽。
             Notice:這邊注意一定要用<video>的標籤才能使用，詳情請看他的github API的部分解釋。--}}

        scanner.addListener('scan', function(content) {
            console.log(content);
        });
        {{-- 開始偵聽掃描事件，若有偵聽到印出內容。 --}}

        Instascan.Camera.getCameras().then(function(cameras) {
        {{-- 取得設備的相機數目 --}}
            if (cameras.length > 0) {
                {{-- 若設備相機數目大於0 則先開啟第0個相機(程式的世界是從第零個開始的) --}}
                scanner.start(cameras[0]);
            } else {
                {{-- 若設備沒有相機數量則顯示"No cameras found"; --}}
                {{-- 這裡自行判斷要寫什麼 --}}
                console.error('No cameras found.');
            }
        }).catch(function(e) {
            console.error(e);
        });
    }    

    function checkInAjax(){
        // https://hsiangfeng.github.io/javascript/20190627/3176878235/
        var url = 'https://soweb.kcg.gov.tw/open1999/ServiceRequestsQuery.asmx/ServiceRequestsQuery?startdate=&enddate=';
        fetch(url).then((respons) => {
            return respons.json(); //取的資料後將資料傳給下一個 then
        }).then((data) => {
            
        }).catch((error) => { // 當初出現錯誤時跑 catch
            console.log(error);
        })
    }
</script>
@endsection