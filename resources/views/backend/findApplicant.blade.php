@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} {{ $title }}</h2>
    <form action="{{ route("showCandidate", $campFullData->id) }}" method="post" class="form-horizontal">
        @csrf
        請輸入報名序號或錄取序號：<input type="text" name="snORadmittedSNorName" class="form-control" placeholder="">
        <br>
        <input type="submit" class="btn btn-success" value="送出">
    </form>
@endsection
