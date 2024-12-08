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
    @if($campFullData->table == 'ycamp')
    <p class="text-info">輔導組表格：請點選各組下載輔導組表格</p>
    @endif
    @foreach ($batches as $batch)
        <h4>梯次：{{ $batch->name }}</h4>
        <table>
            <tr>
                @foreach ($batch->regions as $region)
                <td style="vertical-align: top;">
                    <table class="table table-bordered">
                        <thead><tr class="bg-primary text-white"><th colspan="2">{{ $region->region }}</th></tr></thead>
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($region->groups as $groupRepresentativeApplicant)
                            <tr>
                                <td>
                                    <a @if($user->canAccessResource($groupRepresentativeApplicant->groupRelation, 'read', $campFullData))
                                        href="{{ route("showGroup", [$campFullData->id, $batch->id, $groupRepresentativeApplicant->group]) }}"
                                    @endif class="card-link">{{ $groupRepresentativeApplicant->group }}</a></td>
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
                @endforeach
            </tr>
        </table>
        <hr>
    @endforeach
@endsection
