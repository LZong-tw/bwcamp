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
@if(isset($applicant))
    <h4>學員關懷系統</h4>
    <h5>{{ $camp->fullName }}>>個人詳細資料>>{{ $applicant->name }}</h5>

    <!-- 修改學員資料,使用報名網頁 -->
    <form action="{{ route('queryupdate', $batch->id) }}" method="post">
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
                <b>生日</b>：{{$applicant->birthyear}}/{{$applicant->birthmonth}}/{{$applicant->birthday}}<br>
                <b>宗教信仰</b>：{{$applicant->belief}}<br>
                <b>最高學歷</b>：{{$applicant->education}}<br>
            </div>
            <div class="col-md-3">
                <b>服務單位</b>：{{$applicant->unit}}<br>
                <b>服務單位所在地</b>：{{$applicant->unit_location}}<br>
                <b>職稱</b>：{{$applicant->title}}<br>
                <b>工作屬性</b>：{{$applicant->job_property}}<br>
                <b>經歷</b>：{{$applicant->experience}}<br>
            </div>
            <div class="col-md-3">
                <b>報名序號</b>：{{$applicant->applicant_id}}<br>
                <b>所屬組別</b>：{{$applicant->group_legacy}}<br>
                <b>關懷員</b>：<br>
                <b>參加意願</b>：{{$applicant->is_attend}}<br>
            </div>
        </div>
    </div>
    <br>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <a href="#" class="btn btn-warning" onclick="">聯絡方式</a><br><br>
                <b>行動電話</b>：{{$applicant->mobile}}<br>
                <b>公司電話</b>：{{$applicant->phone_work}}<br>
                <b>住家電話</b>：{{$applicant->phone_work}}<br>
                <b>LineID</b>：{{$applicant->line}}<br>
                <b>微信ID</b>：{{$applicant->line}}<br>
                <b>電子郵件</b>：{{$applicant->email}}<br>
                <b>通訊地址</b>：{{$applicant->address}}<br>
            </div>
            <div class="col-md-4">
                <a href="#" class="btn btn-warning" onclick="">關係人資訊</a><br><br>
                <b>緊急聯絡人</b>：{{$applicant->emergency_name}}<br>
                <b>關係</b>：{{$applicant->emergency_relationship}}<br>
                <b>聯絡電話</b>：{{$applicant->emergency_mobile}}<br>
                -----<br>
                <b>介紹人</b>：{{$applicant->introducer_name}}<br>
                <b>關係</b>：{{$applicant->introducer_relationship}}<br>
                <b>聯絡電話</b>：{{$applicant->introducer_phone}}<br>
                <b>福智班別</b>：{{$applicant->introducer_participated}}<br>
            </div>
            <div class="col-md-4">
                <a href="#" class="btn btn-warning" onclick="">其它資訊</a><br><br>
                <b>公司員工人數</b>：{{$applicant->employees}} 人<br>
                <b>直屬管轄人數</b>：{{$applicant->direct_managed_employees}} 人<br>
                <b>產業別</b>：{{$applicant->industry}}<br>
                <b>同意個資使用</b>：@if($applicant->profile_agree) 是 @else 否 
                @endif<br>
                <b>同意肖像權使用</b>：@if($applicant->portrait_agree) 是 @else 否 @endif<br>
                <b>方便參加課程時段</b>：
                @if(isset($applicant->after_camp_available_day_split))
                @foreach($applicant->after_camp_available_day_split as $available_day){{$available_day}}、@endforeach
                @endif<br>
            </div>
        </div>
    </div>
    <br>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <a href="#" class="btn btn-info" onclick="">有興趣的活動</a><br><br>
                @if(isset($applicant->favored_event_split))
                @foreach($applicant->favored_event_split as $event)
                    {{ $event }}<br>
                @endforeach
                @endif
            </div>
            <div class="col-md-8">
                <a href="#" class="btn btn-info" onclick="">關懷記錄</a><br>
                <form action="{{ route('addContactLog', $camp->id) }}" method="POST">
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
                <a href="{{ route('showContactLogs', [$camp->id, $applicant->id]) }}" class="btn btn-secondary float-right">更多關懷記錄</a><br><br>
            </div>
        </div>
    </div>
@endif
@endsection