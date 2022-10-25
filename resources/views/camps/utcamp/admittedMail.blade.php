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
    <h2 class="center">2022第30屆教師生命成長營</h2> 
    <h2 class="center">大專教職員梯 錄取通知單</h2> 
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>報名序號：{{ $applicant->id }}</td>
        <td>姓名：{{ $applicant->name }}</td>
        <td>錄取編號：<u>{{ $applicant->group }}{{ $applicant->number }}</u></td>
        <td>組別：<u>{{ $applicant->group }}</u></td>
    </tr>
</table>
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
<a class="right">財團法人福智文教基金會　謹此</a><br> 
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>