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
    .dropdown-toggle::after{
        display: none !important;
    }
    mark{
        background-color: rgb(255, 180, 255);
        padding: 0;
    }

</style>
@if(isset($applicants))
    <h3 class="font-weight-bold">{{ $fullName }} >> 瀏覽{{ ($is_vcamp && $is_care) ? '關懷組' : '' }}{{ ($is_vcamp) ? '義工' : '學員' }}名單
        @if($is_ingroup && $groupName)
            >> {{ $groupName }}
        @endif
        @if($is_careV)
            >> 關懷員{{ Auth::user()->name }}
        @endif
    </h3>
    <p align="right">
        <a href="{{ route("showAttendeePhoto", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">匯出資料</a>
        @if($is_ingroup)
            @if(!$is_careV)
                &nbsp;&nbsp;
                <a href="{{ route("showAttendeePhoto", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">設定關懷員</a>
            @endif
        @elseif(!$is_vcamp)
            &nbsp;&nbsp;
            <a href="{{ route("showAttendeePhoto", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">新增學員</a>
            @if($is_care)
                &nbsp;&nbsp;
                <a href="{{ route("showAttendeePhoto", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">設定組別</a>
            @endif
        @elseif($is_vcamp)
            @if(!$is_care)
                &nbsp;&nbsp;
                <a href="{{ route("showAttendeePhoto", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">新增義工</a>
            @endif
            &nbsp;&nbsp;
            <a href="{{ route("showAttendeePhoto", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">設定組別/職務</a>
        @endif
    </p>
    @if($is_ingroup)
    @else
        <span class="font-weight-bold">瀏覽組別：</span>
        @if($is_vcamp)
            @if($is_care)
                <button class="btn btn-primary btn-sm" onclick="" value="all"> 全關懷組 </button>
            @else
                <button class="btn btn-primary btn-sm" onclick="" value="all"> 所有義工 </button>
            @endif
            &nbsp;&nbsp;
        @else
            <button class="btn btn-primary btn-sm" onclick="" value="all"> 所有學員 </button>
            &nbsp;&nbsp;
        @endif
        <input type="checkbox" name="no_group" onclick=""> 未分組
        &nbsp;&nbsp;
        @if($is_vcamp && !$is_care)
            <input type="checkbox" name="group001" onclick=""> 秘書組
            &nbsp;&nbsp;
            <input type="checkbox" name="group002" onclick=""> 資訊組
            &nbsp;&nbsp;
            <input type="checkbox" name="group003" onclick=""> 關懷組
            &nbsp;&nbsp;
            <input type="checkbox" name="group004" onclick=""> 教務組
            &nbsp;&nbsp;
            <input type="checkbox" name="group005" onclick=""> 行政組
            &nbsp;&nbsp;
        @else
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
        @endif
        <button type="submit" class="btn btn-secondary btn-sm" onclick="">選定</button>
        <br>
    @endif

<!--
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
-->
    <table class="table table-bordered table-hover"
    data-toggle="table"
    data-show-columns="true"
    data-show-columns-search="true"
    data-search="true"
    data-search-highlight="true"
    data-search-align="left"
    data-pagination="true"
    data-smart-display="false"
    data-pagination-loop="false"
    data-pagination-v-align="both"
    data-page-list="[10, 50, 100]"
    data-pagination-pre-text="上一頁"
    data-pagination-next-text="下一頁">
        <thead>
            <tr class="bg-success text-white">
                @foreach ($columns_zhtw as $key => $item)
                    @if(!$is_vcamp && !$is_care && $key == "caring_logs")
                    @else
                        <th data-field="{{ $key }}" data-sortable="{{ $item['sort'] }}">{{ $item['name'] }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        @forelse ($applicants as $applicant)
            <tr>
                @foreach ($columns_zhtw as $key => $item)
                    @if($key == "avatar" && $applicant->avatar)
                        <td><img src="data:image/png;base64, {{ base64_encode(\Storage::disk('local')->get($applicant->avatar)) }}" width=80 alt="{{ $applicant->name }}"></td>
                    @elseif($key == "avatar" && !$applicant->avatar)
                        <td>no photo</td>
                    @elseif($key == "gender")
                        <td>{{ $applicant->gender_zh_tw }}</td>
                    @elseif(!$is_vcamp && !$is_care && $key == "caring_logs")
                    @elseif(!$applicant->$key)
                        <td>開發中</td>
                    @else
                        <td>{{ $applicant->$key }}</td>
                    @endif
                @endforeach
            </tr>
        @empty
            查詢的條件沒有資料
        @endforelse
    </table>
<!--
    <p align="right">
        第1頁/共2頁
        &nbsp;&nbsp;
        <button type="submit" class="btn btn-secondary btn-sm" onclick="">上一頁</button>
        &nbsp;
        <button type="submit" class="btn btn-secondary btn-sm" onclick="">下一頁</button>
    </p>
-->
@endif
@endsection
