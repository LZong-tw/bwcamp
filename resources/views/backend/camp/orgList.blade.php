@extends('backend.master')
@section('content')
    <style>
        .card-link{
            color: #3F86FB!important;
        }
        .card-link:hover{
            color: #33B2FF!important;
        }
    </style>
    <h2 class="d-inline-block">{{ $camp->abbreviation }} 組織列表</h2>
    <a href="{{ route("showAddOrgs", $camp->id) }}" class="btn btn-success d-inline-block" style="margin-bottom: 10px">建立組織</a>
    @if(\Session::has('message'))
        <div class='alert alert-success' role='alert'>
            {{ \Session::get('message') }}
        </div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>CampID</th>
            <th>ID</th>
            <th>大組</th>
            <th>職稱</th>
            <th>動作</th>
        </tr>
        @foreach($orgs as $org)
            <tr>
                <td>{{ $camp->id }}</td>
                <td>{{ $org->id }}</td>
                <td>{{ $org->section }}</td>
                <td>{{ $org->position }}</td>
                <td><a href="{{ route('showModifyOrg', [$camp->id, $org->id]) }}" class="btn btn-primary">修改</a></td>
            </tr>
        @endforeach
    </table>
@endsection