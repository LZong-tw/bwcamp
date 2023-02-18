<div>
    <!-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi -->
    <span class="text-danger font-weight-bold">
        @if($isVcamp)
            <button type="submit" class="btn btn-success btn-sm" onclick="javascript:self.location='?isSetting=0';"> << 返回義工名單</button>            &nbsp;&nbsp;
            將所選義工設定為{{ ($isVcamp && $isCare) ? '第' : '' }}
        @else
            <button type="submit" class="btn btn-success btn-sm" onclick="javascript:self.location='?isSetting=0';"> << 返回學員名單</button>            &nbsp;&nbsp;
        @endif

        @if($isVcamp && !$isCare)
            <select required name="volunteer_group" onChange="getPosition(this)" id="volunteerGroups">
                <option value=''>- 請選擇 -</option>
            </select>
            組
            <select required name='volunteer_work' onChange='' id="volunteerWorks">
                <option value=''>- 請選擇 -</option>
            </select>
            職務
        @elseif($isSettingCarer)
            將所選學員之關懷員設定為
            <select required name='attendee_care' onChange=''>
                <option value=''>- 請選擇 -</option>
                @forelse($carers as $carer)
                    @php
                        $care = $carer->each(fn($carer) => $carer->groupOrgRelation = $carer->groupOrgRelation->where('batch_id', request()->batch_id)->where('position', 'like', '%關懷小組第%'));
                    @endphp
                    <option value='{{ $carer->id }}'>{{ $carer->groupOrgRelation?->first()->batch?->name }}：{{ $carer->name }}：{{ $carer->groupOrgRelation->first()->position }}</option>
                @empty
                    <option value="">本梯次沒有關懷員</option>
                @endforelse
            </select>
        @elseif(!$isVcamp)
            將所選學員設定為第
            <select required name='attendee_group' onChange='' id="learnerGroups">
                <option value=''>- 請選擇 -</option>
            </select>
            組
        @endif
        &nbsp;&nbsp;
        @if($isVcamp && !$isCare)
            <button type="submit" class="btn btn-danger btn-sm" onclick="userConnection()">指派</button>
        @else
            <button type="submit" class="btn btn-danger btn-sm" onclick="@if($isSettingCarer) setCarer() @else setGroup() @endif">儲存</button>
        @endif
    </span>
</div>

<script>
    (function() {
        @if($isCare)
            axios({
                method: 'get',
                url: '/semi-api/getBatchGroups',
                params: {
                    camp_id: {{ request()->route('camp_id') }},
                    batch_id: {{ request()->input('batch') ?? $batches->first()?->id ?? '' }},
                },
                responseType: 'json'
            })
            .then(function (response) {
                if (Object.keys(response.data).length === 0) {
                    console.log(response.data);
                    // 特殊處理
                }
                else {
                    let groups = Object.entries(response.data);
                    let select = document.getElementById('learnerGroups');
                    for (let i = 0; i < groups.length; i++) {
                        let option = document.createElement('option');
                        option.value = groups[i][1]['id'];
                        option.text = groups[i][1]['alias'];
                        select.appendChild(option);
                    }
                }
            });
        @endif

        axios({
            method: 'get',
            url: '/semi-api/getCampOrganizations',
            params: {
                camp_id: {{ request()->route('camp_id') }},
            },
            responseType: 'json'
        })
        .then(function (response) {
            if (Object.keys(response.data).length === 0) {
                console.log(response.data);
                 // 特殊處理
            }
            else {
                let organizations = Object.entries(response.data);
                let select = document.getElementById('volunteerGroups');
                for (let i = 0; i < organizations.length; i++) {
                    let option = document.createElement('option');
                    option.value = organizations[i][1]['id'];
                    option.text = organizations[i][1]['section'];
                    select.appendChild(option);
                }
            }
        });
    })();

    function setGroup() {
        if(document.getElementsByName('attendee_group')[0].value) {
            if(window.applicant_ids.length > 0) {
                axios({
                    method: 'post',
                    url: '/semi-api/setGroup',
                    data: {
                        applicant_ids: window.applicant_ids,
                        group_id: document.getElementsByName('attendee_group')[0].value
                    },
                    responseType: 'json'
                })
                .then(function (response) {
                    if (response.data.status === 'success') {
                        window.location.reload();
                    }
                    else {
                        console.log(response.data);
                    }
                });
            } else {
                alert('請勾選至少一位學員');
            }
        } else {
            alert('請選擇組別');
        }
    }

    function setGroupOrg() {
        axios({
            method: 'post',
            url: '/semi-api/setGroupOrg',
            data: {
                applicant_ids: window.applicant_ids,
                group_id: document.getElementsByName('volunteer_work')[0].value
            },
            responseType: 'json'
        })
        .then(function (response) {
            if (response.data.status === 'success') {
                window.location.reload();
            }
            else {
                console.log(response.data);
            }
        });
    }

    function userConnection() {
        let applicants_ids = window.applicant_ids;
        let applicants_ids_str = '';
        for (let i = 0; i < applicants_ids.length; i++) {
            applicants_ids_str += "applicant_ids[]=" + applicants_ids[i] + '&';
        }
        window.location = 'volunteer/userConnection?' + applicants_ids_str + 'group_id=' + document.getElementsByName('volunteer_work')[0].value;
    }

    function getPosition(theselect) {
        axios({
            method: 'get',
            url: '/semi-api/getCampPositions',
            params: {
                camp_id: {{ request()->route('camp_id') }},
                section: theselect.options[theselect.selectedIndex].text,
                no_caring_group_detail: true
            },
            responseType: 'json'
        })
        .then(function (response) {
            if (Object.keys(response.data).length === 0) {
                console.log(response.data);
                 // 特殊處理
            }
            else {
                let positions = Object.entries(response.data);
                let select = document.getElementById('volunteerWorks');

                select.innerHTML = "";
                let optionzero = document.createElement('option');
                optionzero.value = "";
                optionzero.text = "- 請選擇 -";
                select.appendChild(optionzero);
                for (let i = 0; i < positions.length; i++) {
                    let option = document.createElement('option');
                    option.value = positions[i][1]['id'];
                    if (positions[i][1]['batch_name']) {
                        option.text = positions[i][1]['batch_name'] + "：" + positions[i][1]['position'];
                    }
                    else {
                        option.text = positions[i][1]['position'];
                    }
                    select.appendChild(option);
                }
            }
        });
    }

    function setCarer() {
        if(document.getElementsByName('attendee_care')[0].value) {
            if(window.applicant_ids.length > 0) {
                axios({
                    method: 'post',
                    url: '/semi-api/setCarer',
                    data: {
                        applicant_ids: window.applicant_ids,
                        carer_id: document.getElementsByName('attendee_care')[0].value
                        {{--is_remove--}}
                    },
                    responseType: 'json'
                })
                    .then(function (response) {
                        if (response.data.status === 'success') {
                            window.location.reload();
                        }
                        else {
                            console.log(response.data);
                        }
                    });
            } else {
                alert('請勾選至少一位學員');
            }
        } else {
            alert('請選擇關懷員');
        }
    }
</script>
