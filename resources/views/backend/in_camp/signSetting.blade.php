@extends('backend.master')
@section('content')
    <div>
        <h2 class="d-inline-block">{{ $campFullData->abbreviation }} 簽到設定</h2>
    </div>
    @foreach ($batches as $batch)
        <h4>梯次：{{ $batch->name }}</h4>
        @if ($instant)
            <h5>{{ now()->format('Y-m-d') }} 快速設定</h5> 
            <form action="{{ route("sign_set_back", $campFullData->id) }}" method="post">
                @csrf
                開始時間：<input type="time" name="start" required> <br>
                結束時間：<input type="time" name="end" required> <br>
            </form>
        @endif
        @foreach ($batch->days as $day)
            <h5>{{ $day }}</h5>
            <table class="table table-bordered">
                @forelse ($batch->sign_info($day) as $row)
                    <tr class="">
                        <td>開始時間</td>
                        <td>{{ $row->start }}</td>
                        <td>結束時間</td>
                        <td>{{ $row->end }}</td>
                        <td>
                            <a href="" class="btn btn-danger">刪除</a>
                        </td>
                    </tr>
                @empty
                    未設定
                @endforelse
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
