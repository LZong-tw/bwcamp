@extends('camps.evcamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}線上報名表</h4>
    </div>
    <div class='alert alert-danger' role='alert'>
        報名已截止，敬請見諒。
    </div>
@stop
