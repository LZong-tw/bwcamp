{{ $applicant->name }} 您好：<br>
<br>
&emsp;&emsp;感謝您報名「{{ $camp_info->fullName }}」。
@includeIf('camps.' . $camp_info->table . '.successMessages')
<p style="text-align: right">財團法人福智文教基金會&emsp;敬啟&emsp;<br>
{{ \Carbon\Carbon::now()->format('Y 年 n 月 j 日') }}&emsp;</p>
