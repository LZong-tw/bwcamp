<div>
    <!-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead -->
    <p align="right">
        <a href="{{ route("showAttendeePhoto", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">匯出資料</a>
        @if($is_ingroup)
            @if(!$is_careV)
                &nbsp;&nbsp;
                <a href="{{ route("showAttendeePhoto", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">設定關懷員</a>
            @endif
        @elseif(!$is_vcamp)
            &nbsp;&nbsp;
            <a href="{{ route("showAttendeePhoto", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">新增學員</a>
            @if($is_care)
                &nbsp;&nbsp;
                <a href="{{ route("showAttendeePhoto", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">設定組別</a>
            @endif
        @elseif($is_vcamp)
            @if(!$is_care)
                &nbsp;&nbsp;
                <a href="{{ route("showAttendeePhoto", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">新增義工</a>
            @endif
            &nbsp;&nbsp;
            <a href="{{ route("showAttendeePhoto", $campFullData->id) }}?download=1" target="_blank" rel="noopener noreferrer" class="btn btn-danger mb-3">設定組別/職務</a>
        @endif
    </p>
</div>
