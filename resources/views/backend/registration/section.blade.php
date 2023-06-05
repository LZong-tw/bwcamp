@extends('backend.master')
@section('content')
    <div>
        <h2 class="d-inline-block">{{ $campFullData->abbreviation }} {{ $batch->name }} {{ $org->section }} 組別名單</h2>
        {{--
        <a href="{{ route('showGroup', [$campFullData->id, $batch->id, request()->group]) }}?download=1" class="btn btn-primary d-inline-block" style="margin-bottom: 14px">下載名單</a>
        <a href="{{ route('showGroup', [$campFullData->id, $batch->id, request()->group]) }}?download=1&template=1" class="btn btn-secondary d-inline-block" style="margin-bottom: 14px">下載名單樣板</a>
        --}}
    </div>
    <form action="" method="post" name="sendEmailByGroup">
    <input type="hidden" name="org_id" value="{{ $org->id }}">
    <table class="table table-bordered">
        @csrf
        <thead>
            <tr class="">
                <th>報名序號</th>
                <th>職務</th>
                <th>姓名</th>
                <th>生理性別</th>
                <th>選取<br>全選<input type="checkbox" name="selectAll" onclick="toggler()"></th>
            </tr>
        </thead>
        @foreach ($applicants as $applicant)
            <tr>
                <td>{{ $applicant->id }}</td>
                <td>{{ $org->section }}{{ $org->position }}</td>
                <td>{{ $applicant->name }}</td>
                <td>{{ $applicant->gender }}</td>
                <td>
                    <input type="checkbox" name="sns[]" value="{{ $applicant->id }}" class="selected">
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
