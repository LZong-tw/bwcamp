@extends('camps.ycamp.layout')
@section('content')
    @if(Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get("error") }}
        </div>
    @endif
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
<!--
    @if($applicant->is_admitted)
        <div class="card">
            <div class="card-header">
                <h2>研習證明下載</h2>
            </div>
            <div class="card-body">
                <a href="https://bwcamp.bwfoce.org/downloads/ycamp2021/{{ $applicant->group }}{{ $applicant->number }}{{ $applicant->applicant_id }}.pdf" target="_blank" rel="noopener noreferrer" class="btn btn-success">下載</a>
            </div>
        </div>
        <br>
    @endif
-->
    <div class="card">
        <div class="card-header">
            錄取查詢
        </div>
        <div class="card-body">
            @if($applicant->is_admitted)
                <p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
                <p class="card-text indent">非常恭喜您錄取「{{ $camp_data->fullName }}」！</p>
                <p class="card-text indent">
                錄取編號：{{ $applicant->group }}{{ $applicant->number }}<br>
                營隊場次：{{ $applicant->batch->name }} <br>
                營隊日期：{{ $applicant->batch->batch_start }} ~ {{ $applicant->batch->batch_end }} <br>
                </p>
                <p class="card-text indent">期待與您共享這場心靈饗宴！請詳閱以下相關訊息，祝福您營隊收穫滿滿。</p>

                <p class="card-text">
                    <h5>營隊相關訊息</h5>
                        <div class="ml-0 mb-2">1.經錄取後，敬請全程參與本活動。全程參與者，發給研習證明文件。</div>
                        <div class="ml-0 mb-2">2.有任何問題，請Email至<a href="mailto:youth@blisswisdom.org">youth@blisswisdom.org</a>，或於<a href="https://www.facebook.com/bwyouth" target="_blank" rel="noopener noreferrer">福智青年粉專</a>留言</div>
                    
<!--
                    <h4>確認參加</h4>
                    <div class="ml-4 mb-2">請回覆確認參加。</div>
                    @if(!isset($applicant->is_attend))
                        <div class="ml-4 mb-2 text-primary">狀態：未回覆參加。</div>
                    @elseif($applicant->is_attend)
                        <div class="ml-4 mb-2 text-success">狀態：已確認參加。</div>
                    @else
                        <div class="ml-4 mb-2 text-danger">狀態：不參加。</div>
                    @endif
                    <form class="ml-4 mb-2" action="{{ route('toggleAttend', $batch_id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $applicant->applicant_id ?? $applicant->id }}">
                        @if(!isset($applicant->is_attend))
                            <input class="btn btn-success" type="submit" value="確認參加">
                            <input class="btn btn-danger" type="submit" value="不參加" id="cancel" name="confirmation_no">
                        @elseif($applicant->is_attend)
                            <input class="btn btn-danger" type="submit" value="取消參加" id="cancel">
                        @else
                            <input class="btn btn-success" type="submit" value="確認參加">
                        @endif
                    </form>
-->
                </p>
                <p class="card-text text-right">財團法人福智文教基金會 敬啟</p>
                <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
            @elseif($applicant->created_at->gte(\Carbon\Carbon::parse('2022-05-31 00:00:00')))
                <p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
                <p class="card-text indent">感謝您報名「{{ $camp_data->fullName }}」，錄取作業正在進行中，請稍後再進行錄取查詢。感謝您的耐心等待！</p>
                <p class="card-text text-right">財團法人福智文教基金會 敬啟</p>
                <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
            @else
                <p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
                <p class="card-text indent">感謝您報名「{{ $camp_data->fullName }}」，錄取作業正在進行中，請稍後再進行錄取查詢。感謝您的耐心等待！</p>
                <p class="card-text text-right">財團法人福智文教基金會 敬啟</p>
                <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
                </p>
<!--
                <p class="card-text">親愛的 {{ $applicant->name }} 同學您好</p>
                <p class="card-text indent">感謝您報名「{{ $camp_data->fullName }}」，依報名資格規範，很抱歉於此活動未能錄取您。</p>
                <p class="card-text indent">福智文教基金會尚有各項精彩活動與課程，竭誠歡迎您的參與！</p>
                <p class="card-text indent"><a href="http://bwfoce.org/web" target="_blank" rel="noopener noreferrer">http://bwfoce.org/web</a></p>
                <p class="card-text indent">祝福您身心健康，吉祥如意！</p>
                <p class="card-text text-right">財團法人福智文教基金會 敬啟</p>
                <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
                </p>
-->
            @endif
            <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
            <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
        </div>
    </div>
    <script>
        let cancel = document.getElementById('cancel');
        cancel.addEventListener('click', function(event) {
            @if(!isset($applicant->is_attend))
                if(confirm('確認不參加？')){
                    return true;
                }
            @else
                if(confirm('確認取消？')){
                    return true;
                }
            @endif
            event.preventDefault();
            return false;
        });
    </script>
@stop