@extends('camps.ycamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
    <div class="card">
        <div class="card-header">
            錄取查詢
        </div>
        <div class="card-body">
            @if($applicant->is_admitted)
                <p class="card-text">
                    你錄取了。<br>
                    錄取編號：{{ $applicant->group }}{{ $applicant->number }}。
                </p>
            @else
                <p class="card-text">
                    你沒錄取。
                </p>
            @endif
            <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
        </div>
    </div>
@stop