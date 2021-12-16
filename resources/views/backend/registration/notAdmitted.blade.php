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
    <h2>{{ $campFullData->abbreviation }} 未錄取名單</h2>
    @foreach ($batches as $batch)
        <h4>梯次：{{ $batch->name }}</h4>
        共 {{ $batch->applicants->count() }} 人
        <table>
            <tr>
                <td style="vertical-align: top;">
                    <table class="table table-bordered">
                        @forelse ($batch->applicants as $applicant)                 
                            <tr>
                                <td>{{ $applicant->sn }}</td>
                                <td>{{ $applicant->name }}</td>
                                <td>{{ $applicant->region }}</td>
                                <td>{{ $campFullData->table == "tcamp" ? $applicant->school_or_course : $applicant->system }}</td>
                                @if($campFullData->table == "ycamp")
                                    <td>{{ $applicant->school }}</td>
                                    <td>{{ $applicant->school_location }}</td>
                                    <td>{{ $applicant->department }}{{ $applicant->grade }}</td>
                                @endif
                                @if($campFullData->table == "tcamp")
                                    <td>{{ $applicant->title }}</td>
                                    <td>{{ $applicant->unit }}</td>
                                @endif
                            </tr>
                        @empty
                        @endforelse
                    </table>
                </td>
            </tr>
        </table>
        <hr>
    @endforeach
@endsection