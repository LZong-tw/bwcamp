<style>
    @font-face {
        font-family: 'msjh';
        font-style: normal;
        src: url('{{ storage_path('fonts/msjh.ttf') }}') format('truetype');
    }
    .table, table.table td{
        border: 1px solid black;
        border-collapse: collapse;
        padding: 8px;   //10px;
    }
    .center{
        text-align: center;
    }
    .padding{
        padding-top: 6px;  //10px;
        padding-left: 6px;  //10px;
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
    .page-break {
        page-break-after: always;
    }
</style>
{{-- 在正式環境用 h 系列標籤，中文字型會壞掉 --}}
<a style="font-size: 2em;">{{ $camp->fullName }} {{ $form_title }}</a>
<table class="table table-bordered" width="{{ $form_width }}">
    <tr>
        <td>男生</td>
        <td>{{ $group }}組</td>
        <td>輔導員：</td>
        <td>輔導員手機：</td>
    </tr>
</table>
{{-- 在正式環境用 h 系列標籤，中文字型會壞掉 --}}
<a style="font-size: 1em;">【住宿概況】</a>
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
{{-- 在正式環境用 h 系列標籤，中文字型會壞掉 --}}
<a style="font-size: 1em;">【報到暨床位安排】</a>
@php
    $emptylines = 18;
@endphp
<table class="table table-bordered" width="{{ $form_width }}">
    <tr>
        @foreach($columns as $key => $val)
        <td>{{ $val }}</td>
        @endforeach
    </tr>
    @foreach($applicants as $applicant)
        @if($applicant->gender == "男")
            @php
                $emptylines--;
            @endphp
            <tr>
            @foreach($columns as $key => $val)
                @if($key == "admitted_no")
                <td>{{ $applicant->group }}{{ $applicant->number }}</td>
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
                @if($key == "admitted_no")
                <td>{{ $applicant->group }}</td>
                @else
                <td>　</td>
                @endif
        @endforeach
    </tr>
    @endfor
</table>
<div class="page-break"></div>
{{-- 在正式環境用 h 系列標籤，中文字型會壞掉 --}}
<a style="font-size: 2em;">{{ $camp->fullName }} {{ $form_title }}</a>
<table class="table table-bordered" width="{{ $form_width }}">
    <tr>
        <td>女生</td>
        <td>{{ $group }}組</td>
        <td>輔導員：</td>
        <td>輔導員手機：</td>
    </tr>
</table>
{{-- 在正式環境用 h 系列標籤，中文字型會壞掉 --}}
<a style="font-size: 1em;">【住宿概況】</a>
<table class="table" width="{{ $form_width }}">
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
{{-- 在正式環境用 h 系列標籤，中文字型會壞掉 --}}
<a style="font-size: 1em;">【報到暨床位安排】</a>
@php
    $emptylines = 18;
@endphp
<table class="table table-bordered" width="{{ $form_width }}">
    <tr>
        @foreach($columns as $key => $val)
        <td>{{ $val }}</td>
        @endforeach
    </tr>
    @foreach($applicants as $applicant)
        @if($applicant->gender == "女")
            @php
                $emptylines--;
            @endphp
            <tr>
            @foreach($columns as $key => $val)
                @if($key == "admitted_no")
                <td>{{ $applicant->group }}{{ $applicant->number }}</td>
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
                @if($key == "admitted_no")
                <td>{{ $applicant->group }}</td>
                @else
                <td>　</td>
                @endif
        @endforeach
    </tr>
    @endfor
</table>
