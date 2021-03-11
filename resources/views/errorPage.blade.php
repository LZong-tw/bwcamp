@extends('camps.ycamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
    <div class="card">
        <div class="card-header">
            輸入錯誤
        </div>
        <div class="card-body">
            <p class="card-text">
                <div class="alert alert-danger">{{ $error }}</div>
            </p>
            <input type='button' class='btn btn-warning' value='回上一頁檢查' onclick=self.history.back()>
            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
        </div>
    </div>
@stop