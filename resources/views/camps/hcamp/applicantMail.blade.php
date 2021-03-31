{{ $applicant->name }} 您好：<br>
<br>
感謝您報名 2021 ∞ 快樂營，我們已收到您的報名表。<br>
請務必於2021年5月4日前繳納費用，逾期視為放棄錄取。<br>
<br>
另請您記下您的報名序號：{{ $applicant->id }} 作為日後查詢使用。<br>
如需修改資料、下載繳費單或退費申請等，請連結以下網址：<a href="{{ url(route("query", $applicant->batch_id)) }}">{{ route("query", $applicant->batch_id) }}</a><br>
<br>
<strong>★提醒您：4/20前報名並繳費始享有早鳥優惠價格；4/21起即恢復原價5500元，若未於5/4前未繳費，則尚失錄取資格。</strong><br>
<br>
營隊問題諮詢方式如下：<br>
一、可掃描 QR-Code 或點選連結 https://line.me/R/ti/g/HbQvGwJyng <br>
加入2021年∞快樂營諮詢line群組。<br>
<img src="{{ $message->embed(public_path('storage/hcamp_line.png')) }}" alt=""><br>
<br>
二、電話洽詢(限周一~周五 上午10時- 下午6時)<br>
1.符小姐 TEL:0921-093-420<br>
2.小華老師 TEL:02-7751-6799#520031
