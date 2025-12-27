@extends('camps.mcamp.layout')
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
    <!--研習證明可供下載後，就隱藏錄取查詢-->
    @if($camp_data->certificate_available_date && \Carbon\Carbon::now()->gte($camp_data->certificate_available_date))    
        @if($applicant->is_admitted && $applicant->workshop_credit_type=="基金會研習數位證明書")
            <div class="card col-sm-12">
                <div class="card-header">
                    <h2>研習證明下載</h2>
                </div>
                <div class="card-body">
                    <a href="https://bwcamp.bwfoce.org/downloads/{{ $applicant->batch->camp->table }}{{ $applicant->batch->camp->year }}/{{ $applicant->group }}{{ $applicant->number }}{{ $applicant->applicant_id }}.pdf" target="_blank" rel="noopener noreferrer" class="btn btn-success">下載</a>
                </div>
                <div class="card-body">
                    如下載顯示錯誤，請聯絡您的小組關懷員，謝謝！
                </div>
            </div><br>
        @endif
    @else
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
                        營隊之前，歡迎您加入「<a href="https://www.facebook.com/groups/bwfoce.happiness.new" target="_blank" rel="noopener noreferrer">幸福心學堂online</a>」臉書社團，收取營隊訊息和教育新知。<br>
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
                        營隊日期：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }})～{{ $applicant->batch->batch_end }}({{ $applicant->batch->batch_end_weekday }})<br>
                        營隊地點：{{ $applicant->batch->location }} {{ $applicant->batch->locationName }}<br>
                        --<br>
                        <b>報名報到諮詢窗口</b>（周一至周五10:00~17:30）<br>
                        <div style="white-space: pre-line;">{{ str_replace('\n', "\n", $applicant->batch->contact_card) }}</div>
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
                            {{--全程參與者，發給研習證明文件。--}}
                        </div>
                    @else
                        敬愛的 {{ $applicant->name }} ，您好！ <br>
                        　　非常感謝您的報名，由於我們的場地容量的侷限性，不克錄取，造成您的不便，敬請見諒包涵。 福智文教基金會在全省各縣市的分支機構，平日都設有適合各年齡層的多元心靈提升課程，誠摯歡迎您的參與！<br>
                        　　敬祝<br>
                        教安<br>
                    @endif
                    <br>
                    <p class="right">財團法人福智文教基金會　謹此<br>
                    {{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}</p>
                    <b>「福智文教基金會」網站：</b>
                    <a href="https://bwfoce.org" target="_blank" rel="noopener noreferrer">https://bwfoce.org</a><br>
                    <br>
                    <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
@stop
