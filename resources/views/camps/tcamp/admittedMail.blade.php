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
    <h2 class="center">大專教職員梯 錄取/確認參加 通知單</h2> 
@else
    <h2 class="center">2022教師生命成長營錄取通知單</h2>
@endif
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        @if(!$applicant->batch->camp->variant)
            <td>場次：幼小中高場(1/26-27)</td>
        @endif
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
    &emsp;&emsp;恭喜您錄取「2022教師生命成長營」！竭誠歡迎您的到來，期待與您共享這場心靈饗宴。 <br>
    &emsp;&emsp;〈心的力量〉：「我們的內心有極其罕見的力量，一定要善於運用這種力量，把它變成是善的力量、和平的力量、慈悲的力量，讓這種力量充滿自他的生命。」————《希望．新生》#280 <br>
    &emsp;&emsp;我們的心蘊涵最神奇的魔法，這次的營隊內容結合魔法學校的情境設計元素，我們會在2022年1/10〜1/12寄送幸福魔法盒(教材包)，屆時請再注意收件。以下先做簡單說明：
    <ul>
        <li>
            情境設計 <br>
            2021年11月1日凌晨，因魔咒解除，霍福華智魔法學校重現世間，從水底深處浮出水面。頓時撼動天上人間，披風家族乘坐魔帚從四面八方凌空而來，一道道光軌劃亮夜空，迎來最明亮潔白的圓月出現天際，共同迎接萬年盛事～古堡矗立，魔法重現，不可思議即將展開~ 
            <hr>
            霍福華智魔法學校首辦2022年教師遊學課程--教師生命成長營，在經過一個多月的報名、審核的過程後，確認有900多位老師們身上流有魔法師的血液，具備初級魔法師的資格，有辦法進行魔法學習。魔法學校校長福智利多很重視這群新到來的初級魔法師，畢竟在麻瓜的世界裡，要找到這群人、延續魔法的血脈很不容易，因此，特別選定經過訓練後的中級魔法師們，以及特派披風家族守護員，來接引他們到學校學習。
            <hr>
            在入學前，初級魔法師們就會收到一封由貓頭鷹嘿美送到的入學通知書，說明入學前的準備工作、班級班號、加入線上平台、如何到達學校等。接著，會開始接到中級魔法師的電話通知、參加遊學前的兩次行前說明會，並收到幸福魔法盒、取得魔法特快車的月台車票，做好遊學前的準備工作。這段期間，也會在魔法學校和幸福心學堂online合作的線上平台上看到各種訊息、進行各種活動。
            <hr>
            入學當天，他們將拿著月台車票，通過九又五分之四月台，搭上魔法特快車、專屬他的班級的車廂，一起前往魔法學校。這次的遊學課程，除了兩天的營隊模式，介紹魔法學校的學習主軸和魔法體驗之外，還有長達一年的深入學習，讓魔法師們可以透過多角度的了解、討論、實作，熟練魔法的使用，堅固施展魔法解決問題、幫助別人的能力。
        </li>
        <li>
            請加入幸福心學堂online臉書社團：<br>
            <a href="https://www.facebook.com/groups/bwfoce.happiness.new" target="_blank" rel="noopener noreferrer">https://www.facebook.com/groups/bwfoce.happiness.new</a>
        </li>
        <li>
            諮詢窗口（請於周一至周五 10:00~17:30 來電）：<br>
            台北場　劉小姐 (02)2545-3788#529
        </li>
    </ul>
@endif
<a class="right">財團法人福智文教基金會　@if($applicant->batch->camp->variant == "utcamp") 謹此 @else 敬啟 @endif</a><br> 
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>