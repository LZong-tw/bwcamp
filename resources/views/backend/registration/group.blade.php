@extends('backend.master')
@section('content')
    <div>
        <h2 class="d-inline-block">{{ $campFullData->abbreviation }} {{ $batch->name }} {{ request()->group }}組 組別名單</h2>
        <a href="{{ route('showGroup', [$campFullData->id, $batch->id, request()->group]) }}?download=1" class="btn btn-primary d-inline-block" style="margin-bottom: 14px">下載名單</a>
        <a href="{{ route('showGroup', [$campFullData->id, $batch->id, request()->group]) }}?download=1&template=1" class="btn btn-secondary d-inline-block" style="margin-bottom: 14px">下載名單樣板</a>
    </div>
    <form action="" method="post" name="sendEmailByGroup">
    <table class="table table-bordered">
        @csrf
        <thead>
            <tr class="">
                <th>報名序號</th>
                <th>錄取編號</th>
                <th>姓名</th>
                <th>生理性別</th>
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
                <th>參加意願</th>
                <th>已繳費</th>
                <th>選取<br>全選<input type="checkbox" name="selectAll" onclick="toggler()"></th>
            </tr>
        </thead>
        @foreach ($applicants as $applicant)
            <tr>
                <td>{{ $applicant->sn }}</td>
                <td>{{ $applicant->group }}{{ $applicant->number }}</td>
                <td>{{ $applicant->name }}</td>
                <td>{{ $applicant->gender }}</td>
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
                @switch($applicant->is_attend)
                    @case(1)
                        <td style='color: green;'>參加</td> @break
                    @case(0)
                        <td style='color: red;'>不參加</td> @break
                    @case(2)
                        <td style='color: #ffb429;'>尚未決定</td> @break
                    @case(3)
                        <td style='color: pink;'>聯絡不上</td> @break
                    @case(4)
                        <td style='color: seagreen;'>無法全程</td> @break
                    @default
                        <td style='color: rgb(0, 132, 255);'>尚未聯絡</td>
                @endswitch
                <td>{!! $applicant->is_paid == "是" ? "<a style='color: green;'>是</a>" : "<a style='color: red;'>否</a>" !!}</td>
                <td>
                    <input type="checkbox" name="sns[]" value="{{ $applicant->sn }}" class="selected">
                </td>
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
    <button type="submit" class="btn btn-success" style="margin-bottom: 15px" onclick="this.innerText = '處理中'; this.disabled = true; document.sendEmailByGroup.action='{{ route('sendAdmittedMail', $camp_id) }}'; document.sendEmailByGroup.submit();">寄送錄取通知信 / 分組通知函</button>
    <button type="submit" class="btn btn-info float-right" style="margin-bottom: 15px" onclick="this.innerText = '處理中'; this.disabled = true; document.sendEmailByGroup.action='{{ route('sendCheckInMail', $camp_id) }}';document.sendEmailByGroup.submit();">寄送報到通知信</button>
</form>
<script>
    function toggler(){
        let sns = document.getElementsByClassName("selected");
        console.log(sns);
        for(let i = 0; i < sns.length ; i++){
            sns[i].checked = sns[i].checked ? false : true;
        }
    }
</script>
@endsection
