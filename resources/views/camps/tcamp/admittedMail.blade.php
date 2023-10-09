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
    <h2 class="center">{{ $applicant->batch->camp->fullName }}&emsp;錄取通知</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>地點：{{ $applicant->batch->locationName }}</td>
        <td>時間：{{ $applicant->batch_start }}-{{ $applicant->batch->batch_end }}</td>
    </tr>
    <tr>
        <td>姓名：{{ $applicant->name }}</td>
        <td>錄取編號：<u>{{ $applicant->group }}{{ $applicant->number }}</u></td>
        <td>組別：<u>{{ $applicant->group }}</u></td>
    </tr>
</table>
    &emsp;&emsp;恭喜您錄取「{{ $applicant->batch->camp->fullName }}」，竭誠歡迎您的到來。<br>
    &emsp;&emsp;相關營隊訊息，將於營隊三周前寄發「報到通知單」，請記得到電子信箱收信。<br>
    &emsp;&emsp;也歡迎加入[幸福心學堂online]臉書社團，收取營隊訊息和教育新知。<br>
    &emsp;&emsp;很期待與您共享這場心靈的饗宴，預祝您歡喜學習，收穫滿滿。<br>
    &emsp;&emsp;敬祝&emsp;教安<br><br>
    <ul>
        <li>
            請加入幸福心學堂online臉書社團：<br>
            <a href="https://www.facebook.com/groups/bwfoce.happiness.new" target="_blank" rel="noopener noreferrer">https://www.facebook.com/groups/bwfoce.happiness.new</a>
        </li>
        <li>
            關注「福智文教基金會」網站：<a href="https://bwfoce.org">https://bwfoce.org</a>
        </li>
        <li>
            營隊相關訊息我們會有義工與您聯繫，或諮詢窗口<span class="font-bold">（請於周一至周五 10:00~17:30 來電）：</span><br>
            陳昶安&emsp;先生<br>
            電話：07-9743280#68601<br>
            Email：ca7974zz@gmail.com<br>
        </li>
    </ul>
@endif
<a class="right">財團法人福智文教基金會&emsp;謹此</a><br>
<a class="right">{{ \Carbon\Carbon::now()->year }}&emsp;年&emsp;{{ \Carbon\Carbon::now()->month }}&emsp;月&emsp;{{ \Carbon\Carbon::now()->day }}&emsp;日</a>
