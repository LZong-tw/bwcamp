@extends('layouts.ycamp')
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
                請記下您的<span class="text-danger font-weight-bold">《 報名序號：{{ $applicant->id }} 》</span>作為日後查詢使用。<br>
                錄取結果將在 <u>{{ $camp_data->admission_announcing_date }} ({{ $admission_announcing_date_Weekday }})</u> 起於網上公佈，請自行上網查詢，<br>
                錄取者收到「錄取繳費通知單」(或可於網站錄取頁面下載)後，請於 <span class="text-primary">12-08(星期二) ～ 12-14(星期一)</span> 期間內完成繳費，繳費後視同確定參加，倘未繳費，視同放棄。
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