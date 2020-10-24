@extends('backend.master')
@section('content')
    <h2>組別名單</h2>
    @foreach ($batches as $batch)
        梯次：{{ $batch->name }}
        @foreach ($batch->regions as $region)
            <table class="table table-bordered">
                <thead><tr><th colspan="2">{{ $region->region }}</th></tr></thead>
                @foreach ($region->groups as $group)
                    <tr>
                        <td>{{ $group->group }}</td>
                        <td>{{ $group->count }}</td>
                    </tr>
                @endforeach
            </table>
        @endforeach
        <hr>
    @endforeach
@endsection