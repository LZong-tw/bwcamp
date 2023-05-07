<style>
    u{
        color: red;
    }
</style>
<h2 class="center">{{ $applicant->batch->camp->fullName }}<br>分組通知函</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>姓名：{{ $applicant->name }}</td>
        <td>序號：{{ $applicant->id }}</td>
        <td>組別：{{ $applicant->groupRelation->alias }}</td>
        <td>場次：{{ $applicant->batch->name }}</td>
    </tr>
</table><br>

親愛的企業主管您好 :<br>
感謝您報名「2023企業主管生命成長營」，誠摯歡迎您參加本研習活動。我們已經為您分編好組別，並安排了充滿熱誠的關懷員提供服務，誠摯的歡迎您來共享這場心靈饗宴，一起翻轉人生，開創無限美好的幸福。<br><br>
為使研習進行順利，請詳閱下列須知：
<ol>
    <li>營隊上課時間：<ul>
            <li>7/14 (五) 13:50~18:00</li>
            <li>7/15 (六) 09:00~18:00</li>
            <li>7/16 (日) 09:00~18:30</li>
        </ul></li>
    <li>舉辦地點 : 東吳大學 外雙溪校區（台北市士林區臨溪路70號）</li>
    <li>
        交通參考 :<br>
        請參閱以下 東吳大學 外雙溪校區 -交通資訊說明<br>
        <a href="https://www-ch.scu.edu.tw/october/school_traffic">https://www-ch.scu.edu.tw/october/school_traffic</a>
    </li>
    <li>營隊關懷員近日內將透過簡訊及電話與您聯繫。<br>
        如有任何問題，也歡迎主動與關懷員聯絡。
    </li>
    <li>
        {{ $applicant->groupRelation->alias }}關懷員 :
        <ol>
            @foreach(\App\Models\User::whereHas('roles', function($q){
                $q->where('group_id', $applicant->group_id);
            })->get() as $user)
                <li>{{ $user->application_log()->orderByDesc('id')->first()->name }} {{ $user->application_log()->orderByDesc('id')->first()->mobile }}</li>
            @endforeach
        </ol>
    </li>
</ol>
<a>敬祝～闔家平安、健康喜樂！</a><br>
<a class="right">2023企業主管生命成長營關懷大組</a><br>
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>
