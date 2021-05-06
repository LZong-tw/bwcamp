@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 福青社學校統計</h2>
    <table class="table table-bordered">
        <tr>
            <td align=center>地區</td>
            <td align=center>學校全名</td>
            <td align=center>報名人數</td>
            <td align=center>小計</td>
        </tr>
        @php
            $all = 0;    
        @endphp
        @foreach ($groups as $groupname => $schools)
            @foreach ($schools as $school)
                @if($loop->first)
                    <tr style="text-align: center;">
                        <td rowspan="{{ count($schools) }}" style="vertical-align: middle!important">{{ $groupname }}</td>
                        <td>{{ $school ?? "" }}</td>
                        <td>{{ $totals[$school] }}</td>  
                        <td rowspan="{{ count($schools) }}" style="vertical-align: middle!important">{{ $totals[$groupname] }}</td>   
                    </tr>
                @else
                    <tr style="text-align: center;">
                        <td>{{ $school ?? "" }}</td>
                        <td>{{ $totals[$school] }}</td>   
                    </tr>    
                @endif
            @endforeach
            @php
                $all += $totals[$groupname];    
            @endphp
        @endforeach
        <tr align=left bgcolor=#D4DFEB>
            <td align=center>總計</td>
            <td align=center colspan=3>{{ $all }}</td>
        </tr>
    </table>
@endsection