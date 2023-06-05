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
    <h2>{{ $campFullData->abbreviation }} 組別名單</h2>
            <table class="table table-bordered">
                @php
                    $count = 0;
                @endphp
                @foreach ($roles as $role)
                <tr>
                    <td><a href="{{ route('showSection', [$campFullData->id, $role->org_id]) }}" class="card-link">{{ $role->section }}{{$role->position }}</a></td>
                    <td>{{ $role->count }}</td>
                    @php
                        $count = $count + $role->count;
                    @endphp
                </tr>
                @endforeach
                <tr class="bg-success text-white">
                    <td>合計</td>
                    <td>{{ $count }}</td>
                </tr>
            </table>
    <hr>
@endsection
