<div class="mt-2">
    @if ($queryStr ?? false) 查詢條件：{{ $queryStr }} @endif
    <!-- Nothing worth having comes easy. - Theodore Roosevelt -->
    <div class="wrapper1">
        <div class="div1">
        </div>
    </div>
    <div class="text-danger mt-3">
        已取消報名： {{ $applicants->whereNotNull("deleted_at")->count() }} 人
    </div>
    <table class="table table-bordered table-hover"
{{--        style="overflow-x: auto;"--}}
        id="applicantTable"
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
        <thead id="applicantTableHead">
            <tr class="bg-success text-white">
                @if(($isSetting ?? false) || ($isSettingCarer ?? false))
                    <th class="text-center" data-field="checkfield"></th>
                @endif
                @foreach ($columns ?? [] as $key => $item)
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
                    @elseif($key == "contactLog" && $currentUser->canAccessResource(new App\Models\ContactLog(), 'read', $campFullData))
                        <th class="text-center" data-field="contactLog" data-sortable="0">關懷記錄</th>
                    @else
                        <th class="text-center" data-field="{{ $key }}" data-sortable="{{ $item['sort'] }}">{{ $item['name'] }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
    </table>
</div>

<script>
    window.applicant_ids = [];
    window.csrf_token = "{{ csrf_token() }}";
    window.columns = @json($columns);
    @php
        $camp = $campFullData;
        $applicants = $applicants->load('carers');
        $applicants = $applicants->load('contactlog');
        $applicants = $applicants->each(function ($applicant) use ($camp) {
            $applicant->gender = $applicant->gender_zh_tw;
            $applicant->age = $applicant->age;
            match ($applicant->is_attend) {
                0 => $applicant->is_attend = "不參加",
                1 => $applicant->is_attend = "參加",
                2 => $applicant->is_attend = "尚未決定",
                3 => $applicant->is_attend = "聯絡不上",
                4 => $applicant->is_attend = "無法全程",
                default => $applicant->is_attend = "尚未聯絡"
            };
            $applicant->contactlogHTML = $applicant->contactlogHTML($isShowVolunteers ?? false, $applicant, $camp);
            $applicant->carer = count($applicant->carers) ? $applicant->carers->map(function($item) {
                return $item->name;
            })->join('<br>') : null;
        });
    @endphp
    let only_applicants = @json($applicants);
    @if($registeredVolunteers ?? false)
        @php
            $theVcampTable = str_contains($camp->table, 'vcamp') ? $camp->table : $camp->vcamp->table;
        @endphp
        window.theVolunteersData = @json($registeredVolunteers);
        @php
            $users_applicants = [];
            foreach ($registeredVolunteers as &$v) {
                if ($v->application_log) {
                    foreach ($v->application_log as $k => &$a) {
                        $a->gender = $a->gender_zh_tw;
                        $a->age = $a->age;
                        match ($a->is_attend) {
                            0 => $a->is_attend = "不參加",
                            1 => $a->is_attend = "參加",
                            2 => $a->is_attend = "尚未決定",
                            3 => $a->is_attend = "聯絡不上",
                            4 => $a->is_attend = "無法全程",
                            default => $a->is_attend = "尚未聯絡"
                        };
                        $a->contactlogHTML = $a->contactlogHTMLoptimized($isShowVolunteers ?? false, $camp);
                        foreach ($columns ?? [] as $key => $item) {
                            if ($key != "batch") {
                                if ($a && !$a->$key && $a->$theVcampTable?->$key) {
                                    $a->$key = $a->$theVcampTable->$key;
                                }
                                if ($key == "roles") {
                                    $a->roles = $a->user?->roles?->map(function ($item) {
                                        return $item->section;
                                    })->implode('<br>');
                                }
                                if ($key == "position") {
                                    $a->position = $a->user?->roles?->map(function ($item) {
                                        return $item->position;
                                    })->implode('<br>');
                                }
                                if ($key == "group_priority") {
                                    $priorities = collect([$a->$theVcampTable->group_priority1, $a->$theVcampTable->group_priority2, $a->$theVcampTable->group_priority3])
                                        ->filter()
                                        ->join('<br>');

                                    $a->group_priority = $priorities ?: null;
                                }
                            }
                        }
                        $users_applicants[] = $a;
                    }
                }
            }
            $applicants = collect($users_applicants)->merge($applicants);
            // 雖然這裡和 Blade 裡的 applicants 用了一樣的變數名，但這裡的 applicants 在 Blade 執行完畢後才會被設定
        @endphp
    @endif
    window.theData = @json($applicants);
    window.isShowLearners = {{ $isShowLearners ? 1 : 0 }};
    window.isShowVolunteers = {{ $isShowVolunteers ? 1 : 0 }};
    let user_application_logs = @json($users_applicants);
    (function() {
        $(".wrapper1").scroll(function(){
            $(".fixed-table-body").scrollLeft($(".wrapper1").scrollLeft());
        });
        $(".fixed-table-body").scroll(function(){
            $(".wrapper1")
                .scrollLeft($(".fixed-table-body").scrollLeft());
        });
        $('#applicantTable').on('page-change.bs.table', function (number, size) {
            sleep(50).then(() => {
                $('.applicants_selector').each(function () {
                    $.inArray('A' + this.value, window.applicant_ids) === -1 ? $(this).prop('checked', false) : $(this).prop('checked', true);
                });
            });
        })
    })();

    $(function() {
        fillTheList();
        $('#applicantTable').on('page-change.bs.table', function (number, size) {
            sleep(50).then(() => {
                $('.applicants_selector').each(function () {
                    $.inArray('A' + this.value, window.applicant_ids) === -1 ? $(this).prop('checked', false) : $(this).prop('checked', true);
                });
            });
        })
    });

    function sleep (time) {
        return new Promise((resolve) => setTimeout(resolve, time));
    }

    function applicant_triggered(id) {
        if ($("#" + id).is(":checked")) {
            window.applicant_ids.push(id);
        } else {
            window.applicant_ids = window.applicant_ids.filter(function(value, index, arr){
                return value != id;
            });
        }
    }

    function fillTheList() {
        let table = $('#applicantTable');
        // merge user_application_logs and only_applicants
        let data = user_application_logs.concat(only_applicants);
        var result = Object.values(data);
        // remove null item
        result = result.filter(function(item) { return item != null && item != 0; });
        let count = 0;
        // check result is only one element and the only one element has many items
        if (result.length == 1 && result[0].length > 1) {
            result = result[0];
        }
        result.forEach(function(item) {
            if (!item) {
                console.log(item, count);
                return;
            }
            count++;
            item.batch = !item.batch ? "沒有梯次資料" : item.batch.name;
            if (item.group_relation) {
                item.group = item.group_relation.alias;
            }
            if (item.birthday || item.birthmonth || item.birthyear) {
                const formatBirthdate = (year, month, day) => {
                    const parts = [];
                    if (year) parts.push(year + '年');
                    if (month) parts.push(month + '月');
                    if (day) parts.push(day + '日');
                    return parts.join('');
                };
                item.birthdate = formatBirthdate(item.birthyear, item.birthmonth, item.birthday);
            }
            item.name_original = item.name;
            if (item.user) {
                item.name = '<a href="{{ route('showAttendeeInfoGET', ($isShowVolunteers ?? false) ? $campFullData->vcamp->id : $campFullData->id) }}?snORadmittedSN=' + item.id + '&openExternalBrowser=1" target="_blank" class="text-primary">' + item.name + '</a>&nbsp;(報名序號：' + item.id + ')<div class="text-success">連結之帳號：' + item.user.name + '(' + item.user.email + ')</div>';
            }
            else {
                item.name = '<a href="{{ route('showAttendeeInfoGET', ($isShowVolunteers ?? false) ? $campFullData->vcamp->id : $campFullData->id) }}?snORadmittedSN=' + item.id + '&openExternalBrowser=1" target="_blank" class="text-primary">' + item.name + '</a>&nbsp;(報名序號：' + item.id + ')';
            }
            item.contactlog = item.contactlogHTML;
            item.avatar = '<img src="{{ url("/backend/" . $campFullData->id . "/avatar/") }}/' + item.id + '" width=80 alt="' + item.name_original + '">';
            @if(($isSetting ?? false) || ($isSettingCarer ?? false))
                item.checkfield = '<input type="checkbox" name="applicants[]" class="applicants_selector" value="' + item.id + '"  id="A' + item.id + '" onclick="applicant_triggered(this.id)">';
            @endif
        });
        // try cacth
        try {
            table.bootstrapTable({data: result})
        } catch (e) {
            console.log(e);
        }
    }
</script>
<style>
    .wrapper1{width: 400px; border: none 0px RED;
        overflow-x: scroll; overflow-y:hidden;}
    .wrapper1{height: 20px; }
    .div1 {width:600px; height: 20px; }
    .fixed-table-body { height: auto !important; }
</style>
