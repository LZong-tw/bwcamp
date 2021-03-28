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
    <h2 class="d-inline-block">營隊列表</h2>
    <a href="{{ route("showAddCamp") }}" class="btn btn-success d-inline-block" style="margin-bottom: 10px">建立營隊</a>
    @if(\Session::has('message'))
        <div class='alert alert-success' role='alert'>
            {{ \Session::get('message') }}
        </div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>全名</th>
            <th>簡稱</th>
            <th>網站網址</th>
            <th>圖示</th>
            <th>資料表名稱</th>
            <th>報名開始日</th>
            <th>報名結束日</th>
            <th>錄取公佈日</th>
            <th>回覆參加截止日</th>
            <th>後台報名結束日</th>
            <th>繳費開始日</th>
            <th>繳費截止日</th>
            <th>營隊費用</th>
            <th>是否有早鳥優惠</th>
            <th>營隊早鳥費用</th>
            <th>早鳥最後一日</th>
            <th>報名資料修改截止日</th>
            <th>取消截止日</th>
            <th>建立日期</th>
            <th>更新日期</th>
            <th>動作</th>
        </tr>
        @foreach($camps as $camp)
            <tr>
                <td>{{ $camp->id }}</td>
                <td>{{ $camp->fullName }}</td>
                <td>{{ $camp->abbreviation }}</td>
                <td><a href="{{ $camp->site_url }}" target="_blank">{{ $camp->site_url }}</a></td>
                <td>{{ $camp->icon }}</td>
                <td>{{ $camp->table }}</td>
                <td>{{ $camp->registration_start }}</td>
                <td>{{ $camp->registration_end }}</td>
                <td>{{ $camp->admission_announcing_date }}</td>
                <td>{{ $camp->admission_confirming_end }}</td>
                <td>{{ $camp->final_registration_end }}</td>
                <td>{{ $camp->payment_startdate }}</td>
                <td>{{ $camp->payment_deadline }}</td>
                <td>{{ $camp->fee }}</td>
                <td>{{ $camp->has_early_bird }}</td>
                <td>{{ $camp->early_bird_fee }}</td>
                <td>{{ $camp->early_bird_last_day }}</td>
                <td>{{ $camp->modifying_deadline }}</td>
                <td>{{ $camp->cancellation_deadline ? $camp->cancellation_deadline->format('Y-m-d') : "" }}</td>
                <td>{{ $camp->created_at }}</td>
                <td>{{ $camp->updated_at }}</td>
                <td>
                    <a href="{{ route("showBatch", $camp->id) }}" class="btn btn-success" target="_blank">梯次列表</a>
                    <a href="{{ route("showModifyCamp", $camp->id) }}" class="btn btn-primary">修改</a></td>
            </tr>
        @endforeach
    </table>
@endsection