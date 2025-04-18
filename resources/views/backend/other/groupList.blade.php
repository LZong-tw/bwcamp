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
    <h2>{{ $campFullData->abbreviation }} 自定郵件指定梯次/組別名單</h2>
    @foreach ($batches as $batch)
        <h4 class="card-link"><a href="{{ route("writeMail", $campFullData->id) }}?target=batch&batch_id={{ $batch->id }}">梯次：{{ $batch->name }}</a></h4>
        <table>
            <tr>
                @php  
                    $batchCount = 0;    
                @endphp
                @foreach ($batch->regions as $region)
                <td style="vertical-align: top;">
                    <table class="table table-bordered">
                        <thead><tr class="bg-primary text-white"><th colspan="2">{{ $region->region }}</th></tr></thead>
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($region->groups as $group)
                            <tr>
                                <td><a href="{{ route("writeMail", $campFullData->id) }}?target=group&batch_id={{ $batch->id }}&group_id={{ $group->groupRelation->id }}" class="card-link">{{ $group->groupRelation->alias }}</a></td>
                                <td>{{ $group->count }}</td>
                                @php
                                    $count = $count + $group->count;
                                @endphp
                            </tr>
                        @endforeach
                        <tr class="bg-success text-white">
                            <td>合計</td>
                            <td>{{ $count }}</td>
                            @php 
                                $batchCount = $count + $batchCount;
                            @endphp
                        </tr>
                    </table>
                </td>
                @endforeach
            </tr>
        </table>
        <h4>總人數：{{ $batchCount }}</h4>
        <hr>
    @endforeach
@endsection