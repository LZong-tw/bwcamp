@extends('camps.ycamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
    <div class="card">
        <div class="card-header">
            報名序號查詢
        </div>
        <div class="card-body">
            @if(isset($applicant))
                <p class="card-text">
                    您的報名序號已寄至 <span class="text-danger font-weight-bold">{{ $applicant->email }}</span>，請查收。
                </p>
            @endif
            @if(isset($error))
                <div class="alert alert-danger">{{ $error }}</div>
            @endif
            <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
        </div>
    </div>
@stop