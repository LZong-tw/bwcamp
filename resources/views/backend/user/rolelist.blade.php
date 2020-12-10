@extends('backend.master')
@section('content')
    <h2>權限列表</h2>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>角色名稱</th>
            <th>等級</th>
            <th>可存取的營隊 ID</th>
            <th>修改資料</th>
        </tr>
        @foreach($roles as $role)
            <tr>
                <td>{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
                <td>{{ $role->level }}</td>
                <td><a href="{{ route("campIndex", $role->camp_id ?? "") }}">{{ $role->camp_id }}</a></td>
                <td><a href="" class="btn btn-success">修改</a></td>
            </tr>
        @endforeach
    </table>
@endsection