@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 寄送自定郵件</h2>
    <h5>請選擇寄送模式</h5>
    <a href="{{ route("writeMail", $campFullData->id) }}?target=all" class="btn btn-success">全體錄取人士</a>
    <a href="{{ route("selectMailTarget", $campFullData->id) }}" class="btn btn-primary">指定錄取人士，點選後將進入場次及組別名單選擇收件人</a>
@endsection