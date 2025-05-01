<div>
    <!-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead -->
    <p align="right">
        @php
            $ceoEmails = config('permissions.ceo_emails', []);
            $ecampEmails = config('permissions.ecamp_emails', []);

            $showExportButton = false;
            $tableContent = $campFullData->table;
            $userEmail = $currentUser->email;
            $isCeocamp = str_contains($tableContent, 'ceo');
            $isEcamp = str_contains($tableContent, 'ecamp');

            if (!$isCeocamp && !$isEcamp) {
                // 如果 tableContent 不包含 'ceo' 也不包含 'ecamp'，則顯示按鈕
                $showExportButton = true;
            } else {
                // 否則，根據 email 判斷
                if ($isCeocamp && in_array($userEmail, $ceoEmails)) {
                    $showExportButton = true;
                } elseif ($isEcamp && in_array($userEmail, $ecampEmails)) {
                    $showExportButton = true;
                }
            }
        @endphp
        @if($showExportButton)
            <a href="{{ route("export", $campFullData->id) }}?vcamp={{ $isShowVolunteers }}" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">匯出資料</a>
            @endif
        @elseif(str_contains($campFullData->table, 'utcamp'))
            @if(
             str_contains($currentUser->email, "dlcheng@gmail.com") ||
             str_contains($currentUser->email, "bluestar.white.723@gmail.com") ||
             str_contains($currentUser->email, "mengichiu@gmail.com"))
            <a href="{{ route("export", $campFullData->id) }}?vcamp={{ $isShowVolunteers }}" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">匯出資料</a>
            @endif
        @else
            <a href="{{ route("export", $campFullData->id) }}?vcamp={{ $isShowVolunteers }}" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">匯出資料</a>
        @endif
        @if($isShowLearners)            &nbsp;&nbsp;
            @if (1)
                <input type=button value='電訪結果' rel='noopener noreferrer' class='btn btn-danger mb-3 btnTelCallResult' target='_blank' onclick=showTelCallResult()>
                {{--<a href="{{ route("showTelCallResults", $campFullData->id) }}" rel="noopener noreferrer" class="btn btn-danger mb-3" target="_blank">電訪結果</a>--}}    &nbsp;&nbsp;
            @endif
            @if ($currentUser->canAccessResource(new \App\Models\Applicant, 'create', $campFullData, target: $campFullData->vcamp))
                <a href="{{ route("showRegistration", $campFullData->id) }}" rel="noopener noreferrer" class="btn btn-danger mb-3" target="_blank">新增學員</a>    &nbsp;&nbsp;
            @endif
            @if($currentUser->canAccessResource(new \App\Models\ApplicantsGroup, 'create', $campFullData, target: $campFullData->vcamp) || $currentUser->canAccessResource(new \App\Models\ApplicantsGroup, 'assign', $campFullData, target: $campFullData->vcamp))
                <a href="?isSetting=1&batch_id={{ $currentBatch?->id ?? "" }}" class="btn btn-danger mb-3">設定組別</a>
            @endif     &nbsp;&nbsp;
            @if($user->canAccessResource(new \App\Models\CarerApplicantXref, "create", $campFullData, target: $campFullData->vcamp) || $user->canAccessResource(new \App\Models\CarerApplicantXref, "assign", $campFullData, target: $campFullData->vcamp))
                <a href="?isSettingCarer=1&batch_id={{ request()->batch_id }}" class="btn btn-danger mb-3">設定關懷員</a>
            @endif
        @endif
        @if($isShowVolunteers)
            <a href="{{ route("showRegistration", $campFullData->vcamp->id) }}" rel="noopener noreferrer" class="btn btn-danger mb-3" target="_blank">新增義工</a>
            @if($currentUser->canAccessResource(new \App\Models\OrgUser, 'create', $campFullData, target: $campFullData->vcamp) || $currentUser->canAccessResource(new \App\Models\OrgUser, 'assign', $campFullData, target: $campFullData->vcamp))
                <a href="?isSetting=1&batch_id={{ $currentBatch?->id ?? "" }}" class="btn btn-danger mb-3">設定組別/職務</a>
            @endif
        @endif
    </p>
</div>
