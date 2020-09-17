@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 錄取程序</h2>
    @if($candidate)
        @if(isset($message))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif
        @if(isset($error))
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endif
        <h4>{{ $candidate->name }}({{ $candidate->gender }})</h4>
        <p>
            報名序號：{{ $candidate->id }} <br>
            @if($candidate->region)
                報名區域：{{ $candidate->region }} <br>
            @endif
            @if(isset($candidate->group) && isset($candidate->number))
                錄取序號：{{ $candidate->group.$candidate->number }} <br>
            @endif
            <a href="" class="btn btn-info">檢視詳細資料</a>
        </p>
        <form action="{{ route("admission", $campFullData->id) }}" method="post" class="form-horizontal">
            @csrf
            <input type="hidden" name="id" value="{{ $candidate->id }}">
            @if(isset($candidate->group) && isset($candidate->number))
                <a href="" class="btn btn-secondary">寄送電子郵件</a><br>
                <br>
                輸入正取序號：<input type="text" name="admittedSN" class="form-control" placeholder="">
                <br>
                <input type="submit" class="btn btn-warning" value="修改錄取序號">
                <input type="submit" name="clear" class="btn btn-danger" value="清除錄取序號">
            @else    
                輸入正取序號：<input type="text" name="admittedSN" class="form-control" placeholder="">
                <br>
                <input type="submit" class="btn btn-success" value="確認錄取">
            @endif
        </form><br>
        <a href="{{ route("admission", $campFullData->id) }}" class="btn btn-primary">下一筆</a>
    @else
        <p>
            查無資料，請 <a href="{{ route("admission", $campFullData->id) }}" class="btn btn-primary">重新執行</a>。
        </p>
    @endif
@endsection