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
{{--        data-show-export="true"--}}
{{--        data-export-data-type="all"--}}
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
                    @elseif(!$isShowVolunteers && !$isShowLearners && $key == "contactlog")
                    @else
                        <th class="text-center" data-field="{{ $key }}" data-sortable="{{ $item['sort'] }}">{{ $item['name'] }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        @forelse ($registeredVolunteers as &$user)
            @forelse($user->application_log as &$applicant)
                <tr @if($applicant->deleted_at) style="color: rgba(120, 120, 120, 0.4)!important" @endif>
                    @if($isSetting ?? false)
                        <td class="text-center">
                            <input type="checkbox" name="applicants[]" class="applicants_selector" value="{{ $user->id }}"  id="U{{ $user->id }}" onclick="applicant_triggered(this.id)">
                        </td>
                    @endif
                    @foreach ($columns as $key => $item)
                        @php
                            if(!$applicant->$key) {
                                $theClass = "\\App\\Models\\" . ucfirst($campFullData->vcamp->table);
                                $applicantCampData = $theClass::where('applicant_id', $applicant->id)->first();
                                $applicant->$key = $applicantCampData->$key;
                                if ($key == "group_priority") {
                                    $applicant->group_priority1 = $applicantCampData->group_priority1;
                                    $applicant->group_priority2 = $applicantCampData->group_priority2;
                                    $applicant->group_priority3 = $applicantCampData->group_priority3;
                                }
                            }
                        @endphp
                        @if($key == "avatar" && $applicant->avatar)
                            <td><img src="data:image/png;base64, {{ base64_encode(\Storage::disk('local')->get($applicant->avatar)) }}" width=80 alt="{{ $applicant->name }}"></td>
                        @elseif($key == "name")
                            <td>
                                <a href="{{ route('showAttendeeInfoGET', ($isShowVolunteers ?? false) ? $campFullData->vcamp->id : $campFullData->id) }}?snORadmittedSN={{ $applicant->id }}" target="_blank">{{ $applicant->name }}</a>
                                <div class="text-success">連結之帳號：{{ $applicant->user->name }}({{ $applicant->user->email }})</div>
                            </td>
                        @elseif($key == "avatar" && !$applicant->avatar)
                            <td>no photo</td>
                        @elseif($key == "gender")
                            <td>{{ $applicant->gender_zh_tw }}</td>
                        @elseif($isShowVolunteers && $key == "roles")
                            <td>@foreach($applicant->user->roles as $role) {{ $role->batch?->name }} {{ $role->section }}<br> @endforeach</td>
                        @elseif($isShowVolunteers && $key == "group")
                            <td>@foreach($applicant->user->roles as $role) {{ $role->batch?->name }} {{ $role->section }}<br> @endforeach</td>
                        @elseif($isShowVolunteers && $key == "position")
                            <td>@foreach($applicant->user->roles as $role) {{ $role->position }}<br> @endforeach</td>
                        @elseif(!$isShowVolunteers && !$isShowLearners && $key == "contactlog")
                        @elseif($key == "is_attend")
                            @if($applicant->$key == 1)
                                <td>參加</td>
                            @elseif($applicant->$key === 0)
                                <td>不參加</td>
                            @elseif($applicant->$key === 2)
                                <td>尚未決定</td>
                            @elseif($applicant->$key === 3)
                                <td>聯絡不上</td>
                            @elseif($applicant->$key === 4)
                                <td>無法全程</td>
                            @else
                                <td>尚未聯絡</td>
                            @endif
                        @elseif($key == "group_priority")
                            <td>
                                @if ($applicant->group_priority1)
                                    1.&nbsp;{{ $applicant->group_priority1 }} <br>
                                @endif
                                @if ($applicant->group_priority2)
                                    2.&nbsp;{{ $applicant->group_priority2 }} <br>
                                @endif
                                @if ($applicant->group_priority3)
                                    3.&nbsp;{{ $applicant->group_priority3 }}
                                @endif
                                @if (!$applicant->group_priority1 && !$applicant->group_priority2 && !$applicant->group_priority3)
                                    未填寫
                                @endif
                            </td>
                        @elseif($key == "reasons_recommend")
                            <td>
                                {{ Str::limit($applicant->$key, 100,'...') ?? "-" }}
                            </td>
                        @elseif($key == "contactlog" && !$isShowVolunteers)
                            <td>
                                {{ Str::limit($applicant->contactlog?->sortByDesc('id')->first()?->notes, 50,'...') ?? "-" }}
                                <div>
                                    <a href="{{ route('showAttendeeInfoGET', ($isShowVolunteers ?? false) ? $campFullData->vcamp->id : $campFullData->id) }}?snORadmittedSN={{ $applicant->id }}#new" target="_blank">⊕新增關懷紀錄</a>
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
            <tr @if($applicant->deleted_at) style="color: rgba(120, 120, 120, 0.4)!important" @endif>
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
                            <a href="{{ route('showAttendeeInfoGET', ($isShowVolunteers ?? false) ? $campFullData->vcamp->id : $campFullData->id) }}?snORadmittedSN={{ $applicant->applicant_id }}" target="_blank">{{ $applicant->name }}</a>
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
                    @elseif($isShowVolunteers && $key == "group" && isset($applicant->groupOrgRelation->section))
                        <td>{{ $applicant->groupOrgRelation?->section }}</td>
                    @elseif(!$isShowVolunteers && !$isShowLearners && $key == "contactlog")
                    @elseif($isSettingCarer && ($key == 'participation_mode'))
                        <td>
                            @if($applicant->is_attend == 1)
                                參加
                            @elseif($applicant->is_attend === 0)
                                不參加
                            @elseif($applicant->is_attend === 2)
                                已聯絡未回應
                            @else
                                未回覆
                            @endif
                        </td>
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
                    @elseif($key == "group_priority")
                        <td>
                            @if ($applicant->group_priority1)
                                1.&nbsp;{{ $applicant->group_priority1 }} <br>
                            @endif
                            @if ($applicant->group_priority2)
                                2.&nbsp;{{ $applicant->group_priority2 }} <br>
                            @endif
                            @if ($applicant->group_priority3)
                                3.&nbsp;{{ $applicant->group_priority3 }}
                            @endif
                            @if (!$applicant->group_priority1 && !$applicant->group_priority2 && !$applicant->group_priority3)
                                未填寫
                            @endif
                        </td>
                    @elseif($key == "reasons_recommend")
                        <td>
                            {{ Str::limit($applicant->$key, 100,'...') ?? "-" }}
                        </td>
                    @elseif($key == "carer" && $isSettingCarer)
                        @continue
                    @elseif($key == "contactlog" && !$isShowVolunteers)
                        <td>
                            {{ Str::limit($applicant->contactlog?->sortByDesc('id')->first()?->notes, 50,'...') ?? "-" }}
                            <div>
                                <a href="{{ route('showAttendeeInfoGET', ($isShowVolunteers ?? false) ? $campFullData->vcamp->id : $campFullData->id) }}?snORadmittedSN={{ $applicant->applicant_id }}#new" target="_blank">⊕新增關懷紀錄</a>
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
    @if($registeredVolunteers ?? false)
        window.theVolunteersData = @json($registeredVolunteers);
        @php
            $users_applicants = [];
            foreach ($registeredVolunteers as &$v) {
                if ($v->application_log) {
                    foreach ($v->application_log as $a) {
                        $users_applicants[] = $a;
                    }
                }
            }
            $applicants = collect($users_applicants)->merge($applicants);
        @endphp
    @endif
    window.theData = @json($applicants);
    window.isShowLearners = {{ $isShowLearners ? 1 : 0 }};
    window.isShowVolunteers = {{ $isShowVolunteers ? 1 : 0 }};
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
