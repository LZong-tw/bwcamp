@extends('backend.master')
@section('content')
    <div>
        <h2 class="d-inline-block">{{ $campFullData->abbreviation }} 簽到退時間設定</h2>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                @if ($loop->last)
                    {{ $error }}
                @else
                    {{ $error }} <br>
                @endif
            @endforeach
        </div>
    @endif
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
    @foreach ($batches as $batch)
        <h4>梯次：{{ $batch->name }}</h4>
        @if ($instant)
            <h5>{{ now()->format('Y-m-d') }} 快速設定</h5>
            <form action="{{ route("sign_set_back", $campFullData->id) }}" method="post">
                @csrf
                <input type="hidden" name="day" value="{{ now()->format('Y-m-d') }}">
                <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                類別：
                <select name="type" id="" class="form-control col-md-6 d-inline-block" required>
                    <option value="">請選擇</option>
                    <option value="in" {{ old("type") == "in" ? "selected" : "" }}>簽到</option>
                    <option value="out" {{ old("type") == "out" ? "selected" : "" }}>簽退</option>
                </select> <br>
                開始時間：<input type="time" name="start" required class="form-control col-md-6 d-inline-block mb-2" value="{{ old("start") }}"> <br>
                結束時間：<input type="time" name="end" class="form-control col-md-6 d-inline-block mb-1" value="{{ old("end") }}"> <br> 或
                <input type="integer" min="0" name="duration" class="form-control col-md-1 d-inline-block" value="{{ old("duration") }}"> 分鐘後結束 <br>
                <button class="btn btn-success mt-2 mb-2">送出</button>
            </form>
        @endif
        <hr>
        @foreach ($batch->days as $day)
            <h5>{{ $day }}</h5>
            <table class="table table-bordered text-center" style="width: 50%">
                @if ($day != now()->format('Y-m-d'))
                    <form action="{{ route("sign_set_back", $campFullData->id) }}" method="post">
                        @csrf
                        <input type="hidden" name="day" value="{{ $day }}">
                        <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                        類別：
                        <select name="type" id="" class="form-control col-md-6 d-inline-block" required>
                            <option value="">請選擇</option>
                            <option value="in" {{ old("type") == "in" ? "selected" : "" }}>簽到</option>
                            <option value="out" {{ old("type") == "out" ? "selected" : "" }}>簽退</option>
                        </select> <br>
                        開始時間：<input type="time" name="start" required class="form-control col-md-6 d-inline-block mb-2" value="{{ old("start") }}"> <br>
                        結束時間：<input type="time" name="end" class="form-control col-md-6 d-inline-block mb-1" value="{{ old("end") }}"> <br> 或
                        <input type="integer" min="0" name="duration" class="form-control col-md-1 d-inline-block" value="{{ old("duration") }}"> 分鐘後結束 <br>
                        <button class="btn btn-success mt-2 mb-2">送出</button>
                    </form>
                @endif
                @foreach ($batch->sign_info($day) as $key => $row)
                    <tr class="">
                        <td class="align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ $row->type == "in" || !$row->type ? "簽到" : "簽退" }}</td>
                        <td class="align-middle">{{ substr($row->start, 11, 5) }} ~ {{ substr($row->end, 11, 5) }}</td>
                        <td class="align-middle">
                            <form action="{{ route("sign_delete_back", [$campFullData->id, $row->id]) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button class="btn btn-danger">刪除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
            <hr>
        @endforeach
    @endforeach
@endsection
