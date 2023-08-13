@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 設定取消參加</h2>
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
            @if(!isset($applicant->is_attend))
                參加意願：<label class="text-primary">尚未聯絡。</label>
            @elseif($applicant->is_attend == 1)
                參加意願：<label class="text-success">參加。</label>
            @elseif($applicant->is_attend == 0)
                參加意願：<label class="text-danger">不參加。</label>
            @elseif($applicant->is_attend == 2)
                參加意願：<label class="text-secondary">尚未決定。</label>
            @elseif($applicant->is_attend == 3)
                參加意願：<label class="text-secondary">聯絡不上。</label>
            @elseif($applicant->is_attend == 4)
                參加意願：<label class="text-secondary">無法全程。</label>
            @endif
        </p>
        <hr>
        @if ($applicant->deleted_at)
            <label class="text-danger">
                本學員已取消報名。
            </label>            
        @else
            <form action="{{ route('toggleAttendBackend', $applicant->batch->id) }}" method="POST">
                    @csrf
                <input type="hidden" name="id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                <label><input type="radio" name="is_attend" id="" value="0">不參加</label>
                <label><input type="radio" name="is_attend" id="" value="1">參加</label>
                <label><input type="radio" name="is_attend" id="" value="2">尚未決定</label>
                <label><input type="radio" name="is_attend" id="" value="3">聯絡不上</label>
                <label><input type="radio" name="is_attend" id="" value="4">無法全程</label>
                <br>
                <input class="btn btn-success" type="submit" value="修改參加狀態">
            </form>
        @endif

        <br>
        <a href="{{ route('modifyAttendGET', $campFullData->id) }}" class="btn btn-primary">下一筆</a>
    @else
        <p>
            查無資料，請 <a href="{{ route("modifyAttendGET", $campFullData->id) }}" class="btn btn-primary">重新執行</a>。
        </p>
    @endif
@endsection
