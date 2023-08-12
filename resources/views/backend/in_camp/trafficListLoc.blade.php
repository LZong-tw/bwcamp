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
    <h2>{{ $camp->abbreviation }}{{ $batch->name }}梯〈{{ $direction }}〉〈{{ $location }}〉交通名單</h2>
        @php
            $count=1;
        @endphp
        <table class="table table-bordered">
            <tr>
                <th>編號</th>
                <th>錄取序號</th>
                <th>姓名</th>
                <th>性別</th>
                <th>手機</th>
            </tr>
            @foreach ($applicants as $applicant)
                <tr>
                    <td>{{ $count}}</td>
                    <td>{{ $applicant->group }}{{ $applicant->number }}</td>
                    <td>{{ $applicant->name}}</td>
                    <td>{{ $applicant->gender}}</td>
                    <td>{{ $applicant->mobile}}</td>
                </tr>
                @php
                    $count++;
                @endphp
            @endforeach
        </table>
@endsection