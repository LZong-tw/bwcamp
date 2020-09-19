@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->fullName }} ({{ $campFullData->abbreviation }})</h2>
    <p>請選擇欲報名梯次：</p>
    @foreach ($campFullData->batchs as $key => $batch)
        <ol>
            <li>
                <form action="{{ route("registration", $batch->id) }}" target="_blank" method="post">
                    @csrf
                    <input type="hidden" name="isBackend" value="1">
                    <a>{{ $batch->name }}，{{ $batch->batch_start }} ~ {{ $batch->batch_end }}：</a>
                    <button class="btn btn-primary">前往</button>
                </form>
            </li>
        </ol>
    @endforeach
@endsection