@extends('backend.ioiMaster')
@section('content')
@include('..partials.counties_areas_script')
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
<script>
    window.isShowVolunteers = {{ $isShowVolunteers ?? 0 }};
</script>
@if($errors->any())
    @foreach ($errors->all() as $message)
        <div class='alert alert-danger' role='alert'>
            {{ $message }}
        </div>
    @endforeach
@endif
@if(session()->has('messages'))
    @foreach (session()->get('messages') as $message)
        <div class='alert alert-success' role='alert'>
            {{ $message }}
        </div>
    @endforeach
@endif
@if(isset($applicants))
    <h3 class="font-weight-bold">{{ $fullName }} >>
        @if(($isSettingCarer ?? false) || ($isSetting ?? false)) 設定
        @else 瀏覽 @endif
        {{ ($isShowVolunteers) ? '關懷組' : '' }}
        {{ ($isShowVolunteers) ? '義工' : '學員' }}@if(!($isSettingCarer ?? true))名單@else所屬關懷員@endif
    </h3>
    <h4>目前名單為@if($current_batch){{  $current_batch->name . "梯次 / 場次" }}@else{{  "所有梯次 / 場次" }}@endif</h4>
    <h5>
        選擇梯次：@foreach ($batches as $batch) <a href='?isSetting={{ $isSetting ?? 0 }}&isSettingCarer={{ $isSettingCarer ?? 0 }}&batch_id={{ $batch->id }}'>{{ $batch->name }}梯</a> @endforeach
        @if($batches->count() > 1) <a href='?isSetting={{ $isSetting }}&isSettingCarer={{ $isSettingCarer ?? 0 }}'>所有梯次</a> @endif
    </h5>
    <x-button.options :$isShowVolunteers :$isShowLearners :currentBatch="$current_batch"/>
    @if(($isSettingCarer ?? false) || ($isSetting ?? false))
        <x-general.settings :$isShowVolunteers :$isShowLearners :$batches :$isSettingCarer :$carers :$targetGroupIds/>
    @endif
    <x-general.search-component :columns="$columns_zhtw" :camp="$campFullData" :$groups :currentBatch="$current_batch" :$queryStr :$isShowLearners :$isShowVolunteers :queryRoles="$queryRoles ?? null" :$applicants :registeredVolunteers="$registeredVolunteers ?? collect([])" />
    <x-table.applicant-list :columns="$columns_zhtw" :$applicants :$isShowVolunteers :$isShowLearners :$isSetting :registeredVolunteers="$registeredVolunteers ?? collect([])" :$isSettingCarer />
@endif
@endsection
