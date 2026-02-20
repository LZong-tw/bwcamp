@php
    $cancellation_add1_with_weekday = \Carbon\Carbon::parse($camp_info->cancellation_deadline)->addDays(1)->isoFormat('YYYY-MM-DD(dd)');
@endphp
請記下您的<b><span style="color: #DC3545;">《 報名序號：{{ $applicant->id }} 》</span></b>作為日後查詢使用。
<br>
錄取方式​​​：經審核報名資格，將於七日內以email 通知錄取與繳費資訊。請於期限內完成繳費，即正式完成報名！<br>
<p><b>取消參加退費原則</b></p>
<p>{{ $camp_info->cancellation_deadline }}({{ $camp_info->cancellation_deadline_weekday }})(含)前可全額退費(需扣除5%手續費)；<br>
{{ $cancellation_add1_with_weekday }}(含)以後恕不退費。<br></p>
<p><b>聯絡我們</b></p>
<p>{!! nl2br(e(str_replace('\n', "\n", $applicant->batch->contact_card))) !!}</p>