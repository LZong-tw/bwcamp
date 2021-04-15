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
        <table class="table table-bordered">
            <tr>
                <th>姓名</th>
                <th>去程</th>
                <th>回程</th>
            </tr>
            @php
                $count = 0;    
            @endphp
            @foreach ($applicants as $applicant)
                @if($applicant->$camp->traffic_depart == "自往" && $applicant->$camp->traffic_return == "自往")
                    @continue
                @else
                    <tr>
                        <td>{{ $applicant->name }}</td>
                        <td>{{ $applicant->$camp->traffic_depart }}</td>
                        <td>{{ $applicant->$camp->traffic_return }}</td>
                    </tr>
                    @php
                        $count++;    
                    @endphp
                @endif
            @endforeach
        </table>
        共 {{ $count }} 位
        <br><br>
        <h5>全程自往</h5>
        <table class="table table-bordered">
            <tr>
                <th>姓名</th>
            </tr>
            @php
                $count = 0;    
            @endphp
            @foreach ($applicants as $applicant)
                @if($applicant->$camp->traffic_depart == "自往" && $applicant->$camp->traffic_return == "自往")
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