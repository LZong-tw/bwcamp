<div class="mt-2">
    <!-- Nothing worth having comes easy. - Theodore Roosevelt -->
    <table class="table table-bordered table-hover"
        data-toggle="table"
        data-show-columns="true"
        data-show-columns-search="true"
        data-search="true"
        data-search-highlight="true"
        data-search-align="left"
        data-pagination="true"
        data-smart-display="false"
        data-pagination-loop="false"
        data-pagination-v-align="both"
        data-page-list="[10, 50, 100]"
        data-pagination-pre-text="上一頁"
        data-pagination-next-text="下一頁">
        <caption></caption>
        <thead>
            <tr class="bg-success text-white">
                @if($isSetting ?? false)
                    <th></th>
                @endif
                @foreach ($columns as $key => $item)
                    @if(!$isVcamp && !$isCare && $key == "caring_logs")
                    @else
                        <th class="text-center" data-field="{{ $key }}" data-sortable="{{ $item['sort'] }}">{{ $item['name'] }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        @forelse ($applicants as &$applicant)
            <tr>
                @if($isSetting ?? false)
                    <td class="text-center">
                        <input type="checkbox" name="applicants[]" class="applicants_selector" value="{{ $applicant->sn }}"  id="{{ $applicant->sn }}" onclick="applicant_triggered(this.id)">
                    </td>
                @endif
                @foreach ($columns as $key => $item)
                    @php
                        $applicant->age = $applicant->age;
                        $applicant->group = $applicant->groupRelation?->alias;
                    @endphp
                    @if($key == "avatar" && $applicant->avatar)
                        <td><img src="data:image/png;base64, {{ base64_encode(\Storage::disk('local')->get($applicant->avatar)) }}" width=80 alt="{{ $applicant->name }}"></td>
                    @elseif($key == "name")
                        <td>
                            <a href="{{ route('showAttendeeInfoGET', $campFullData->id) }}?snORadmittedSN={{ $applicant->applicant_id }}" target="_blank">{{ $applicant->name }}</a>
                        </td>
                    @elseif($key == "avatar" && !$applicant->avatar)
                        <td>no photo</td>
                    @elseif($key == "gender")
                        <td>{{ $applicant->gender_zh_tw }}</td>
                    @elseif(!$isVcamp && !$isCare && $key == "caring_logs")
                    @elseif($key == "reasons_recommend")
                        <td>
                            {{ Str::limit($applicant->$key, 100,'...') ?? "-" }}
                        </td>
                    @elseif(!$isSetting && $key == "contactlog")
                        <td>
                            {{ Str::limit($applicant->contactlog?->sortByDesc('id')->first()?->notes, 50,'...') ?? "-" }}
                            <div>
                                <a href="{{ route('showAttendeeInfoGET', $campFullData->id) }}?snORadmittedSN={{ $applicant->id }}#new" target="_blank">⊕新增關懷紀錄</a>
                                @if(count($applicant->contactlog))
                                    &nbsp;&nbsp;
                                    <a href="{{ route('showContactLogs', [$campFullData->id, $applicant->id]) }}" target="_blank">🔍看更多</a>
                                @endif
                            </div>
                        </td>
                    @else
                        <td>{{ $applicant->$key ?? "-" }}</td>
                    @endif
                @endforeach
            </tr>
        @empty
            查詢的條件沒有資料
        @endforelse
    </table>
</div>

<script>
    window.applicant_ids = [];
    window.columns = @json($columns);
    window.theData = @json($applicants);
    (function() {
    })();

    function applicant_triggered(id) {
        if ($("#" + id).is(":checked")) {
            window.applicant_ids.push(id);
        } else {
            window.applicant_ids = window.applicant_ids.filter(function(value, index, arr){
                return value != id;
            });
        }
    }
</script>
