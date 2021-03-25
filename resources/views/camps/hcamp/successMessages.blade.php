請記下您的<span class="text-danger font-weight-bold">《 報名序號：{{ $applicant->id }} 》</span>作為日後查詢使用。
並請於 <u>5/4 前完成繳費</u>，<strong>繳費後始完成報名手續</strong>，倘未繳費，視同放棄。
<br>
<form action="{{ route("downloadPaymentForm", $applicant->batch_id) }}" method="post" name="downloadPaymentForm" class="d-inline">
    @csrf
    <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
    請下載「<button class="btn btn-success" onclick="this.innerText = '正在產生繳費單'; this.disabled = true; document.downloadPaymentForm.submit();">錄取繳費通知單</button>
</form>」(或可於網站錄取頁面查詢)後，至銀行或超商繳費。 
<h6>請注意，若您使用 Line 開啟網頁，將會無法下載繳費單，請在普通瀏覽器開啟本站後再下載。</h6>