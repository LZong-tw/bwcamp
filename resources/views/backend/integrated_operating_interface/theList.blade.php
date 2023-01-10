@extends('backend.master')
@section('content')
@include('..partials.counties_areas_script')
<link rel="stylesheet" href="{{ asset('bootstrap-table/bootstrap-table.min.css') }}">
<script src="{{ asset('js/axios.min.js') }}"></script>
<script defer src="{{ asset('bootstrap-table/bootstrap-table.min.js') }}"></script>
<script defer src="{{ asset('bootstrap-table/locale/bootstrap-table-zh-TW.min.js') }}"></script>
@vite(['resources/js/app.js'])
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
    <h3 class="font-weight-bold">{{ $fullName }} >>
        @if($isSetting ?? false) 設定
        @else 瀏覽 @endif
        {{ ($isShowVolunteers && $is_care) ? '關懷組' : '' }}
        {{ ($isShowVolunteers) ? '義工' : '學員' }}名單
        @if($is_ingroup && $groupName)
            >> {{ $groupName }}
        @endif
        @if($is_careV)
            >> 關懷員{{ Auth::user()->name }}
        @endif
    </h3>
    @if($is_care)
        <h4>目前名單為@if($current_batch){{  $current_batch->name . "梯次 / 場次" }}@else{{  "所有梯次 / 場次" }}@endif</h4>
        <h5>
            選擇梯次：@foreach ($batches as $batch) <a href='?isSetting={{ $isSetting }}&batch_id={{ $batch->id }}'>{{ $batch->name }}梯</a> @endforeach
            @if($batches->count() > 1) <a href='?isSetting={{ $isSetting }}'>所有梯次</a> @endif
        </h5>
    @endif
    @if($isSetting ?? false)
    @else
        <x-button.options :isIngroup="$is_ingroup" :isVcamp="$isShowVolunteers" :isCare="$is_care" :isCareV="$is_careV" :currentBatch="$current_batch"/>
    @endif
    @if($is_ingroup)
    @else
        @if($isShowVolunteers && !$is_care)
{{--            <x-checkbox.position-groups :isCare="$is_care" />--}}
        @else
{{--            <x-checkbox.caring-groups :$batches />--}}
        @endif
{{--    <br>--}}
    @endif
    @if($isSetting ?? false)
        <x-general.settings :isIngroup="$is_ingroup" :isVcamp="$isShowVolunteers" :isCare="$is_care" :$batches />
    @endif
    <x-general.search-component :columns="$columns_zhtw" :camp="$campFullData" :$groups :currentBatch="$current_batch" :$queryStr :isCareV="$is_careV" :$isShowVolunteers />
    <x-table.applicant-list :columns="$columns_zhtw" :$applicants :isVcamp="$isShowVolunteers" :isCare="$is_care" :$isSetting :onlyRegisteredVolunteers="$onlyRegisteredVolunteers ?? collect([])"/>
@endif
{{--  $is_care: 正在關懷學員  --}}
@endsection
