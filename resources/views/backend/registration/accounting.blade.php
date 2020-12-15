@extends('backend.master')
@section('content')
    <style>
        .card-link{
            color: #3F86FB!important;
        }
        .card-link:hover{
            color: #33B2FF!important;
        }
    </style>
    <div>
        <h2 class="d-inline-block">{{ $campFullData->abbreviation }} 銷帳資料</h2>
        <a href="{{ route("accounting", $campFullData->id) }}?download=1" class="btn btn-primary d-inline-block" style="margin-bottom: 14px">下載資料</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr class="bg-secondary text-white">
                <th>銷帳流水序號</th>
                <th>營隊梯次</th>
                <th>姓名</th>
                <th>應繳金額</th>
                <th>實繳金額</th>
                <th>銷帳流水號</th>
                <th>銷帳編號</th>
                <th>繳費日期</th>
                <th>入帳日期</th>
                <th>繳費管道</th>
                <th>建檔日期</th>
            </tr>
        </thead>
        @foreach ($accountings as $accounting)
            <tr>
                <td>{{ $accounting->id }}</td>
                <td>{{ $accounting->batch->camp->abbreviation }} - {{ $accounting->batch->name }}</td>
                <td>{{ $accounting->aName }}</td>
                <td>{{ $accounting->shouldPay }}</td>
                <td>{{ $accounting->amount }}</td>
                <td>{{ $accounting->accounting_sn }}</td>
                <td>{{ $accounting->accounting_no }}</td>
                <td>{{ $accounting->paid_at }}</td>
                <td>{{ $accounting->creditted_at }}</td>
                <td>{{ $accounting->name }}</td>
                <td>{{ $accounting->created_at }}</td>
            </tr>
        @endforeach
    </table>
@endsection