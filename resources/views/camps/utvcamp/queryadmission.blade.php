@extends('camps.utvcamp.layout')
@section('content')
@if($errors->any())
    @foreach ($errors->all() as $message)
        <div class='alert alert-danger' role='alert'>
            {{ $message }}
        </div>
    @endforeach
@endif
@if($camp->variant == "utcamp")
    <div class="alert alert-success" role="alert">
        您好，感謝您報名 【2022第30屆教師生命成長營 大專教職員梯】，大會因故需調整錄取時程，將修正為：11/30(二)、12/20(一)、1/5(三)，將於以上時間點寄發「錄取通知」電子信件(email)。再次感謝您的報名！
    </div>
@endif
<form method="post" action="{{ route("queryadmit", $batch_id) }}" name="QueryRegis" class="form-horizontal">
    @csrf
    <div class="page-header form-group">
        <h4>錄取查詢</h4>
    </div>
    <div class='row form-group'>
        <label for='inputName' class='col-md-2'>姓名</label>
        <div class='col-md-10'>
            <input type='text' name='name' class='form-control' id='inputName' placeholder='' value='{{ old('name') }}' required>
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
            <INPUT type=submit name=sub class='btn btn-success' value='錄取查詢'>
            <INPUT type=reset  class='btn btn-danger' value='清除重來'>
        </div>
    </div>
</form>
@stop