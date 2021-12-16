@extends('backend.master')
@section('content')
    <style>
        .card-link{
            color: #3F86FB!important;
        }
        .card-link:hover{
            color: #33B2FF!important;
        }
    </style>
    <h2>{{ $campFullData->abbreviation }} 未錄取名單</h2>
    <form action="" method="post" name="sendEmailByGroup">
        @foreach ($batches as $batch)
            <h4>梯次：{{ $batch->name }}</h4>
            共 {{ $batch->applicants->count() }} 人
            <table>
                <tr>
                    <td style="vertical-align: middle;">
                        <table class="table table-bordered">
                            <tr>
                                <th>報名序號</th>
                                <th>姓名</th>
                                <th>區域</th>
                                <th>學程</th>
                                @if($campFullData->table == "ycamp")
                                    <th>學校</th>
                                    <th>學校所在地</th>
                                    <th>系級</th>
                                @endif
                                @if($campFullData->table == "tcamp")
                                    <th>職稱</th>
                                    <th>單位</th>
                                @endif
                                <th>選取<br>全選<input type="checkbox" name="selectAll" onclick="toggler()"></th>
                            </tr>
                            @forelse ($batch->applicants as $applicant)                 
                                <tr>
                                    <td>{{ $applicant->sn }}</td>
                                    <td>{{ $applicant->name }}</td>
                                    <td>{{ $applicant->region }}</td>
                                    <td>{{ $campFullData->table == "tcamp" ? $applicant->school_or_course : $applicant->system }}</td>
                                    @if($campFullData->table == "ycamp")
                                        <td>{{ $applicant->school }}</td>
                                        <td>{{ $applicant->school_location }}</td>
                                        <td>{{ $applicant->department }}{{ $applicant->grade }}</td>
                                    @endif
                                    @if($campFullData->table == "tcamp")
                                        <td>{{ $applicant->title }}</td>
                                        <td>{{ $applicant->unit }}</td>
                                    @endif
                                    <td>
                                        <input type="checkbox" name="sns[]" value="{{ $applicant->sn }}" class="selected">
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </table>
                    </td>
                </tr>
            </table>
            <hr>
        @endforeach
        @if(auth()->user()->getPermission()->level <= 2)
            <button type="submit" class="btn btn-danger" style="margin-bottom: 15px" onclick="this.innerText = '處理中'; this.disabled = true; document.sendEmailByGroup.action='{{ route("sendNotAdmittedMail", $camp_id) }}'; document.sendEmailByGroup.submit();">寄送未錄取通知信</button>
        @endif
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