@extends('camps.mvcamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}線上報名表</h4>
    </div>
    <div class='alert alert-danger' role='alert'>
        因報名踴躍，已超過人數限制，敬請見諒
        <!-- {{ $outdatedMessage }}-->
    </div>
@stop
