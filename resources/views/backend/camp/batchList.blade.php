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
            <th scope="col" class="text-nowrap">ID</th>
            <th scope="col" class="text-nowrap">梯次<br>名稱</th>
            <th scope="col" class="text-nowrap">錄取<br>編號<br>前綴</th>
            <th scope="col" class="text-nowrap">梯次<br>開始日</th>
            <th scope="col" class="text-nowrap">梯次<br>結束日</th>
            <th scope="col" class="text-nowrap">允許<br>前台<br>報名</th>
            <th scope="col" class="text-nowrap">是否延後<br>截止報名</th>
            <th scope="col" class="text-nowrap">報名<br>延後<br>截止日 </th>
            <th scope="col" class="text-nowrap">報到日</th>
            <th scope="col" class="text-nowrap">地點</th>
            <th scope="col" class="text-nowrap">地址</th>
            <th scope="col" class="text-nowrap">電話</th>
            <th scope="col" class="text-nowrap">學員組數</th>
            <th scope="col" class="text-nowrap">建立日期</th>
            <th scope="col" class="text-nowrap">更新日期</th>
            <th scope="col" class="text-nowrap">動作</th>
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
                <td>
                    <a href="{{ route("showModifyBatch", [$camp->id, $batch->id]) }}" class="btn btn-primary">修改</a>
                    <form action="{{ route('copyBatch', $camp->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                        <input type="submit" class="btn btn-success float-right" value="複製">
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@endsection