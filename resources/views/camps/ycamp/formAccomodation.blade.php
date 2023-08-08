<style>
    @font-face {
        font-family: 'msjh';
        font-style: normal;
        src: url('{{ storage_path('fonts/msjh.ttf') }}') format('truetype');
    }
    .table, table.table td{
        border: 1px solid black;
        border-collapse: collapse;
        padding: 10px;
    }
    .center{
        text-align: center;
    }
    .padding{
        padding-top: 6px;  //10
        padding-left: 6px;  //10
    }
    html,body{
        padding:15px;
        height:297mm;
        width:210mm;
        font-family: "msjh", sans-serif !important;
    }
    .right{
        float: right;
        margin-right: 15px;
    }
    u{
        color: red;
    }
</style>
{{--
<a href="{{ route('showPaymentForm', [$applicant->batch->camp_id, $applicant->id]) }}?download=1" target="_blank">下載繳費單</a>
--}}
{{-- 用 h 系列標籤，中文字型會壞掉 --}}
<center><a style="font-size: 1.17em;">{{ $camp->fullName }} 報名報到暨宿舍安排單</a></center>
<table class="table table-bordered" width="740px">
    <tr>
        <td>男生</td>
        <td>{{ $group }}組</td>
        <td>輔導員：</td>
        <td>輔導員手機：</td>
    </tr>
</table>
<h4>【住宿概況】</h4>
<table class="table" width="740px">
    <tr>
        <td>棟別_戶別</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td>
        <td>總數</td>
    </tr>
    <tr>
        <td>房號</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
    </tr>
    <tr>
        <td>提供床位數(含預留)</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
    </tr>
    <tr>
        <td>實際報到床位數</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
    </tr>
    <tr>
        <td>空床數</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
    </tr>
</table>
<h4>【報到暨床位安排】</h4>
@php
    $emptylines = 20;
@endphp
<table class="table table-bordered" width="740px">
    <tr>
        @foreach($columns as $key => $val)
        <td>{{ $val }}</td>
        @endforeach
    </tr>
    @foreach($applicants as $applicant)
        @if($applicant->gender == "M")
            @php
                $emptylines--;
            @endphp
            <tr>
            @foreach($columns as $key => $val)
                @if($key == "admitted_no")
                <td>{{ $applicant->group }}{{ $applicant->number }}</td>
                @elseif($key == "gender")
                <td>男</td>
                @else
                <td>{{ $applicant->$key }}</td>
                @endif
            @endforeach
            </tr>
        @endif
    @endforeach

    @if ($emptylines<0)
        @php
            $emptylines=5;
        @endphp
    @endif
    @for($l = 0; $l < $emptylines; $l++)
    <tr>
        @foreach($columns as $key => $val)
            <td>　</td>
        @endforeach
    </tr>
    @endfor
</table>
{{-- 用 h 系列標籤，中文字型會壞掉 --}}
<center><a style="font-size: 1.17em;">{{ $camp->fullName }} 報名報到暨宿舍安排單</a></center>
<table class="table table-bordered" width="740px">
    <tr>
        <td>女生</td>
        <td>{{ $group }}組</td>
        <td>輔導員：</td>
        <td>輔導員手機：</td>
    </tr>
</table>
<h4>【住宿概況】</h4>
<table class="table" width="740px">
    <tr>
        <td>棟別_戶別</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td>
        <td>總數</td>
    </tr>
    <tr>
        <td>房號</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
    </tr>
    <tr>
        <td>提供床位數(含預留)</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
    </tr>
    <tr>
        <td>實際報到床位數</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
    </tr>
    <tr>
        <td>空床數</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
        <td>　</td><td>　</td>
    </tr>
</table>
<h4>【報到暨床位安排】</h4>
@php
    $emptylines = 20;
@endphp
<table class="table table-bordered" width="740px">
    <tr>
        @foreach($columns as $key => $val)
        <td>{{ $val }}</td>
        @endforeach
    </tr>
    @foreach($applicants as $applicant)
        @if($applicant->gender == "F")
            @php
                $emptylines--;
            @endphp
            <tr>
            @foreach($columns as $key => $val)
                @if($key == "admitted_no")
                <td>{{ $applicant->group }}{{ $applicant->number }}</td>
                @elseif($key == "gender")
                <td>女</td>
                @else
                <td>{{ $applicant->$key }}</td>
                @endif
            @endforeach
            </tr>
        @endif
    @endforeach

    @if ($emptylines<0)
        @php
            $emptylines=5;
        @endphp
    @endif
    @for($l = 0; $l < $emptylines; $l++)
    <tr>
        @foreach($columns as $key => $val)
            <td>　</td>
        @endforeach
    </tr>
    @endfor
</table>
