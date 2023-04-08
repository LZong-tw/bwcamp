@extends('backend.master')
@section('content')
    <style>
        .card-link{
            color: #3F86FB!important;
        }
        .card-link:hover{
            color: #33B2FF!important;
        }
        /* customize */
        .form-group.required .control-label:after {
            content: "＊";
            color: red;
        }
    </style>
    <h2>{{ $camp->abbreviation }} 修改組織職務 </h2>
    <form action="{{ route("modifyOrg", [$camp->id, $org->id]) }}" method="POST">
        @csrf
        <div class='row form-group'>
            <label for='inputPos' class='col-md-2 control-label'>梯次</label>
            <div class='col-md-6'>
                <select name="batch_id" id="" class="form-control">
                    <option value="">不限</option>
                    @foreach($camp->batches ?? [] as $batch)
                        <option value="{{ $batch->id }}" {{ $batch->id == $org->batch_id ? "selected" : "" }}>{{ $batch->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputSec' class='col-md-2 control-label'>大組名稱</label>
            @if($org->position == 'root')
            <div class='col-md-6'>
                <input type="string" name="section" id="" class='form-control' value="{{ $org->section ?? "" }}">
            </div>
            @else
            <div class='col-md-6'>
                <input type='hidden' name='section' value='{{ $org->section }}'>
                {{ $org->section }}
            </div>
            @endif
        </div>
        @if($org->position != 'root')
        <div class='row form-group'>
            <label for='inputPos' class='col-md-2 control-label'>小組及職務名稱</label>
            <div class='col-md-6'>
                <input type="string" name="position" id="" class='form-control' value="{{ $org->position ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputPos' class='col-md-2 control-label'>綁定的學員組別</label>
            <div class='col-md-6'>
                <select name="group_id" id="" class="form-control">
                    <option value="">無</option>
                    @foreach($camp->groups ?? [] as $group)
                        <option value="{{ $group->id }}" {{ $group->id == $org->group_id ? "selected" : "" }}>{{ $group->alias }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class='row form-group'>
        </div>
        <div class='row form-group'>
            <div class='ml-3 mt-3'>
                @include('backend.camp.permission_table')
            </div>
        </div>
        @else
        <input type='hidden' name='position' value='{{ $org->position }}'>
        @endif
        <input type="submit" class="btn btn-success" value="確認修改">
        <a href="{{ route('showOrgs', $camp->id) }}" class="btn btn-danger">取消修改</a>
    </form>
@endsection
