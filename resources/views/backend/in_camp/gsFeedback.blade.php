@extends('backend.master')
@section('content')
    <style>
        .card-link{
            color: #3F86FB!important;
        }
        .card-link:hover{
            color: #33B2FF!important;
        }
        /* customize */
        .form-group.required .control-label:after {
            content: "＊";
            color: red;
        }
    </style>
    <h4>問卷內容</h4>
    @if(!isset($contents))
    <p>學員未填問卷</p>
    @else
    <table class="table table-bordered">
        @foreach($titles as $idx => $value)
        <tr>
            <td scope="col">{{ $titles[$idx] }}</td>
            @if($idx<$content_count)
            <td scope="col">{{ $contents[$idx] }}</td>
            @else
            <td scope="col"> </td>
            @endif
        </tr>
        @endforeach
    </table>
    @endif

    <script>
    </script>
@endsection