@extends('backend.master')
@section('content')
    <h2>{{ $user->name }} 權限設定</h2>
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
    <h4>目前擁有的角色</h4>
    <table class="table table-bordered">
        <tr>
            <th>序號</th>
            <th>名稱</th>
            <th>可存取的營隊</th>
            <th>刪除</th>
        </tr>
        @foreach($user->role_relations as $role_relation)
            <tr>
                <td>{{ $role_relation->role_data->id }}</td>
                <td>{{ $role_relation->role_data->name }}</td>
                <td>
                        <a href="{{ route("campIndex", $role_relation->role_data->camp_id ?? "") }}">{{ $role_relation->role_data->camp->abbreviation ?? "" }}</a>
                </td>
                <td>
                    <form action="{{ route("removeRole") }}" method="post">
                        @csrf
                        <input type="hidden" name="role_id" value="{{ $role_relation->role_data->id }}">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="submit" class="btn btn-danger" value="刪除">
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    <h4>所有角色</h4>
    <table class="table table-bordered">
        <tr>
            <th>序號</th>
            <th>名稱</th>
            <th>可存取的營隊</th>
            <th>新增</th>
        </tr>
        @foreach($roles_available as $role)
            <tr>
                <td>{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
                <td>
                        <a href="{{ route("campIndex", $role->camp_id ?? "") }}">{{ $role->camp->abbreviation ?? "" }}</a>
                </td>
                <td>
                    <form action="{{ route("addRole") }}" method="post">
                        @csrf
                        <input type="hidden" name="role_id" value="{{ $role->id }}">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="submit" class="btn btn-success" value="新增">
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@endsection