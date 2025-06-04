@extends('backend.master')
@section('content')
    <div>
        <h2 class="d-inline-block">{{ $campFullData->fullName }} 上傳報名資料</h2>
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
        action="{{ route("registrationUpload", $camp_id) }}">
        @csrf
        <div class='form-group required'>
            選擇梯次（不同梯次請分別上傳）＊
            <select required name='batch_id' id='selBatchId' class='form-control' onChange=''>
                    <option value='' selected>- 請選擇 -</option>
                    @foreach($batches as $batch)
                    <option value={{ $batch->id }} >{{ $batch->name }}</option>
                    @endforeach
            </select>
        </div>
        <div class='form-group required'>
            <input required type="file" name="fn_registration_upload" id="selFile">
        </div>
        <div class='form-group required'>
            <input type="button" class="btn btn-success" value="上傳" onclick="document.filesForm.submit()">
            <button type="reset" class="btn btn-danger">重設</button>
        </div>
    </form>
    @if(isset($create_count))
    <hr>
    更新結果：<br>
    新增{{ $create_count }}筆資料<br>
    更新{{ $update_count }}筆資料<br>
    @endif
@endsection
