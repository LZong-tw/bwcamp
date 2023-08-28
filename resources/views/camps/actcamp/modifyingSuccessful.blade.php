@extends('camps.actcamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
    <div class="card">
        <div class="card-header">
            修改成功
        </div>
        <div class="card-body">
            <p class="card-text">
                您成功修改報名 {{ $camp_data->fullName }}（簡稱本營隊）的個人資料。<br>
                @include('camps.actcamp.successMessages')
            </p>
            <form action="{{ route("queryview", $applicant->batch_id) }}" method="post" class="d-inline">
                @csrf
                <input type="hidden" name="sn" value="{{ $applicant->id }}">
                <button class="btn btn-primary">檢視報名資料</button>
            </form>
            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
        </div>
    </div>
@stop
