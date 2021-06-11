@extends('camps.ycamp.layout')
@section('content')
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
    <div class="card">
        <div class="card-header">
            錄取查詢
        </div>
        <div class="card-body">
            @if($applicant->is_admitted)
                <p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
                <p class="card-text indent">非常恭喜您錄取「{{ $camp_data->fullName }}」，您的錄取編號為：{{ $applicant->group }}{{ $applicant->number }}。</p>
                <p class="card-text indent">期待與你共享這場心靈饗宴！請詳閱以下相關訊息，祝福您活動收穫滿滿。</p>
                <p class="card-text">
                    <h4>營隊資訊</h4>
                    <div class="ml-4 mb-2">營隊期間：2021/8/14~15 (六、日) 9:30~17:30  共 2天</div>
                    <h4>確認參加</h4>
                    <div class="ml-4 mb-2">請回覆確認參加。（加按鈕？）</div>
                </p>
                <p class="card-text indent">錄取學員敬請全程參與本活動。全程參與者，發給研習證明文件。</p>
                <p class="card-text indent">有任何問題，請Email至<a href="mailto:youth@blisswisdom.org">youth@blisswisdom.org</a>，或於<a href="https://www.facebook.com/bwyouth" target="_blank" rel="noopener noreferrer">福智青年粉專</a>留言</p>
                <p class="card-text text-right">財團法人福智文教基金會 敬啟</p>
                <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
            @else
                <p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
                        <p class="card-text indent">感謝您報名「{{ $camp_data->fullName }}」，依報名資格規範，很抱歉於此活動未能錄取您。</p>
                        <p class="card-text indent">福智文教基金會尚有各項精彩活動與課程，竭誠歡迎您的參與！</p>
                        <p class="card-text indent"><a href="http://bwfoce.org/web" target="_blank" rel="noopener noreferrer">http://bwfoce.org/web</a></p>
                        <p class="card-text indent">祝福您身心健康，吉祥如意！</p>
                        <p class="card-text text-right">財團法人福智文教基金會 敬啟</p>
                        <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
                </p>
            @endif
            <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
        </div>
    </div>
@stop