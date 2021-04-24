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
                        {{ $role_relation->role_data->level }} - {{ $role_relation->role_data->name }} - <a href="{{ route("campIndex", $role_relation->role_data->camp_id ?? "") }}" class="card-link" target="_blank">{{ $role_relation->role_data->camp->abbreviation ?? "" }}</a>
                    @else
                        {{ $role_relation->role_data->level }} - {{ $role_relation->role_data->name }} - <a href="{{ route("campIndex", $role_relation->role_data->camp_id ?? "") }}" class="card-link" target="_blank">{{ $role_relation->role_data->camp->abbreviation ?? "" }}</a><br>
                    @endif
                @endforeach</td>
                <td><a href="" class="btn btn-success" target="_blank">修改</a></td>
                <td><a href="{{ route("userAddRole", [$user->id]) }}" class="btn btn-primary" target="_blank">設定</a></td>
            </tr>
        @endforeach
    </table>
@endsection