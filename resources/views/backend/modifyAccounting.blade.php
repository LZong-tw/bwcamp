@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 現場手動繳費 / 修改繳費資料</h2>
    @if($applicant)
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
            <h5>{{ $applicant->name }}({{ $applicant->gender }})</h5>
            梯次：{{ $applicant->batch->name }} <br>
            報名序號：{{ $applicant->id }} <br>
            分區：{{ $applicant->region }} <br>
            錄取序號：{{ $applicant->group.$applicant->number }} <br>
            應繳金額：{{ $applicant->fee ?? 0 }} <br>
            已繳金額：{{ $applicant->deposit ?? 0 }} <br>
            繳費狀態：{{ $applicant->payment_status }}
        </p>
        @if(!$applicant->showCheckInInfo || $applicant->deposit > $applicant->fee)
            <form action="{{ route("modifyAccounting", $campFullData->id) }}" method="post" class="form-horizontal">
                @csrf
                <input type="hidden" name="id" value="{{ $applicant->id }}">
                確認報名序號：<input type="text" class="form-control" name="double_check" placeholder="輸入報名序號" required><br>
                <input type="submit" class="btn btn-success" value="後台繳費 / 繳費金額調整">
            </form>
        @endif
    @else
        <p>
            查無資料，請 <a href="{{ route("modifyAccounting", $campFullData->id) }}" class="btn btn-primary">重新執行</a>。
        </p>
    @endif
@endsection