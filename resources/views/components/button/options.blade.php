<div>
    <!-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead -->
    <p align="right" style="float: right;">
        <a href="{{ route("showLearners", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">匯出資料</a>
        @if($isIngroup)
            @if(!$isCareV)
                &nbsp;&nbsp;
                <a href="?isSetting=1" class="btn btn-danger mb-3">設定關懷員</a>
            @endif
        @elseif(!$isVcamp)
            &nbsp;&nbsp;
            <a href="{{ route("showLearners", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">新增學員</a>
            @if($isCare)
                &nbsp;&nbsp;
                <a href="?isSetting=1" class="btn btn-danger mb-3">設定組別</a>
            @endif
        @elseif($isVcamp)
            @if(!$isCare)
                &nbsp;&nbsp;
                <a href="{{ route("showAttendeePhoto", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">新增義工</a>
            @endif
            &nbsp;&nbsp;
            <a href="?isSetting=1" class="btn btn-danger mb-3">設定組別/職務</a>
        @endif
    </p>
</div>
