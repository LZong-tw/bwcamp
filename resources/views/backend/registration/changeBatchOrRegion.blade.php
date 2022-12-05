@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 修改梯次 / 區域</h2>
    <form action="{{ route("showCandidate", $campFullData->id) }}" method="post" class="form-horizontal">
        @csrf
        <input type="hidden" name="change" value="change">
        請輸入報名序號或錄取序號：<input type="text" name="snORadmittedSNorName" class="form-control" placeholder="">
        <br>
        <input type="submit" class="btn btn-success" value="送出">
    </form>
@endsection
