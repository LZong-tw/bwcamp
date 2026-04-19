{{ $applicant->name }} 您好：<br>
<br>
&emsp;&emsp;感謝您報名「{{ $camp_info->fullName }}」。
@includeIf('camps.' . $camp_info->table . '.successMessages')
