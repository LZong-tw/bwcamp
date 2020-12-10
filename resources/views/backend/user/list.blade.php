@extends('backend.master')
@section('content')
    <h2>使用者/權限列表</h2>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>名稱</th>
            <th>Email</th>
            <th>等級</th>
            <th>角色</th>
            <th>可存取的營隊 ID</th>
            <th>修改資料</th>
        </tr>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role_relation->role_data->level }}</td>
                <td>{{ $user->role_relation->role_data->name }}</td>
                <td>{{ $user->role_relation->role_data->camp_id }}</td>
                <td><a href="" class="btn btn-success">修改</a></td>
            </tr>
        @endforeach
    </table>
@endsection