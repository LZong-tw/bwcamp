@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 批次錄取程序</h2>
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

    @if(isset($applicants))
        <form action="" method="post">
            @csrf
            <table class="table table-bordered"> 
                <thead>
                    <tr>
                        <th>報名序號</th>
                        <th>姓名 / 生理性別</th>
                        <th>分區</th>
                        <th>錄取序號</th>
                        <th>輸入錄取序號</th>
                    </tr>
                </thead>
                @foreach ($applicants as $applicant)
                    <tr>
                        <td>{{ $applicant->id }}</td>
                        <td>
                            {{ $applicant->name }}({{ $applicant->gender }})
                            {{-- <form target="_blank" action="{{ route("queryview", $applicant->batch_id) }}" method="post" class="d-inline">
                                @csrf
                                <input type="hidden" name="sn" value="{{ $applicant->id }}">
                                <input type="hidden" name="name" value="{{ $applicant->name }}">
                                <input type="hidden" name="isBackend" value="目前為後台檢視狀態。">
                                <button class="btn btn-info" style="margin-top: 10px">檢視報名資料</button>
                            </form> --}}
                        </td>
                        <td>{{ $applicant->region }}</td>
                        <td>{{ $applicant->group.$applicant->number }}</td>
                        <td>
                            @if($applicant->gender !== "N/A")
                                <input type="hidden" name="id[]" value="{{ $applicant->id }}">
                                <input type="text" name="admittedSN[]" class="form-control">
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
            <button type="submit" class="btn btn-success">確認批次錄取</button>
        </form>
    @endif
    <br>
    {{-- <p>
        <h5>{{ $candidate->name }}({{ $candidate->gender }})</h5>
        報名序號：{{ $candidate->id }} <br>
        @if($candidate->region)
            分區：{{ $candidate->region }} <br>
        @endif
        @if(isset($candidate->group) && isset($candidate->number))
            錄取序號：{{ $candidate->group.$candidate->number }} <br>
        @endif
        <form target="_blank" action="{{ route("queryview", $candidate->batch_id) }}" method="post" class="d-inline">
            @csrf
            <input type="hidden" name="sn" value="{{ $candidate->id }}">
            <input type="hidden" name="name" value="{{ $candidate->name }}">
            <input type="hidden" name="isBackend" value="目前為後台檢視狀態。">
            <button class="btn btn-info" style="margin-top: 10px">檢視報名資料</button>
        </form>
    </p>
    <form action="{{ route("admission", $campFullData->id) }}" method="post" class="form-horizontal">
        @csrf
        <input type="hidden" name="id" value="{{ $candidate->id }}">
        @if(isset($candidate->group) && isset($candidate->number))
            <a href="" class="btn btn-secondary" style="margin-bottom: 15px">寄送電子郵件</a>
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
    </form><br> --}}
    <a href="{{ route("batchAdmission", $campFullData->id) }}" class="btn btn-primary">繼續批次錄次</a>
@endsection