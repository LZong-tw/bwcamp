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
    <h2>{{ $campFullData->abbreviation }} 交通名單</h2>
    @foreach ($batches as $batch)
        <h4 class="card-link"><a href="{{ route("writeMail", $campFullData->id) }}?target=batch&batch_id={{ $batch->id }}">梯次：{{ $batch->name }}</a></h4>
        <h5>去程</h5>
        <table class="table table-bordered">
            <tr>
                <th>地點</th>
                <th>人數</th>
                <th>動作</th>
            </tr>
            @php
                $count_depart = 0;    
            @endphp
            @foreach ($traffic_depart as $t)
                <tr>
                    <td>{{ $t->traffic_depart }}</td>
                    <td>{{ $t->count }}</td>
                    <td><a href="{{ route("showTrafficListLoc", $campFullData->id) }}?batch_id={{ $batch->id }}&depart_from={{ $t->traffic_depart }}" class="btn btn-info">看名單</a>
                    <a href="{{ route("showTrafficListLoc", $campFullData->id) }}?batch_id={{ $batch->id }}&depart_from={{ $t->traffic_depart }}" class="btn btn-warning">下載名單(待完成)</a></td>
                </tr>
                @php
                    $count_depart += $t->count;    
                @endphp
            @endforeach
        </table>
        共 {{ $count_depart }} 位
        <h5>回程</h5>
        <table class="table table-bordered">
            <tr>
                <th>地點</th>
                <th>人數</th>
                <th>動作</th>
            </tr>
            @php
                $count_return = 0;    
            @endphp
            @foreach ($traffic_return as $t)
                <tr>
                    <td>{{ $t->traffic_return }}</td>
                    <td>{{ $t->count }}</td>
                    <td><a href="{{ route("showTrafficListLoc", $campFullData->id) }}?batch_id={{ $batch->id }}&depart_from={{ $t->traffic_return }}" class="btn btn-info">看名單</a>
                    <a href="{{ route("showTrafficListLoc", $campFullData->id) }}?batch_id={{ $batch->id }}&depart_from={{ $t->traffic_return }}" class="btn btn-warning">下載名單(待完成)</a></td>
                </tr>
                @php
                    $count_return += $t->count;    
                @endphp
            @endforeach
        </table>
        共 {{ $count_return }} 位
        <br><br>
    @endforeach
@endsection