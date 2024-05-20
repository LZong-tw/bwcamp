<div>
    <!-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead -->
    <p align="right">
        @if(!str_contains($campFullData->table, 'ceo') ||
             str_contains($currentUser->email, "cuboy.chen@gmail.com") ||
             str_contains($currentUser->email, "evelynhua@gmail.com") ||
             str_contains($currentUser->email, "jadetang01@gmail.com") ||
             str_contains($currentUser->email, "jadetang004@gmail.com") ||
             str_contains($currentUser->email, "tsai.scow@gmail.com") ||
             str_contains($currentUser->email, "luckybelle999@gmail.com") ||
             str_contains($currentUser->email, "0808leo.er@gmail.com") ||
             str_contains($currentUser->email, "irene.lee0713@gmail.com") || 
             str_contains($currentUser->email, "cindychen302@gmail.com") ||
             str_contains($currentUser->email, "christinelo0806@gmail.com"))
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
