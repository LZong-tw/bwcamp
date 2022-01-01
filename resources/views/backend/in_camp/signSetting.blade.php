@extends('backend.master')
@section('content')
    <div>
        <h2 class="d-inline-block">{{ $campFullData->abbreviation }} 簽到設定</h2>
    </div>
    @foreach ($batches as $batch)
        <h4>梯次：{{ $batch->name }}</h4>
        @foreach ($batch->days as $day)
            <h6>{{ $day }}</h6>
            <table class="table table-bordered">
                <tr class="">
                    <th>開始時間</th>
                    <th>結束時間</th>
                    <th>操作</th>
                </tr>
                {{-- @foreach ($batches as $batch)

                    <tr>
                        <td>{{ $applicant->sn }}</td>
                        <td>{{ $applicant->group }}{{ $applicant->number }}</td>
                        <td>{{ $applicant->name }}</td>
                    </tr>
                @endforeach --}}
            </table>
        @endforeach
    @endforeach
    @if(Session::has("message"))
        <div class="alert alert-success" role="alert">
            {{ Session::get("message") }}
        </div>
    @endif
    @if(Session::has("error"))
        <div class="alert alert-danger" role="alert">
            {{ Session::get("error") }}
        </div>
    @endif
@endsection
