@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->fullName }} ({{ $campFullData->abbreviation }})</h2>
    <p>請選擇欲報名梯次：</p>
    @foreach ($campFullData->batchs as $key => $batch)
        <ol>
            <li><p><a href="{{ route("registration", $batch->id) }}" target="_blank">{{ $batch->name }}，{{ $batch->batch_start }} ~ {{ $batch->batch_end }}</a></p></li>
        </ol>
    @endforeach
@endsection