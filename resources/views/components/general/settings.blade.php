@if($errors->any())
    @foreach ($errors->all() as $message)
        <div class='alert alert-danger' role='alert'>
            {{ $message }}
        </div>
    @endforeach
@endif
<div>
    <!-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi -->
    <span class="text-danger font-weight-bold">
        @if($isShowVolunteers)
            <button type="submit" class="btn btn-success btn-sm" onclick="javascript:self.location='?isSetting=0&batch_id={{ request()->batch_id }}';"> << 返回義工名單</button>            &nbsp;&nbsp;
            將所選義工設定為{{ ($isShowVolunteers && $isShowLearners) ? '第' : '' }}
        @else
            <button type="submit" class="btn btn-success btn-sm" onclick="javascript:self.location='?isSetting=0&batch_id={{ request()->batch_id }}';"> << 返回學員名單</button>            &nbsp;&nbsp;
        @endif

        @if($isShowVolunteers && !$isShowLearners)
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
            <span class="filter-selects">
                <!-- Batch 選單 -->
                <select id="batch_select" onchange="filterCarers2()">
                    <option value="">- 選擇梯次 -</option>
                    @foreach($carers->pluck('groupOrgRelation.*.batch.name')->flatten()->unique() as $batch)
                        <option value="{{ $batch ?? 'all' }}">{{ $batch ?? '不分梯' }}</option>
                    @endforeach
                </select>

                <!-- Region 選單 -->
                <select id="region_select" onchange="filterCarers2()">
                    <option value="">- 選擇區域 -</option>
                    @foreach($carers->pluck('groupOrgRelation.*.region.name')->flatten()->unique() as $regionName)
                        <option value="{{ $regionName ?? 'all' }}">{{ $regionName ?? '不分區' }}</option>
                    @endforeach
                </select>

                <!-- Position 選單 -->
                <select id="position_select" onchange="filterCarers2()">
                    <option value="">- 選擇職位 -</option>
                    @foreach($carers->pluck('groupOrgRelation.*.position')->flatten()->unique() as $position)
                        <option value="{{ $position }}">{{ $position }}</option>
                    @endforeach
                </select>

                <!-- Carer 選單 -->
                <select id="carer_select" name="attendee_care">
                    <option value="">- 選擇關懷員 -</option>
                    @foreach($carers as $carer)
                        @foreach($carer->groupOrgRelation as $relation)
                            <option
                                value="{{ $carer->id }}"
                                data-batch="{{ $relation->batch?->name ?? 'all' }}"
                                data-region="{{ $relation->region?->name ?? 'all' }}"
                                data-position="{{ $relation->position }}"
                            >
                                {{ $relation->batch?->name ?? "不分梯" }}：{{ $relation->region?->name ?? "不分區" }}：{{ $carer->name }}：{{ $relation->section . $relation->position }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
            </span>
        @elseif(!$isShowVolunteers)
            將所選學員設定為第
            <select required name='attendee_group' onChange='' id="learnerGroups">
                <option value=''>- 請選擇 -</option>
            </select>
            組
        @endif
        &nbsp;&nbsp;
        @if($isShowVolunteers && !$isShowLearners)
            <button type="submit" class="btn btn-danger btn-sm" onclick="userConnection()">指派</button>
        @else
            <button type="submit" class="btn btn-danger btn-sm" onclick="@if($isSettingCarer) setCarer() @else setGroup() @endif">儲存</button>
        @endif
    </span>
</div>

<script>
    (function() {
        @if($isShowLearners)
            axios({
                method: 'get',
                url: '/semi-api/getBatchGroups',
                params: {
                    camp_id: {{ request()->route('camp_id') }},
                    batch_id: {{ request()->batch_id ?? 0 }},
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
                        option.text = groups[i][1]['name'];
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

                // 按 region 排序
                organizations.sort((a, b) => {
                    if (a && b) {
                        return a[1]['region_name'].localeCompare(b[1]['region_name']);
                    }
                    return 0;
                });

                // 去重複
                for (let i in organizations) {
                    for (let j in organizations) {
                        if (organizations[i] != null && organizations[j] != null) {
                            if (organizations[i][1]['section'] == organizations[j][1]['section'] && i < j) {
                                organizations[j] = null;
                            }
                        }
                    }
                }

                let select = document.getElementById('volunteerGroups');
                let currentRegion = null;

                for (let i = 0; i < organizations.length; i++) {
                    if (organizations[i] != null) {
                        // 如果 region 改變，插入 region 標題
                        if (currentRegion !== organizations[i][1]['region_name']) {
                            currentRegion = organizations[i][1]['region_name'];
                            let regionOption = document.createElement('option');
                            regionOption.text = `--- ${currentRegion} ---`;
                            regionOption.disabled = true;
                            select.appendChild(regionOption);
                        }

                        // 插入 option
                        let option = document.createElement('option');
                        option.value = organizations[i][1]['id'];
                        option.text = organizations[i][1]['section'];
                        select.appendChild(option);
                    }
                }
            }
        });

        @if ($isSettingCarer)
            let carers = @json($carers);
            {{-- console.log(carers); --}}
        @endif
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
        let select = document.getElementById('volunteerWorks');
        let length = select.options.length;
        for (i = length - 1; i >= 0; i--) {
            select.options[i] = null;
        }
        axios({
            method: 'get',
            url: '/semi-api/getCampPositions',
            params: {
                camp_id: {{ request()->route('camp_id') }},
                node_id: theselect.value,
                section: theselect.options[theselect.selectedIndex].text,
                no_caring_group_detail: true
            },
            responseType: 'json'
        })
        .then(function (response) {
            select.innerHTML = "";
            let optionzero = document.createElement('option');
            optionzero.value = "";
            if (Object.keys(response.data).length === 0) {
                console.log(response.data);
                 // 特殊處理
                 optionzero.text = "尚未設定任何職位"
                 select.appendChild(optionzero);
            }
            else {
                let positions = Object.entries(response.data);
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

    function filterCarers() {
        const batchSelect = document.getElementById('batch_select');
        const regionSelect = document.getElementById('region_select');
        const carerSelect = document.getElementById('carer_select');

        const selectedBatch = batchSelect.value;
        const selectedRegion = regionSelect.value;

        // 遍歷所有關懷員選項
        Array.from(carerSelect.options).forEach(option => {
            if (option.value === '') return; // 跳過預設選項

            const matchBatch = !selectedBatch || option.dataset.batch === selectedBatch;
            const matchRegion = !selectedRegion || option.dataset.region === selectedRegion;

            option.style.display = matchBatch && matchRegion ? '' : 'none';
        });
    }

    function filterCarers2() {
        const batchSelect = document.getElementById('batch_select');
        const regionSelect = document.getElementById('region_select');
        const positionSelect = document.getElementById('position_select');
        const carerSelect = document.getElementById('carer_select');

        const selectedBatch = batchSelect.value || null;
        const selectedRegion = regionSelect.value || null;
        const selectedPosition = positionSelect.value || null;

        // 遍歷所有關懷員選項
        Array.from(carerSelect.options).forEach(option => {
            if (option.value === '') return; // 跳過預設選項

            const matchBatch = selectedBatch === null || option.dataset.batch === selectedBatch;
            const matchRegion = selectedRegion === null || option.dataset.region === selectedRegion;
            const matchPosition = selectedPosition === null || option.dataset.position === selectedPosition;

            option.style.display = matchBatch && matchRegion && matchPosition  ? '' : 'none';
        });
    }
</script>
