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
            <th scope="col" class="text-nowrap">營隊全名</th>
            <th scope="col" class="text-nowrap">營隊簡稱</th>
            <!-- <th>網站網址</th> -->
            <th scope="col" class="text-nowrap">圖示</th>
            <th scope="col" class="text-nowrap">資料表<br>名稱</th>
            <th scope="col" class="text-nowrap">Variant</th>
            <th scope="col" class="text-nowrap">報名<br>開始日</th>
            <th scope="col" class="text-nowrap">報名<br>結束日</th>
            <th scope="col" class="text-nowrap">錄取<br>公佈日</th>
            <th scope="col" class="text-nowrap">回覆<br>參加<br>截止日</th>
            <th scope="col" class="text-nowrap">是否需<br>回覆<br>參加</th>
            <th scope="col" class="text-nowrap">後台<br>報名<br>結束日</th>
            <th scope="col" class="text-nowrap">繳費<br>開始日</th>
            <th scope="col" class="text-nowrap">繳費<br>截止日</th>
            <th scope="col" class="text-nowrap">營隊費用</th>
            <th scope="col" class="text-nowrap">是否有<br>早鳥<br>優惠</th>
            <th scope="col" class="text-nowrap">營隊<br>早鳥<br>費用</th>
            <th scope="col" class="text-nowrap">早鳥<br>最後<br>一日</th>
            <th scope="col" class="text-nowrap">報名資料<br>修改<br>截止日</th>
            <th scope="col" class="text-nowrap">取消<br>截止日</th>
            <th scope="col" class="text-nowrap">建立日期</th>
            <th scope="col" class="text-nowrap">更新日期</th>
            <th scope="col" class="text-nowrap">編輯營隊、　<br>梯次與組織</th>
        </tr>
        @foreach($camps as $camp)
            <tr @if($camp->test) style="background: #83BFF3; color:white;" @endif>
                <td>{{ $camp->id }}</td>
                <td>{{ $camp->fullName }}</td>
                <td>{{ $camp->abbreviation }}</td>
                <!-- <td><a href="{{ $camp->site_url }}" target="_blank">{{ $camp->site_url }}</a></td> -->
                <td>{{ $camp->icon }}</td>
                <td>{{ $camp->table }}</td>
                <td>{{ $camp->variant }}</td>
                <td>{{ $camp->registration_start }}</td>
                <td>{{ $camp->registration_end }}</td>
                <td>{{ $camp->admission_announcing_date }}</td>
                <td>{{ $camp->admission_confirming_end }}</td>
                <td>{{ $camp->needed_to_reply_attend }}</td>
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
                    <a href="{{ route("showModifyCamp", $camp->id) }}" class="btn btn-primary">編輯營隊</a>
                    <a href="{{ route("showBatch", $camp->id) }}" class="btn btn-success" target="_blank">梯次列表</a>
                    <a href="{{ route("showOrgs", $camp->id) }}" class="btn btn-secondary">組織列表</a>
                </td>
            </tr>
        @endforeach
    </table>
@endsection