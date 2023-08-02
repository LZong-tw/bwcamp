<style>
    .table, table.table td{
        border: 1px solid black;
        border-collapse: collapse;
        padding: 10px;
    }
    .center{
        text-align: center;
    }
    .padding{
        padding-top: 10px;
        padding-left: 10px;
    }
    html,body{
        padding:15px;
        height:297mm;
        width:210mm;
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
<h2 class="center">{{ $camp->fullName }} 回程交通確認表</h2>
<table class="table table-bordered" width="100%" >
    <tr>
        @foreach($columns as $key => $val)
        <td>{{ $val }}</td>
        @endforeach
    </tr>    
    @foreach($applicants as $applicant)
    <tr>
        @foreach($columns as $key => $val)
            @if($key == "admitted_no")
            <td>{{ $applicant->group }}{{ $applicant->number }}</td>
            @elseif($key == "gender" && $applicant->$key == "M")
            <td>男</td>
            @elseif($key == "gender" && $applicant->$key == "F")
            <td>女</td>
            @elseif($key == "depart_from" || $key == "back_to" || $key == "fare" || $key == "deposit" )
            <td>{{ $applicant->traffic->$key }}</td>
            @else
            <td>{{ $applicant->$key }}</td>
            @endif
        @endforeach
    </tr>
    @endforeach
</table>
