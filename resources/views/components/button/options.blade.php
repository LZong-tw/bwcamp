<div>
    <!-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead -->
    <p align="right">
        @if(!str_contains($campFullData->table, 'ceo') ||
             str_contains($currentUser->email, "cuboy.chen@gmail.com") ||
             str_contains($currentUser->email, "evelynhua@gmail.com"))
            <a href="{{ route("export", $campFullData->id) }}?vcamp={{ $isShowVolunteers }}" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">匯出資料</a>
        @endif
        @if($isShowLearners)            &nbsp;&nbsp;
            @if ($currentUser->isAbleTo('\App\Models\Applicant.create'))
                <a href="{{ route("showRegistration", $campFullData->id) }}" rel="noopener noreferrer" class="btn btn-danger mb-3" target="_blank">新增學員</a>    &nbsp;&nbsp;
            @endif
            @if($currentUser->isAbleTo('\App\Models\ApplicantsGroup.create') || $currentUser->isAbleTo('\App\Models\ApplicantsGroup.assign'))
                <a href="?isSetting=1&batch_id={{ $currentBatch?->id ?? "" }}" class="btn btn-danger mb-3">設定組別</a>
            @endif     &nbsp;&nbsp;
            @if($user->isAbleTo("\App\Models\CarerApplicantXref.create") || $user->isAbleTo("\App\Models\CarerApplicantXref.assign"))
                <a href="?isSettingCarer=1&batch_id={{ request()->batch_id }}" class="btn btn-danger mb-3">設定關懷員</a>
            @endif
        @endif
        @if($isShowVolunteers)
            <a href="{{ route("showRegistration", $campFullData->vcamp->id) }}" rel="noopener noreferrer" class="btn btn-danger mb-3" target="_blank">新增義工</a>
            @if($currentUser->isAbleTo('\App\Models\OrgUser.create') || $currentUser->isAbleTo('\App\Models\OrgUser.assign'))
                <a href="?isSetting=1&batch_id={{ $currentBatch?->id ?? "" }}" class="btn btn-danger mb-3">設定組別/職務</a>
            @endif
        @endif
    </p>
</div>
