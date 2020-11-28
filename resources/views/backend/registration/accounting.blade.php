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
    <h2>{{ $campFullData->abbreviation }} 銷帳資料</h2>
    <table class="table table-bordered">
        <thead>
            <tr class="bg-secondary text-white">
                <th>銷帳流水序號</th>
                <th>實繳金額</th>
                <th>銷帳流水號</th>
                <th>銷帳編號</th>
                <th>繳費日期</th>
                <th>入帳日期</th>
                <th>繳費管道</th>
            </tr>
        </thead>
        @foreach ($accountings as $accounting)
            <tr>
                <td>{{ $accounting->id }}</td>
                <td>{{ $accounting->paid }}</td>
                <td>{{ $accounting->sn }}</td>
                <td>{{ $accounting->no }}</td>
                <td>{{ $accounting->paid_at }}</td>
                <td>{{ $accounting->creditted_at }}</td>
                <td>{{ $accounting->name }}</td>
            </tr>
        @endforeach
    </table>
@endsection