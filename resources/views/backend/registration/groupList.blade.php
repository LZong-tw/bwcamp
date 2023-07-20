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
    @foreach ($batches as $batch)
        <h4>梯次：{{ $batch->name }}</h4>
        <table>
            
                @foreach ($batch->regions as $region)
                <tr style="vertical-align: top;">
                    <td>
                    <table class="table table-bordered">
                        <thead><tr class="bg-primary text-white"><th colspan="2">{{ $region->region }}</th></tr></thead>
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($region->groups as $groupRepresentativeApplicant)
                            <tr>
                                <td><a href="{{ route("showGroup", [$campFullData->id, $batch->id, $groupRepresentativeApplicant->group]) }}" class="card-link">{{ $groupRepresentativeApplicant->group }}</a></td>
                                <td>{{ $groupRepresentativeApplicant->groupApplicantsCount }}</td>
                                @php
                                    $count = $count + $groupRepresentativeApplicant->groupApplicantsCount;
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
                @endforeach
            
        </table>
        <hr>
    @endforeach
@endsection
