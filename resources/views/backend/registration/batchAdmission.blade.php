@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 批次錄取程序</h2>
    <p>
        全區報名人數：{{ $count }}<br>
        錄取人數：{{ $admitted }}
    </p>
    <form action="{{ route("showBatchCandidate", $campFullData->id) }}" method="post" class="form-horizontal">
        @csrf
        請輸入報名序號或錄取序號，以半形逗號(,)分隔：<input type="text" name="snORadmittedSN" class="form-control" placeholder="">
        <br>
        <input type="submit" class="btn btn-success"" value="送出">
    </form>
@endsection