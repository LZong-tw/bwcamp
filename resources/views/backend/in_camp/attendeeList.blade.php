@extends('backend.master')
@section('content')
@include('..partials.counties_areas_script')
<link rel="stylesheet" href="{{ asset('bootstrap-table/bootstrap-table.min.css') }}">
<script defer src="{{ asset('bootstrap-table/bootstrap-table.min.js') }}"></script>
<script defer src="{{ asset('bootstrap-table/locale/bootstrap-table-zh-TW.min.js') }}"></script>
<style>
    .card-link{
        color: #3F86FB!important;
    }
    .card-link:hover{
        color: #33B2FF!important;
    }
    .btn{
        border-radius: 15px;
    }
</style>
@if(isset($applicants))
    <h3>{{ $fullName }} >> 瀏覽{{ ($is_vcamp)?'義工':'學員' }}名單</h3>
    <p align="right">
        <a href="{{ route("showAttendeePhoto", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">匯出資料</a>
        &nbsp;&nbsp;
        <a href="{{ route("showAttendeePhoto", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">設定小組別/職務</a>
    </p>
    瀏覽組別：
    <button class="btn btn-primary btn-sm" onclick="" value="all"> 全關懷組 </button>
    &nbsp;&nbsp;
    <input type="checkbox" name="no_group" onclick=""> 未分小組
    &nbsp;&nbsp;
    <input type="checkbox" name="group001" onclick=""> 第1組
    &nbsp;&nbsp;
    <input type="checkbox" name="group002" onclick=""> 第2組
    &nbsp;&nbsp;
    <input type="checkbox" name="group003" onclick=""> 第3組
    &nbsp;&nbsp;
    <input type="checkbox" name="group004" onclick=""> 第4組
    &nbsp;&nbsp;
    <input type="checkbox" name="group005" onclick=""> 第5組
    &nbsp;&nbsp;
    <input type="checkbox" name="group006" onclick=""> 第6組
    &nbsp;&nbsp;
    <input type="checkbox" name="group007" onclick=""> 第7組
    &nbsp;&nbsp;
    <input type="checkbox" name="group008" onclick=""> 第8組
    &nbsp;&nbsp;
    <button type="submit" class="btn btn-secondary btn-sm" onclick="">選定</button>
    <br>
    每頁顯示：
    <input type="radio" name="show" onclick="" value="50"> 50筆
    &nbsp;&nbsp;
    <input type="radio" name="show" onclick="" value="100"> 100筆
    &nbsp;&nbsp;
    <button type="submit" class="btn btn-secondary btn-sm" onclick="">選定</button>
    <br>
    共 {{ $applicants->count() }} 筆資料
    <p align="right">
        第1頁/共2頁
        &nbsp;&nbsp;
        <button type="submit" class="btn btn-secondary btn-sm" onclick="">上一頁</button>
        &nbsp;
        <button type="submit" class="btn btn-secondary btn-sm" onclick="">下一頁</button>
    </p>
    <table class="table table-bordered table-hover" data-toggle="table">
        <thead>
            <tr class="bg-success text-white">
                <th data-field="group" data-sortable="true">組別</th>
                @if($is_vcamp)
                <th>職務</th>
                <th>照片</th>
                @endif
                <th>姓名</th>
                <th data-field="gender" data-sortable="true">性別</th>
                <th data-field="birthyear" data-sortable="true">年齡</th>
                @if($is_vcamp)
                <th data-field="lrclass" data-sortable="true">班別</th>
                @endif
                <th data-field="industry" data-sortable="true">產業別</th>
                <th>公司名稱</th>
                <th>職稱</th>
                @if($is_vcamp)
                <th>義工護持紀錄</th>
                <th>班級護持紀錄</th>
                <th>專長</th>
                <th>交通方式</th>
                <th>手機號碼</th>
                @else
                <th>推薦人</th>
                <th>關懷員</th>
                <th>參加意願</th>
                <th data-field="participation_mode" data-sortable="true">參加形式</th>
                <th>推薦理由</th>
                <th>關懷紀錄</th>
                @endif
            </tr>
        </thead>
        @forelse ($applicants as $applicant)
            <tr>
                <td>{{ $applicant->group }}</td>
                @if($is_vcamp)
                    <td></td>
                    @if($applicant->avatar)
                    <td><img src="data:image/png;base64, {{ base64_encode(\Storage::disk('local')->get($applicant->avatar)) }}" width=80 alt="{{ $applicant->name }}"></td>
                    @else
                    <td>no photo</td>
                    @endif
                @endif
                <td>{{ $applicant->name }}</td>
                <td>{{ ($applicant->gender=='M')?'男':'女' }}</td>
                <td>{{ date("Y")-$applicant->birthyear }}</td>
                @if($is_vcamp)
                <td>{{ $applicant->lrclass }}</td>
                @endif
                <td>{{ $applicant->industry }}</td>
                <td>{{ $applicant->unit }}</td>
                <td>{{ $applicant->title }}</td>
                @if($is_vcamp)
                <td>{{ $applicant->cadre_experiences }}</td>
                <td>{{ $applicant->volunteer_experiences }}</td>
                <td>{{ $applicant->expertise }}</td>
                <td>{{ $applicant->transport }}</td>
                <td>{{ $applicant->mobile }}</td>
                @else
                <td>{{ $applicant->introducer_name }}</td>
                <td></td>
                <td></td>
                <td>{{ $applicant->participation_mode }}</td>
                <td>{{ $applicant->reasons_recommend }}</td>
                <td></td>
                @endif
            </tr>
        @empty
            查詢的條件沒有資料
        @endforelse
    </table>
    <p align="right">
        第1頁/共2頁
        &nbsp;&nbsp;
        <button type="submit" class="btn btn-secondary btn-sm" onclick="">上一頁</button>
        &nbsp;
        <button type="submit" class="btn btn-secondary btn-sm" onclick="">下一頁</button>
    </p>
@endif
@endsection
