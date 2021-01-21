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
    <h2>新增角色</h2>
    <form action="{{ route('listAddRole', \Request::route('camp_id') ?? "") }}" method="POST">
        @csrf
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>名稱</label>
            <div class='col-md-6'>
                <input type="text" name="name" id="" class='form-control' required>
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>等級</label>
            <div class='col-md-6'>
                <input type="number" name="level" id="" min="2" max="6" class='form-control' required>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>可存取的營隊</label>
            <div class='col-md-6'>
                <select name="camp_id" id="" class='form-control'>
                    <option value="">不指定</option>
                    @foreach ($camps as $camp)
                        <option value="{{ $camp->id }}">{{ $camp->abbreviation }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <input type="submit" class="btn btn-success" value="新增">
    </form>
@endsection