@extends('other.layout')
@section('content')

<style>
img {
  width: 50%;
  height: auto;
}
</style>

@if($errors->any())
    @foreach ($errors->all() as $message)
        <div class='alert alert-danger' role='alert'>
            {{ $message }}
        </div>
    @endforeach
@endif


@if ($action == 'showTossBlocks')
<form method="post" action="{{ route('tossBlocks') }}" name="TossBlocks" class="form-toss">
    @csrf
    <div class="page-header form-group">
        @if($result>3)
        <h4>請先祈求，並且默念你想要問的問題，再擲筊</h4>
        @elseif($result==0)
        <h4>問題可能不太清楚，想清楚再擲一次</h4>
        @elseif($result==3)
        <h4>你確定你要問的是這個問題？要不要換個問法，或換個問題，再擲一次</h4>
        @endif
    </div>
    <!--- 確認送出 -->
    <div class=row>
        <div class='col-md-8'>
            <INPUT type=submit name=sub class='btn btn-primary' value='擲筊'>
        </div>
    </div>
</form>
@elseif ($action == 'showDrawStick')
<form method="post" action="{{ route('drawStick') }}" name="DrawStick" class="form-draw">
    @csrf
    <input type="hidden" name="action" value="draw">
    <div class="page-header form-group">
        <h4>你可以抽籤啦，請先祈求再抽</h4>
    </div>
    <div class="message form-group" style='display:none'>
        <h4>抽籤中。。。。</h4>
    </div>
    <!--- 確認送出 -->
    <div class=row>
        <div class='col-md-8'>
            <INPUT type=button name=draw class='btn btn-success' value='抽靈籤' onclick="delayandsubmit('draw','message', this.closest('form'));">
        </div>
    </div>
</form>

@elseif ($action == 'showStickContent')
<form method="post" name="ShowStick" action="{{ route('showStick') }}" class="form-show">
    @csrf
    <div class="page-header form-group">
        <h4>您抽到 #{{$result}} 上上籤</h4>
    </div>

    <div class="row form-group">
        <img src="{{ $fp1 }}" >
        <img src="{{ $fp2 }}" >
    </div>

    <!--- 確認送出 -->
    <div class=row>
        <div class='col-md-8'>
            <INPUT type=submit name=sub class='btn btn-success' value='再抽一次'>
        </div>
    </div>
</form>
@endif
@stop

<script>
        function delayandsubmit(name1, name2, form) {
            let myButton = document.getElementsByName(name1);
            myButton[0].disabled=true;
            let myLabel = document.getElementsByClassName(name2);
            myLabel[0].style.display = '';
            setTimeout(() => {
                form.submit();
            }, 3000);
        }
</script>