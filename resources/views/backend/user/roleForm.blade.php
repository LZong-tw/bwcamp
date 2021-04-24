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
    @if(!isset($role))
        <h2>新增角色</h2>
        <form action="{{ route('listAddRole', \Request::route('camp_id') ?? "") }}" method="POST">
    @else
        <h2>修改角色</h2>
        <form action="{{ route('editRole', $role->id) }}" method="POST">
    @endif
        @csrf
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>名稱</label>
            <div class='col-md-6'>
                <input type="text" name="name" id="" class='form-control' value="{{ $role->name ?? "" }}" required>
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>等級</label>
            <div class='col-md-6'>
                <input type="number" name="level" id="" min="2" max="6" class='form-control' value="{{ $role->level ?? "" }}" required>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>可存取的營隊</label>
            <div class='col-md-6'>
                <select name="camp_id" id="" class='form-control'>
                    <option value="">不指定</option>
                    @foreach ($camps as $camp)
                        <option value="{{ $camp->id }}" @if(isset($role) && $role->camp_id == $camp->id) selected @endif>{{ $camp->abbreviation }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>地區(權限數字大於 3 才會判斷)</label>
            <div class='col-md-6'>
                <input type="text" name="region" id="" class='form-control' value="{{ $role->region ?? "" }}">
            </div>
        </div>
        @if(!isset($role))
            <input type="submit" class="btn btn-success" value="新增">
        @else
            <input type="submit" class="btn btn-success" value="修改">
        @endif
    </form>
@endsection