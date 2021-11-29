<style>
    u{
        color: red;
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
    <h2 class="center">大專教職員梯 錄取/確認參加 通知單</h2> 
@else
    <h2 class="center">{{ $applicant->batch->camp->fullName }} 錄取通知單</h2>
@endif
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        {{-- <td>梯次：{{ $applicant->batch->name }}</td> --}}
        <td>姓名：{{ $applicant->name }}</td>
        <td>錄取編號：<u>{{ $applicant->group }}{{ $applicant->number }}</u></td>
        <td>組別：<u>{{ $applicant->group }}</u></td>
    </tr>
</table>
@if($applicant->batch->camp->variant == "utcamp") 
    歡迎您參加「2022第30屆教師生命成長營-大專教職員梯」，誠摯地期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。請於12月7日(二)前於報名網站透過「錄取查詢」頁面（<a href="https://bwcamp.bwfoce.org/camp/46/queryadmit" target="_blank" rel="noopener noreferrer">https://bwcamp.bwfoce.org/camp/46/queryadmit</a>）進行「確認參加」步驟，以利後續作業。請詳閱以下相關訊息，祝福您營隊收穫滿滿：<br>
    <br>
    營隊資訊
    <ol>
        <li>活動期間：2022/1/23、24(日、一)</li>
        <li>連線軟體與帳號(當日09:00開放連線)：<br>
            Zoom 帳號：95556824059 密碼：703112
        </li>
        <li>大會將於營隊開始前實體寄發「教師營幸福禮盒」給完成「確認參加」的學員，請於一月密切注意。</li>
    </ol>
    若有任何問題，歡迎與各組關懷員聯絡，或來電02-7751-6799 #520023邱先生(2022第30屆教師生命成長營大專教職員梯秘書組)。<br>
@else

@endif

{{-- 恭喜您錄取「{{ $applicant->batch->camp->fullName }} 」！竭誠歡迎您的到來，期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。以下幾點事情需要您的協助與配合：<br>
<ul>
    <li>活動費用：1200元，繳費聯請下載附件檔。</li>
    <li>請於{{ \Carbon\Carbon::now()->year }}年{{ substr($campFullData->payment_deadline, 2, 2) }}月{{ substr($campFullData->payment_deadline, 4, 2) }}日前完成繳費，<a style="color: red;">逾時將<u>視同放棄錄取資格</u>！</a></li>
    <li>繳費地點：可至超商、上海銀行繳費，或使用ATM轉帳、臨櫃匯款。</li>
    <li>若完成繳費，請於至少一個工作天後，上網查詢是否已繳費完畢。<br>
        （<a href="http://bwcamp.bwfoce.org/camp/2/queryadmit" target="_blank" rel="noopener noreferrer">http://bwcamp.bwfoce.org/camp/2/queryadmit</a> ）</li>
    <li>發票於營隊第一天提供，<strong>若需開立統一編號，請於 1/22 前填寫<a href="https://docs.google.com/forms/d/e/1FAIpQLSeVcqd01trNPKMSvc-RH8Zhac5Gexn-fBaAfAWMCn323PVgFw/viewform" target="_blank">申請單</a></strong>。</li>
    <li>若繳費後，因故無法參加研習需退費者，請參照<a href="https://bwfoce.wixsite.com/bwtcamp/faq" target="_blank">報名網站申請退費注意事項</a>，並填寫<strong>退費申請單</strong>。</li>
    <li><a style="color: red;">本會密切注意新冠疫情發展，若因故必須取消營隊或改變舉辦方式，將公布於教師營網頁。</a></li>
    <li>各區諮詢窗口<strong>（請於周一至周五 10:00~17:30 來電）</strong>：
        <table width="100%" style="table-layout:fixed; border: 0;">
            <tr>
                <td>台北場　劉小姐 (02)2545-3788#529</td>
                <td>雲林場　吳小姐0921-013450</td>
            </tr>
            <tr>
                <td>桃園場　趙小姐  (03)275-6133#1312</td>
                <td>嘉義場　吳小姐0928-780108</td>
            </tr>
            <tr>
                <td>新竹場　張小姐 (03)532-5566#246</td>
                <td>台南場　簡小姐0919-852066</td>
            </tr>
            <tr>
                <td>台中場　蔣小姐  0933-199203</td>
                <td>高雄場　胡小姐(07)9769341#417</td>
            </tr>
        </table>	
	</li>
</ul> --}}
<a class="right">財團法人福智文教基金會　謹此</a><br> 
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>