<style>
    u{
        @if($applicant->batch->camp->variant == "utcamp")
            color: red;
        @endif
    }

    .center{
        text-align: center;
    }

    .right{
        text-align: right;
    }
</style>
@if($applicant->batch->camp->variant == "utcamp")
    <h2 class="center">2022第30屆教師生命成長營</h2>
    <h2 class="center">大專教職員梯 錄取通知單</h2>
@else
    <h2 class="center">2023第31屆教師生命成長營　　錄取通知</h2>
@endif
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        @if(!$applicant->batch->camp->variant)
            <td>場次：{{ $applicant->batch->name }}(1/31-2/1)</td>
        @else
            <td>報名序號：{{ $applicant->id }}</td>
        @endif
        <td>姓名：{{ $applicant->name }}</td>
        <td>錄取編號：<u>{{ $applicant->group }}{{ $applicant->number }}</u></td>
        <td>組別：<u>{{ $applicant->group }}</u></td>
    </tr>
</table>
@if($applicant->batch->camp->variant == "utcamp")
    歡迎您參加「2022第30屆教師生命成長營-大專教職員梯」，誠摯地期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。請詳閱以下相關訊息，祝福您營隊收穫滿滿：<br>
    <br>
    營隊資訊
    <ol>
        <li>活動期間：2022/1/23、24(日、一)</li>
        <li>連線軟體與帳號(當日09:00開放連線)：<br>
            Zoom 帳號：95556824059 密碼：703112
        </li>
        <li>大會將於營隊開始前實體寄發「教師營幸福禮盒」給學員，請於一月密切注意。</li>
        <li>全程參與者，發給研習證明文件。</li>
    </ol>
    若有任何問題，歡迎與各組關懷員聯絡，或來電02-7751-6799 #520023邱先生(2022第30屆教師生命成長營大專教職員梯秘書組)。<br>
@else
    &emsp;&emsp;恭喜您錄取「2023教師生命成長營 」，竭誠歡迎您的到來。<br>
    &emsp;&emsp; 相關營隊訊息，將於營隊三周前寄發「報到通知單」，請記得到電子信箱收信。<br>
        也歡迎加入[幸福心學堂online]臉書社團，收取營隊訊息和教育新知。<br>
        很期待與您共享這場心靈的饗宴，預祝您歡喜學習，收穫滿滿。<br>
        敬祝             教安
    <ul>
        <li>
            請加入幸福心學堂online臉書社團：<br>
            <a href="https://www.facebook.com/groups/bwfoce.happiness.new" target="_blank" rel="noopener noreferrer">https://www.facebook.com/groups/bwfoce.happiness.new</a>
        </li>
        <li>
            關注「福智文教基金會」網站：<a href="https://bwfoce.org">https://bwfoce.org</a>
        </li>
        <li>
            各區諮詢窗口 <span class="font-bold">（請於周一至周五 10:00~17:30 來電）：</span><br>
            <table>
                <tr>
                    <td>臺北場</td>
                    <td>劉小姐</td>
                    <td>02-25453788 #529</td>
                    <td>雲林場</td>
                    <td>陳小姐</td>
                    <td>05-5370133 #303</td>
                </tr>
                <tr>
                    <td>桃園場</td>
                    <td>李小姐</td>
                    <td>03-2756133 #1324</td>
                    <td></td>
                    <td>方先生</td>
                    <td>05-2833940 #305</td>
                </tr>
                <tr>
                    <td>新竹場</td>
                    <td>張小姐</td>
                    <td>03-5325566 #246</td>
                    <td>臺南場</td>
                    <td>吳小姐</td>
                    <td>06-2646831 #412</td>
                </tr>
                <tr>
                    <td>臺中場</td>
                    <td>廖小姐</td>
                    <td>04-37069300 #621400</td>
                    <td>高雄場</td>
                    <td>林小姐</td>
                    <td>07-9769341 #417</td>
                </tr>
            </table>
        </li>
    </ul>
@endif
<a class="right">財團法人福智文教基金會　@if($applicant->batch->camp->variant == "utcamp") 謹此 @else 敬啟 @endif</a><br>
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>
