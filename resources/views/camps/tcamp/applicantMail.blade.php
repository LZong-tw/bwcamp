{{ $applicant->name }} 您好：<br>
    <br>
    感謝您報名{{ $camp_data->fullName }}，報名手續已完成，<br>
    請記下您的報名序號： {{ $applicant->id }} 作為日後查詢使用。<br>
    我們將於 {{ \Carbon\Carbon::parse($campData->admission_announcing_date)->translatedFormat("Y年m月d日(l)") }} 寄發錄取與否通知信至您的電子信箱，或您可至活動網站 http://bwfoce.org/2023tcamp 查詢。<br>
    <br>
    洽詢電話：(02)2545-3788*529 劉小姐 (週一〜週五 上午10時〜下午5時)<br>
    <br>
    LINE@：http://bwfoce.org/line <br>
    LINE ID：@bwfoce <br>
    電子郵件：bwmedu@blisswisdom.org<br>
<blockquote>財團法人福智文教基金會  敬啟</blockquote>
