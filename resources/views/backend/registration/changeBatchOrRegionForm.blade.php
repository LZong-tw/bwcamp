@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 修改梯次 / 區域</h2>
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
        <p>
            <h5>{{ $candidate->name }}({{ $candidate->gender }})</h5>
            報名序號：{{ $candidate->id }} <br>
            區域：{{ $candidate->region }} <br>
            梯次：
            @foreach ($batches as $batch)
                @if($candidate->batch_id == $batch->id) {{ $batch->name }}梯 @endif</option>
            @endforeach <br>
            <form target="_blank" action="{{ route("queryview", $candidate->batch_id) }}" method="post" class="d-inline">
                @csrf
                <input type="hidden" name="sn" value="{{ $candidate->id }}">
                <input type="hidden" name="name" value="{{ $candidate->name }}">
                <input type="hidden" name="isBackend" value="目前為後台檢視狀態。">
                <button class="btn btn-info" style="margin-top: 10px">檢視報名資料</button>
            </form>
        </p>
        <form action="{{ route("changeBatchOrRegion", $campFullData->id) }}" method="post" class="form-horizontal">
            @csrf
            <input type="hidden" name="id" value="{{ $candidate->id }}">
            梯次：
            <select class="form-control" name=batch size=1>
                @foreach ($batches as $batch)
                    <option value='{{ $batch->id}}' @if($candidate->batch_id == $batch->id) selected @endif>{{ $batch->name }}梯</option>
                @endforeach
            </select>
            <br>
            區域：<input type="text" name="region" class="form-control" value="{{ $candidate->region }}">
            （區域列表：台北、桃園、新竹、台中、嘉義、台南、高雄、海外）
            <br><br>
            <input type="submit" class="btn btn-success" value="送出修改">
        </form><br>
        <a href="{{ route("changeBatchOrRegion", $campFullData->id) }}" class="btn btn-primary">下一筆</a>
    @else
        <p>
            查無資料，請 <a href="{{ route("changeBatchOrRegion", $campFullData->id) }}" class="btn btn-primary">重新執行</a>。
        </p>
    @endif
@endsection