<div class="mt-2">
    @if ($queryStr ?? false) 查詢條件：{{ $queryStr }} @endif
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
                    @if(!$isVcamp && !$isCare && $key == "contactlog")
                    @else
                        <th class="text-center" data-field="{{ $key }}" data-sortable="{{ $item['sort'] }}">{{ $item['name'] }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        @forelse ($onlyRegisteredVolunteers as &$applicant)
            <tr>
                @if($isSetting ?? false)
                    <td class="text-center">
                        <input type="checkbox" name="applicants[]" class="applicants_selector" value="{{ $applicant->sn }}"  id="U{{ $applicant->id }}" onclick="applicant_triggered(this.id)">
                    </td>
                @endif
                @foreach ($columns as $key => $item)
                    @php
                        $applicant->age = $applicant->age;
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
                    @elseif($isVcamp && $key == "group")
                        <td>@foreach($applicant->roles as $role) {{ $role->section }}<br> @endforeach</td>
                    @elseif($isVcamp && $key == "position")
                        <td>@foreach($applicant->roles as $role) {{ $role->position }}<br> @endforeach</td>
                    @elseif(!$isVcamp && !$isCare && $key == "contactlog")
                    @elseif($key == "is_attend")
                        @if($applicant->$key == 1)
                            <td>是</td>
                        @elseif($applicant->$key === 0)
                            <td>否</td>
                        @else
                            <td>未定</td>
                        @endif
                    @elseif($key == "reasons_recommend")
                        <td>
                            {{ Str::limit($applicant->$key, 100,'...') ?? "-" }}
                        </td>
                    @elseif($key == "contactlog")
                        <td>
                            {{ Str::limit($applicant->contactlog?->sortByDesc('id')->first()?->notes, 50,'...') ?? "-" }}
                            <div>
                                <a href="{{ route('showAttendeeInfoGET', $campFullData->id) }}?snORadmittedSN={{ $applicant->id }}#new" target="_blank">⊕新增關懷紀錄</a>
                                @if(count($applicant->contactlog) && !$isSetting)
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
        @endforelse
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
                        $applicant->job = $applicant->groupOrgRelation?->position;
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
                    @elseif($isVcamp && $key == "group" && isset($applicant->groupOrgRelation->section))
                        <td>{{ $applicant->groupOrgRelation?->section }}</td>
                    @elseif(!$isVcamp && !$isCare && $key == "contactlog")
                    @elseif($key == "is_attend")
                        @if($applicant->$key == 1)
                            <td>是</td>
                        @elseif($applicant->$key === 0)
                            <td>否</td>
                        @else
                            <td>未定</td>
                        @endif
                    @elseif($key == "reasons_recommend")
                        <td>
                            {{ Str::limit($applicant->$key, 100,'...') ?? "-" }}
                        </td>
                    @elseif($key == "contactlog")
                        <td>
                            {{ Str::limit($applicant->contactlog?->sortByDesc('id')->first()?->notes, 50,'...') ?? "-" }}
                            <div>
                                <a href="{{ route('showAttendeeInfoGET', $campFullData->id) }}?snORadmittedSN={{ $applicant->id }}#new" target="_blank">⊕新增關懷紀錄</a>
                                @if(count($applicant->contactlog) && !$isSetting)
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
        @endforelse
    </table>
</div>

<script>
    window.applicant_ids = [];
    window.csrf_token = "{{ csrf_token() }}";
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
