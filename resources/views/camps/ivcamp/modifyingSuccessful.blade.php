@extends('camps.ivcamp.layout')
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
                您成功修改報名 {{ $applicant->batch->camp->fullName }} 的個人資料。<br>
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