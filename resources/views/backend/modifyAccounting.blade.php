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
            @if($campFullData->table=='ycamp')
            應繳金額：{{ $applicant->traffic->fare ?? 0 }} <br>
            轉帳繳費：{{ $applicant->traffic->deposit ?? 0 }} <br>
            現金繳費：{{ $applicant->traffic->cash ?? 0 }} <br>
            已交金額（轉帳＋現金）：{{ $applicant->traffic->sum ?? 0 }} <br>
            @else
            應繳金額：{{ $applicant->fee ?? 0 }} <br>
            已繳金額：{{ $applicant->deposit ?? 0 }} <br>
            繳費狀態：{{ $applicant->payment_status }}
            @endif
        </p>
        <hr>
        @if($campFullData->table=='ycamp')
            @php 
                $cash=0 
            @endphp
            <form action="{{ route("modifyAccounting", $campFullData->id) }}" method="post" class="form-horizontal">
                @csrf
                <input type="hidden" name="id" value="{{ $applicant->applicant_id }}">

                <div class='row form-group required'>
                    <label for='inputCash' class='col-md-2 control-label text-md-left'>現場手動（現金）繳費金額</label>
                    <div class='col-md-10'>
                        <input type="text" class="form-control" name="cash" placeholder="填寫現場手動（現金）繳費金額" required><br>
                        <div class="invalid-feedback">
                            請填寫輸入現場手動（現金）繳費金額
                        </div>
                    </div>
                </div>
                <div class='row form-group required'>
                    <label for='inputModifyMethod' class='col-md-2 control-label text-md-left'>修改方式</label>
                    <div class='col-md-10'>
                        <label class=radio-inline>
                            <input type=radio required name='is_add' value=replace checked> 覆寫現金繳費
                            <div class="invalid-feedback">
                                請選擇修改方式
                            </div>
                        </label> 
                        <label class=radio-inline>
                            <input type=radio required name='is_add' value=add > 加入現金繳費
                            <div class="invalid-feedback">
                                &nbsp;
                            </div>
                        </label> 
                    </div>
                </div>
            <input type="submit" class="btn btn-success" value="確認">
            </form>
            <br>
            <a href="{{ route('modifyAccountingGET', $campFullData->id) }}" class="btn btn-primary">下一筆</a>

        @else
            @if(!$applicant->showCheckInInfo || $applicant->deposit > $applicant->fee)
                <form action="{{ route("modifyAccounting", $campFullData->id) }}" method="post" class="form-horizontal">
                    @csrf
                    <input type="hidden" name="id" value="{{ $applicant->applicant_id }}">
                    確認報名序號或錄取序號：<input type="text" class="form-control" name="double_check" placeholder="輸入報名序號或錄取序號" required><br>
                    <input type="submit" class="btn btn-success" value="後台繳費 / 繳費金額調整">
                </form>
            @endif
        @endif
    @else
        <p>
            查無資料，請 <a href="{{ route("modifyAccountingGET", $campFullData->id) }}" class="btn btn-primary">重新執行</a>。
        </p>
    @endif
@endsection
