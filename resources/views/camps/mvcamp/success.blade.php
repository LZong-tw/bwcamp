@extends('camps.mvcamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
    @if(isset($isRepeat))<div class="alert alert-warning">{{ $isRepeat }}</div>@endif
    <div class="card">
        <div class="card-header">
            <h5>報名成功</h5>
        </div>
        <div class="card-body">
            <div class="card-text">
                恭喜您已完成{{ $camp_data->fullName }}網路報名程序。
                @include('camps.mvcamp.successMessages')
            </div>
            <form action="{{ route("queryview", $applicant->batch_id) }}" method="post" class="d-inline">
                @csrf
                <input type="hidden" name="sn" value="{{ $applicant->id }}">
                <input type="hidden" name="name" value="{{ $applicant->name }}">
                <button class="btn btn-primary">檢視報名資料</button>
            </form>
        </div>
    </div>
@stop
