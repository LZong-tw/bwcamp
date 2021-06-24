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
    <h2>{{ $campFullData->abbreviation }} 回覆參加</h2>
    @foreach ($batches as $batch)
        <h4>梯次：{{ $batch->name }}</h4>
        <table>
            <tr>
                @foreach ($batch->regions as $region)
                <td style="vertical-align: top;">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th colspan="3">{{ $region->region }}</th>
                            </tr>
                            <tr class="bg-secondary text-white">
                                <th>組別</th>
                                <th>錄取人數</th>
                                <th>回覆參加人數</th>
                            </tr>
                        </thead>
                        @php
                            $count_total = 0;    
                            $sum_total = 0;
                        @endphp
                        @foreach ($region->groups as $group)
                            <tr>
                                <td>
                                    <a href="{{ route("showGroup", [$campFullData->id, $batch->id, $group->group]) }}/?showAttend=1" class="card-link">{{ $group->group }}</a>
                                </td>
                                <td>{{ $group->count }}</td>
                                <td>{{ $group->sum }}</td>
                                @php
                                    $count_total = $count_total + $group->count;
                                    $sum_total = $sum_total + $group->sum;
                                @endphp
                            </tr>
                        @endforeach
                        <tr class="bg-success text-white">
                            <td>合計</td>
                            <td>{{ $count_total }}</td>
                            <td>{{ $sum_total }}</td>
                        </tr>
                    </table>
                </td>
                @endforeach
            </tr>
        </table>
        <hr>
    @endforeach
@endsection