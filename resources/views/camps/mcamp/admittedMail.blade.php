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
<body style="font-size:16px;">
    <h2 class="center">{{ $applicant->batch->camp->fullName }}&emsp;錄取通知</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>
        地點：{{ $applicant->batch->locationName }}<br>
        時間：{{ $applicant->batch->batch_start }}({{ $applicant->batch->batch_start_weekday }})至{{ $applicant->batch->batch_end }}({{ $applicant->batch->batch_end_weekday }})
        </td>
    </tr>
    <tr>
        <td>
        姓名：{{ $applicant->name }}<br>
        錄取編號：<u>{{ $applicant->group }}{{ $applicant->number }}</u><br>
        組別：<u>{{ $applicant->group }}</u>
        </td>
    </tr>
</table>
<br>
    {{ $applicant->name }} 您好：<br>
    <br>
    &emsp;&emsp;恭喜您錄取「{{ $applicant->batch->camp->fullName }}」，竭誠歡迎您的到來。<br>
    &emsp;&emsp;相關營隊訊息，將於營隊三周前寄發「報到通知單」，請記得到電子信箱收信。<br>
    &emsp;&emsp;很期待與您共享這場心靈的饗宴，預祝您歡喜學習，收穫滿滿。<br>
    &emsp;&emsp;敬祝&emsp;教安<br><br>
    <ul>
        <li>
            關注「福智文教基金會」網站：<a href="https://bwfoce.org">https://bwfoce.org</a>
        </li>
        <li>
            報名報到諮詢窗口<span class="font-bold">（周一至周五 10:00~17:30）：</span><br>
            <div style="white-space: pre-line;">{{ str_replace('\n', "\n", $applicant->batch->contact_card) }}</div>
        </li>
    </ul>
<br>
<a class="right">財團法人福智文教基金會&emsp;謹此</a><br>
<a class="right">{{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}</a>
</body>