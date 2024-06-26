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
@if(isset($applicant))
    <h4>{{ $camp->fullName }}>>個人詳細資料>>{{ $applicant->name }}</h4>

    <!-- 修改學員資料,使用報名網頁 -->
    <form target="_blank" action="{{ route('queryupdate', $batch->id) }}" method="post">
        @csrf
        <input type="hidden" name="sn" value="{{ $applicant->applicant_id }}">
        <input type="hidden" name="name" value="{{ $applicant->name }}">
        <!-- input type="hidden" name="isBackend" value="目前為後台檢視狀態。"-->
        <input type="hidden" name="isModify" value="1">
        <button class="btn btn-primary float-right">修改學員(報名)資料</button>
        <br>
    </form>
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
                <b>中文姓名</b>：{{$applicant->name}}<br>
                <b>性別</b>：{{$applicant->gender}}<br>
                <b>國籍</b>：{{$applicant->nationality}}<br>
                <b>生日</b>：{{$applicant->birthyear}}/{{$applicant->birthmonth}}/{{$applicant->birthday}}<br>
            </div>
            <div class="col-md-3">
                <b>課程學制</b>：{{$applicant->system}}<br>
                <b>部別</b>：{{$applicant->day_night}}<br>
                <b>就讀學校</b>：{{$applicant->school}}<br>
                <b>系所年級</b>：{{$applicant->department}}{{$applicant->grade}}<br>
            </div>
            <div class="col-md-3">
                <b>報名序號</b>：{{$applicant->applicant_id}}<br>
                <b>所屬組別</b>：@if($applicant->groupRelation)
                    {{ $applicant->groupRelation->alias }}
                    @if($currentUser->canAccessResource(new \App\Models\ApplicantsGroup, 'delete', $applicant->camp))（<a href="{{ route('deleteApplicantGroupAndNumber', [$camp->id, "applicant_id" => $applicant->id, "group_id" => $applicant->groupRelation->id]) }}" class="text-danger">刪除</a>）@endif
                @else
                    此學員尚未分入任何組別
                @endif<br>
                <b>帶組老師</b>：@forelse($applicant->carers as $carer)
                    {{ $carer->name }}@if($currentUser->canAccessResource(new \App\Models\CarerApplicantXref, 'delete', $applicant->camp, target: $applicant))（<a href="{{ route('deleteApplicantCarer', [$camp->id, "applicant_id" => $applicant->id, "carer_id" => $carer->id]) }}" class="text-danger">刪除</a>）@endif
                    @if(!$loop->last) {{ "、" }} @endif
                @empty
                    {{ '-' }}
                @endforelse<br>
            </div>
        </div>
        <div class="row d-flex justify-content-end">
            <div class="mr-4 mb-2 font-weight-bold">是否錄取</div>
                @if(!isset($applicant->is_admitted))
                    狀態：<div class="mr-4 text-primary">尚未錄取。</div>
                @elseif($applicant->is_attend == 1)
                    狀態：<div class="mr-4 text-success">錄取。</div>
                @else
                    狀態：<div class="mr-4 text-danger">不錄取。</div>
                @endif
            </div>
        <div class="row d-flex justify-content-end">        
            <div class="mr-4 mb-2 font-weight-bold">參加意願</div>
                @if(!isset($applicant->is_attend))
                    狀態：<div class="mr-4 text-primary">尚未聯絡。</div>
                @elseif($applicant->is_attend == 1)
                    狀態：<div class="mr-4 text-success">參加。</div>
                @elseif($applicant->is_attend == 0)
                    狀態：<div class="mr-4 text-danger">不參加。</div>
                @elseif($applicant->is_attend == 2)
                    狀態：<div class="mr-4 text-secondary">尚未決定。</div>
                @elseif($applicant->is_attend == 3)
                    狀態：<div class="mr-4 text-secondary">聯絡不上。</div>
                @elseif($applicant->is_attend == 4)
                    狀態：<div class="mr-4 text-secondary">無法全程。</div>
                @endif
        </div>
        @if ($applicant->deleted_at)
            <div class="text-danger">
                本學員已取消報名。
            </div>            
        @else
            <div class="row d-flex justify-content-end">
                <form class="mr-4 mb-2" action="{{ route('toggleAttendBackend', $applicant->batch->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                    <label><input type="radio" name="is_attend" id="" value="0">不參加</label>
                    <label><input type="radio" name="is_attend" id="" value="1">參加</label>
                    <label><input type="radio" name="is_attend" id="" value="2">尚未決定</label>
                    <label><input type="radio" name="is_attend" id="" value="3">聯絡不上</label>
                    <label><input type="radio" name="is_attend" id="" value="4">無法全程</label>
                    <input class="btn btn-success" type="submit" value="修改參加狀態">
                </form>
            </div>
        @endif
    </div>

{{--    <div class="container alert alert-primary">--}}
{{--        @if($applicant->groupRelation)--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-8">--}}
{{--                    {{ $applicant->groupRelation->alias }}--}}
{{--                </div>--}}
{{--                <div class="col-md-4">--}}
{{--                    <a href="{{ route('deleteApplicantGroupAndNumber', [$camp->id, "applicant_id" => $applicant->id, "group_id" => $applicant->groupRelation->id]) }}" class="btn btn-danger">刪除</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @else--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-12">--}}
{{--                    此學員尚未分入任何組別--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}
{{--    </div>--}}
{{--    <br>--}}

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <span class="btn btn-warning">聯絡方式</span><br><br>
                <b>行動電話</b>：<a href="tel:{{$applicant->mobile}}">{{$applicant->mobile}}</a><br>
                <b>家中電話</b>：<a href="tel:{{$applicant->phone_home}}">{{$applicant->phone_work}}</a><br>
                <b>電子信箱</b>：<a href="mailto:{{$applicant->email}}">{{$applicant->email}}</a><br>
                <b>LineID</b>：<a href="https://line.me/ti/p/~{{$applicant->line}}">{{$applicant->line}}</a><br>
                <b>地址</b>：{{$applicant->address}}<br>
            </div>
            <div class="col-md-4">
                <span class="btn btn-warning">關係人資訊</span><br><br>
                <b>父親姓名</b>：{{$applicant->father_name}}<br>
                <b>廣論班別</b>：{{$applicant->father_lamrim}}<br>
                <b>聯絡電話</b>：<a href="tel:{{$applicant->father_phone}}">{{$applicant->father_phone}}</a><br>
                --<br>              
                <b>母親姓名</b>：{{$applicant->mother_name}}<br>
                <b>廣論班別</b>：{{$applicant->mother_lamrim}}<br>
                <b>聯絡電話</b>：<a href="tel:{{$applicant->mother_phone}}">{{$applicant->mother_phone}}</a><br>
                --<br>              
                <b>介紹人</b>：{{$applicant->introducer_name}}<br>
                <b>關係</b>：{{$applicant->introducer_relationship}}<br>
                <b>福智活動</b>：{{$applicant->introducer_participated}}<br>
                <b>聯絡電話</b>：<a href="tel:{{$applicant->introducer_phone}}">{{$applicant->introducer_phone}}</a><br>
                --<br>              
                <b>代填人</b>：{{$applicant->agent_name}}<br>
                <b>聯絡電話</b>：<a href="tel:{{$applicant->agent_phone}}">{{$applicant->agent_phone}}</a><br>
                --<br>              
                <b>緊急聯絡人</b>：{{$applicant->emergency_name}}<br>
                <b>關係</b>：{{$applicant->emergency_relationship}}<br>
                <b>聯絡電話</b>：<a href="tel:{{$applicant->emergency_phone}}">{{$applicant->emergency_mobile}}</a><br>
            </div>
            <div class="col-md-4">
                <span class="btn btn-warning">其他資訊</span><br><br>
                <b>福智活動</b>：{{$applicant->blisswisdom_type}}<br>
                <b>福智活動(其它)</b>：{{$applicant->blisswisdom_type_other}}<br>
                <b>如何得知</b>：{{$applicant->way}}<br>                --<br>
                <b>興趣</b>：{{$applicant->habbit}}<br>
                <b>社團活動</b>：{{$applicant->club}}<br>
                <b>目標</b>：{{$applicant->goal}}<br>
                <b>期望</b>：{{$applicant->expectation}}<br>
                --<br>
                <b>同意個資使用</b>：@if($applicant->profile_agree) 是 @else 否
                @endif<br>
                <b>同意肖像權使用</b>：@if($applicant->portrait_agree) 是 @else 否 @endif<br>
            </div>
        </div>
    </div>
    <br>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <span class="btn btn-info">備註</span><br>
                <form action="{{ route('editRemark', $camp->id) }}" method="POST">
                    @csrf
                    <br>
                    <textarea class=form-control rows=5 required name='remark' id="remark" readonly onclick='enableEditRemark()'>{{ $applicant->remark }}</textarea>
                    <br>
                    <input type="hidden" name="applicant_id" value="{{ $applicant->applicant_id }}">
                    <input type="submit" class="btn btn-primary float-right" name="editremark" id="editremark" value="確認編輯" disabled>
                </form>
            </div>
            @if($currentUser->canAccessResource(new App\Models\ContactLog(), 'read', $campFullData, target: $applicant))
                <div class="col-md-8">
                    <span class="btn btn-info">關懷記錄</span><br>
                    @if($currentUser->canAccessResource(new App\Models\ContactLog(), 'create', $campFullData))
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
<!--
    <div class="container">
    <a href="{{ route('showGSFeedback', [$camp->id, $applicant->applicant_id]) }}">回饋單內容在這裡</a>
    </div>
-->
@endif
<script>
        function enableEditRemark(){
            document.getElementById("remark").readOnly=false;
            document.getElementById("editremark").disabled=false;
        }
</script>

@endsection
