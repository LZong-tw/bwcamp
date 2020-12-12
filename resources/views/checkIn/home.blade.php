@extends('checkIn.master')
@section('content')
<style>
    .text-center {
        text-align: center;
    }
</style>
<div class="container">
    <h3 class="mt-5 text-center">福智營隊報到系統</h3>
    <h5 class="text-center">當前報到營隊：{{ $camp->fullName }}<br>報到日期：{{ \Carbon\Carbon::today()->format('Y-m-d') }}</h5>
    <form action="/checkin/query">
        <div class="form-group input-group">
            <input type="text" class="form-control" name="query_str" id="" placeholder="請輸入報名序號、組別、錄取編號、姓名、電話查詢 ..." value="{{ old("query_str") }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit" id="checkinsearch">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
    @if($errors->any())
        @foreach ($errors->all() as $message)
            <div class='alert alert-danger' role='alert'>
                {{ $message }}
            </div>
        @endforeach
    @endif
    @if(\Session::has('message'))
        <div class='alert alert-success' role='alert'>
            {{ \Session::get('message') }}
        </div>
    @endif
    @if(isset($applicants) && $applicants->count() > 0)
        <table class="table table-bordered text-break">
            <tr class="table-active">
                <th style="width: 20%">組別</th>
                <th style="width: 20%">編號</th>
                <th style="width: 20%">姓名</th>
                <th style="width: 20%">手機</th>
                <th style="width: 15%">本日報到</th>
            </tr>
            @foreach ($applicants as $applicant)
                <tr id="{{ $applicant->id }}">
                    <td>{{ $applicant->group }}</td>
                    <td>{{ $applicant->number }}</td>
                    <td>{{ $applicant->name }}</td>
                    <td>{{ $applicant->mobile }}</td>
                    <td>
                        @php
                            $yes = 0;   
                        @endphp
                        @foreach($applicant->checkInData as $checkInData)
                            @if($checkInData->check_in_date == \Carbon\Carbon::today()->format('Y-m-d'))
                                @php
                                    $yes = 1;   
                                @endphp
                            @endif
                        @endforeach
                        @if($yes)
                            <form action="/checkin/un-checkin" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                                <input type="hidden" name="check_in_date" value="{{ $checkInData->check_in_date }} ">
                                <input type="hidden" name="query_str" value="{{ old("query_str") }}">
                                <input type="submit" value="取消" class="btn btn-danger">
                            </form> 
                        @else
                            <form action="/checkin/checkin" method="POST">
                                @csrf
                                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                                <input type="hidden" name="query_str" value="{{ old("query_str") }}">
                                <input type="submit" value="報到" class="btn btn-success" id="btn{{ $applicant->id }}">
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    @elseif(isset($applicants) && $applicants->count() == 0)
        <div class="alert alert-danger">查無資料。</div>
    @endif
</div>  
<script>
    (function() {
        @foreach ($applicants as $applicant)
            @foreach($applicant->checkInData as $checkInData)
                @if($checkInData->check_in_date == \Carbon\Carbon::today()->format('Y-m-d'))
                    document.getElementById("{{ $applicant->id }}").classList.add("table-success");
                    document.getElementById("btn{{ $applicant->id }}").disabled = true;
                    document.getElementById("btn{{ $applicant->id }}").value = "已報到";
                @endif
            @endforeach
        @endforeach
    })();
</script>
@endsection