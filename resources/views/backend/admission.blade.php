@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 錄取程序</h2>
    <form action="" method="post">
        <input type="text" name="sn" placeholder="請輸入報名序號">
        <input type="submit" value="">
    </form>
@endsection