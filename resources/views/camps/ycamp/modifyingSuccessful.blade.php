@extends('camps.ycamp.layout')
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
                錄取名單將於 5/30，6/15，7/5 分三波於<a href="{{ $camp_data->site_url }}">營隊官網</a>公佈，請自行上網查詢。<br>
                {{-- 錄取結果將在 <u>{{ $camp_data->admission_announcing_date }} ({{ $admission_announcing_date_Weekday }})</u> 起於網上公佈，請自行上網查詢，<br>
                並於 <u>{{ $camp_data->admission_confirming_end }} ({{ $admission_confirming_end_Weekday }})</u> 前上網回覆確認參加，倘未回覆，視同放棄。 --}}
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