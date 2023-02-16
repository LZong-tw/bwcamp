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
                @if(($isSetting ?? false) || ($isSettingCarer ?? false))
                    <th></th>
                @endif
                @foreach ($columns as $key => $item)
                    @if($isSettingCarer && ($key == 'mobile' || $key == 'email' || $key == 'zipcode' || $key == 'address' || $key == 'birthdate' || $key == 'after_camp_available_day' || $key == 'region'))
                    @elseif($isSettingCarer && ($key == 'participation_mode'))
                        <th class="text-center" data-field="is_attend" data-sortable="1">參加意願</th>
                        <th class="text-center" data-field="participation_mode" data-sortable="1">參加形式</th>
                    @elseif($key == "industry" && $isSettingCarer)
                        <th class="text-center" data-field="introducer" data-sortable="0">推薦人</th>
                        <th class="text-center" data-field="carer" data-sortable="1">關懷員</th>
                    @elseif($key == "carer" && $isSettingCarer)
                        @continue
                    @elseif(!$isVcamp && !$isCare && $key == "contactlog")
                    @else
                        <th class="text-center" data-field="{{ $key }}" data-sortable="{{ $item['sort'] }}">{{ $item['name'] }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        @forelse ($registeredVolunteers as $user)
            @forelse($user->application_log as &$applicant)
                <tr @if($applicant->deleted_at) style="color: gray!important" @endif>
                    @if($isSetting ?? false)
                        <td class="text-center">
                            <input type="checkbox" name="applicants[]" class="applicants_selector" value="{{ $user->id }}"  id="U{{ $user->id }}" onclick="applicant_triggered(this.id)">
                        </td>
                    @endif
                    @foreach ($columns as $key => $item)
                        @php
                            if(!$applicant->$key) {
                                // $theClass = "\\App\\Models\\" . ucfirst($campFullData->table);
                                $applicantCampData = \App\Models\Ceovcamp::where('applicant_id', $applicant->id)->first();
                                $applicant->$key = $applicantCampData->$key;
                            }
                        @endphp
                        @if($key == "avatar" && $applicant->avatar)
                            <td><img src="data:image/png;base64, {{ base64_encode(\Storage::disk('local')->get($user->application_log->first()->avatar)) }}" width=80 alt="{{ $applicant->name }}"></td>
                        @elseif($key == "name")
                            <td>
                                <a href="{{ route('showAttendeeInfoGET', ($isVcamp ?? false) ? $campFullData->vcamp->id : $campFullData->id) }}?snORadmittedSN={{ $applicant->id }}" target="_blank">{{ $applicant->name }}</a>
                                <div class="text-success">連結之帳號：{{ $applicant->user->name }}({{ $applicant->user->email }})</div>
                            </td>
                        @elseif($key == "avatar" && !$applicant->avatar)
                            <td>no photo</td>
                        @elseif($key == "gender")
                            <td>{{ $applicant->gender_zh_tw }}</td>
                        @elseif($isVcamp && $key == "roles")
                            <td>@foreach($applicant->user->roles as $role) {{ $role->section }}<br> @endforeach</td>
                        @elseif($isVcamp && $key == "group")
                            <td>@foreach($applicant->user->roles as $role) {{ $role->section }}<br> @endforeach</td>
                        @elseif($isVcamp && $key == "position")
                            <td>@foreach($applicant->user->roles as $role) {{ $role->position }}<br> @endforeach</td>
                        @elseif(!$isVcamp && !$isCare && $key == "contactlog")
                        @elseif($key == "is_attend")
                            @if($applicant->$key == 1)
                                <td>參加</td>
                            @elseif($applicant->$key === 0)
                                <td>不參加</td>
                            @elseif($applicant->$key === 2)
                                <td>已聯絡未回應</td>
                            @else
                                <td>未回覆</td>
                            @endif
                        @elseif($key == "reasons_recommend")
                            <td>
                                {{ Str::limit($applicant->$key, 100,'...') ?? "-" }}
                            </td>
                        @elseif($key == "contactlog" && !$isVcamp)
                            <td>
                                {{ Str::limit($applicant->contactlog?->sortByDesc('id')->first()?->notes, 50,'...') ?? "-" }}
                                <div>
                                    <a href="{{ route('showAttendeeInfoGET', ($isVcamp ?? false) ? $campFullData->vcamp->id : $campFullData->id) }}?snORadmittedSN={{ $applicant->id }}#new" target="_blank">⊕新增關懷紀錄</a>
                                    @if(count($applicant->contactlog))
                                        &nbsp;&nbsp;
                                        <a href="{{ route('showContactLogs', [$campFullData->id, $applicant->id]) }}" target="_blank">🔍看更多</a>
                                    @endif
                                </div>
                            </td>
                        @elseif($key == "batch")
                            <td>{{ $applicant->batch?->name ?? "-" }}</td>
                        @else
                            <td>{{ $applicant->$key ?? "-" }}</td>
                        @endif
                    @endforeach
                </tr>
            @empty
            @endforelse
        @empty
        @endforelse
        @forelse ($applicants as &$applicant)
            <tr>
                @if(($isSetting ?? false) || ($isSettingCarer ?? false))
                    <td class="text-center">
                        <input type="checkbox" name="applicants[]" class="applicants_selector" value="{{ $applicant->sn }}"  id="A{{ $applicant->sn }}" onclick="applicant_triggered(this.id)">
                    </td>
                @endif
                @foreach ($columns as $key => $item)
                    @php
                        $applicant->age = $applicant->age;
                        $applicant->group = $applicant->groupRelation?->alias;
                        $applicant->job = $applicant->groupOrgRelation?->position;
                    @endphp
                    @if($isSettingCarer && ($key == 'mobile' || $key == 'email' || $key == 'zipcode' || $key == 'address' || $key == 'birthdate' || $key == 'after_camp_available_day' || $key == 'region'))
                        @continue
                    @elseif($key == "industry" && $isSettingCarer)
                        <td>{{ $applicant->introducer ?? "-" }}</td>
                        <td>@forelse($applicant->carers as $carer)
                                {{ $carer->name }}
                                @if(!$loop->last) <br> @endif
                            @empty
                                {{ '-' }}
                            @endforelse</td>
                    @elseif($key == "avatar" && $applicant->avatar)
                        <td><img src="data:image/png;base64, {{ base64_encode(\Storage::disk('local')->get($applicant->avatar)) }}" width=80 alt="{{ $applicant->name }}"></td>
                    @elseif($key == "name")
                        <td>
                            <a href="{{ route('showAttendeeInfoGET', ($isVcamp ?? false) ? $campFullData->vcamp->id : $campFullData->id) }}?snORadmittedSN={{ $applicant->applicant_id }}" target="_blank">{{ $applicant->name }}</a>
                            @if($applicant->user)
                                <div class="text-success">連結之帳號：{{ $applicant->user->name }}({{ $applicant->user->email }})</div>
                            @endif
                        </td>
                    @elseif($key == "avatar" && !$applicant->avatar)
                        <td>no photo</td>
                    @elseif($key == "gender")
                        <td>{{ $applicant->gender_zh_tw }}</td>
                    @elseif($key == "batch")
                        <td>{{ $applicant->batch->name }}</td>
                    @elseif($isVcamp && $key == "group" && isset($applicant->groupOrgRelation->section))
                        <td>{{ $applicant->groupOrgRelation?->section }}</td>
                    @elseif(!$isVcamp && !$isCare && $key == "contactlog")
                    @elseif($isSettingCarer && ($key == 'participation_mode'))
                        <td>{{ $applicant->is_attend ?? "-" }}</td>
                        <td>{{ $applicant->participation_mode ?? "-" }}</td>
                    @elseif($key == "is_attend")
                        @if($applicant->$key == 1)
                            <td>參加</td>
                        @elseif($applicant->$key === 0)
                            <td>不參加</td>
                        @elseif($applicant->$key === 2)
                            <td>已聯絡未回應</td>
                        @else
                            <td>未回覆</td>
                        @endif
                    @elseif($key == "reasons_recommend")
                        <td>
                            {{ Str::limit($applicant->$key, 100,'...') ?? "-" }}
                        </td>
                    @elseif($key == "carer" && $isSettingCarer)
                        @continue
                    @elseif($key == "contactlog" && !$isVcamp)
                        <td>
                            {{ Str::limit($applicant->contactlog?->sortByDesc('id')->first()?->notes, 50,'...') ?? "-" }}
                            <div>
                                <a href="{{ route('showAttendeeInfoGET', ($isVcamp ?? false) ? $campFullData->vcamp->id : $campFullData->id) }}?snORadmittedSN={{ $applicant->applicant_id }}#new" target="_blank">⊕新增關懷紀錄</a>
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
        @endforelse
    </table>
</div>

<script>
    window.applicant_ids = [];
    window.csrf_token = "{{ csrf_token() }}";
    window.columns = @json($columns);
    window.theData = @json($applicants);
    @if($registeredVolunteers ?? false)
        @foreach($registeredVolunteers as &$v)
            @php
                $v->roles = $v->roles;
            @endphp
        @endforeach
        window.theVolunteersData = @json($registeredVolunteers);
    @endif
    window.is_care = {{ $isCare ? 1 : 0 }};
    window.isShowVolunteers = {{ $isVcamp ? 1 : 0 }};
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
