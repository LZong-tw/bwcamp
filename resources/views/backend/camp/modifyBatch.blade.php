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
    <h2>{{ $camp->abbreviation }} 修改梯次 - {{ $batch->name }}</h2>
    <form action="{{ route("modifyBatches", [$camp->id, $batch->id]) }}" method="POST">
        @csrf
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>名稱</label>
            <div class='col-md-6'>
                <input type="text" name="name" id="" class='form-control' value="{{ $batch->name ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputSuffix' class='col-md-1 control-label'>錄取編號前綴</label>
            <div class='col-md-6'>
                <input type="text" name="admission_suffix" maxlength="1" class='form-control' value="{{ $batch->admission_suffix ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputStartDate' class='col-md-1 control-label'>梯次開始日</label>
            <div class='col-md-6'>
                <input type="date" name="batch_start" id="" class='form-control' value="{{ $batch->batch_start ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputEndDate' class='col-md-1 control-label'>梯次結束日</label>
            <div class='col-md-6'>
                <input type="date" name="batch_end" id="" class='form-control' value="{{ $batch->batch_end ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputFEReg' class='col-md-1 control-label'>允許前台報名</label>
            <div class='col-md-6'>
                <select name="is_appliable" id="" class="form-control">
                    <option value="1" @if($batch->is_appliable) selected @endif>是</option>
                    <option value="0" @if(!$batch->is_appliable) selected @endif>否</option>
                </select>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputLateReg' class='col-md-1 control-label'>是否延後截止報名</label>
            <div class='col-md-6'>
                <select name="is_late_registration_end" id="" class='form-control'>
                    <option value="">請選擇</option>
                    <option value="1" @if($batch->is_late_registration_end) selected @endif>是</option>
                    <option value="0" @if(!$batch->is_late_registration_end) selected @endif>否</option>
                </select>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputLateDate' class='col-md-1 control-label'>報名延後截止日</label>
            <div class='col-md-6'>
                <input type="date" name="late_registration_end" id="" class='form-control' value="{{ $batch->late_registration_end ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputCheckInDate' class='col-md-1 control-label'>報到日</label>
            <div class='col-md-6'>
                <input type="date" name="check_in_day" id="" class='form-control' value="{{ $batch->check_in_day ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputLocation' class='col-md-1 control-label'>地點</label>
            <div class='col-md-6'>
                <input type="text" name="locationName" id="" class='form-control' value="{{ $batch->locationName ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputAddr' class='col-md-1 control-label'>地址</label>
            <div class='col-md-6'>
                <input type="text" name="location" id="" class='form-control' value="{{ $batch->location ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputTel' class='col-md-1 control-label'>電話</label>
            <div class='col-md-6'>
                <input type="text" name="tel" id="" class='form-control' value="{{ $batch->tel ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputNumGroups' class='col-md-1 control-label'>電話</label>
            <div class='col-md-6'>
                <input type="number" name="num_groups" id="" class='form-control' value="{{ $batch->num_groups ?? "" }}">
            </div>
        </div>
        <input type="submit" class="btn btn-success" value="修改梯次">
    </form>
@endsection