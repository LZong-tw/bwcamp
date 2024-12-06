@extends('backend.master')
@section('content')
    <div>
        <h2 class="d-inline-block">{{ $campFullData->abbreviation }} 更新簽到資料</h2>
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
    <form method="POST" class="" name="filesForm" enctype="multipart/form-data" 
        action="{{ route("sign_update", $camp_id) }}">
        @csrf
        <input required type="file" name="fn_sign_update" id="">
        <input type="button" class="btn btn-success" value="上傳" onclick="document.filesForm.submit()">
        <button type="reset" class="btn btn-danger">重設</button>
    </form>
    @if(isset($num_record_add))
    <hr>
    更新結果：<br>
    新增{{$num_record_add}}筆資料<br>
    刪除{{$num_record_delete}}筆資料<br>
    @endif
@endsection
