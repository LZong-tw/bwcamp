@extends('backend.master')
@section('content')
    <h2>使用者列表</h2>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>名稱</th>
            <th>Email</th>
            <th>等級 - 角色 - 可存取的營隊</th>
            <th>修改資料</th>
            <th>權限</th>
        </tr>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>@foreach ($user->role_relations as $role_relation)
                    @if($loop->last)
                        {{ $role_relation->role_data->level }} - {{ $role_relation->role_data->name }} - <a href="{{ route("campIndex", $role_relation->role_data->camp_id ?? "") }}">{{ $role_relation->role_data->camp->abbreviation ?? "" }}</a>
                    @else
                        {{ $role_relation->role_data->level }} - {{ $role_relation->role_data->name }} - <a href="{{ route("campIndex", $role_relation->role_data->camp_id ?? "") }}" title="">{{ $role_relation->role_data->camp->abbreviation ?? "" }}</a><br>
                    @endif
                @endforeach</td>
                <td><a href="" class="btn btn-success">修改</a></td>
                <td><a href="{{ route("editRole", [$user->id]) }}" class="btn btn-primary">設定</a></td>
            </tr>
        @endforeach
    </table>
@endsection