@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 職務適用範圍</h2>
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

    <form action="{{ route("batchAdmission", $campFullData->id) }}" method="post">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>報名序號</th>
                    <th>姓名 / 生理性別</th>
                    <th>分區</th>
                    <th>錄取序號</th>
                </tr>
            </thead>
                <tr>
                    <td>{{ $applicant?->id }}</td>
                    <td>
                        {{ $applicant?->name }}({{ $applicant?->gender }})
                    </td>
                    <td>{{ $applicant?->region }}</td>
                    <td>{{ $applicant?->group.$applicant?->number }}</td>
                </tr>
        </table>
        <button type="submit" class="btn btn-success">確認批次錄取</button>
    </form>
    <br>
    <a href="{{ route("batchAdmissionGET", $campFullData->id) }}" class="btn btn-primary">繼續批次錄次</a>
@endsection
