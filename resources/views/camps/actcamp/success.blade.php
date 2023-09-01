@extends('camps.actcamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
    @if(isset($isRepeat))<div class="alert alert-warning">{{ $isRepeat }}</div>@endif
    <div class="card">
        <div class="card-header">
            報名成功
        </div>
        <div class="card-body">
            <p class="card-text">
                感謝您報名{{ $camp_data->fullName }}，報名手續已完成，
                @include('camps.actcamp.successMessages')
            </p>
            <form action="{{ route("queryview", $applicant->batch_id) }}" method="post" class="d-inline">
                @csrf
                <input type="hidden" name="sn" value="{{ $applicant->id }}">
                <input type="hidden" name="name" value="{{ $applicant->name }}">
                <button class="btn btn-primary">檢視報名資料</button>
            </form>
            <a href="{{ route('registration', $applicant->batch_id) }}" class="btn btn-info">再報名一位</a>
        </div>
    </div>
@stop
