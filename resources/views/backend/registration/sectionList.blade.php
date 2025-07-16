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
    @foreach ($campFullData->batchs as $batch)
        <h4>梯次：{{ $batch->name }}</h4>
        <table>
            <tr>
                <td style="vertical-align: top;">
                    <table style="width:100%; height:100%;" class="table table-bordered">
                        <thead><tr class="bg-primary text-white"><th colspan="2">{{ $batch->name }}</th></tr></thead>
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($orgs as $org)
                            <tr>
                                <td><a href="{{ route("showSection", [$campFullData->id, $org->id]) }}" class="card-link">{{ $org->section }}.{{ $org->position }}</a></td>
                                <td>{{ $org->user_count() }}</td>
                                @php
                                    $count = $count + $org->user_count();
                                @endphp
                            </tr>
                        @endforeach
                        <tr class="bg-success text-white">
                            <td>合計</td>
                            <td>{{ $count }}</td>
                        </tr>
                    </table>
                </td> 
            </tr>
        </table>
        <hr>
    @endforeach
    <!--
            <table class="table table-bordered">
                @php
                    $count = 0;
                @endphp
                @foreach ($orgs as $org)
                <tr>
                    <td><a href="{{ route('showSection', [$campFullData->id, $org->id]) }}" class="card-link">{{ $org->section }}.{{$org->position }}</a></td>
                    <td>{{ $org->user_count() }}</td>
                    @php
                        $count = $count + $org->user_count();
                    @endphp
                </tr>
                @endforeach
                <tr class="bg-success text-white">
                    <td>合計</td>
                    <td>{{ $count }}</td>
                </tr>
            </table>
    -->
    <hr>
@endsection
