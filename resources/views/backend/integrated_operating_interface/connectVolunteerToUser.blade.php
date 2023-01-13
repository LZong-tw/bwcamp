@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 義工授權</h2>
    <div class="alert alert-info pb-1 pt-3"><h4>即將指派<span class="text-danger font-weight-bold">{{ $group->section }}{{ $group->position }}</span>予以下人員，並執行相關流程，請針對各人員資料再次進行檢查</h4></div>
    <form action="{{ route("showCandidate", $campFullData->id) }}" method="post" class="form-horizontal">
        @csrf
        <div class="row">
            <span class="col-1">報名序號</span>
            <span class="col-1">姓名</span>
            <span class="col">Email</span>
            <span class="col-2">手機</span>
            <span class="col-1">資料類別</span>
            <span class="col">指定動作</span>
        </div>
        @foreach($list as $candidate)
            <div class="row">
                <span class="col-1">{{ $candidate["data"]->id }}</span>
                <span class="col-1">{{ $candidate["data"]->name }}</span>
                <span class="col">{{ $candidate["data"]->email }}</span>
                <span class="col-2">{{ $candidate["data"]->mobile }}</span>
                <span class="col-1">{{ $candidate["type"] }}</span>
                <span class="col">
                    @if ($candidate["action"])
                        {{ $candidate["action"] }}
                    @else
                        <form action="">
                            使用
                            <input type="text" name="name" id="" placeholder="姓名" class="form-control">
                            <input type="text" name="email" id="" placeholder="Email" class="form-control">
                            <input type="text" name="mobile" id="" placeholder="手機" class="form-control">
                            查詢系統中是否已存在幹部帳號
                            <input type="submit" value="查詢" class="btn btn-primary">
                        </form>

                    @endif
                </span>
            </div>
            <div class="row">

            </div>
        @endforeach
        <input type="submit" class="btn btn-success" value="送出">
    </form>
    <script>
        (function() {

        })();
    </script>
@endsection
