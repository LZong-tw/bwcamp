@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 批次錄取程序</h2>
    @if(isset($message))
        @foreach ($message as $m)
            <div class="alert alert-success" role="alert">
                {{ $m }}
            </div>
        @endforeach
    @endif
    @if(isset($error))
        @foreach ($error as $e)
            <div class="alert alert-danger" role="alert">
                {{ $e }}
            </div>
        @endforeach
    @endif

    @if(isset($applicants))
        <form action="{{ route("batchAdmission", $campFullData->id) }}" method="post">
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
                        </td>
                        <td>{{ $applicant->region }}</td>
                        <td>{{ $applicant->group.$applicant->number }}</td>
                        <td>
                            @if($applicant->gender !== "N/A" && !$applicant->group && !$applicant->number)
                                <input type="hidden" name="id[]" value="{{ $applicant->id }}">
                                <input type="text" name="admittedSN[]" class="form-control" required>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
            <button type="submit" class="btn btn-success">確認批次錄取</button>
        </form>
    @endif
    <br>
    <a href="{{ route("batchAdmission", $campFullData->id) }}" class="btn btn-primary">繼續批次錄次</a>
@endsection