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
                    @elseif(!$isVcamp && !$isCare && $key == "contactlog")
                    @else
                        <th class="text-center" data-field="{{ $key }}" data-sortable="{{ $item['sort'] }}">{{ $item['name'] }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        @forelse ($registeredVolunteers as &$user)
            <tr>
                @if($isSetting ?? false)
                    <td class="text-center">
                        <input type="checkbox" name="applicants[]" class="applicants_selector" value="{{ $user->application_log->first()->sn }}"  id="U{{ $user->application_log->first()->id }}" onclick="applicant_triggered(this.id)">
                    </td>
                @endif
                @foreach ($columns as $key => $item)
                    @php
                        $user->application_log->first()->age = $user->application_log->first()?->age;
                        if(!$user->application_log->first()->$key) {
                            // $theClass = "\\App\\Models\\" . ucfirst($campFullData->table);
                            $applicantCampData = \App\Models\Ceovcamp::where('applicant_id', $user->application_log->first()->id)->first();
                            $user->application_log->first()->$key = $applicantCampData->$key;
                        }
                    @endphp
                    @if($key == "avatar" && $user->application_log->first()->avatar)
                        <td><img src="data:image/png;base64, {{ base64_encode(\Storage::disk('local')->get($user->application_log->first()->avatar)) }}" width=80 alt="{{ $user->application_log->first()->name }}"></td>
                    @elseif($key == "name")
                        <td>
                            <a href="{{ route('showAttendeeInfoGET', ($isVcamp ?? false) ? $campFullData->vcamp->id : $campFullData->id) }}?snORadmittedSN={{ $user->application_log->first()->applicant_id }}" target="_blank">{{ $user->application_log->first()->name }}</a>
                            <div class="text-success">連結之帳號：{{ $user->name }}({{ $user->email }})</div>
                        </td>
                    @elseif($key == "avatar" && !$user->application_log->first()->avatar)
                        <td>no photo</td>
                    @elseif($key == "gender")
                        <td>{{ $user->application_log->first()->gender_zh_tw }}</td>
                    @elseif($isVcamp && $key == "roles")
                        <td>@foreach($user->roles as $role) {{ $role->section }}<br> @endforeach</td>
                    @elseif($isVcamp && $key == "group")
                        <td>@foreach($user->roles as $role) {{ $role->section }}<br> @endforeach</td>
                    @elseif($isVcamp && $key == "position")
                        <td>@foreach($user->roles as $role) {{ $role->position }}<br> @endforeach</td>
                    @elseif(!$isVcamp && !$isCare && $key == "contactlog")
                    @elseif($key == "is_attend")
                        @if($user->application_log->first()->$key == 1)
                            <td>是</td>
                        @elseif($user->application_log->first()->$key === 0)
                            <td>否</td>
                        @else
                            <td>未定</td>
                        @endif
                    @elseif($key == "reasons_recommend")
                        <td>
                            {{ Str::limit($user->application_log->first()->$key, 100,'...') ?? "-" }}
                        </td>
                    @elseif($key == "contactlog" && !$isVcamp)
                        <td>
                            {{ Str::limit($user->application_log->first()->contactlog?->sortByDesc('id')->first()?->notes, 50,'...') ?? "-" }}
                            <div>
                                <a href="{{ route('showAttendeeInfoGET', ($isVcamp ?? false) ? $campFullData->vcamp->id : $campFullData->id) }}?snORadmittedSN={{ $user->application_log->first()->id }}#new" target="_blank">⊕新增關懷紀錄</a>
                                @if(count($user->application_log->first()->contactlog))
                                    &nbsp;&nbsp;
                                    <a href="{{ route('showContactLogs', [$campFullData->id, $user->application_log->first()->id]) }}" target="_blank">🔍看更多</a>
                                @endif
                            </div>
                        </td>
                    @elseif($key == "batch")
                        <td>{{ $user->application_log->first()->batch?->name ?? "-" }}</td>
                    @else
                        <td>{{ $user->application_log->first()->$key ?? "-" }}</td>
                    @endif
                @endforeach
            </tr>
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
                        <td>@forelse($applicant->carers as $carer) {{ $carer->name }} @if(!$loop->last) {{ "<br>" }} @endif @empty {{ '-' }} @endforelse</td>
                    @elseif($key == "avatar" && $applicant->avatar)
                        <td><img src="data:image/png;base64, {{ base64_encode(\Storage::disk('local')->get($applicant->avatar)) }}" width=80 alt="{{ $applicant->name }}"></td>
                    @elseif($key == "name")
                        <td>
                            <a href="{{ route('showAttendeeInfoGET', ($isVcamp ?? false) ? $campFullData->vcamp->id : $campFullData->id) }}?snORadmittedSN={{ $applicant->applicant_id }}" target="_blank">{{ $applicant->name }}</a>
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
