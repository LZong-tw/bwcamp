@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 義工授權</h2>
    <form action="{{ route("showCandidate", $campFullData->id) }}" method="post" class="form-horizontal">
        @csrf
        @foreach($list as $candidate)
            <div class="row">
                <span>{{ $candidate["data"]->name }}</span>
            </div>
        @endforeach
        <input type="submit" class="btn btn-success" value="送出">
    </form>
@endsection
