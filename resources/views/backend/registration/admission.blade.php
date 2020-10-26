@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 單一錄取程序</h2>
    <p>
        全區報名人數：{{ $count }}<br>
        錄取人數：{{ $admitted }}
    </p>
    <form action="{{ route("showCandidate", $campFullData->id) }}" method="post" class="form-horizontal">
        @csrf
        請輸入報名序號或錄取序號：<input type="text" name="snORadmittedSN" class="form-control" placeholder="">
        <br>
        <input type="submit" class="btn btn-success"" value="送出">
    </form>
@endsection