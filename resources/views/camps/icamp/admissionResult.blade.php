@extends('camps.icamp.layout')
@section('content')
    <style>
        .indent{
            text-indent: 22px;
        }
    </style>
    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}</h4>
    </div>
    <div class="row">
        @if($applicant->is_admitted)
            <div class="col-sm-10">
                <div class="card">
                    <div class="card-header">
                        錄取通知
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $applicant->name }} 您好</p>
                        <p class="card-text">恭喜您錄取「{{ $camp_data->fullName }}」！您的錄取組別為：{{ $applicant->group }}，錄取編號為：{{ $applicant->group }}{{ $applicant->number }}。</p>
                        <p class="card-text">我們誠摯歡迎您來共享這場心靈饗宴。三天研習務必全程參加，請參閱下列說明。</p>
                        <p class="card-text">
                            <h4>營隊資訊</h4>
                                <div class="ml-4 mb-2">研習日期：2023年8月4日(星期五)至8月6日(星期日)，請務必<u>全程參加</u>。</div>
                                <div class="ml-4 mb-2">報到時間：2023年8月4日(星期五)</div>
                                <div class="ml-4 mb-2">報到地點：開南大學(桃園市蘆竹區開南路1號)(詳見報到通知單，預計7月寄出Email)</div>
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
                            <h4>建議攜帶物品</h4>
                                <div class="ml-4 mb-2">以下謹列出參加此次活動所需攜帶物品，以及本營隊提供之物品，方便您準備之依據。</div>
                                <br>
                                <div class="ml-4 mb-2">＊多套換洗衣物(洗衣不方便)、備用袋(裝使用過之衣物)。</div>
                                <div class="ml-4 mb-2">＊毛巾、牙膏、牙刷、香皂、洗髮精、拖鞋、衣架、輕薄外套(防上課地點冷氣過冷)。</div>
                                <div class="ml-4 mb-2">＊寢具(睡袋或薄被、枕頭、軟墊)(寢室有冷氣)。</div>
                                <div class="ml-4 mb-2">＊隨身背包(教材約A4大小)、文具用品、環保水杯、環保筷、摺疊傘、遮陽帽。</div>
                                <div class="ml-4 mb-2">＊刮鬍刀、耳塞(睡覺時易受聲音干擾者)、眼罩、口罩、手帕。</div>
                                <div class="ml-4 mb-2">＊身份證、健保卡(生病時就診用)。</div>
                                <br>
                                <div class="ml-4 mb-2">請勿攜帶貴重物品，本會無法負保管責任！</div>
                            <h4>注意事項</h4>
                                <div class="ml-4 mb-2">＊<b>如您目前懷孕中，請考量本營隊因為休息皆為硬板床、一天十多小時上課、及天氣炎熱多次出入冷氣房等各項因素，依照您的身體狀況慎重考量。</b></div>
                                <div class="ml-4 mb-2">＊<u>校園停車位有限</u>，請多利用大眾交通運輸工具。預計在台鐵/高鐵桃園站備有接駁巴士接送。</div>
                                <div class="ml-4 mb-2">＊本次營隊報名踴躍，因場地考量容納有限，若您無法全程參加，請告知關懷員，感謝您的善行。謝謝！</div>
                        </p>
                        <br>
                        <p class="card-text">本會關懷員近日將以電話跟您確認，若有任何問題，歡迎與該關懷員聯絡，或致電本會。</p>
                        <p class="card-text">聯絡電話：02-7751-6788 分機：610408、613091、610301</p>
                        <p class="card-text">洽詢時間：週一～週五 10:00～20:00、週六 10:00～16:00</p>
                        <br><br>
                        <p class="card-text text-right">主辦單位：財團法人福智文教基金會 敬啟</p>
                        <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
                        <p class="card-text">Facebook 卓越青年 <a href="https://www.facebook.com/YoungOneCamp" target="_blank" rel="noopener noreferrer">https://www.facebook.com/YoungOneCamp</a></p>
                        <p class="card-text">{{ $camp_data->fullName }}官方網站 <a href="http://www.youngone.org.tw/camp/" target="_blank" rel="noopener noreferrer">http://www.youngone.org.tw/camp/</a></p>
                        <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                        <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                    </div>
                </div>
            </div>
            {{--
            <div class="col-sm-2">
                <div class="card">
                    <div class="card-header">
                        相關資訊
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            繳費狀態：<strong>{{ $applicant->payment_status }}</strong>
                        </p>
                        <form action="{{ route("downloadPaymentForm", $applicant->batch_id) }}" method="post" name="downloadPaymentForm">
                            @csrf
                            <input type="hidden" name="applicant_id" value="{{ $applicant->applicant_id }}">
                            <button class="btn btn-success" onclick="this.innerText = '正在產生繳費聯'; this.disabled = true; document.downloadPaymentForm.submit();">下載繳費聯</button>
                        </form>
                    </div>
                </div>
                @if($applicant->showCheckInInfo)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            報到資訊
                        </div>
                        <div class="card-body">
                            <form action="{{ route("downloadCheckInNotification", $applicant->batch_id) }}" method="post" name="downloadCheckInNotification">
                                @csrf
                                <input type="hidden" name="applicant_id" value="{{ $applicant->applicant_id }}">
                                <button class="btn btn-primary" onclick="this.innerText = '正在產生報到通知單'; this.disabled = true; document.downloadCheckInNotification.submit();">報到通知單</button>
                            </form>
                            <form action="{{ route("downloadCheckInQRcode", $applicant->batch_id) }}" method="post" name="downloadCheckInQRcode">
                                @csrf
                                <input type="hidden" name="applicant_id" value="{{ $applicant->applicant_id }}">
                                <button class="btn btn-success" onclick="this.innerText = '正在產生 QR code 報到單'; this.disabled = true; document.downloadCheckInQRcode.submit();">QR Code 報到單</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
            --}}
        <!--第二波錄取公告為7/9，6/10之後未錄取就是真的不錄取了-->
        @elseif(\Carbon\Carbon::now() >= \Carbon\Carbon::createFromFormat("Y-m-d", "2023-07-10"))
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        錄取查詢結果
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $applicant->name }} 您好</p>
                        <p class="card-text indent">非常感謝您報名「{{ $camp_data->fullName }}」，由於場地與各項條件的限制，非常抱歉未能錄取您。誠摯地邀請您參與本會其它活動。</p>
                        <p class="card-text indent">相關活動訊息，請洽詢各區聯絡窗口：</p>
                        <p class="card-text indent"><a href="https://www.blisswisdom.org/about/branches" target="_blank" rel="noopener noreferrer">全球辦事處</a></p>
                        <p class="card-text indent"><a href="http://tp.blisswisdom.org/352-2#local" target="_blank" rel="noopener noreferrer">北區服務據點</a></p>
                        <p class="card-text indent">若有任何問題，歡迎來電本會02-7751-6788分機：610408、613091、610301。</p>
                        <p class="card-text indent">（洽詢時間：週一～週五 10:00～ 20:00、週六 10:00～16:00）</p>
                        <p class="card-text text-right">主辦單位：財團法人福智文教基金會 敬啟</p>
                        <p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
                        <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                        <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                    </div>
                </div>
            </div>
        @else
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        錄取查詢結果
                    </div>
                    <div class="card-body">
                        錄取作業進行中。
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop