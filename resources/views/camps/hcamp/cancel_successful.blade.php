@extends('camps.hcamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>取消報名 / 取消參加</h4>
    </div>
    <div class="card">
        <div class="card-header">
            成功
        </div>
        <div class="card-body">
            <p class="card-text">
                您已成功取消報名/參加{{ $camp_data->fullName }}。<br>
                若您已繳款，退款程序將統一於營隊結束後二週內辦理，並於退款中，另外扣除匯款手續費 30 元。<br>
                <br>
                相關退費資訊，請點選以下連結了解： <br>
                <a href="https://reurl.cc/raAjq1" target="_blank" rel="noopener noreferrer">https://reurl.cc/raAjq1</a><br>
                <br>
                若有退費相關問題，請洽 屠小姐 02-7751-6799#520038
            </p>
            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
        </div>
    </div>
@stop