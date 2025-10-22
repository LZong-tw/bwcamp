@extends('camps.nycamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
    @if(isset($isRepeat))<div class="alert alert-warning">{{ $isRepeat }}</div>@endif
    <div class="card">
        <div class="card-header">
            Your registration is complete. 報名成功
        </div>
        <div class="card-body">
            <p class="card-text">
                Thank you for registering for {{ $applicant->batch->camp->fullName }}.<br>
                Please keep your <span class="text-danger font-weight-bold">《 registration number: {{ $applicant->id }} 》</span>for future reference.<br>
                Once your registration is accepted, you will receive a confirmation email containing important camp details and payment instructions within 7 days. 
                Your registration will be considered complete only after your payment has been received.<br>
                <br>
                If you have any question, feel free to contact<br>
                Jasmine Hu<br>
                Email: chunhu@blisswisdom.org<br>
                Phone: (902)808-0069<br>
                Online Service: https://lin.ee/8iOmovI<br>
            </p>
            <br>
            <p class="card-text">
                感謝您報名{{ $camp_data->fullName }}。您所填寫的個人資料，僅用於此次大專營的報名及活動聯絡之用。The Oneness Truth Foundation將依個人資料保護法及相關法令之規定善盡保密責任。<br>
                請記下您的<span class="text-danger font-weight-bold">《 報名序號：{{ $applicant->id }} 》</span>作為日後查詢使用。<br>
                經審核報名資格後，將於七日內email您錄取通知，含營隊注意事項及繳費資訊。收到您的繳費後才算正式報名完成！<br>
                <br>
                如果您有任何問題，請聯絡<br>
                胡純<br>
                Email: chunhu@blisswisdom.org<br>
                洽詢電話(北美地區)：(902)808-0069<br>
                線上客服：https://lin.ee/8iOmovI<br>
            </p>
            <br>
            <form action="{{ route("queryview", $applicant->batch_id) }}" method="post" class="d-inline">
                @csrf
                <input type="hidden" name="sn" value="{{ $applicant->id }}">
                <button class="btn btn-primary">檢視報名資料</button>
            </form>
            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
        </div>
    </div>
@stop
