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
<h2 class="center">{{ $camp->fullName }} 報名報到暨宿舍安排單</h2>
<table class="table table-bordered" width="100%" >
    <tr>
        <td>男生</td>
        <td>{{ $group }}組</td>
        <td>輔導員：</td>
        <td>輔導員手機：</td>        
    </tr>
</table>
<h4>【住宿概況】</h4>
<table class="table" width="100%" >
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
<table class="table table-bordered" width="100%" >
    <tr>
        @foreach($columns as $key => $val)
        <td>{{ $val }}</td>
        @endforeach
    </tr>    
    @foreach($applicants as $applicant)
    @if($applicant->gender == "M")
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
</table>
<h2 class="center">{{ $camp->fullName }} 報名報到暨宿舍安排單</h2>
<table class="table table-bordered" width="100%" >
    <tr>
        <td>女生</td>
        <td>{{ $group }}組</td>
        <td>輔導員：</td>
        <td>輔導員手機：</td>        
    </tr>
</table>
<h4>【住宿概況】</h4>
<table class="table" width="100%" >
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
<table class="table table-bordered" width="100%" >
    <tr>
        @foreach($columns as $key => $val)
        <td>{{ $val }}</td>
        @endforeach
    </tr>    
    @foreach($applicants as $applicant)
    @if($applicant->gender == "F")
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
</table>
