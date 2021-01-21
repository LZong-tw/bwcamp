@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 寄送自定郵件</h2>
    @if($errors->any())
        @foreach ($errors->all() as $message)
            <div class='alert alert-danger' role='alert'>
                {{ $message }}
            </div>
        @endforeach
    @endif
    @if(isset($message))
        <div class='alert alert-success' role='alert'>
            {{ $message }}
        </div>
    @endif
@endsection