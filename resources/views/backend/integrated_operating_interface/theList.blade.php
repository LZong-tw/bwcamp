@extends('backend.master')
@section('content')
@include('..partials.counties_areas_script')
<link rel="stylesheet" href="{{ asset('bootstrap-table/bootstrap-table.min.css') }}">
<script src="{{ asset('js/axios.min.js') }}"></script>
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
    <h3 class="font-weight-bold">{{ $fullName }} >>
        @if($isSetting ?? false) 設定
        @else 瀏覽 @endif
        {{ ($is_vcamp && $is_care) ? '關懷組' : '' }}
        {{ ($is_vcamp) ? '義工' : '學員' }}名單 @foreach ($batches as $batch) <a href='?batch={{ $batch->id }}'>{{ $batch->name }}梯</a> @endforeach
        @if($is_ingroup && $groupName)
            >> {{ $groupName }}
        @endif
        @if($is_careV)
            >> 關懷員{{ Auth::user()->name }}
        @endif
    </h3>
    @if($isSetting ?? false)
    @else
        <x-button.options :isIngroup="$is_ingroup" :isVcamp="$is_vcamp" :isCare="$is_care" :isCareV="$is_careV"/>
    @endif
    @if($is_ingroup)
    @else
        <span class="font-weight-bold">瀏覽組別：</span>
        @if($is_vcamp && !$is_care)
            <x-checkbox.position-groups :isCare="$is_care" />
        @else
            <x-checkbox.caring-groups :$batches />
        @endif
        <br>
    @endif
    <x-general.settings :$is_vcamp :$is_care :$batches />
    <x-general.search-component :columns="$columns_zhtw" />
    <x-table.applicant-list :columns="$columns_zhtw" :$applicants :$is_vcamp :$is_care/>
@endif
@endsection
