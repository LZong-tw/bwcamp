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
                <b>生日</b>：{{$applicant->birthyear}}/{{$applicant->birthmonth}}/{{$applicant->birthday}}<br>
                <b>英文姓名</b>：{{$applicant->english_name}}<br>
                <b>性別</b>：{{$applicant->gender}}<br>
                <b>是否已加入廣論班</b>：@if($applicant->is_lrclass) 是 @else 否 @endif<br>
                <b>廣論班別</b>：{{$applicant->lrclass}}<br>
            </div>
            <div class="col-md-3">
                <b>產業別</b>：{{$applicant->industry}}<br>
                <b>公司名稱</b>：{{$applicant->unit}}<br>
                <b>職稱</b>：{{$applicant->title}}<br>
                <b>職務類型</b>：{{$applicant->job_property}}<br>
            </div>
            <div class="col-md-3">
                <b>報名序號</b>：{{$applicant->applicant_id}}<br>
                <b>所屬組別</b>：@if($applicant->groupRelation)
                    {{ $applicant->groupRelation->alias }}
                    @if($currentUser->canAccessResource(new \App\Models\ApplicantsGroup, 'delete', $applicant->camp))（<a href="{{ route('deleteApplicantGroupAndNumber', [$camp->id, "applicant_id" => $applicant->id, "group_id" => $applicant->groupRelation->id]) }}" class="text-danger">刪除</a>）@endif
                @else
                    此學員尚未分入任何組別
                @endif<br>
                <b>關懷員</b>：@forelse($applicant->carers as $carer)
                    {{ $carer->name }}@if($currentUser->canAccessResource(new \App\Models\CarerApplicantXref, 'delete', $applicant->camp, target: $applicant))（<a href="{{ route('deleteApplicantCarer', [$camp->id, "applicant_id" => $applicant->id, "carer_id" => $carer->id]) }}" class="text-danger">刪除</a>）@endif
                    @if(!$loop->last) {{ "、" }} @endif
                @empty
                    {{ '-' }}
                @endforelse<br>
                <b>參加形式</b>：{{$applicant->participation_mode}}<br>
            </div>
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
        {{--
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
        --}}
        <div class="row d-flex justify-content-end">
            <div class="mr-4 mb-2 font-weight-bold">住宿選項</div>
            @if (!isset($applicant->lodging))
                狀態：<div class="mr-4 text-primary">尚未登記。</div>
            @else
                狀態：<div class="mr-4 text-success">{{ $applicant->lodging->room_type }}</div>
            @endif
        </div>
        {{--
        @if($applicant->deleted_at)
            <div class="text-danger">
                本學員已取消報名。
            </div>
        @else
            <div class="row d-flex justify-content-end">
                <form class="mr-4 mb-2" action="{{ route('modifyAccounting', $camp->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                    <input type="hidden" name="cash" value="0">
                    <input type="hidden" name="is_add" value="add">
                    <input type="hidden" name="nights" value="1">
                    <input type="hidden" name="page" value="attendeeInfo">
                    @foreach($lodgings as $room_type => $value)
                    <label><input type="radio" name="room_type" id="" value="{{ $room_type }}">{{ $room_type }}</label>
                    @endforeach
                    <input class="btn btn-success" type="submit" value="修改住宿選項">
                </form>
            </div>
        @endif
        --}}
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
    <form method="POST">
        @csrf
        Name: <input type="text" name="name">

        <x-media-library-attachment multiple name="images"/>

        <button type="submit">Submit</button>
    </form>
    <div class="container alert alert-primary">
        <div class="row">
            <div class="col-md-12">
                <span class="text-danger font-weight-bold">注意：應一次上傳兩個檔案</span>
                <form method="POST" class="" name="filesForm" enctype="multipart/form-data">
                    @csrf
                    @if($applicant->files)
                        @foreach(json_decode($applicant->files) as $file)
                            <div class="row">
                                <div class="col-md-8">
                                    <img src="{{ url("/backend/" . $applicant->camp->id . "/image/" . $file) }}" width=80>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <input type="file" name="file1" id="">
                    <input type="file" name="file2" id="">
                    <input type="button" class="btn btn-success" value="上傳" onclick="document.filesForm.submit()">
                    <button type="reset" class="btn btn-danger">重設</button>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <span class="btn btn-warning">聯絡方式</span><br><br>
                <b>手機號碼</b>：<a href="tel:{{$applicant->mobile}}">{{$applicant->mobile}}</a><br>
                <b>公司電話</b>：<a href="tel:{{$applicant->phone_work}}">{{$applicant->phone_work}}</a><br>
                <b>電子信箱</b>：<a href="mailto:{{$applicant->email}}">{{$applicant->email}}</a><br>
                <b>LineID</b>：<a href="https://line.me/ti/p/~{{$applicant->line}}">{{$applicant->line}}</a><br>
                <b>代理人</b>：{{$applicant->substitute_name}}<br>
                <b>代理人電話</b>：<a href="tel:{{$applicant->substitute_phone}}">{{$applicant->substitute_phone}}</a><br>
                <b>代理人電子信箱</b>：<a href="mailto:{{$applicant->substitute_email}}">{{$applicant->substitute_email}}</a><br>
                <b>適合聯絡時段</b>：<br>
                @if(isset($applicant->contact_time_split))
                @foreach($applicant->contact_time_split as $contact_time)
                    {{ $contact_time }}<br>
                @endforeach
                @endif
                <br>
                <b>地址</b>：{{$applicant->address}}<br>
            </div>
            <div class="col-md-4">
                <span class="btn btn-warning">推薦人資訊</span><br><br>
                <b>推薦人</b>：{{$applicant->introducer_name}}<br>
                <b>廣論班別</b>：{{$applicant->introducer_participated}}<br>
                <b>手機號碼</b>：<a href="tel:{{$applicant->introducer_phone}}">{{$applicant->introducer_phone}}</a><br>
                <b>電子信箱</b>：<a href="mailto:{{$applicant->introducer_email}}">{{$applicant->introducer_email}}</a><br>
                <b>與推薦人關係</b>：{{$applicant->introducer_relationship}}<br>
                <b>特別推薦理由或社會影響力說明</b>：<br>{{$applicant->reasons_recommend}}<br>
            </div>
            <div class="col-md-4">
                <span class="btn btn-warning">其他資訊</span><br><br>
                <b>公司員工</b>：{{$applicant->employees}} 人<br>
                <b>所轄員工</b>：{{$applicant->direct_managed_employees}} 人<br>
                <b>資本額</b>：{{$applicant->capital}} {{$applicant->capital_unit}}<br>
                <b>公司/組織形式</b>：{{$applicant->org_type}}<br>
                <b>公司成立幾年</b>：{{$applicant->years_operation}}<br>
                <b>同意個資使用</b>：@if($applicant->profile_agree) 是 @else 否
                @endif<br>
                <b>同意肖像權使用</b>：@if($applicant->portrait_agree) 是 @else 否 @endif<br>
                <b>繳費虛擬帳號</b>：上海銀行(011){{$applicant->bank_second_barcode ?? ""}}<br>
                <b>應繳金額</b>：{{$applicant->lodging?->fare ?? 0}}<br>
                <b>已繳金額</b>：{{$applicant->lodging?->deposit ?? 0}}<br>
            </div>
        </div>
    </div>
    <br>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
            	<span class="btn btn-info">關心議題</span><br><br>
                @if(isset($applicant->favored_events))
                @foreach($applicant->favored_events as $event)
                    {{ $event }}<br>
                @endforeach
                @endif
                <span class="btn btn-info">備註</span><br>
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
                <hr>
                <h5>報到條碼</h5>
                <img src="data:image/png;base64,{{ \DNS2D::getBarcodePNG('{"applicant_id":' . $applicant->id .
                '}', 'QRCODE') }}" alt="barcode" height="200px" class="mb-3"/>
                <br>
            </div>
            @if($currentUser->canAccessResource(new App\Models\ContactLog(), 'read', $campFullData, target: $applicant))
                <div class="col-md-8">
                    <span class="btn btn-info">關懷記錄</span><br>
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
                <div class="row mt-3">
                    <a href="{{ $stat->google_sheet_url }}" target="_blank" class="btn btn-primary mb-3">電訪調查表連結</a>
                    <br>
                    <iframe src="{{ $stat->google_sheet_url }}">Your browser isn't compatible</iframe>
                </div>
            @endforeach
        </div>
    @endif
    @if($dynamic_stat_urls)
        <div class="container">
            <div class="row mt-3">
                <h4>請選擇電訪表：
                @foreach($dynamic_stat_urls as $purpose => $url)
                <label class=radio-inline>
                <input type=radio name='sel_url' onClick='showTelCallForm("{{ $purpose }}");'> 顯示{{ $purpose }}  
                </label>
                @endforeach
                </h4>
            </div>
            @foreach($dynamic_stat_urls as $purpose => $url)
                <div class="row mt-3 divTelCallForm" style="display:none" id='{{ $purpose }}'>
                    <iframe src="{{ $url }}">Your browser isn't compatible</iframe>
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
    function showTelCallForm(purpose){
        console.log(purpose);      
        urls = document.getElementsByClassName('divTelCallForm');
        for (var i = 0; i < urls.length; i++){
            console.log(urls[i].id);
            if (urls[i].id == purpose) {
                urls[i].style.display = '';
            } else {
                urls[i].style.display = 'none';
            }
        }
    }
</script>
@endsection
