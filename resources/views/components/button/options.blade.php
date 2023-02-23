<div>
    <!-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead -->
    <p align="right">
        <a href="{{ route("showLearners", ($isShowVolunteers ?? false) ? $campFullData->vcamp->id : $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">匯出資料</a>
        @if($isShowLearners)            &nbsp;&nbsp;
            <a href="{{ route("showRegistration", $campFullData->id) }}" rel="noopener noreferrer" class="btn btn-danger mb-3" target="_blank">新增學員</a>    &nbsp;&nbsp;
            <a href="?isSetting=1&batch_id={{ $currentBatch?->id ?? "" }}" class="btn btn-danger mb-3">設定組別</a>         &nbsp;&nbsp;
            @if($user->isAbleTo("\App\Models\CarerApplicantXref.create") || $user->isAbleTo("\App\Models\CarerApplicantXref.assign"))
                <a href="?isSettingCarer=1&batch_id={{ request()->batch_id }}" class="btn btn-danger mb-3">設定關懷員</a>
            @endif
        @endif
        @if($isShowVolunteers)
            <a href="{{ route("showRegistration", $campFullData->vcamp->id) }}" rel="noopener noreferrer" class="btn btn-danger mb-3" target="_blank">新增義工</a>
            <a href="?isSetting=1&batch_id={{ $currentBatch?->id ?? "" }}" class="btn btn-danger mb-3">設定組別/職務</a>
        @endif
    </p>
</div>
