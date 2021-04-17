@extends('camps.acamp.layout')
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
                恭喜您已完成{{ $camp_data->fullName }}網路報名程序。您所填寫的個人資料，僅用於本營隊的報名及活動聯絡之用。本基金會（財團法人福智文教基金會）將依個人資料保護法及相關法令之規定善盡保密責任。<br>
                @include('camps.acamp.successMessages')
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