@extends('checkIn.master')
@section('content')
<div class="container">
    <h2 class="mt-4 text-center">福智營隊報到系統 - 今日各梯次即時報到資料</h2>
    <h5 class="text-center">當前報到營隊：{{ $camp->fullName }}<br>報到日期：{{ \Carbon\Carbon::today()->format('Y-m-d') }}</h5>       
    <h6 class="text-center">每 20 秒自動更新資料 &nbsp;<a href="#" onclick="this.innerText = '更新中...'; location.reload();">手動更新資料</a></h6>
    <div id="data">
        @foreach ($batchArray as $batch)
            @if(!$batch)
                @continue
            @endif
            <h3>梯次：{{ $batch['name'] }}</h3>
            <h4>累積報到人數 / 未報到人數：{{ $batch['checkedInApplicants'] }} / {{ $batch['allApplicants'] - $batch['checkedInApplicants'] }}</h4>
            @if(!$loop->last)
                <hr>
            @endif
        @endforeach
    </div>
</div>  
<script>
    httpRequest = new XMLHttpRequest();
    httpRequest.onreadystatechange = function(){
        // Process the server response here.
        if (httpRequest.status === 200) {
            // Perfect!
            console.log(httpRequest.responseText);
        } else {
            // There was a problem with the request.
            // For example, the response may have a 404 (Not Found)
            // or 500 (Internal Server Error) response code.
        }
    };
    httpRequest.open('GET', '{{ url("") }}/checkin/realtimeStat', true);
    httpRequest.send();
</script>
@endsection