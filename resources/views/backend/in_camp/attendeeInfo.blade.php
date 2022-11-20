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
    <h5>{{$camp->fullName}}>>個人詳細資料>>{{$applicant->name}}</h5>
    <div class="alert alert-warning">
        <table class="table table-borderless table-hover">
            <tr>
                @if($applicant->avatar)
                <td><img src="data:image/png;base64, {{ base64_encode(\Storage::disk('local')->get($applicant->avatar)) }}" width=80 alt="{{ $applicant->name }}"></td>
                @else
                <td>no photo</td>
                @endif
                <td>
                <b>中文姓名</b>：{{$applicant->name}}<br>
                <b>生日</b>：{{$applicant->birthyear}}/{{$applicant->birthmonth}}/{{$applicant->birthday}}<br>
                <b>英文姓名</b>：{{$applicant->english_name}}<br>
                <b>性別</b>：{{$applicant->gender}}<br>
                </td>
                <td>
                <b>產業別</b>：{{$applicant->industry}}<br>
                <b>公司名稱</b>：{{$applicant->unit}}<br>
                <b>職稱</b>：{{$applicant->title}}<br>
                <b>職務類型</b>：{{$applicant->job_property}}<br>
                </td>
                <td>
                <b>報名序號</b>：{{$applicant->id}}<br>
                <b>所屬組別</b>：{{$applicant->group}}<br>
                <b>關懷員</b>：<br>
                <b>參加形式</b>：{{$applicant->participation_mode}}<br>
                <b>參加意願</b>：{{$applicant->is_attend}}<br>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <table class="table table-borderless table-hover">
        <tr>
            <td>
            <a href="#" class="btn btn-warning" onclick="">聯絡方式</a><br><br>
            <b>手機號碼</b>：{{$applicant->mobile}}<br>
            <b>公司電話</b>：{{$applicant->phoe_work}}<br>
            <b>電子信箱</b>：{{$applicant->email}}<br>
            <b>LineID</b>：{{$applicant->line}}<br>
            <b>代理人</b>：{{$applicant->substitute_name}}<br>
            <b>代理人電話</b>：{{$applicant->substitute_phone}}<br>
            <b>代理人電子信箱</b>：{{$applicant->substitute_email}}<br>
            <b>適合聯絡時段</b>：{{$applicant->contact_time}}<br>
            <b>地址</b>：{{$applicant->introducer_name}}<br>
            </td>
            <td>
            <a href="#" class="btn btn-warning" onclick="">推薦人資訊</a><br><br>
            <b>推薦人</b>：{{$applicant->introducer_name}}<br>
            <b>廣論班別</b>：{{$applicant->introducer_participated}}<br>
            <b>手機號碼</b>：{{$applicant->introducer_phone}}<br>
            <b>電子信箱</b>：{{$applicant->introducer_email}}<br>
            <b>與推薦人關係</b>：{{$applicant->introducer_relationship}}<br>
            <b>特別推薦理由或社會影響力說明</b>：{{$applicant->reasons_recommend}}<br>
            </td>
            <td>
            <a href="#" class="btn btn-warning" onclick="">其他資訊</a><br><br>
            <b>公司員工</b>：{{$applicant->employees}} 人<br>
            <b>所轄員工</b>：{{$applicant->direct_managed_employees}} 人<br>
            <b>資本額</b>：{{$applicant->capital}}<br>
            <b>公司/組織形式</b>：{{$applicant->org_type}}<br>
            <b>公司成立幾年</b>：{{$applicant->years_operation}}<br>
            <b>同意個資使用</b>：{{$applicant->profile_agree}}<br>
            <b>同意肖像權使用</b>：{{$applicant->portrait_agree}}<br>
            </td>
        </tr>
    </table>
    <br>
    <table class="table table-borderless table-hover">
        <tr>
            <td>
            <a href="#" class="btn btn-info" onclick="">關心議題</a><br><br>
            {{ $applicant->favored_event }}<br>
            </td>
            <td>
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
            @if(isset($contactlog))
            {{ $contactlog->updated_at }} 由 {{ $contactlog->takenby_name }} 記錄：<br>
            {{ $contactlog->notes }}<br><br>
            @else
            無關懷記錄
            @endif
            <a href="{{ route('showContactLogs', [$camp->id, $applicant->applicant_id]) }}" class="btn btn-primary float-right">更多關懷記錄</a><br><br>

            </td>
        </tr>
    </table>
@endif
@endsection