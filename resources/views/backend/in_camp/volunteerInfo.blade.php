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
    <h4>{{ $camp->fullName }}>>義工詳細資料>>{{ $applicant->name }}</h4>

    <!-- 修改學員資料,使用報名網頁 -->
    @if(\App\Models\User::find(auth()->user()->id)->isAbleTo("\App\Models\Volunteer.update"))
        <form target="_blank" action="{{ route('queryupdate', $batch->id) }}" method="post">
            @csrf
            <input type="hidden" name="sn" value="{{ $applicant->applicant_id }}">
            <input type="hidden" name="name" value="{{ $applicant->name }}">
            <!-- input type="hidden" name="isBackend" value="目前為後台檢視狀態。"-->
            <input type="hidden" name="isModify" value="1">
            <button class="btn btn-primary float-right">修改義工(報名)資料</button>
            <br>
        </form>
        <br>
    @endif

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
                <b>生日</b>：{{$applicant->birthyear}}/{{$applicant->birthmonth}}/{{$applicant->birthday}}<br>
                <b>性別</b>：{{$applicant->gender}}<br>
                <b>廣論班別</b>：{{$applicant->lrclass}}<br>
            </div>
            <div class="col-md-3">
                <b>產業別</b>：{{$applicant->industry}}<br>
                <b>公司名稱</b>：{{$applicant->unit}}<br>
                <b>職稱</b>：{{$applicant->title}}<br>
                <b>職務類型</b>：{{$applicant->job_property}}<br>
            </div>
            @php
                $roles = $applicant->user?->roles()->where('camp_id', $applicant->vcamp->mainCamp->id)->get();
                if (!$roles) {
                    $roles = [];
                }
            @endphp
            <div class="col-md-3">
                <b>義工任務</b>：
                @forelse($roles as $role)
                    {{ $role->batch?->name }}{{ $role->section }} - {{ $role->position }}@if($currentUser->canAccessResource(new \App\Models\OrgUser, 'delete', $applicant->vcamp->mainCamp))（<a href="{{ route('deleteUserRole', [$camp->id, "user_id" => $applicant->user->id, "role_id" => $role->id, "applicant_id" => $applicant->id]) }}" class="text-danger">刪除</a>）@endif @if(!$loop->last)、@endif
                @empty
                    此義工尚未分配任何職務
                @endforelse
                <br>
                <b>交通方式</b>：{{$applicant->group_legacy}}<br>
                <b>專長</b>：
                @if(isset($applicant->expertise_split))
                    @foreach($applicant->expertise_split as $expertise)
                        {{$expertise}}
                        @if(!$loop->last)、@endif
                    @endforeach
                @endif<br>
                <b>語言</b>：
                @if(isset($applicant->language_split))
                    @foreach($applicant->language_split as $language)
                        {{$language}}
                        @if(!$loop->last)、@endif
                    @endforeach
                @endif<br>
            </div>
        </div>
    </div>

{{--    <div class="container alert alert-primary">--}}
{{--        @php--}}
{{--            $roles = $applicant->user?->roles()->where('camp_id', $applicant->vcamp->mainCamp->id)->get();--}}
{{--            if (!$roles) {--}}
{{--                $roles = [];--}}
{{--            }--}}
{{--        @endphp--}}
{{--        @forelse($roles as $role)--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-8">--}}
{{--                    {{ $role->batch?->name }}{{ $role->section }} - {{ $role->position }}--}}
{{--                </div>--}}
{{--                <div class="col-md-4">--}}
{{--                    <a href="{{ route('deleteUserRole', [$camp->id, "user_id" => $applicant->user->id, "role_id" => $role->id, "applicant_id" => $applicant->id]) }}" class="btn btn-danger">刪除</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @empty--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-12">--}}
{{--                    此義工尚未分配任何職務--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endforelse--}}
{{--    </div>--}}
{{--    <br>--}}

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <span class="btn btn-warning">聯絡方式</span><br><br>
                <b>手機號碼</b>：<a href="tel:{{$applicant->mobile}}">{{$applicant->mobile}}</a><br>
                <b>公司電話</b>：<a href="tel:{{$applicant->phone_work}}">{{$applicant->phone_work}}</a><br>
                <b>電子信箱</b>：<a href="mailto:{{$applicant->email}}">{{$applicant->email}}</a><br>
                <b>LineID</b>：<a href="https://line.me/ti/p/~{{$applicant->line}}">{{$applicant->line}}</a><br>
                <b>邀請人</b>：{{$applicant->introducer_name}}<br>
            </div>
            <div class="col-md-4">
                <span class="btn btn-warning">工作資訊</span><br><br>
                <b>公司員工</b>：{{$applicant->employees}} 人<br>
                <b>所轄員工</b>：{{$applicant->direct_managed_employees}} 人<br>
                <b>資本額</b>：{{$applicant->capital}} {{$applicant->capital_unit}}<br>
                <b>公司/組織形式</b>：{{$applicant->org_type}}<br>
                <b>公司成立幾年</b>：{{$applicant->years_operation}}<br>
            </div>
            <div class="col-md-4">
                <span class="btn btn-warning">其他資訊</span><br><br>
                <b>報名第一志願：</b>{{$applicant->group_priority1}}<br>
                <b>報名第二志願：</b>{{$applicant->group_priority2}}<br>
                <b>報名第三志願：</b>{{$applicant->group_priority3}}<br>
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
                <span class="btn btn-info">班級護持記錄</span><br><br>
                {{ $applicant->cadre_experiences }}<br>
                <br>
                <span class="btn btn-info">義工護持記錄</span><br><br>
                {{ $applicant->volunteer_experiences }}<br>
                <br>
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
            <div class="col-md-8">
                <span class="btn btn-info">關懷記錄</span><br>
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
                <b>最新一筆關懷記錄</b><br>
                @if(isset($contactlog))
                {{ $contactlog->updated_at }} 由 {{ $contactlog->takenby_name }} 記錄：<br>
                {{ $contactlog->notes }}<br><br>
                @else
                <b>無關懷記錄</b>
                @endif
                <a href="{{ route('showContactLogs', [$camp->id, $applicant->applicant_id]) }}" class="btn btn-secondary float-right">更多關懷記錄</a><br><br>
            </div>
        </div>
    </div>
@endif
<script>
        function enableEditRemark(){
            document.getElementById("remark").readOnly=false;
            document.getElementById("editremark").disabled=false;
        }
</script>

@endsection
