
請記下您的<span class="text-danger font-weight-bold">《 報名序號：{{ $applicant->id }} 》</span>作為日後查詢使用。<br><br>

@if(now()->gt($applicant->camp->registration_end))
    報名截止時間 {{ $applicant->camp->registration_end }} 已過。<br>
@else
@endif
<br>
