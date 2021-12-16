@extends('camps.tcamp.layout')
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
        @php
            $skip = false;   
        @endphp
        @if($camp_data->variant == "utcamp" && now()->lt("2021-11-30"))
            @php
                $skip = true;
            @endphp        
            <div class="alert alert-success" role="alert">
                您好，感謝您報名 【2022第30屆教師生命成長營 大專教職員梯】，大會因故需調整錄取時程，將修正為：11/30(二)、12/20(一)、1/5(三)，將於以上時間點寄發「錄取通知」電子信件(email)。再次感謝您的報名！
            </div>
        @endif
        @if(!$skip)
            @if($camp_data->variant == "utcamp")
                @if($applicant->is_admitted)
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                查詢結果
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <table width="100%" style="table-layout:fixed; border: 0;">
                                        <tr>
                                            {{-- <td>梯次：{{ $applicant->batch->name }}</td> --}}
                                            <td>姓名：{{ $applicant->name }}</td>
                                            <td>錄取編號：<u>{{ $applicant->group }}{{ $applicant->number }}</u></td>
                                            <td>組別：<u>{{ $applicant->group }}</u></td>
                                        </tr>
                                    </table>
                                    親愛的老師您好，非常恭喜您錄取「2022第30屆教師生命成長營-大專教職員梯」，誠摯地期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫！請詳閱以下相關訊息，祝福您營隊收穫滿滿：
                                    <h4>營隊資訊</h4>
                                    <div class="ml-4 mb-2">
                                        活動期間：2022/1/23、24 (日、一)<br>
                                        本次活動採線上舉辦。 <br>
                                        連線軟體與帳號：Zoom&nbsp;(請事先下載安裝，當日09:00將開放連線)：<br>
                                        Zoom 帳號：95556824059 密碼：703112 <br>
                                        全程參與者，發給研習證明文件。
                                    </div>
                                    {{-- <h4>請回覆確認參加</h4>
                                    <div class="ml-4 mb-2">
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
                                        </form> --}}
                                    </div>
                                    <div class="mb-2">
                                        有任何問題，歡迎與關懷員聯絡，或來電福智文教基金會02-7751-6799 #520023邱先生(2022第30屆教師生命成長營大專教職員梯秘書組)
                                    </div>
                                </p>
                                <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                                <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-sm-2">
                        @if($applicant->showCheckInInfo)
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
                    </div> --}}
                @else
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                查詢結果
                            </div>
                            <div class="card-body">                                
                                <p class="card-text">您好，感謝您報名 【2022第30屆教師生命成長營 大專教職員梯】，後續錄取時程為：12/20(一)、1/5(三)，將於以上時間點寄發電子信件通知錄取相關訊息。再次感謝您的報名！</p>
                                <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                                <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                @if($applicant->is_admitted)
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                查詢結果
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <table width="100%" style="table-layout:fixed; border: 0; text-align: center; margin-bottom: 12px; margin-top: -4px;">
                                        <tr>
                                            <td><h6>場次</h6></td>
                                            <td><h6>姓名</h6></td>
                                            <td><h6>錄取編號</h6></td>
                                            <td><h6>組別</h6></td>
                                        </tr>
                                        <tr>
                                            <td>幼小中高場(1/26-27)</td>
                                            <td>{{ $applicant->name }}</td>
                                            <td><u>{{ $applicant->group }}{{ $applicant->number }}</u></td>
                                            <td><u>{{ $applicant->group }}</u></td>
                                        </tr>
                                    </table>
                                    &emsp;&emsp;恭喜您錄取「2022教師生命成長營」！竭誠歡迎您的到來，期待與您共享這場心靈饗宴。 <br>
                                    &emsp;&emsp;〈心的力量〉：「我們的內心有極其罕見的力量，一定要善於運用這種力量，把它變成是善的力量、和平的力量、慈悲的力量，讓這種力量充滿自他的生命。」————《希望．新生》#280 <br>
                                    &emsp;&emsp;我們的心蘊涵最神奇的魔法，這次的營隊內容結合魔法學校的情境設計元素，我們會在2022年1/10〜1/12寄送幸福魔法盒(教材包)，屆時請再注意收件。以下先做簡單說明：                                    
                                    <h5>情境設計</h5>
                                    <div class="ml-4 mb-2">
                                        2021年11月1日凌晨，因魔咒解除，霍福華智魔法學校重現世間，從水底深處浮出水面。頓時撼動天上人間，披風家族乘坐魔帚從四面八方凌空而來，一道道光軌劃亮夜空，迎來最明亮潔白的圓月出現天際，共同迎接萬年盛事～古堡矗立，魔法重現，不可思議即將展開~ 
                                        <hr>
                                        霍福華智魔法學校首辦2022年教師遊學課程--教師生命成長營，在經過一個多月的報名、審核的過程後，確認有900多位老師們身上流有魔法師的血液，具備初級魔法師的資格，有辦法進行魔法學習。魔法學校校長福智利多很重視這群新到來的初級魔法師，畢竟在麻瓜的世界裡，要找到這群人、延續魔法的血脈很不容易，因此，特別選定經過訓練後的中級魔法師們，以及特派披風家族守護員，來接引他們到學校學習。
                                        <hr>
                                        在入學前，初級魔法師們就會收到一封由貓頭鷹嘿美送到的入學通知書，說明入學前的準備工作、班級班號、加入線上平台、如何到達學校等。接著，會開始接到中級魔法師的電話通知、參加遊學前的兩次行前說明會，並收到幸福魔法盒、取得魔法特快車的月台車票，做好遊學前的準備工作。這段期間，也會在魔法學校和幸福心學堂online合作的線上平台上看到各種訊息、進行各種活動。
                                        <hr>
                                        入學當天，他們將拿著月台車票，通過九又五分之四月台，搭上魔法特快車、專屬他的班級的車廂，一起前往魔法學校。這次的遊學課程，除了兩天的營隊模式，介紹魔法學校的學習主軸和魔法體驗之外，還有長達一年的深入學習，讓魔法師們可以透過多角度的了解、討論、實作，熟練魔法的使用，堅固施展魔法解決問題、幫助別人的能力。
                                    </div>
                                    <h5>請加入幸福心學堂online臉書社團</h5>
                                    <div class="ml-4 mb-2">
                                        <a href="https://www.facebook.com/groups/bwfoce.happiness.new" target="_blank" rel="noopener noreferrer">https://www.facebook.com/groups/bwfoce.happiness.new</a>
                                    </div>
                                    <h5>諮詢窗口（請於周一至周五 10:00~17:30 來電）</h5>
                                    <div class="ml-4 mb-2">
                                        台北場　劉小姐 (02)2545-3788#529
                                    </div>
                                    {{-- <h4>營隊資訊</h4>
                                    <div class="ml-4 mb-2">
                                        活動期間：2022/1/23、24 (日、一)<br>
                                        本次活動採線上舉辦。 <br>
                                        連線軟體與帳號：Zoom&nbsp;(請事先下載安裝，當日09:00將開放連線)：<br>
                                        Zoom 帳號：95556824059 密碼：703112
                                    </div> --}}
                                    <h4>請回覆確認參加</h4>
                                    <div class="ml-4 mb-2">
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
                                        {{-- 全程參與者，發給研習證明文件。 --}}
                                    </div>
                                </p>
                                <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                                <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                查詢結果
                            </div>
                            <div class="card-body">                                
                                <p class="card-text">
                                    敬愛的教育夥伴，您好！ <br>
                                    「教師生命成長營」自舉辦以來，每年都得到教育夥伴們的支持和肯定，思及社會上仍有這麼多人共同關心莘莘學子們的學習成長，令人深感振奮！每一位老師的報名都是鼓舞我們的一分力量，激勵基金會全體人員持續不懈，與大家共同攜手為教育盡心盡力。
                                    非常感謝您的報名，由於我們的視訊配備侷限，不克錄取，造成您的不便，敬請見諒包涵！ <br><br>
                                    福智文教基金會在全省各縣市的分支機構，平日都設有適合各年齡層的多元心靈提升課程，誠摯歡迎您的參與！<br><br>
                                    關注「福智文教基金會」網站：<a href="https://bwfoce.org" target="_blank" rel="noopener noreferrer">https://bwfoce.org</a><br>
                                    關注「哈特麥1D」(heart mind edu.)FB：<a href="https://www.facebook.com/heartmind1d/" target="_blank" rel="noopener noreferrer">https://www.facebook.com/heartmind1d/</a><br>
                                    <br>
                                    祝福　教安，健康平安！ 
                                </p>
                                <a class="right">財團法人福智文教基金會　敬啟</a><br> 
                                <a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月  {{ \Carbon\Carbon::now()->day }}  日</a>
                                <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
                                <a href="{{ $camp_data->site_url }}" class="btn btn-primary">回營隊首頁</a>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endif
    </div>
@stop