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
    <h2>{{ $camp->abbreviation }} 修改組織 </h2>
    <form action="{{ route("modifyOrg", [$camp->id, $org->id]) }}" method="POST">
        @csrf
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
            <label for='inputPos' class='col-md-2 control-label'>職務名稱</label>
            <div class='col-md-6'>
                <input type="string" name="position" id="" class='form-control' value="{{ $org->position ?? "" }}">
            </div>
        </div>
        @else
        <input type='hidden' name='position' value='{{ $org->position }}'>
        @endif
        <input type="submit" class="btn btn-success" value="確認修改">
        <a href="{{ route('showOrgs', $camp->id) }}" class="btn btn-danger">取消修改</a>
    </form>
@endsection