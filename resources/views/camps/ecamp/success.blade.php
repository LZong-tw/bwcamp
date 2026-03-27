@extends('camps.ecamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
    @if(isset($isRepeat))
    <div class="alert alert-warning">{{ $isRepeat }}</div>
    @endif
    <div class="card">
        <div class="card-header">
            @if(isset($isModify))
            	<h5>修改成功</h5>
            @else
            	<h5>報名成功</h5>
            @endif
        </div>
        <div class="card-body">
            <p class="card-text">
                @if(isset($isModify))
                    您成功修改報名「{{ $camp_data->fullName }}」的資料。<br>
                @else
                    恭喜您已完成「{{ $camp_data->fullName }}」網路報名程序。<br>
                @endif
                @include('camps.ecamp.successMessages')
            </p>
            <form action="{{ route("queryview", $applicant->batch_id) }}" method="post" class="d-inline">
                @csrf
                <input type="hidden" name="sn" value="{{ $applicant->id }}">
                <input type="hidden" name="name" value="{{ $applicant->name }}">
                <button class="btn btn-primary">檢視報名資料</button>
            </form>
            <a href="{{ $camp_data->site_url }}" class="btn btn-info">回營隊首頁</a>
        </div>
    </div>
@stop
