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
</style>
{{--
<a href="{{ route('showPaymentForm', [$applicant->batch->camp_id, $applicant->id]) }}?download=1" target="_blank">下載繳費單</a>
--}}
{{-- 在正式環境用 h 系列標籤，中文字型會壞掉；portrait 740px；landscape 740x1.414=1046px--}}
<a style="font-size: 2em;">{{ $camp->fullName }} {{ $form_title }}</a>
<table class="table table-bordered" width={{ $form_width }}>
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
            @elseif($key == "deposit")
                {{-- use traffic.deposit --}}
                @if(isset($applicant->traffic->$key))
                <td>{{ $applicant->traffic->$key }}</td>
                @else
                <td></td>
                @endif
            @elseif(isset($applicant->$key))
            <td>{{ $applicant->$key }}</td>
            @elseif(isset($applicant->traffic->$key))
            <td>{{ $applicant->traffic->$key }}</td>
            @else
            <td></td>
            @endif
        @endforeach
    </tr>
    @endforeach
</table>
