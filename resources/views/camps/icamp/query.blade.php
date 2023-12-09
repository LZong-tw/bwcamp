@extends('camps.icamp.layout')
@section('content')
@if($errors->any())
    @foreach ($errors->all() as $message)
        <div class='alert alert-danger' role='alert'>
            {{ $message }}
        </div>
    @endforeach
@endif
<form method="post" action='{{ route("queryview", $batch_id) }}' name="QueryRegis" class="form-horizontal">
    @csrf
    <div class="page-header form-group">
        <h4>報名資料查詢</h4>
    </div>
    <div class='row form-group'>
        <label for='inputName' class='col-md-2'>姓名</label>
        <div class='col-md-10'>
            <input type='text' name='name' class='form-control' id='inputName' placeholder='' value='{{ old("name") }}' required>
        </div>
    </div>

    <div class="row form-group">
        <label for='inputSN' class='col-md-2'>報名序號</label>
        <div class='col-md-10'>
            <input type='text' name='sn' class='form-control' id='inputSN' maxlength=5 placeholder='' value='{{ old("sn") }}' required>
        </div>
    </div>

    {{-- <div class="row form-group">
        <label for='inputRecap' class='col-md-2 control-label'></label>
        <div class='col-md-8'>
        <div class='g-recaptcha' data-sitekey='6Lc6sdASAAAAACovaErznXN6DikqaOlqoVw2SEUK'></div>
        <script type='text/javascript' src='https://www.google.com/recaptcha/api.js?hl=zh-TW'>
        </script>
        </div>
    </div> --}}

    <!--- 確認送出 -->
    <div class=row>
        <div class='col-md-4'></div>
        <div class='col-md-8'>
            <INPUT type=submit name=sub class='btn btn-primary' value='查詢資料'>
            <INPUT type=reset  class='btn btn-danger' value='清除重來'>
        </div>
    </div>
</form>

<form method="post" action='{{ route("queryupdate", $batch_id) }}' name="updateRegis" class="form-horizontal">
    @csrf
    <input type="hidden" name="isModify" value="1">
    <div class="page-header form-group">
        <h4>報名資料修改</h4>
    </div>
    <div class='row form-group'>
        <label for='inputName' class='col-md-2'>姓名</label>
        <div class='col-md-10'>
            <input type='text' name='name' class='form-control' id='inputName' placeholder='' value='{{ old("name") }}' required>
        </div>
    </div>

    <div class="row form-group">
        <label for='inputSN' class='col-md-2'>報名序號</label>
        <div class='col-md-10'>
            <input type='text' name='sn' class='form-control' id='inputSN' maxlength=5 placeholder='' value='{{ old('sn') }}' required>
        </div>
    </div>

    {{-- <div class="row form-group">
        <label for='inputRecap' class='col-md-2 control-label'></label>
        <div class='col-md-8'>
        <div class='g-recaptcha' data-sitekey='6Lc6sdASAAAAACovaErznXN6DikqaOlqoVw2SEUK'></div>
        <script type='text/javascript' src='https://www.google.com/recaptcha/api.js?hl=zh-TW'>
        </script>
        </div>
    </div> --}}

    <!--- 確認送出 -->
    <div class=row>
        <div class='col-md-4'></div>
        <div class='col-md-8'>
            <INPUT type=submit name=sub class='btn btn-success' value='修改資料'>
            <INPUT type=reset  class='btn btn-danger' value='清除重來'>
        </div>
    </div>
</form>

<form method="post" name="QuerySN" action="{{ route("querysn", $batch_id) }}" class="form-horizontal">
    @csrf
    <div class="page-header form-group">
        <h4>報名序號查詢</h4>
    </div>

    <div class="row form-group">
        <label for='inputName2' class='col-md-2'>姓名</label>
        <div class='col-md-10'>
        <input type='text' name="name" class='form-control' id='inputName2' placeholder=''>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputBirth' class='col-md-1'>生日</label>
        <div class='date col-md-11' id='inputBirth'>
            <div class='row form-group required'>
                <div class="col-md-2 text-md-right">
                    西元
                </div>
                <div class="col-md-3">
                    <input type='number' required class='form-control' name='birthyear' min='{{ \Carbon\Carbon::now()->subYears(90)->year }}' max='{{ \Carbon\Carbon::now()->subYears(10)->year }}' value='' placeholder=''>
                </div>
                <div class="col-md-1">
                    年
                </div>
                <div class="col-md-2">
                    <input type='number' required class='form-control' name='birthmonth' min=1 max=12 value='' placeholder=''>
                </div>
                <div class="col-md-1">
                    月
                </div>
                <div class="col-md-2">
                    <input type='number' required class='form-control' name='birthday' min=1 max=31 value='' placeholder=''>
                </div>
                <div class="col-md-1">
                    日
                </div>
            </div>
            <div class='help-block with-errors'></div>
        </div>
    </div>

    <!--- 確認送出 -->
    <div class=row>
        <div class='col-md-4'></div>
        <div class='col-md-8'>
        <INPUT type=submit name=sub class='btn btn-info' value='查詢序號'>
        </div>
    </div>
</form>
@stop