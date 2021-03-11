<style>
    u{
        color: red;
    }
</style>
<h2 class="center">{{ $applicant->fullName }} 錄取繳費通知單</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>梯次：{{ $applicant->bName }}</td>
        <td>姓名：{{ $applicant->name }}</td>
        <td>錄取編號：{{ $applicant->group }}{{ $applicant->number }}</td>
        <td>組別：{{ $applicant->group }}</td>
    </tr>
</table><br>
恭喜您錄取「{{ $applicant->fullName }}」！竭誠歡迎您的到來，期待與您共享這場心靈饗宴，希望您能獲得豐盛的收穫。請於{{ \Carbon\Carbon::now()->year }}年{{ substr($campFullData->payment_deadline, 2, 2) }}月{{ substr($campFullData->payment_deadline, 4, 2) }}日前完成繳費，<u>逾時將視同放棄錄取資格！</u>
<br>
<ul>
    <li>費用：1200元</li>
    <li>繳費地點：可至超商、上海銀行繳費，或使用ATM轉帳、臨櫃匯款。</li>
    <li>可自行於報名網頁（網址{{ url('camp/' . $applicant->batch_id . '/queryadmit') }}）查詢是否已繳費完畢。</li>
    <li>若繳費後，因故無法參加研習需退費者，請參照報名網申請退費注意事項（https://bwfoce.wixsite.com/bwtcamp/faq），並填寫退費申請單。</li>
    <li>台北場 諮詢窗口：劉慧娟小姐 (02)2545-3788#529</li>
    <li>桃園場 諮詢窗口：趙小姐 (03)275-6133#1312</li>
    <li>新竹場 諮詢窗口：張小姐 (03)532-5566#246</li>
    <li>台中場 諮詢窗口：蔣小姐  0933-199203</li>
    <li>雲林場 諮詢窗口：吳春桂小姐0921-013450</li>
    <li>嘉義場 諮詢窗口：吳淑貞小姐0928-780108</li>
    <li>台南場 諮詢窗口：簡小姐0919-852066</li>
    <li>高雄場 諮詢窗口：胡小姐 (07)9769341#417 </li>
</ul>



<a class="right">財團法人福智文教基金會　謹此</a><br> 
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>