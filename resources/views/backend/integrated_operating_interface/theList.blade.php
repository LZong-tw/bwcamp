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
        {{ ($isShowVolunteers) ? '義工' : '學員' }}@if(!($isSettingCarer ?? true))名單@else所屬關懷員@endif
    </h3>
    <h4>目前名單為@if($current_batch){{  $current_batch->name . "梯次 / 場次" }}@else{{  "所有梯次 / 場次" }}@endif</h4>
    <h5>
        選擇梯次：@foreach ($batches as $batch) <a href='?isSetting={{ $isSetting ?? 0 }}&isSettingCarer={{ $isSettingCarer ?? 0 }}&batch_id={{ $batch->id }}'>{{ $batch->name }}梯</a> @endforeach
        @if($batches->count() > 1) <a href='?isSetting={{ $isSetting }}&isSettingCarer={{ $isSettingCarer ?? 0 }}'>所有梯次</a> @endif
    </h5>
    <x-button.options :$isShowVolunteers :$isShowLearners :currentBatch="$current_batch"/>

    <div class="divTelCallResult" style="display:none">
    @if($dynamic_stats->isNotEmpty())
        @foreach($dynamic_stats as $stat)
            <a href="{{ $stat->google_sheet_url }}" target="_blank" class="btn btn-primary mb-3">電訪結果連結</a>
            <br>
            <iframe src="{{ $stat->google_sheet_url }}" width="100%" height="480">Your browser isn't compatible</iframe>
        @endforeach
    @else
        無電訪結果可查看<br>
    @endif 
    </div>

    @if(($isSettingCarer ?? false) || ($isSetting ?? false))
        <x-general.settings :$isShowVolunteers :$isShowLearners :$batches :$isSettingCarer :$carers :targetGroupIds="$targetGroupIds ?? null"/>
    @endif
    <x-general.search-component :columns="$columns_zhtw" :camp="$campFullData" :$groups :currentBatch="$current_batch" :$queryStr :$isShowLearners :$isShowVolunteers :queryRoles="$queryRoles ?? null" :$applicants :registeredVolunteers="$registeredVolunteers ?? collect([])" />
    <x-table.applicant-list :columns="$columns_zhtw" :$applicants :$isShowVolunteers :$isShowLearners :$isSetting :registeredVolunteers="$registeredVolunteers ?? collect([])" :$isSettingCarer />
@endif
    <a href="#top" class="text-danger font-weight-bold">↑TOP</a>
@endsection

<script>
function showTelCallResult(){        
            tg_div = document.getElementsByClassName('divTelCallResult');
            tg_btn = document.getElementsByClassName('btnTelCallResult');
            console.log(tg_div);
            console.log(tg_btn);
            if (tg_div[0].style.display == 'none') {
                tg_div[0].style.display = '';
                tg_btn[0].value = '隱藏電訪結果';
            }
            else {
                tg_div[0].style.display = 'none';
                tg_btn[0].value = '電訪結果';
            }
        }
</script>