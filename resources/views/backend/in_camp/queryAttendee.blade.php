@extends('backend.master')
@section('content')
@include('..partials.counties_areas_script')
<style>
    .card-link{
        color: #3F86FB!important;
    }
    .card-link:hover{
        color: #33B2FF!important;
    }
</style>

    <h2>{{ $campFullData->abbreviation }} 查詢及檢視義工/學員資料</h2>
    <form action="{{ route("showAttendeeInfo", $campFullData->id) }}" method="post" class="form-horizontal">
        @csrf
        請輸入報名序號或錄取序號：<input type="text" name="snORadmittedSN" class="form-control" placeholder="">
        <br>
        <input type="submit" class="btn btn-success" value="送出">
    </form>
    
@endsection