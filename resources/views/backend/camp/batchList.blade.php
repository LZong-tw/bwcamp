@extends('backend.master')
@section('content')
    <style>
        .card-link{
            color: #3F86FB!important;
        }
        .card-link:hover{
            color: #33B2FF!important;
        }
    </style>
    <h2 class="d-inline-block">{{ $camp->abbreviation }} 梯次列表</h2>
    <a href="{{ route("showAddBatch", $camp->id) }}" class="btn btn-success d-inline-block" style="margin-bottom: 10px">建立梯次</a>
    @if(\Session::has('message'))
        <div class='alert alert-success' role='alert'>
            {{ \Session::get('message') }}
        </div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>梯次名</th>
            <th>錄取編號前綴</th>
            <th>梯次開始日</th>
            <th>梯次結束日</th>
            <th>允許前台報名</th>
            <th>是否延後截止報名</th>
            <th>報名延後截止日 </th>
            <th>報到日</th>
            <th>地點</th>
            <th>地址</th>
            <th>電話</th>
            <th>學員組數</th>
            <th>建立日期</th>
            <th>更新日期</th>
            <th>動作</th>
        </tr>
        @foreach($batches as $batch)
            <tr>
                <td>{{ $batch->id }}</td>
                <td>{{ $batch->name }}</td>
                <td>{{ $batch->admission_suffix }}</td>
                <td>{{ $batch->batch_start }}</td>
                <td>{{ $batch->batch_end }}</td>
                <td>{{ $batch->is_appliable }}</td>
                <td>{{ $batch->is_late_registration_end }}</td>
                <td>{{ $batch->late_registration_end }}</td>
                <td>{{ $batch->check_in_day }}</td>
                <td>{{ $batch->locationName }}</td>
                <td>{{ $batch->location }}</td>
                <td>{{ $batch->tel }}</td>
                <td>{{ $batch->num_groups }}</td>
                <td>{{ $batch->created_at }}</td>
                <td>{{ $batch->updated_at }}</td>
                <td><a href="{{ route("showModifyBatch", [$camp->id, $batch->id]) }}" class="btn btn-primary">修改</a></td>
            </tr>
        @endforeach
    </table>
@endsection