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
                                <th colspan="5">{{ $region->region }}</th>
                            </tr>
                            <tr class="bg-secondary text-white">
                                <th>組別</th>
                                <th>錄取人數</th>
                                <th>回覆參加人數</th>
                                <th>回覆不參加人數</th>
                                <th>未回覆人數</th>
                            </tr>
                        </thead>
                        @php
                            $count_total = 0;    
                            $attend_sum_total = 0;
                            $not_attend_sum_total = 0;
                        @endphp
                        @foreach ($region->groups as $group)
                            <tr>
                                <td>
                                    <a href="{{ route("showGroup", [$campFullData->id, $batch->id, $group->group]) }}/?showAttend=1" class="card-link">{{ $group->group }}</a>
                                </td>
                                <td>{{ $group->count }}</td>
                                <td>{{ $group->attend_sum }}</td>
                                <td>{{ $group->not_attend_sum }}</td>
                                <td>{{ $group->count - $group->attend_sum - $group->not_attend_sum }}</td>
                                @php
                                    $count_total = $count_total + $group->count;
                                    $attend_sum_total = $attend_sum_total + $group->attend_sum;
                                    $not_attend_sum_total = $not_attend_sum_total + $group->not_attend_sum;
                                @endphp
                            </tr>
                        @endforeach
                        <tr class="bg-success text-white">
                            <td>合計</td>
                            <td>{{ $count_total }}</td>
                            <td>{{ $attend_sum_total }}</td>
                            <td>{{ $not_attend_sum_total }}</td>
                            <td>{{ $count_total - $attend_sum_total - $not_attend_sum_total }}</td>
                        </tr>
                    </table>
                </td>
                @endforeach
            </tr>
        </table>
        <hr>
    @endforeach
@endsection