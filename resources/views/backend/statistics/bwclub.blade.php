@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 福青社統計</h2>
    <table class="table table-bordered">
        <tr>
            <td align=center>地區</td>
            <td align=center colspan="2"></td>
            <td align=center>小計</td>
        </tr>
        @php
            $all = 0;    
        @endphp
        @foreach ($groups as $groupname => $schools)
            <tr style="text-align: center; vertical-align: middle" valign="middle">
                <td>{{ $groupname }}</td>
                <td colspan="2">
                    <table class="table">
                        <tr>
                            <td align=center>學校全名</td>
                            <td align=center>報名人數</td>
                        </tr>
                        @foreach ($schools as $school)
                            <tr style="text-align: center; vertical-align: middle" valign="middle">
                                <td>{{ $school ?? "" }}</td>
                                <td>{{ $totals[$school] }}</td>   
                            </tr>    
                        @endforeach
                    </table>
                </td>
                <td>{{ $totals[$groupname] }}</td>  
            </tr>                
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