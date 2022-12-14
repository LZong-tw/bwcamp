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
    @if(Session::has("message"))
        <div class="alert alert-success" role="alert">
            {{ Session::get("message") }}
        </div>
    @endif
    @if(Session::has("error"))
        <div class="alert alert-danger" role="alert">
            {{ Session::get("error") }}
        </div>
    @endif
    <a href="{{ route("listAddRoleGET", \Request::route('camp_id') ?? "") }}" class="btn btn-success d-inline-block" style="margin-bottom: 10px">新增角色</a>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>角色名稱</th>
            <th>等級</th>
            <th>可存取的營隊</th>
            <th>地區</th>
            <th>修改資料</th>
            <th>刪除角色</th>
        </tr>
        @foreach($roles as $role)
            <tr>
                <td>{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
                <td>{{ $role->level }}</td>
                <td><a href="{{ route("campIndex", $role->camp_id ?? "") }}" class="card-link" target="_blank">{{ \App\Models\Camp::find($role->camp_id)->abbreviation ?? "" }}</a></td>
                <td>{{ $role->region }}</td>
                <td>
                    @if($role->level > 1)
                        <a href="{{ route("editRoleGET", $role->id) }}" class="btn btn-primary">修改</a>
                    @else
                        無法修改
                    @endif
                </td>
                <td>
                    @if($role->level > 1)
                        <form action="{{ route("listRemoveRole") }}" method="post">
                            @csrf
                            <input type="hidden" name="role_id" value="{{ $role->id }}">
                            <input type="submit" class="btn btn-danger" value="刪除">
                        </form>
                    @else
                        無法刪除
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
@endsection
