@extends('backend.master')
@section('content')
    <h2>{{ $batch->name }} {{ request()->group }}組 組別名單</h2>
    <table class="table table-bordered">
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
                    <th>區域</th>   			
            </tr>
        </thead>
        @foreach ($applicants as $applicant)
            <tr>
                <td>{{ $applicant->id }}</td>
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
            </tr>
        @endforeach
    </table>
    @if(Session::has("message"))
        <div class="alert alert-success" role="alert">
            {{ Session::get("message") }}
        </div>
    @endif
    <form action="{{ route("sendAdmittedMail", $camp_data->id) }}" method="post" name="sendEmailByGroup">
        @csrf
        @foreach ($applicants as $applicant)
            <input type="hidden" name="names[]" value="{{ $applicant->name }}">
            <input type="hidden" name="emails[]" value="{{ $applicant->email }}">
            <input type="hidden" name="admittedNos[]" value="{{ $applicant->group }}{{ $applicant->number }}">
        @endforeach
        <button type="submit" class="btn btn-success" style="margin-bottom: 15px" onclick="this.innerText = '寄送中'; this.disabled = true; document.sendEmailByGroup.submit();">全組寄送錄取通知信</button>
    </form>
@endsection