@extends('layouts.ycamp')
@section('content')
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>

    <div class='alert alert-success' role='alert'>
        恭喜您已完成{{ $camp_data->fullName }}（簡稱本營隊）網路報名程序，您所填寫的個人資料，僅用於此次大專營的報名及活動聯絡之用。財團法人福智文教基金會將依個人資料保護法及相關法令之規定善盡保密責任。
        請記下您的《 報名序號：{{ $applicant->id }} 》作為日後查詢使用。
        錄取結果將於 {{ $camp_data->admission_announcing_date }} ({{ $admission_announcing_date_Weekday }}) 網上公佈，請自行上網查詢，
        並於 {{ $camp_data->admission_confirming_end }} ({{ $admission_confirming_end_Weekday }}) 前上網回覆確認參加，倘未回覆，視同放棄。
    </div>
@stop