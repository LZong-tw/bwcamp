@extends('checkIn.master')
@section('content')
<div class="container">
    <h2 class="mt-4 text-center">福智營隊報到系統 - 各區即時報到資料</h2>
    <h5 class="text-center">當前報到營隊：{{ $camp->fullName }}<br>報到日期：{{ \Carbon\Carbon::today()->format('Y-m-d') }}</h5>       
    <a href="#" onclick="this.innerText = '更新中...'; location.reload();">更新資料</a>
    @foreach ($batchArray as $batch)
        @if(!$batch)
            @continue
        @endif
        <h3>梯次：{{ $batch['name'] }}</h3>
        <h4>今日累積報到人數 / 未報到人數：{{ $batch['checkedInApplicants'] }} / {{ $batch['allApplicants'] - $batch['checkedInApplicants'] }}</h4>
        @if(!$loop->last)
            <hr>
        @endif
    @endforeach
</div>  
@endsection