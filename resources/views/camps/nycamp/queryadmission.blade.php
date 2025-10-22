@extends('camps.nycamp.layout')
@section('content')
@if($errors->any())
    @foreach ($errors->all() as $message)
        <div class='alert alert-danger' role='alert'>
            {{ $message }}
        </div>
    @endforeach
@endif
<br>
<div class='alert alert-info' role='alert'>
    Instruction: 
    If you provide Chinese Name upon registration, type Chinese Name in (Last First) order without space. (例如：王小花) <br>
    Otherwise, type English Name in (First Last) order. Use only single space to separate. (for example, Peter Wu)<br>
</div>
<form method="post" action="{{ route("queryadmit", $batch_id) }}" name="QueryRegis" class="form-horizontal">
    @csrf
    <div class="page-header form-group">
        <h4>Admission 錄取查詢</h4>
    </div>
    <div class='row form-group'>
        <label for='inputName' class='col-md-2'>Name<br>姓名</label>
        <div class='col-md-10'>
            <input type='text' name='name' class='form-control' id='inputName' placeholder='' value='{{ old('name') }}' required>
        </div>
    </div>

    <div class="row form-group">
        <label for='inputSN' class='col-md-2'>Registration Number<br>報名序號</label>
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
            <INPUT type=submit name=sub class='btn btn-success' value='Submit 錄取查詢'>
            <INPUT type=reset  class='btn btn-danger' value='Clear 清除重來'>
        </div>
    </div>
</form>
@stop