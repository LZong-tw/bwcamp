@extends('camps.hcamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>取消報名 / 取消參加</h4>
    </div>
    <div class="card">
        <div class="card-header">
            成功
        </div>
        <div class="card-body">
            <p class="card-text">
                您已成功取消報名/參加{{ $camp_data->fullName }}。
            </p>
            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
        </div>
    </div>
@stop