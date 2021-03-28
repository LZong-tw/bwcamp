@extends('camps.hcamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>回復報名</h4>
    </div>
    <div class="card">
        <div class="card-header">
            成功
        </div>
        <div class="card-body">
            <p class="card-text">
                您已成功回復報名{{ $camp_data->fullName }}。 <br>
                報名序號：{{ $applicant->id }} <br>
                姓名：{{ $applicant->name }} <br>
                身分證：{{ $applicant->idno }} <br>
            </p>
            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
        </div>
    </div>
@stop