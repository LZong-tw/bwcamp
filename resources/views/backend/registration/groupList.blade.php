@extends('backend.master')
@section('content')
    <h2>組別名單</h2>
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
                        @foreach ($region->groups as $group)
                            <tr>
                                <td><a href="{{ route("showGroup", [$campFullData->id, $batch->id, $group->group]) }}" class="card-link">{{ $group->group }}</a></td>
                                <td>{{ $group->count }}</td>
                                @php
                                    $count = $count + $group->count;
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