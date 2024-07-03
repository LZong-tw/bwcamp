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
        <tbody id="applicantTableBody">
            <!-- Data will be inserted here by JavaScript -->
        </tbody>
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
        $(".wrapper1").scroll(function(){
            $(".fixed-table-body").scrollLeft($(".wrapper1").scrollLeft());
        });
        $(".fixed-table-body").scroll(function(){
            $(".wrapper1")
                .scrollLeft($(".fixed-table-body").scrollLeft());
        });
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const applicants = @json($applicants);
        const columns = @json($columns);
        const isSetting = @json($isSetting ?? false);
        const isSettingCarer = @json($isSettingCarer ?? false);
        const isShowVolunteers = @json($isShowVolunteers ?? false);
        const campFullData = @json($campFullData);

        const tableBody = document.getElementById('applicantTableBody');
        let cancelledCount = 0;

        applicants.forEach(applicant => {
            const row = document.createElement('tr');
            if (applicant.deleted_at) {
                row.style.color = 'rgba(120, 120, 120, 0.4)';
                cancelledCount++;
            }

            if (isSetting || isSettingCarer) {
                const checkboxCell = document.createElement('td');
                checkboxCell.className = 'text-center';
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'applicants[]';
                checkbox.className = 'applicants_selector';
                checkbox.value = applicant.sn;
                checkbox.id = `A${applicant.sn}`;
                checkbox.onclick = () => applicant_triggered(checkbox.id);
                checkboxCell.appendChild(checkbox);
                row.appendChild(checkboxCell);
            }

            Object.keys(columns).forEach(key => {
                const cell = document.createElement('td');
                if (key === 'avatar' && applicant.avatar) {
                    const img = document.createElement('img');
                    img.src = `/backend/${applicant.camp.id}/avatar/${applicant.id}`;
                    img.width = 80;
                    img.alt = applicant.name;
                    cell.appendChild(img);
                } else if (key === 'name') {
                    const link = document.createElement('a');
                    link.href = `/attendee-info/${applicant.id}`;
                    link.target = '_blank';
                    link.textContent = applicant.name;
                    cell.appendChild(link);
                    cell.appendChild(document.createTextNode(`(報名序號：${applicant.id})`));
                    if (applicant.user) {
                        const userInfo = document.createElement('div');
                        userInfo.className = 'text-success';
                        userInfo.textContent = `連結之帳號：${applicant.user.name}(${applicant.user.email})`;
                        cell.appendChild(userInfo);
                    }
                } else {
                    cell.textContent = applicant[key] || '-';
                }
                row.appendChild(cell);
            });

            tableBody.appendChild(row);
        });

        document.getElementById('cancelledCount').textContent = cancelledCount;
    });

    function applicant_triggered(id) {
        if (document.getElementById(id).checked) {
            window.applicant_ids.push(id);
        } else {
            window.applicant_ids = window.applicant_ids.filter(value => value != id);
        }
    }
</script>
<style>
    .wrapper1{width: 400px; border: none 0px RED;
        overflow-x: scroll; overflow-y:hidden;}
    .wrapper1{height: 20px; }
    .div1 {width:600px; height: 20px; }
</style>
