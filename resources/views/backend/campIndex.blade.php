@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->fullName }} ({{ $campFullData->abbreviation }})</h2>
    <p>營隊資訊：</p>
    <ul>
        <li><p>報名期間：{{ $campFullData->registration_start }} ~ {{ $campFullData->registration_end }}</p></li>
        <li><p>錄取公佈日：{{ $campFullData->admission_announcing_date }}</p></li>
        <li><p>確認參加期限：{{ $campFullData->admission_confirming_end }}</p></li>
        <li><p>後台最終報名期限：{{ $campFullData->final_registration_end }}</p></li>
        <li><p>繳費金額：{{ $campFullData->fee }}</p></li>
        <li><p>繳費期限(民國年 yymmdd)：{{ $campFullData->payment_deadline }}</p></li>
    </ul>
    <p>本營隊梯次：</p>
    <ol>
        @foreach ($campFullData->batchs as $key => $batch)
            <li><p>{{ $batch->name }}，{{ $batch->batch_start }} ~ {{ $batch->batch_end }}</p></li>
        @endforeach
    </ol>
    <p>請從左側選單選擇功能。</p>
@endsection