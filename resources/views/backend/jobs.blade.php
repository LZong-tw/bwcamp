@extends('backend.master')
@section('content')
<style>
    table{
        word-wrap: break-word;
        table-layout: fixed;
    }
</style>
<h2>任務佇列</h2>
<h3>Jobs</h3>
<table class="table table-bordered">
    <thead>
        <tr class="bg-secondary text-white">
            <th>id</th>
            <th>payload</th>
            <th>attempts</th>
            <th>reserved_at</th>
            <th>available_at</th>
            <th>created_at</th>
        </tr>
    </thead>
    @foreach ($jobs as $key => $job)
        <tr>
            <td>{{ $job['id'] }}</td>            
            @php 
                $payload = json_decode($job['payload']);
                $payload->data->command = unserialize($payload->data->command);
            @endphp
            <td>
                <a href="#" class="" type="button" data-toggle="collapse" data-target="#collapse{{ $key }}" aria-expanded="false" aria-controls="collapse{{ $key }}">
                    展開 / 收合
                </a>
                <div class="collapse" id="collapse{{ $key }}">                      
                    {!! var_dump($payload) !!} <br> 
                    {!! var_dump($payload->data->command) !!}
                </div>
            </td>
            <td>{{ $job['attempts'] }}</td>
            <td>{{ \Carbon\Carbon::createFromTimestamp($job['reserved_at'])->format('Y-m-d H:i:s') }}</td>
            <td>{{ \Carbon\Carbon::createFromTimestamp($job['available_at'])->format('Y-m-d H:i:s') }}</td>
            <td>{{ \Carbon\Carbon::createFromTimestamp($job['created_at'])->format('Y-m-d H:i:s') }}</td>
        </tr>
    @endforeach
</table>
<div>
    <h3 class="d-inline-block">Failed Jobs</h3>
    @if(auth()->user()->getPermission()->level == 1)
        <a href="{{ route("failedJobsClear") }}" target="_blank" class="btn btn-danger d-inline-block" style="margin-bottom: 14px">Clear</a>
    @endif
</div>
<table class="table table-bordered">
    <thead>
        <tr class="bg-secondary text-white">
            <th>id</th>
            <th>connection</th>
            <th>queue</th>
            <th>payload</th>
            <th>exception</th>
            <th>failed_at</th>
        </tr>
    </thead>
    @foreach ($failedJobs as $key => $job)
        <tr>
            <td>{{ $job['id'] }}</td>
            <td>{{ $job['connection'] }}</td>
            <td>{{ $job['queue'] }}</td>
            @php 
                $payload = json_decode($job['payload']);
                $payload->data->command = unserialize($payload->data->command);
            @endphp
            <td>           
                <a href="#" class="" type="button" data-toggle="collapse_failed" data-target="#collapse_failed{{ $key }}" aria-expanded="false" aria-controls="collapse_failed{{ $key }}">
                    展開 / 收合
                </a>
                <div class="collapse_failed" id="collapse_failed{{ $key }}">                      
                    {!! var_dump($payload) !!} <br> 
                    {!! var_dump($payload->data->command) !!}
                </div>            
            </td>
            <td>{{ $job['exception'] }}</td>
            <td>{{ \Carbon\Carbon::createFromTimestamp($job['failed_at'])->format('Y-m-d H:i:s') }}</td>
        </tr>
    @endforeach
</table>
@endsection