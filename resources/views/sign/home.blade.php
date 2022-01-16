@extends('sign.master')
@section('content')
<style>
    .text-center {
        text-align: center;
    }

    #CenterDIV {
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(255, 255, 255, 0.75);
        width: 100%;
        height: 100%;    
        padding-top: 200px;
        display: none;
    }

    .divFloat {
        margin: 0 auto;
        background-color: #FFF;
        color: #000;
        width: 90%;
        height: auto;
        padding: 20px;
        border: solid 1px #999;
        -webkit-border-radius: 3px;
        -webkit-box-orient: vertical;
        -webkit-transition: 200ms -webkit-transform;
        box-shadow: 0 4px 23px 5px rgba(0, 0, 0, 0.2), 0 2px 6px rgba(0, 0, 0, 0.15);
        display: block;
    }

    .footer {
        background-color: rgba(221, 221, 221, 0.80);
    }
</style>
<div class="container">
    <h2 class="mt-4 text-center">學員簽到退系統</h2>
    @if($errors->any())
        @foreach ($errors->all() as $message)
            <div class='alert alert-danger' role='alert'>
                {{ $message }}
            </div>
        @endforeach
    @endif
    <form action="{{ route("sign_page.search") }}" id="query" method="POST">
        @csrf
        <div class="form-group input-group">
            {{-- <input type="text" class="form-control" name="query_str" id="" placeholder="請任選報名序號、姓名、手機輸入..." value="{{ old("query_str") }}" required> --}}
            <input type="text" class="form-control" name="name" id="" placeholder="請輸入姓名全名..." value="{{ old("name", $name ?? "") }}" required>
        </div>
        <div class="form-group input-group">
            {{-- <input type="text" class="form-control" name="admitted_no" id="" placeholder="請輸入錄取序號..." value="{{ old("admitted_no") }}" maxlength="5" minlength="5" required> --}}
            <input type="text" class="form-control" name="mobile" id="" placeholder="請輸入手機..." value="{{ old("mobile", $mobile ?? "") }}" required>
        </div>
        <div class="form-group input-group justify-content-center">
            <button class="btn btn-outline-success" type="submit" id="signinsearch">
                Go <i class="fa fa-search"></i>
            </button> 
            <button class="btn btn-outline-danger ml-1" type="reset" id="">
                Reset <i class="fas fa-redo-alt"></i>
            </button>
        </div>
    </form>
    @if(isset($applicant))
        @if(isset($message) && $message['status'])
            <div class='alert alert-success' role='alert'>
                {{ $message['message'] }}
            </div>
        @endif
        <table class="table table-bordered table-hover">
            @if(isset($message) && !$message['status'])
                <div class='alert alert-warning' role='alert'>
                    {{ $message['message'] }}
                </div>
            @else
                <tr>
                    <td>
                        @if (!$isSigned)
                            <div class="text-danger">請確認以下為個人資料及最近簽到退資料後，再進行簽到/簽退</div>
                            報名序號：{{ $applicant->id }} <br>
                            錄取序號：{{ $applicant->group . $applicant->number }} <br>
                            姓名：{{ $applicant->name }} <br>
                            手機：{{ $applicant->mobile }} <br>
                            <form action="{{ route("sign_page.store") }}" method="POST">
                                @csrf
                                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                                <input type="hidden" name="availability_id" value="{{ $signInfo->id }}">
                                @if ($signInfo->type == "in" || !$signInfo->type)
                                    <button class="btn btn-outline-success" type="submit" id="signin">
                                        簽到 <i class="fas fa-sign-in-alt"></i>
                                    </button>
                                @else
                                    <button class="btn btn-outline-danger" type="submit" id="signout">
                                        簽退 <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                @endif
                            </form>
                        @else
                            <div class="text-success">已完成{{ $isSigned->type == 'in' || !$isSigned->type ? "簽到" : "簽退" }}</div>
                        @endif
                    </td>
                </tr>
            @endif
            <tr>
                <td colspan="3">簽到退記錄</td>
            </tr>
            @foreach ($applicant->batch->sign_info as $sign_info)
                <tr>
                    <td>{{ $sign_info->sign_time }} {{ $sign_info->type == "in" ? '簽到' : '簽退' }}</td>
                    <td>{{ $applicant->hasAlreadySigned($sign_info->id) ? "✔️" : "❌" }}</td>
                </tr>       
            @endforeach
        </table>
    @endif
</div>  
@endsection