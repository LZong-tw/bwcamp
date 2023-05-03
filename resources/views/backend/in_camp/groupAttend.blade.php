@extends('backend.master')
@section('content')
    <div>
        <h2 class="d-inline-block">{{ $campFullData->abbreviation }} {{ $batch->name }} {{ request()->group }}組 回覆參加</h2>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr class="">
                <th>報名序號</th>
                <th>錄取編號</th>
                <th>姓名</th>
                {{-- <th>生理性別</th>
                @if($camp_data->table == "tcamp")
                    <th>縣市 / 區鄉鎮</th>
                    <th>服務單位 / 職稱</th>
                @endif
                @if($camp_data->table == "ycamp")
                    <th>就讀學程</th>
                    <th>就讀學校</th>
                    <th>就讀科系所 / 年級</th>
                    <th>行動電話</th>
                    <th>家中電話</th>
                @endif
                <th>分區</th>
                <th>已繳費</th>			 --}}
                <th>狀態</th>
            </tr>
        </thead>
        @foreach ($applicants as $applicant)
            <tr>
                <td>{{ $applicant->sn }}</td>
                <td>{{ $applicant->group }}{{ $applicant->number }}</td>
                <td>{{ $applicant->name }}</td>
                {{-- <td>{{ $applicant->gender }}</td>
                @if($camp_data->table == "tcamp")
                    <td>{{ $applicant->county }} / {{ $applicant->district }}</td>
                    <td>{{ $applicant->unit }} / {{ $applicant->title }}</td>
                @endif
                @if($camp_data->table == "ycamp")
                    <td>{{ $applicant->system }}</td>
                    <td>{{ $applicant->school }}</td>
                    <td>{{ $applicant->department }} / {{ $applicant->grade }}</td>
                    <td>{{ $applicant->mobile }}</td>
                    <td>{{ $applicant->phone_home }}</td>
                @endif
                <td>{{ $applicant->region }}</td>
                <td>{!! $applicant->is_paid == "是" ? "<a style='color: green;'>是</a>" : "<a style='color: red;'>否</a>" !!}</td> --}}
                @if(!$applicant->is_attend)
                    <th>{!! "<a style='color: rgb(0, 132, 255);'>未回覆 / 尚未聯絡</a>" !!}</th>
                @elseif($applicant->is_attend == 0)
                    <th>{!! "<a style='color: red;'>回覆不參加</a>" !!}</th>
                @elseif($applicant->is_attend == 1)
                    <th>{!! "<a style='color: green;'>回覆參加</a>" !!}</th>
                @elseif($applicant->is_attend == 2)
                    <th>{!! "<a style='color: yellow;'>尚未決定</a>" !!}</th>
                @elseif($applicant->is_attend == 3)
                    <th>{!! "<a style='color: pink;'>聯絡不上</a>" !!}</th>
                @elseif($applicant->is_attend == 4)
                    <th>{!! "<a style='color: Aqua;'>無法全程</a>" !!}</th>
                @else
                    <th>{!! "<a style='color: orange;'>非預期狀況</a>" !!}</th>
                @endif

            </tr>
        @endforeach
    </table>
    @if(Session::has("message"))
        <div class="alert alert-success" role="alert">
            {{ Session::get("message") }}
        </div>
    @endif
    @if(Session::has("error"))
        <div class="alert alert-danger" role="alert">
            {{ Session::get("error") }}
        </div>
    @endif
@endsection
