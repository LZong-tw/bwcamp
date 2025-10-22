@extends('camps.nycamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
    <div class="card">
        <div class="card-header">
            修改成功
        </div>
        <div class="card-body">
            <p class="card-text">
                You have successfully modify your registration for {{ $camp_data->fullName }} .<br>
                Please keep your <span class="text-danger font-weight-bold">《 registration number: {{ $applicant->id }} 》</span>for future reference.<br>
                Once your registration is accepted, you will receive a confirmation email containing important camp details and payment instructions within 7 days. 
                Your registration will be considered complete only after your payment has been received.<br>
            </p>
            <p class="card-text">
                您成功修改報名 {{ $camp_data->fullName }} 的個人資料。<br>
                請記下您的<span class="text-danger font-weight-bold">《 報名序號：{{ $applicant->id }} 》</span>作為日後查詢使用。<br>
                經審核報名資格後，將於七日內email您錄取通知，含營隊注意事項及繳費資訊。收到您的繳費後才算正式報名完成！<br>
            </p>
            <form action="{{ route("queryview", $applicant->batch_id) }}" method="post" class="d-inline">
                @csrf
                <input type="hidden" name="sn" value="{{ $applicant->id }}">
                <button class="btn btn-primary">review form 檢視報名資料</button>
            </form>
            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">home 回營隊首頁</a>
        </div>
    </div>
@stop
