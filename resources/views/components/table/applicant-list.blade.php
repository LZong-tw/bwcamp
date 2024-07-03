<div class="mt-2">
    @if ($queryStr ?? false) 查詢條件：{{ $queryStr }} @endif
    <div class="wrapper1">
        <div class="div1">
        </div>
    </div>
    <div class="text-danger mt-3">
        已取消報名： <span id="cancelledCount">0</span> 人
    </div>
    <table class="table table-bordered table-hover" id="applicantTable">
        <thead>
            <tr class="bg-success text-white">
                @if(($isSetting ?? false) || ($isSettingCarer ?? false))
                    <th></th>
                @endif
                @foreach ($columns ?? [] as $key => $item)
                    <th class="text-center" data-field="{{ $key }}" data-sortable="{{ $item['sort'] }}">{{ $item['name'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody id="applicantTableBody">
            <!-- Dynamic content will be inserted here -->
        </tbody>
    </table>
</div>

<script>
    // Keep your existing JavaScript variables
    window.applicant_ids = [];
    window.csrf_token = "{{ csrf_token() }}";
    window.columns = @json($columns);
    window.theData = @json($applicants);
    window.isShowLearners = {{ $isShowLearners ? 1 : 0 }};
    window.isShowVolunteers = {{ $isShowVolunteers ? 1 : 0 }};
    window.isSetting = {{ ($isSetting ?? false) ? 'true' : 'false' }};
    window.isSettingCarer = {{ ($isSettingCarer ?? false) ? 'true' : 'false' }};

    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.getElementById('applicantTableBody');
        const cancelledCountElement = document.getElementById('cancelledCount');

        function renderApplicants() {
            let cancelledCount = 0;
            let tableContent = '';

            window.theData.forEach(applicant => {
                if (applicant.deleted_at) cancelledCount++;

                tableContent += `
                    <tr ${applicant.deleted_at ? 'style="color: rgba(120, 120, 120, 0.4)!important"' : ''}>
                        ${(window.isSetting || window.isSettingCarer) ? `
                            <td class="text-center">
                                <input type="checkbox" name="applicants[]" class="applicants_selector" value="${applicant.sn}" id="A${applicant.sn}" onclick="applicant_triggered(this.id)">
                            </td>
                        ` : ''}
                        ${Object.keys(window.columns).map(key => {
                            if (key === "avatar") {
                                const imgSrc = applicant && applicant.camp && applicant.camp.id && applicant.id
                                    ? `/backend/${applicant.camp.id}/avatar/${applicant.id}`
                                    : 'path/to/default/image.jpg';
                                return `<td><img src="${imgSrc}" width="80" alt="${applicant.name || ''}"></td>`;
                            } else if (key === "name") {
                                return `
                                    <td>
                                        <a href="/attendee-info/${applicant.id}" target="_blank">${applicant.name}</a>&nbsp;(報名序號：${applicant.id})
                                        ${applicant.user ? `<div class="text-success">連結之帳號：${applicant.user.name}(${applicant.user.email})</div>` : ''}
                                    </td>
                                `;
                            } else {
                                return `<td>${applicant[key] || "-"}</td>`;
                            }
                        }).join('')}
                    </tr>
                `;
            });

            tableBody.innerHTML = tableContent;
            cancelledCountElement.textContent = cancelledCount;
        }

        renderApplicants();

        // Re-use your existing scroll function
        (function() {
            $(".wrapper1").scroll(function(){
                $(".fixed-table-body").scrollLeft($(".wrapper1").scrollLeft());
            });
            $(".fixed-table-body").scroll(function(){
                $(".wrapper1").scrollLeft($(".fixed-table-body").scrollLeft());
            });
        })();
    });

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

<style>
    .wrapper1 {
        width: 400px;
        border: none 0px RED;
        overflow-x: scroll;
        overflow-y: hidden;
        height: 20px;
    }
    .div1 {
        width: 600px;
        height: 20px;
    }
</style>
