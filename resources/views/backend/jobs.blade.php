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
            <th width="50">id</th>
            <th>payload</th>
            <th>payload->data->command</th>
            <th width="60">atts</th>
            <th width="190">reserved_at</th>
            {{-- <th width="190">available_at</th> --}}
            <th width="190">created_at</th>
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
                <a href="#" class="" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="collapse{{ $key }} collapse_command{{ $key }}">
                    展開 / 收合
                </a>
                <div class="collapse multi-collapse" id="collapse{{ $key }}">                      
                    {!! var_dump($payload) !!}
                </div>
            </td>
            <td>                
                <div class="collapse multi-collapse" id="collapse_command{{ $key }}">
                    {!! var_dump($payload->data->command) !!}
                </div>
            </td>
            <td>{{ $job['attempts'] }}</td>
            <td>{{ \Carbon\Carbon::createFromTimestamp($job['reserved_at'])->format('Y-m-d H:i:s') }}</td>
            {{-- <td>{{ \Carbon\Carbon::createFromTimestamp($job['available_at'])->format('Y-m-d H:i:s') }}</td> --}}
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
            <th width="50">id</th>
            {{-- <th>connection</th>
            <th>queue</th> --}}
            <th>payload</th>
            <th>payload->data->command</th>
            <th>exception</th>
            <th width="190">failed_at</th>
        </tr>
    </thead>
    @foreach ($failedJobs as $key => $job)
        <tr>
            <td>{{ $job['id'] }}</td>
            {{-- <td>{{ $job['connection'] }}</td>
            <td>{{ $job['queue'] }}</td> --}}
            @php 
                $payload = json_decode($job['payload']);
                $payload->data->command = unserialize($payload->data->command);
            @endphp
            <td>           
                <a href="#" data-toggle="collapse" data-target=".multi-collapse_failed" aria-expanded="false" aria-controls="collapse_failed{{ $key }} collapse_command_failed{{ $key }} collapse_failed_exception{{ $key }}">
                    展開 / 收合
                </a>
                <div class="collapse multi-collapse_failed" id="collapse_failed{{ $key }}">                      
                    {!! var_dump($payload) !!}
                </div>            
            </td>
            <td>                
                <div class="collapse multi-collapse_failed" id="collapse_command_failed{{ $key }}">      
                    {!! var_dump($payload->data->command) !!}
                </div>            
            </td>
            <td>
                <div class="collapse multi-collapse_failed" id="collapse_failed_exception{{ $key }}"> 
                    {{ $job['exception'] }}
                </div>
            </td>
            <td>{{ \Carbon\Carbon::createFromTimestamp($job['failed_at'])->format('Y-m-d H:i:s') }}</td>
        </tr>
    @endforeach
</table>
@endsection