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
                        <th>輸入錄取序號(共五碼)</th>
                    </tr>
                </thead>
                @foreach ($applicants as $applicant)
                    <tr>
                        <td>{{ $applicant->applicant_id }}</td>
                        <td>
                            {{ $applicant->name }}({{ $applicant->gender }})
                        </td>
                        <td>{{ $applicant->region }}</td>
                        <td>{{ $applicant->group.$applicant->number }}</td>
                        <td>
                            @if($applicant->gender !== "N/A")
                                <input type="hidden" name="id[]" value="{{ $applicant->applicant_id }}">
                                @if(!$applicant->group && !$applicant->number)
                                    <input type="text" name="admittedSN[]" value="{{ $applicant->getBatch->admission_suffix }}" class="form-control" required pattern=".{5}">
                                @else
                                    <input type="text" name="admittedSN[]" value="{{ $applicant->group.$applicant->number }}" class="form-control" required pattern=".{5}">
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
            <button type="submit" class="btn btn-success">確認批次錄取</button>
        </form>
    @endif
    <br>
    <a href="{{ route("batchAdmissionGET", $campFullData->id) }}" class="btn btn-primary">繼續批次錄次</a>
@endsection
