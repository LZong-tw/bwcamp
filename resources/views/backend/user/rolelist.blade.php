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
    <h2 class="d-inline-block">權限列表</h2>
    <a href="" class="btn btn-success d-inline-block" style="margin-bottom: 10px">新增角色</a>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>角色名稱</th>
            <th>等級</th>
            <th>可存取的營隊</th>
            <th>修改資料</th>
            <th>刪除角色</th>
        </tr>
        @foreach($roles as $role)
            <tr>
                <td>{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
                <td>{{ $role->level }}</td>
                <td><a href="{{ route("campIndex", $role->camp_id ?? "") }}" class="card-link" target="_blank">{{ $role->camp->abbreviation ?? "" }}</a></td>
                <td><a href="" class="btn btn-primary">修改</a></td>
                <td><a href="" class="btn btn-danger">刪除</a></td>
            </tr>
        @endforeach
    </table>
@endsection