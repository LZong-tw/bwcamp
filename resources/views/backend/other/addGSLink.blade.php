@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 新增/查詢 GS連結</h2>
    <h4>新增GS連結</h4>
    <form action="{{ route('addGSLink', $camp_id) }}" method="post" class="form-horizontal">
        @csrf
        <div class='form-group required'>
            選擇GS連結營隊或個人＊
            <select required name='urltable_type' id='selUrlTableType' class='form-control' onChange='showFields()'>
                    <option value='' selected>- 請選擇 -</option>
                    <option value='Applicant' >Applicant</option>
                    <option value='Camp' >Camp</option>
            </select>
        </div>
        <div class='form-group required'>
            請輸入營隊id或個人報名序號＊
            <input required type="number" name="urltable_id" id='inputUrlTableId' class="form-control"  placeholder="">
        </div>
        <div class='form-group'>
            請輸入連結的用途
            <input type="text" name="purpose" id='inputPurpose' class="form-control"  placeholder="">
        </div>
        <div class='form-group required field_applicant itemreq_applicant' style='display:none'>
            GS URL＊
            <input required type="text" name="google_sheet_url" id='inputGSUrl' class="form-control"  placeholder="">
        </div>
        <div class='form-group required field_camp itemreq_camp' style='display:none'>
            GS ID＊
            <input required type="text" name="spreadsheet_id" id='inputSSID' class="form-control"  placeholder="">
            GS Sheet Name＊
            <input required type="text" name="sheet_name" id='inputSheetName' class="form-control"  placeholder="">
        </div>
        <inptu type="hidden", name="camp_id", value="{{ $camp_id }}">
        <input type="submit" class="btn btn-success" value="新增">
    </form>
    <br><br>
    <h4>查詢GS連結</h4>
    <form action="{{ route('queryGSLink', $camp_id) }}" method="post" class="form-horizontal">
        @csrf
        <div class='form-group required'>
            選擇GS連結營隊或個人＊
            <select required name='urltable_type' id='selUrlTableType1' class='form-control'>
                <option value='' selected>- 請選擇 -</option>
                <option value='Applicant' >Applicant</option>
                <option value='Camp' >Camp</option>
            </select>
        </div>
        <div class='form-group required'>
            請輸入營隊id或個人報名序號＊
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
        if (sel == 'Camp') {
            for (i=0;i<fields_camp.length;i++) fields_camp[i].style.display = '';
            for (i=0;i<fields_applicant.length;i++) fields_applicant[i].style.display = 'none';
            for (i=0;i<itemsreq_camp.length;i++) itemsreq_camp[i].required = true;
            for (i=0;i<itemsreq_applicant.length;i++) itemsreq_camp[i].required = false;
        } else {
            for (i=0;i<fields_camp.length;i++) fields_camp[i].style.display = 'none';
            for (i=0;i<fields_applicant.length;i++) fields_applicant[i].style.display = '';
            for (i=0;i<itemsreq_camp.length;i++) itemsreq_camp[i].required = false;
            for (i=0;i<itemsreq_applicant.length;i++) itemsreq_camp[i].required = true;
        }
    }
    </script>

    @if(isset($ds))
    <script>
        {{-- 回填關懷記錄 --}}
        (function() {
            let ds_data = JSON.parse('{!! $ds !!}');
            console.log(ds_data);
            let selects = document.getElementsByTagName('select');
            if (ds_data["urltable_type"] == "App\\Models\\Camp")
                selects[0].value = "Camp";
            else
                selects[0].value = "Applicant";
            console.log("Hello");
            showFields();

            let numbers= document.getElementsByTagName('number');
            let texts= document.getElementsByTagName('text');
            numbers[0].value = ds_data[numbers[0].name];
            for (i=0; i<texts.length; i++) {
                texts[i].value = ds_data[texts[i].name];
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
