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
    <h2>{{ $action }}營隊</h2>
    <form action="{{ $actionURL }}" method="POST">
        @csrf
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>全名</label>
            <div class='col-md-6'>
                <input type="text" name="fullName" id="" class='form-control' required value="{{ $camp->fullName ?? "" }}">
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>簡稱</label>
            <div class='col-md-6'>
                <input type="text" name="abbreviation" id="" class='form-control' required value="{{ $camp->abbreviation ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>網站網址</label>
            <div class='col-md-6'>
                <input type="text" name="site_url" id="" class='form-control' value="{{ $camp->site_url ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>圖示</label>
            <div class='col-md-6'>
                <input type="text" name="icon" id="" class='form-control' value="{{ $camp->icon ?? "" }}">
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>資料表名稱</label>
            <div class='col-md-6'>
                <select name="table" id="" class='form-control' required>
                    <option value="">請選擇</option>
                    <option value="tcamp" @if(isset($camp) && $camp->table == "tcamp") selected @endif>教師營</option>
                    <option value="ycamp" @if(isset($camp) && $camp->table == "ycamp") selected @endif>大專營</option>
                    <option value="ecamp" @if(isset($camp) && $camp->table == "ecamp") selected @endif>企業營</option>
                    <option value="hcamp" @if(isset($camp) && $camp->table == "hcamp") selected @endif>快樂營</option>
                </select>
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>報名開始日</label>
            <div class='col-md-6'>
                <input type="date" name="registration_start" id="" class='form-control' required value="{{ $camp->registration_start ?? "" }}">
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>報名結束日</label>
            <div class='col-md-6'>
                <input type="date" name="registration_end" id="" class='form-control' required value="{{ $camp->registration_end ?? "" }}">
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>錄取公佈日</label>
            <div class='col-md-6'>
                <input type="date" name="admission_announcing_date" id="" class='form-control' required value="{{ $camp->admission_announcing_date ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>回覆參加截止日</label>
            <div class='col-md-6'>
                <input type="date" name="admission_confirming_end" id="" class='form-control' value="{{ $camp->admission_confirming_end ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>後台報名結束日</label>
            <div class='col-md-6'>
                <input type="date" name="final_registration_end" id="" class='form-control' value="{{ $camp->final_registration_end ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>繳費開始日</label>
            <div class='col-md-6'>
                <input type="text" name="payment_startdate" id="" class='form-control' value="{{ $camp->payment_startdate ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>繳費截止日</label>
            <div class='col-md-6'>
                <input type="text" name="payment_deadline" id="" class='form-control' value="{{ $camp->payment_deadline ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>營隊費用</label>
            <div class='col-md-6'>
                <input type="number" name="fee" id="" class='form-control' value="{{ $camp->fee ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>是否有早鳥優惠</label>
            <div class='col-md-6'>
                <select name="has_early_bird" id="" class='form-control' required>
                    <option value="0" @if(isset($camp) && !$camp->has_early_bird) selected @endif>否</option>
                    <option value="1" @if(isset($camp) && $camp->has_early_bird) selected @endif>是</option>
                </select>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>營隊早鳥費用</label>
            <div class='col-md-6'>
                <input type="number" name="early_bird_fee" id="" class='form-control' value="{{ $camp->early_bird_fee ?? "0" }}">
            </div>
        </div>
        
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>早鳥最後一日</label>
            <div class='col-md-6'>
                <input type="date" name="early_bird_last_day" id="" class='form-control' value="{{ $camp->early_bird_last_day ?? "" }}">
            </div>
        </div>

        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>報名資料修改截止日</label>
            <div class='col-md-6'>
                <input type="date" name="modifying_deadline" id="" class='form-control' value="{{ $camp->modifying_deadline ?? "" }}">
            </div>
        </div>

        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>取消截止日</label>
            <div class='col-md-6'>
                <input type="date" name="cancellation_deadline" id="" class='form-control' value="{{ isset($camp->cancellation_deadline) ? $camp->cancellation_deadline->format('Y-m-d') : "" }}">
            </div>
        </div>
        
        @if($action == "建立")
            <input type="submit" class="btn btn-success" value="建立營隊">
        @else
            <input type="submit" class="btn btn-success" value="修改營隊">
        @endif
    </form>
@endsection