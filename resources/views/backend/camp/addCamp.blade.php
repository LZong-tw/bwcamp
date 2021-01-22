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
    <h2>建立營隊</h2>
    <form action="" method="POST">
        @csrf
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>全名</label>
            <div class='col-md-6'>
                <input type="text" name="fullName" id="" class='form-control' required>
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>簡稱</label>
            <div class='col-md-6'>
                <input type="text" name="abbreviation" id="" class='form-control' required>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>網站網址</label>
            <div class='col-md-6'>
                <input type="text" name="site_url" id="" class='form-control'>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>圖示</label>
            <div class='col-md-6'>
                <input type="text" name="icon" id="" class='form-control'>
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>資料表名稱</label>
            <div class='col-md-6'>
                <select name="table" id="" class='form-control' required>
                    <option value="">請選擇</option>
                    <option value="tcamp">教師營</option>
                    <option value="ycamp">大專營</option>
                    <option value="ecamp">企業營</option>
                </select>
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>報名開始日</label>
            <div class='col-md-6'>
                <input type="date" name="registration_start" id="" class='form-control' required>
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>報名結束日</label>
            <div class='col-md-6'>
                <input type="date" name="registration_end" id="" class='form-control' required>
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label'>錄取公佈日</label>
            <div class='col-md-6'>
                <input type="date" name="admission_announcing_date" id="" class='form-control' required>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>回覆參加截止日</label>
            <div class='col-md-6'>
                <input type="date" name="admission_confirming_end" id="" class='form-control'>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>後台報名結束日</label>
            <div class='col-md-6'>
                <input type="date" name="final_registration_end" id="" class='form-control'>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>繳費開始日</label>
            <div class='col-md-6'>
                <input type="date" name="payment_startdate" id="" class='form-control'>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>繳費截止日</label>
            <div class='col-md-6'>
                <input type="date" name="payment_deadline" id="" class='form-control'>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label'>營隊費用</label>
            <div class='col-md-6'>
                <input type="number" name="fee" id="" class='form-control'>
            </div>
        </div>

        <input type="submit" class="btn btn-success" value="新增">
    </form>
@endsection