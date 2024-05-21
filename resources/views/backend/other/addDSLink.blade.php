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
    <h2>{{ $campFullData->abbreviation }} 新增/查詢/修改 動態統計連結</h2>
    @if(\Session::has('message'))
        <div class='alert alert-success' role='alert'>
            {{ \Session::get('message') }}
        </div>
    @endif
    @if(isset($ds))
    <h4>修改 動態統計連結</h4>
    <form action="{{ route('modifyDSLink', $camp_id) }}" method="post" class="form-horizontal">
    @else
    <h4>新增 動態統計連結</h4>
    <form action="{{ route('addDSLink', $camp_id) }}" method="post" class="form-horizontal">
    @endif
        @csrf
        <div class='form-group required'>
            選擇動態統計連結 營隊/梯次/個人＊
            <select required name='urltable_type' id='selUrlTableType' class='form-control' onChange='showFields()'>
                    <option value='' selected>- 請選擇 -</option>
                    <option value='Camp' >Camp</option>
                    <option value='Batch' >Batch</option>
                    <option value='Applicant' >Applicant</option>
            </select>
        </div>
        <div class='form-group required'>
            請輸入營隊id/梯次id/個人報名序號＊
            <input required type="number" name="urltable_id" id='inputUrlTableId' class="form-control"  placeholder="">
        </div>
        <div class='form-group'>
            請輸入連結的用途
            <input type="text" name="purpose" id='inputPurpose' class="form-control"  placeholder="">
        </div>
        <div class='form-group field_applicant' style='display:none'>
            Link URL＊
            <input required type="text" name="google_sheet_url" id='inputGSUrl' class="form-control itemreq_applicant" placeholder="">
        </div>
        <div class='form-group field_camp' style='display:none'>
            GoogleSheet ID＊
            <input required type="text" name="spreadsheet_id" id='inputSSID' class="form-control itemreq_camp" placeholder="">
            GoogleSheet Sheet Name＊
            <input required type="text" name="sheet_name" id='inputSheetName' class="form-control itemreq_camp" placeholder="">
        </div>
        <input type="hidden", name="camp_id", value="{{ $camp_id }}">
        @if(isset($ds))
        <input type="hidden", name="gslink_id", value="{{ $ds->id }}">
        <input type="submit" class="btn btn-success" value="修改">
        @else
        <input type="submit" class="btn btn-success" value="新增">
        @endif
    </form>
    <br><br>
    <h4>查詢GS連結</h4>
    <form action="{{ route('queryDSLink', $camp_id) }}" method="post" class="form-horizontal">
        @csrf
        <div class='form-group required'>
            選擇動態統計連結 營隊/梯次/個人＊
            <select required name='urltable_type' id='selUrlTableType1' class='form-control'>
                <option value='' selected>- 請選擇 -</option>
                <option value='Camp' >Camp</option>
                <option value='Batch' >Batch</option>
                <option value='Applicant' >Applicant</option>
            </select>
        </div>
        <div class='form-group required'>
            請輸入營隊id/梯次id/個人報名序號＊
            <input required type="text" name="urltable_id" id='inputUrlTableId1' class="form-control"  placeholder="">
        </div>
        <inptu type="hidden", name="camp_id", value="{{ $camp_id }}">
        <input type="submit" class="btn btn-primary" value="查詢">
    </form>

    <script>
    function showFields(){
        sel = document.getElementById('selUrlTableType').value;
        fields_camp = document.getElementsByClassName('field_camp');
        fields_applicant = document.getElementsByClassName('field_applicant');
        itemsreq_camp = document.getElementsByClassName('itemreq_camp');
        itemsreq_applicant = document.getElementsByClassName('itemreq_applicant');
        if (sel == 'Camp' || sel == 'Batch') {
            for (i=0;i<itemsreq_camp.length;i++) itemsreq_camp[i].required = true;
            for (i=0;i<itemsreq_applicant.length;i++) itemsreq_applicant[i].required = false;
            for (i=0;i<fields_camp.length;i++) fields_camp[i].style.display = '';
            for (i=0;i<fields_applicant.length;i++) fields_applicant[i].style.display = 'none';
        } else {
            for (i=0;i<itemsreq_camp.length;i++) itemsreq_camp[i].required = false;
            for (i=0;i<itemsreq_applicant.length;i++) itemsreq_applicant[i].required = true;
            for (i=0;i<fields_camp.length;i++) fields_camp[i].style.display = 'none';
            for (i=0;i<fields_applicant.length;i++) fields_applicant[i].style.display = '';
        }
    }
    </script>

    @if(isset($ds))
    <script>
        {{-- 回填 --}}
        (function() {
            let ds_data = JSON.parse('{!! $ds !!}');
            let selects = document.getElementsByTagName('select');
            selects[0].value = ds_data["urltable_type"];
            console.log(selects);
            showFields();

            let inputs= document.getElementsByTagName('input');
            console.log(inputs);
            for (i=0; i<inputs.length; i++) {
                if (inputs[i].type == 'number' || inputs[i].type == 'text')
                    inputs[i].value = ds_data[inputs[i].name];
            }
        })();
    </script>
    @endif

    <style>
        .required .control-label::after {
            content: "＊";
            color: red;
        }
    </style>
@endsection
