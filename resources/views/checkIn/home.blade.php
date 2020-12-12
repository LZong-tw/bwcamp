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
    <form action="/checkin/query" method="POST">
        @csrf
        <div class="form-group input-group">
            <input type="text" class="form-control" name="query_str" id="" placeholder="請輸入報名序號、組別、錄取編號、姓名、電話查詢 ...">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit" id="checkinsearch">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
    @if(isset($applicants) && $applicants->count() > 0)
        <table class="table table-bordered">
            <tr>
                <th>報名序號</th>
                <th>組別</th>
                <th>錄取序號</th>
                <th>姓名</th>
                <th>手機</th>
                <th>報到資料</th>
                <th>本日報到</th>
            </tr>
            @foreach ($applicants as $applicant)
                <tr>
                    <td>{{ $applicant->id }}</td>
                    <td>{{ $applicant->group }}</td>
                    <td>{{ $applicant->group }}{{ $applicant->number }}</td>
                    <td>{{ $applicant->name }}</td>
                    <td>{{ $applicant->mobile }}</td>
                    <td>
                        @forelse($applicant->checkInData as $checkInData)
                            @if(!$loop->last)
                                {{ $checkInData->check_in_data }} <br>
                            @else
                                {{ $checkInData->check_in_data }}
                            @endif
                        @empty
                            沒有報到資料
                        @endforelse
                    </td>
                    <td>
                        <form action="/checkIn/checkIn">
                            @csrf
                            <input type="hidden" value="applicant_id" value="{{ $applicant->id }}">
                            <input type="submit" value="報到" class="btn btn-success">
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    @elseif(isset($applicants) && $applicants->count() == 0)
        <div class="alert alert-danger">查無資料。</div>
    @endif
</div>  
@endsection