@extends('backend.master')
@section('content')
    <h2>組別名單</h2>
    @foreach ($batches as $batch)
        {{ $batch->name }}
    @endforeach
@endsection