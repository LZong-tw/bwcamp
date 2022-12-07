@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 單一錄取程序</h2>
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
            梯次：{{ $candidate->batch->name }} <br>
            報名序號：{{ $candidate->applicant_id }} <br>
            報名日期：{{ $candidate->created_at }} <br>
            @if($candidate->region)
                分區：{{ $candidate->region }} <br>
            @endif
            @if(isset($candidate->group) && isset($candidate->number))
                錄取序號：{{ $candidate->group.$candidate->number }} <br>
            @endif
            <form target="_blank" action="{{ route("queryview", $candidate->batch_id) }}" method="post" class="d-inline">
                @csrf
                <input type="hidden" name="sn" value="{{ $candidate->applicant_id }}">
                <input type="hidden" name="name" value="{{ $candidate->name }}">
                <input type="hidden" name="isBackend" value="目前為後台檢視狀態。">
                <button class="btn btn-info" style="margin-top: 10px">檢視報名資料</button>
            </form>
            <a href="{{ route('showPaymentForm', [$campFullData->id, $candidate->applicant_id]) }}" class="btn btn-primary" target="_blank" style="margin-top: 10px">顯示繳費單</a>
        </p>
        <form action="{{ route("admission", $campFullData->id) }}" method="post" class="form-horizontal">
            @csrf
            <input type="hidden" name="id" value="{{ $candidate->applicant_id }}">
            @if(isset($candidate->group) && isset($candidate->number))
                輸入正取序號(共五碼)：<input type="text" name="admittedSN" class="form-control" placeholder="請確認錄取梯次前綴後再送出修改" pattern=".{5}" required id="admittedSN">
                本梯次正取序號前綴：{{ $candidate->getBatch->admission_suffix }}
                <br><br>
                <input type="submit" class="btn btn-warning" value="修改錄取序號">
                <input type="submit" name="clear" class="btn btn-danger" value="清除錄取序號" onclick="document.getElementById('admittedSN').required = false;">
            @else
                輸入正取序號(共五碼)：<input type="text" name="admittedSN" class="form-control" placeholder="" value="{{ $candidate->getBatch->admission_suffix }}" pattern=".{5}" required>
                <br>
                <input type="submit" class="btn btn-success" value="確認錄取">
            @endif
        </form><br>
        <a href="{{ route("admissionGET", $campFullData->id) }}" class="btn btn-primary">下一筆</a>
    @else
        <p>
            查無資料，請 <a href="{{ route("admissionGET", $campFullData->id) }}" class="btn btn-primary">重新執行</a>。
        </p>
    @endif
@endsection
