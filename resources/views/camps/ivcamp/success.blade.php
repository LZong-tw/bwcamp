@extends('camps.ivcamp.layout')
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
                感謝您報名{{ $applicant->batch->camp->fullName }}，報名手續已完成，<br>
                您在本活動所填寫的個人資料，僅用於本活動報名及聯絡之用。<br>
                大會將依個人資料保護法及相關法令之規定善盡保密責任。<br>
                請記下您的<span class="text-danger font-weight-bold">《 報名序號：{{ $applicant->id }} 》</span>作為日後查詢使用。<br><br>
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