@extends('backend.master')
@section('content')
    <div><h2 class="d-inline-block">{{ $campFullData->abbreviation }} {{ $batch->name }} {{ request()->group }}組 組別名單</h2>
    @if(auth()->user()->getPermission()->level == 1)
        <a href="{{ route("showGroup", [$campFullData->id, $batch->id, request()->group]) }}?download=1" class="btn btn-primary d-inline-block" style="margin-bottom: 14px">下載名單</a>
    @endif
    </div>
    <form action="{{ route("sendAdmittedMail", $camp_data->id) }}" method="post" name="sendEmailByGroup">
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
    <button type="submit" class="btn btn-success" style="margin-bottom: 15px" onclick="this.innerText = '處理中'; this.disabled = true; document.sendEmailByGroup.submit();">全組寄送錄取通知信</button>
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
