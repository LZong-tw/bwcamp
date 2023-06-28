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
            </tr>
            @php
                $count = 0;    
            @endphp
            @foreach ($traffic_depart as $t)
                <tr>
                    <td>{{ $t->traffic_depart }}</td>
                    <td>{{ $t->count }}</td>
                </tr>
                @php
                    $count += $t->count;    
                @endphp
            @endforeach
        </table>
        共 {{ $count }} 位
        <h5>回程</h5>
        <table class="table table-bordered">
            <tr>
                <th>地點</th>
                <th>人數</th>
            </tr>
            @php
                $count = 0;    
            @endphp
            @foreach ($traffic_return as $t)
                <tr>
                    <td>{{ $t->traffic_return }}</td>
                    <td>{{ $t->count }}</td>
                </tr>
                @php
                    $count += $t->count;    
                @endphp
            @endforeach
        </table>
        共 {{ $count }} 位
        <br><br>
        <h5>自往+自回</h5>
        <table class="table table-bordered">
            <tr>
                <th>姓名</th>
            </tr>
            @php
                $count = 0;    
            @endphp
            @foreach ($applicants as $applicant)
                @if($applicant->traffic != null && $applicant->traffic->depart_from == "自往" && $applicant->traffic->back_to == "自回")
                    <tr>
                        <td>{{ $applicant->name }}</td>
                    </tr>
                    @php
                        $count++;    
                    @endphp
                @else
                    @continue
                @endif
            @endforeach
        </table>
        共 {{ $count }} 位
        <hr>
    @endforeach
@endsection