<div>
    <!-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead -->
    <p align="right">
        <a href="{{ route("showLearners", ($isVcamp ?? false) ? $campFullData->vcamp->id : $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">匯出資料</a>
        @if(!$isVcamp)            &nbsp;&nbsp;
            <a href="{{ route("showRegistration", $campFullData->id) }}" rel="noopener noreferrer" class="btn btn-danger mb-3" target="_blank">新增學員</a>
            @if($isShowLearners)
                &nbsp;&nbsp;
                <a href="?isSetting=1&batch_id={{ $currentBatch?->id ?? "" }}" class="btn btn-danger mb-3">設定組別</a>
            @endif
            @if(!$isShowLearners)                &nbsp;&nbsp;
                <a href="?isSettingCarer=1" class="btn btn-danger mb-3">設定關懷員</a>
            @endif
        @elseif($isVcamp)
            @if(!$isShowLearners)
                &nbsp;&nbsp;
                <a href="{{ route("showRegistration", $campFullData->vcamp->id) }}" rel="noopener noreferrer" class="btn btn-danger mb-3" target="_blank">新增義工</a>
                &nbsp;&nbsp;
                <a href="?isSetting=1&batch_id={{ $currentBatch?->id ?? "" }}" class="btn btn-danger mb-3">設定組別/職務</a>
            @endif
        @endif
    </p>
</div>
