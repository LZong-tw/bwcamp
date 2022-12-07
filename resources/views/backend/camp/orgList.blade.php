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
    <h2 class="d-inline-block">{{ $camp->abbreviation }} 組織列表　</h2><br>
    @if(\Session::has('message'))
        <div class='alert alert-success' role='alert'>
            {{ \Session::get('message') }}
        </div>
    @endif
    <br>


    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>大組名稱</th>
            <th>職務名稱</th>
            <th>修改</th>
            <th>刪除</th>
            <th>新增</th>
        </tr>
        @foreach($orgs as $org)
            <tr>
                <td>{{ $org->id }}</td>
                @if($org->position == 'root')
                <td class="font-weight-bold">{{ $org->section }}</td>
                @else
                <td class="text-muted">{{ $org->section }}</td>
                @endif
                @if($org->position == 'root')
                    <td>（大組）</td>
                    <td>
                        <a href="{{ route('showModifyOrg', [$camp->id, $org->id]) }}" class="btn btn-primary">修改</a>
                    </td>
                    <td>
                        <form action="{{ route("removeOrg") }}" method="post">
                            @csrf
                            <input type="hidden" name="org_id" value="{{ $org->id }}">
                            <input type="hidden" name="org_section" value="{{ $org->section }}">
                            <input type="hidden" name="org_position" value="{{ $org->position }}">
                            <input type="hidden" name="camp_id" value="{{ $camp->id }}">
                            <input type="submit" class="btn btn-danger" value="刪除">
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('showAddOrgs', [$camp->id, $org->id]) }}" class="btn btn-success">新增職務</a>
                    </td>
                @else
                    <td>{{ $org->position }}</td>
                    <td><a href="{{ route('showModifyOrg', [$camp->id, $org->id]) }}" class="btn btn-primary">修改</a></td>
                    <td>
                        <form action="{{ route("removeOrg") }}" method="post">
                            @csrf
                            <input type="hidden" name="org_id" value="{{ $org->id }}">
                            <input type="hidden" name="org_section" value="{{ $org->section }}">
                            <input type="hidden" name="org_position" value="{{ $org->position }}">
                            <input type="hidden" name="camp_id" value="{{ $camp->id }}">
                            <input type="submit" class="btn btn-danger" value="刪除">
                        </form>
                    </td>
                    <td></td>
                @endif
            </tr>
        @endforeach
    </table>

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a href="{{ route("showAddOrgs", [$camp->id, 0]) }}" class="btn btn-success d-inline-block" style="margin-bottom: 10px">批次新增組織</a>
            </div>
            <div class="col-md-9">
            </div>
        </div>
    </div>
    <hr>
    <form action="{{ route('copyOrgs', $camp->id) }}" method="post">
        @csrf
        <div class="container">
            <div class="row">
                <h5>複製現有營隊組織</h5>
            </div>
            <div class="row">
                <div class="col-md-2 text-md-right">
                    選擇要複製的營隊
                </div>
                <div class="col-md-8">
                    <select class='form-control' name='camp2copy' id='inputCamp2Copy'>
                    @foreach($camp_list as $item)
                    @if($item->id != $camp->id)
                    <option value='{{$item->id}}'> {{$item->id}} {{$item->fullName}} </option>
                    @endif
                    @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary">複製</button><br>
                </div>
            </div>
        </div>
    </form>
@endsection
