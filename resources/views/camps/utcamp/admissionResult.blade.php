@extends('camps.utcamp.layout')
@section('content')
<style>
    u{
        color: red;
    }
    .indent{
        text-indent: 22px;
    }
</style>
<div class='page-header form-group'>
    <h4>{{ $camp_data->fullName }}</h4>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ $applicant->batch->camp->fullName }} 錄取查詢結果</h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                @if($applicant->is_admitted)
                    {{ $applicant->name }} 您好！<br>
                    　　恭喜您錄取「{{ $applicant->batch->camp->fullName }}」，竭誠歡迎您的到來。<br>
                    我們將於營隊三周前寄發「報到通知單」，提供營隊相關訊息，請記得到電子信箱收信。<br>
                    期待與您共享這場心靈的饗宴，預祝您歡喜學習，收穫滿滿。<br>
                    　　敬祝<br>
                    教安<br>
                    <h5>報名/錄取/營隊資訊</h5>
                    <div class="ml-4 mb-2">
                    您的姓名：{{ $applicant->name }}<br>
                    報名序號：{{ $applicant->applicant_id }}<br>
                    錄取編號：<u>{{ $applicant->group }}{{ $applicant->number }}</u><br>
                    組別：<u>{{ $applicant->group }}</u><br>
                    --<br>
                    營隊日期：{{ $applicant->batch->batch_start }}({{ $applicant->batch_start_Weekday }})～{{ $applicant->batch->batch_end }}({{ $applicant->batch_end_Weekday }})<br>
                    營隊地點：{{ $applicant->batch->location }} {{ $applicant->batch->locationName }}<br>
                    --<br>
                    <b>報名報到諮詢窗口</b>（周一至周五10:00~17:30）<br>
                    　　王淑靜 小姐<br>
                    　　電話：07-9769341#413<br>
                    　　Email：shu-chin.wang@blisswisdom.org<br>
                    </div>
                    <br>
                    <h5>您的參加狀態</h5>
                    <div class="ml-4 mb-2">
                        @if(!isset($applicant->is_attend))
                            <div class="text-primary">狀態：未回覆參加。</div>
                        @elseif($applicant->is_attend)
                            <div class="text-success">狀態：已確認參加。如欲取消請按取消參加。</div>
                        @else
                            <div class="text-danger">狀態：取消參加。如欲恢復參加請按確認參加。</div>
                        @endif
                        <form class="" action="{{ route('toggleAttend', $batch_id) }}" method="POST">
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
                    </div>
                @else
                    敬愛的教育夥伴 {{ $applicant->name }} ，您好！ <br>
                    　　非常感謝您的報名，但是很遺憾，因資格問題，您此次未獲錄取「{{ $applicant->batch->camp->fullName }} 
                    期盼未來仍能在福智文教基金會舉辦之心靈提升、教育活動見到您的身影。<br>
                    　　另外，福智文教基金會在各縣市的分支機構，平日都設有適合各年齡層的多元心靈提升課程，誠摯歡迎您的參與！<br>
                    　　敬祝<br>
                    教安<br>
                @endif
                <br>
                <p class="right">財團法人福智文教基金會　謹此<br>
                {{ \Carbon\Carbon::now()->year }}  年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
                <b>「福智文教基金會」網站：</b>
                <a href="https://bwfoce.org" target="_blank" rel="noopener noreferrer">https://bwfoce.org</a><br>
                <b>「幸福心學堂online」臉書社團：</b>
                <a href="https://www.facebook.com/groups/bwfoce.happiness.new" target="_blank" rel="noopener noreferrer">https://www.facebook.com/groups/bwfoce.happiness.new</a><br>
                <br>
                <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                </p>
            </div>
        </div>
    </div>
</div>
@stop