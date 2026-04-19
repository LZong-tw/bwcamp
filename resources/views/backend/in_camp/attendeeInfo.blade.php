@extends('backend.master')
@section('content')
@include('..partials.counties_areas_script')
<style>
    .card-link{
        color: #3F86FB!important;
    }
    .card-link:hover{
        color: #33B2FF!important;
    }
    iframe {
        overflow: scroll;
        width: 100%;
        height: 100vh;
        border: 0;
    }
</style>
@if($errors->any())
    @foreach ($errors->all() as $message)
        <div class='alert alert-danger' role='alert'>
            {{ $message }}
        </div>
    @endforeach
@endif
@if(session()->has('message'))
    <div class='alert alert-success' role='alert'>
        {{ session()->get('message') }}
    </div>
@endif
@php
    $fields1 = $layout['sec_basic']['fields1'];
    $fields2 = $layout['sec_basic']['fields2'];
    $fields3 = $layout['sec_basic']['fields3'];
    $fields4 = $layout['sec_adv1']['fields'];
    $fields5 = $layout['sec_adv2']['fields'];
    $fields6 = $layout['sec_adv3']['fields'];
    $keyword_phone = ['phone','mobile'];
@endphp

@if(isset($applicant))
    <h4>{{ $camp->fullName }}>>個人詳細資料>>{{ $applicant->name }}</h4>

    <!-- 修改學員資料,使用報名網頁 -->
    @if($currentUser->canAccessResource($applicant, 'update', $applicant->camp, target: $applicant))
    <div class="container mr-4 mb-2">
    <form target="_blank" action="{{ route('queryupdate', $batch->id) }}" method="post">
        @csrf
        <input type="hidden" name="sn" value="{{ $applicant->applicant_id }}">
        <input type="hidden" name="name" value="{{ $applicant->name }}">
        <!-- input type="hidden" name="isBackend" value="目前為後台檢視狀態。"-->
        <input type="hidden" name="isModify" value="1">
        <button class="mr-4 btn btn-primary float-right">修改學員(報名)資料</button>
        <br>
    </form>
    </div>
    @endif
    <br>
    <div class="container alert alert-warning">
        <div class="row">
            <div class="col-md-3">
                @if($applicant->avatar)
                <img src="data:image/png;base64, {{ base64_encode(\Storage::disk('local')->get($applicant->avatar)) }}" width=80 alt="{{ $applicant->name }}">
                @else
                no photo
                @endif
            </div>
            <div class="col-md-3">
                @foreach($fields1 as $key => $val)
                @if($key == "gender")
                <b>{{$val}}</b>：{{$applicant->gender_chn?? ""}}<br>
                @else
                <b>{{$val}}</b>：{{$applicant->$key?? ""}}<br>
                @endif
                @endforeach  
            </div>
            <div class="col-md-3">
                @foreach($fields2 as $key => $val)
                <b>{{$val}}</b>：{{$applicant->$key?? ""}}<br>
                @endforeach  
            </div>
            <div class="col-md-3">
                @foreach($fields3 as $key => $val)
                <b>{{$val}}</b>：{{$applicant->$key?? ""}}<br>
                @endforeach
            </div>
        </div>
    </div>
    @if($layout['sec_lodging']['is_shown'] || $layout['sec_traffic']['is_shown'])
    <div class="container alert" style="background-color:#eaeaea">
        @if($layout['sec_lodging']['is_shown'])
        <div class="row d-flex justify-content-start">
            <div class="ml-2 mb-2 font-weight-bold">住宿選項</div>：
            @if (!isset($applicant->lodging))
                <div class="mr-4 text-primary">尚未登記。</div>
            @else
                <div class="mr-4 text-success">{{ $applicant->lodging->room_type }}</div>
            @endif
        </div>
        @endif

        @if($layout['sec_traffic']['is_shown'])
        <div class="row d-flex justify-content-start">
            <div class="ml-2 mb-2 font-weight-bold">交通選項</div>：
            @if (!isset($applicant->traffic))
                <div class="mr-4 text-primary">尚未登記。</div>
            @else
                <div class="mr-4 text-success">{{ $applicant->traffic->depart_from }}/{{ $applicant->traffic->back_to }}</div>
            @endif
        </div>
        @endif
    </div>
    @endif

    @if($layout['sec_attend']['is_shown'])
    <div class="container alert alert-warning">
        <div class="row d-flex">
            <div class="ml-2 mb-2 font-weight-bold">參加意願</div>：
            @if($applicant->is_attend)
            <div class="mr-4 mb-2">
                {{ $applicant->is_attend_chn }}
            </div>
            @else
            <div class="mr-4 mb-2 text-primary">尚未聯絡。</div>
            @endif
        </div>
        @if ($applicant->deleted_at)
        <div class="row d-flex ml-2 mb-2 text-danger">本學員已取消報名。</div>
        <div class='row d-flex ml-2 mb-2 justify-content-end'>
            <form class="mr-4 mb-2" action="{{ route('revertCancellation', $camp->id) }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                <input class="btn btn-success" type="submit" value="重新報名">
            </form>
        </div>
        @else
            <form class="ml-2 mr-4 mb-2" action="{{ route('toggleAttendBackend', $applicant->batch->id) }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                @foreach(\App\Enums\AttendanceStatus::cases() as $status)
                    <label class="ml-2 mr-2">
                        <input type="radio" name="is_attend" value="{{ $status->value }}"
                            {{ $applicant->is_attend === $status ? 'checked' : '' }}>{{ $status->label() }}
                    </label>
                @endforeach
                <div class="row d-flex justify-content-end">
                <input class="btn btn-success" type="submit" value="修改參加狀態">
                </div>
            </form>
            <form class="mr-4 mb-2" action="{{ route('cancelRegistration', $camp->id) }}" method="POST">
                <div class="row d-flex justify-content-end">
                    @csrf
                    <input type="hidden" name="id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                    <input class="btn btn-danger" type="submit" value="取消報名">
                </div>
            </form>            
        @endif
    </div>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-4 container" >
                <div class="pt-3 pb-3 px-3" style="background-color:#eaeaea">
                <span class="font-weight-bold bg-warning" style="font-size: 18px;">　{{ $layout['sec_adv1']['title']?? "聯絡方式"}}　</span><br><br>
                <x-applicant-data-display :applicant="$applicant" :fields="$fields4" />
                </div>
            </div>
            <div class="col-md-4 container">
                <div class="pt-3 pb-3 px-3" style="background-color:#eaeaea">
                <span class="font-weight-bold bg-warning" style="font-size: 18px;">　{{ $layout['sec_adv2']['title']?? "推薦人資訊"}}　</span><br><br>
                <x-applicant-data-display :applicant="$applicant" :fields="$fields5" />
                </div>
            </div>
            <div class="col-md-4 container">
                <div class="pt-3 pb-3 px-3" style="background-color:#eaeaea">
                <span class="font-weight-bold bg-warning" style="font-size: 18px;">　{{ $layout['sec_adv3']['title']?? "其他資訊"}}　</span><br><br>
                <x-applicant-data-display :applicant="$applicant" :fields="$fields6" />
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
            @if($layout['sec_interest']['is_shown'])
            	<span class="btn btn-info">關心議題</span><br><br>
                @if(isset($applicant->favored_events))
                @foreach($applicant->favored_events as $event)
                    {{ $event }}<br>
                @endforeach
                @endif
            @endif
            @if($layout['sec_remark']['is_shown'])
                <span class="font-weight-bold text-white bg-info" style="font-size: 18px;">　備註　</span><br>
                <form action="{{ route('editRemark', $camp->id) }}" method="POST">
                    @csrf
                    <br>
                    <textarea class=form-control rows=5 required name='remark' id="remark" readonly onclick='enableEditRemark()'>{{ $applicant->remark }}</textarea>
                    <br>
                    <input type="hidden" name="applicant_id" value="{{ $applicant->applicant_id }}">
                    <input type="submit" class="btn btn-primary float-right" name="editremark" id="editremark" value="確認編輯" disabled>
                </form>
                <br>
                <br>
            @endif
            @if($layout['sec_qrcode']['is_shown'])
                <hr>
                <h5>報到條碼</h5>
                <img src="data:image/png;base64,{{ $qrcode }}" alt="barcode" height="300px" class="mb-3"/>
                <br>
            </div>
            @endif
            @if($currentUser->canAccessResource(new App\Models\ContactLog(), 'read', $campFullData, target: $applicant))
                <div class="col-md-8">
                    <span class="font-weight-bold text-white bg-info" style="font-size: 18px;">　關懷記錄　</span><br>
                    @if($currentUser->canAccessResource(new App\Models\ContactLog(), 'create', $campFullData, target: $applicant))
                        <form action="{{ route('addContactLog', $camp->id) }}" method="POST">
                            <a id="new"></a>
                            @csrf
                            <br>
                            <textarea class=form-control rows=5 required name='notes' id=""></textarea>
                            <br>
                            <input type="hidden" name="applicant_id" value="{{ $applicant->applicant_id }}">
                            <input type="hidden" name="todo" value="add">
                            <input type="submit" class="btn btn-primary float-right" value="新增關懷記錄">
                        </form>
                        <br><br><hr>
                    @endif
                    <b>最新一筆關懷記錄</b><br>
                    @if(isset($contactlog))
                    {{ $contactlog->updated_at }} 由 {{ $contactlog->takenby_name }} 記錄：<br>
                    {{ $contactlog->notes }}<br><br>
                    @else
                    <b>無關懷記錄</b>
                    @endif
                    <a href="{{ route('showContactLogs', [$camp->id, $applicant->applicant_id]) }}" class="btn btn-secondary float-right">更多關懷記錄</a><br><br>
                </div>
            @endif
        </div>
    </div>
    @if ($applicant->dynamic_stats)
        <div class="container">
            @foreach($applicant->dynamic_stats as $stat)
                <br>
                <div class="row mt-3">
                    <a href="{{ $stat->google_sheet_url }}" target="_blank" class="btn btn-primary mb-3">電訪調查表連結</a>
                    <br>
                    <iframe src="{{ $stat->google_sheet_url }}">Your browser isn't compatible</iframe>
                </div>
            @endforeach
        </div>
    @endif
@endif
<script>
    function enableEditRemark(){
        document.getElementById("remark").readOnly=false;
        document.getElementById("editremark").disabled=false;
    }
</script>

@endsection
