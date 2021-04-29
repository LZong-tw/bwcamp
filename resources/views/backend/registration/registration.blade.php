@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->fullName }} ({{ $campFullData->abbreviation }})</h2>
    <p>請選擇欲報名梯次：</p>
    <ol>
        @foreach ($campFullData->batchs as $key => $batch)
            @if($user_batch_or_region && $user_batch_or_region != 'empty')
                @if($user_batch_or_region->id == $batch->id)
                    <li>
                        <form action="{{ route("registration", $batch->id) }}" target="_blank" method="post">
                            @csrf
                            <input type="hidden" name="isBackend" value="目前為後台報名狀態。">
                            <p><a>{{ $batch->name }}，{{ $batch->batch_start }} ~ {{ $batch->batch_end }}：</a>
                            <button class="btn btn-primary">前往</button></p>
                        </form>
                    </li>
                @endif
            @elseif($user_batch_or_region == 'empty')
            @else
                <li>
                    <form action="{{ route("registration", $batch->id) }}" target="_blank" method="post">
                        @csrf
                        <input type="hidden" name="isBackend" value="目前為後台報名狀態。">
                        <p><a>{{ $batch->name }}，{{ $batch->batch_start }} ~ {{ $batch->batch_end }}：</a>
                        <button class="btn btn-primary">前往</button></p>
                    </form>
                </li>
            @endif
        @endforeach
    </ol>
@endsection