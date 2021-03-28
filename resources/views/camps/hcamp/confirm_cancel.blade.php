@extends('camps.hcamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>確認取消報名 / 取消參加</h4>
    </div>
    <div class="card alert alert-danger">
        <p class="card-text alert alert-primary">
            報名序號：{{ $applicant->id }} <br>
            姓名：{{ $applicant->name }} <br>
            身分證：{{ $applicant->idno }} <br>
            地址：{{ $applicant->address }} <br>
        </p>
        <p class="card-text alert alert-warning">
            請確認是否取消報名 / 取消參加營隊？
        </p>
        <form action="{{ route('cancel', $batch_id) }}" method="POST">
            @csrf
            <input type="hidden" name="sn" value="{{ $applicant->id }}">
            <input type="submit" class='btn btn-danger' value="確認">
            <input type='button' class='btn btn-success' value='取消' onclick=self.history.back()>
        </form>
    </div>
@stop