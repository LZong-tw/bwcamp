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
    <h3>{{ $fullName }} >> 瀏覽{{ ($is_vcamp) ? '義工' : '學員' }}名單</h3>
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
                @foreach ($columns_zhtw as $key => $item)
                    <th data-field="{{ $key }}" data-sortable="{{ $item['sort'] }}">{{ $item['name'] }}</th>
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
                    @else
                        <td>{{ $applicant->$key }}</td>
                    @endif
                @endforeach
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
