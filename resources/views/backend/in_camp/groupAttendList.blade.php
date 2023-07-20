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
                                <th colspan="10">{{ $region->region }}</th>
                            </tr>
                            <tr class="bg-secondary text-white">
                                <th>組別</th>
                                <th>錄取人數</th>
                                <th>回覆參加人數</th>
                                <th>回覆參加人數(男)</th>
                                <th>回覆參加人數(女)</th>
                                <th>回覆不參加人數</th>
                                <th>尚未決定人數</th>
                                <th>聯絡不上人數</th>
                                <th>無法全程人數</th>
                                <th>未回覆 / 尚未聯絡人數</th>
                            </tr>
                        </thead>
                        @php
                            $attend_sum_total = 0;
                            $attend_sum_f_total = 0;
                            $attend_sum_m_total = 0;
                            $not_attend_sum_total = 0;
                            $not_decided_yet_sum_total = 0;
                            $couldnt_contact_sum_total = 0;
                            $cant_full_event_sum_total = 0;
                            $null_sum_total = 0;
                        @endphp
                        @foreach ($region->groups as $group)
                            <tr>
                                <td>
                                    <a href="{{ route("showGroup", [$campFullData->id, $batch->id, $group->group]) }}/?showAttend=1" class="card-link">{{ $group->group }}</a>
                                </td>
                                <td>{{ $group->count }}</td>
                                <td>{!! "<a style='color: green;'>" . $group->attend_sum . "</a>" !!}</td>
                                <td>{!! "<a style='color: green;'>" . $group->attend_sum_m . "</a>" !!}</td>
                                <td>{!! "<a style='color: green;'>" . $group->attend_sum_f . "</a>" !!}</td>
                                <td>{!! "<a style='color: red;'>" . $group->not_attend_sum . "</a>" !!}</td>
                                <td>{!! "<a style='color: #ffb429;'>" . $group->not_decided_yet_sum . "</a>" !!}</td>
                                <td>{!! "<a style='color: pink;'>" . $group->couldnt_contact_sum . "</a>" !!}</td>
                                <td>{!! "<a style='color: seagreen;'>" . $group->cant_full_event_sum . "</a>" !!}</td>
                                <td>{!! "<a style='color: rgb(0, 132, 255);'>" . $group->null_sum . "</a>" !!}</td>
                                @php
                                    $attend_sum_total += $group->attend_sum;
                                    $attend_sum_m_total += $group->attend_sum_m;
                                    $attend_sum_f_total += $group->attend_sum_f;
                                    $not_attend_sum_total += $group->not_attend_sum;
                                    $not_decided_yet_sum_total += $group->not_decided_yet_sum;
                                    $couldnt_contact_sum_total += $group->couldnt_contact_sum;
                                    $cant_full_event_sum_total += $group->cant_full_event_sum;
                                    $null_sum_total += $group->null_sum;
                                @endphp
                            </tr>
                        @endforeach
                        <tr class="bg-success text-white">
                            <td>合計</td>
                            <td>{{ $attend_sum_total + $not_attend_sum_total + $not_decided_yet_sum_total + $couldnt_contact_sum_total + $cant_full_event_sum_total + $null_sum_total }}</td>
                            <td>{{ $attend_sum_total }}</td>
                            <td>{{ $attend_sum_m_total }}</td>
                            <td>{{ $attend_sum_f_total }}</td>
                            <td>{{ $not_attend_sum_total }}</td>
                            <td>{{ $not_decided_yet_sum_total }}</td>
                            <td>{{ $couldnt_contact_sum_total }}</td>
                            <td>{{ $cant_full_event_sum_total }}</td>
                            <td>{{ $null_sum_total }}</td>
                        </tr>
                    </table>
                </td>
                @endforeach
            </tr>
        </table>
        <hr>
    @endforeach
@endsection
