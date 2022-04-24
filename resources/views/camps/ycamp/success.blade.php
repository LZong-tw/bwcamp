@extends('camps.ycamp.layout')
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
                恭喜您已完成{{ $camp_data->fullName }}（簡稱本營隊）網路報名程序，您所填寫的個人資料，僅用於此次大專營的報名及活動聯絡之用。財團法人福智文教基金會將依個人資料保護法及相關法令之規定善盡保密責任。<br>
                請記下您的<span class="text-danger font-weight-bold">《 報名序號：{{ $applicant->id }} 》</span>作為日後查詢使用。<br>
                錄取名單將於 5/30，6/15，7/5 分三波於<a href="{{ $camp_data->site_url }}">營隊官網</a>公佈，請自行上網查詢。<br>
                {{-- 錄取結果將在 <u>{{ $camp_data->admission_announcing_date }} ({{ $admission_announcing_date_Weekday }})</u> 起於網上公佈，請自行上網查詢，<br>
                並於 <u>{{ $camp_data->admission_confirming_end }} ({{ $admission_confirming_end_Weekday }})</u> 前上網回覆確認參加，倘未回覆，視同放棄。--}}
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