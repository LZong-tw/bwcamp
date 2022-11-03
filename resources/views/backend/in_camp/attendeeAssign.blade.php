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
    option{
        text-align: center;
    }
    mark{
        background-color: rgb(255, 180, 255);
        padding: 0;
    }

</style>
@if(isset($applicants))
    <h3 class="font-weight-bold">{{ $fullName }} >> 設定{{ ($is_vcamp && $is_care) ? '關懷組' : '' }}{{ (!$is_vcamp && $is_care) ? '關懷員' : '' }}{{ ($is_vcamp) ? '義工組別/職務' : '' }}{{ (!$is_vcamp && !$is_care) ? '學員組別' : '' }}
        @if($is_ingroup && $groupName)
            >> {{ $groupName }}
        @endif
    </h3>
    <br>
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
    <span class="text-danger font-weight-bold">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @if($is_vcamp)
            <button type="submit" class="btn btn-success btn-sm" onclick=""> << 返回義工名單</button>
            &nbsp;&nbsp;
            將所選義工設定為{{ ($is_vcamp && $is_care) ? '第' : '' }}
        @else
            <button type="submit" class="btn btn-success btn-sm" onclick=""> << 返回學員名單</button>
            &nbsp;&nbsp;
        @endif

        @if($is_vcamp && !$is_care)
            <select required name='volunteer_group' onChange=''>
                <option value=''>- 請選擇 -</option>
                <option value='秘書'>秘書</option>
                <option value='資訊'>資訊</option>
                <option value='關懷'>關懷</option>
                <option value='教務'>教務</option>
                <option value='行政'>行政</option>
            </select>
            組
            <select required name='volunteer_work' onChange=''>
                <option value=''>- 請選擇 -</option>
                <option value='總護持'>總護持</option>
                <option value='副總護持'>副總護持</option>
                <option value='文書'>文書</option>
                <option value='大組長'>大組長</option>
                <option value='副大組長'>副大組長</option>
            </select>
            職務
        @else
            @if(!$is_vcamp && $is_care)
                將所選學員之關懷員設定為
                <select required name='attendee_care' onChange=''>
                    <option value=''>- 請選擇 -</option>
                    <option value='楊圓滿'>楊圓滿</option>
                    <option value='陳莊嚴'>陳莊嚴</option>
                </select>
            @else
                @if(!$is_vcamp)
                    將所選學員設定為第
                @endif
                <select required name='attendee_group' onChange=''>
                    <option value=''>- 請選擇 -</option>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                    <option value='4'>4</option>
                    <option value='5'>5</option>
                    <option value='6'>6</option>
                    <option value='7'>7</option>
                    <option value='8'>8</option>
                </select>
                組
            @endif

            @if($is_vcamp)
                <select required name='attendee_work' onChange=''>
                    <option value=''>- 請選擇 -</option>
                    <option value='小組長'>小組長</option>
                    <option value='副小組長'>副小組長</option>
                    <option value='組員'>組員</option>
                </select>
                職務
            @endif
        @endif
        &nbsp;&nbsp;
        <button type="submit" class="btn btn-danger btn-sm" onclick="">儲存</button>
    </span>

    <table class="table table-bordered table-hover"
    data-toggle="table"
    data-search="true"
    data-show-columns-search="true"
    data-search-highlight="true"
    data-search-align="left"
    data-pagination="true"
    data-smart-display="false"
    data-pagination-loop="false"
    data-pagination-v-align="both"
    data-page-list="[10, 50, 100]"
    data-click-to-select="true"
    data-pagination-pre-text="上一頁"
    data-pagination-next-text="下一頁">
        <thead>
            <tr class="bg-success text-white">
                <th data-field="state" data-checkbox="true"></th>
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
                <td></td>
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
