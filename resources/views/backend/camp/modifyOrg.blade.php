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
            <label for='inputBatch' class='col-md-2 control-label'>梯次</label>
            <div class='col-md-6'>
                <select name="batch_id" id="" class="form-control">
                    <option value="">不限</option>
                    @foreach($camp->batchs ?? [] as $batch)
                        <option value="{{ $batch->id }}" {{ $batch->id == $org->batch_id ? "selected" : "" }}>{{ $batch->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputRegion' class='col-md-2 control-label'>區域</label>
            <div class='col-md-6'>
                <select name="region_id" id="" class="form-control">
                    <option value="">不限</option>
                    @foreach($camp->regions ?? [] as $region)
                        <option value="{{ $region->id }}" {{ $region->id == $org->region_id ? "selected" : "" }}>{{ $region->name }}</option>
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
                <div class="row">
                    <span class='col-3 justify-content-center align-self-center'><input type="radio" name="all_group" id="" value="0" @checked(!isset($org) || $org->all_group == 0)> 單一學員組別</span>
                    <select name="group_id" id="" class="form-control col-9">
                        <option value="">無</option>
                        @foreach($camp->groups ?? [] as $group)
                            <option value="{{ $group->id }}" {{ $group->id == $org->group_id ? "selected" : "" }}>{{ $group->alias }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="justify-content-center align-self-center">或
                    <input type="radio" name="all_group" id="all_group_true" class='' value="1" @checked(isset($org) && $org->all_group == 1)> 全部學員組別
                </span>
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
    <script>
        $(document).ready(function(){
            $("#all_group_true").click(function(){
                $("select[name='group_id']").val("");
            });
        });
    </script>
@endsection
