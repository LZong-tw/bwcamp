@extends('camps.acamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}線上報名表</h4>
    </div>
    <div class='alert alert-danger' role='alert'>
        報名期限已過，敬請見諒。
    </div>
@stop