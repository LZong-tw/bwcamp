{{ $applicant->name }} 您好：<br>
<br>
    恭喜您已完成{{ $applicant->batch->camp->fullName }}網路報名程序，
@includeIf('camps.' . $camp_info->table . '.successMessages')
