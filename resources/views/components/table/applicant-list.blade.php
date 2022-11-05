<div>
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
                @foreach ($columns as $key => $item)
                    @if(!$is_vcamp && !$is_care && $key == "caring_logs")
                    @else
                        <th data-field="{{ $key }}" data-sortable="{{ $item['sort'] }}">{{ $item['name'] }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        @forelse ($applicants as $applicant)
            <tr>
                @foreach ($columns as $key => $item)
                    @if($key == "avatar" && $applicant->avatar)
                        <td><img src="data:image/png;base64, {{ base64_encode(\Storage::disk('local')->get($applicant->avatar)) }}" width=80 alt="{{ $applicant->name }}"></td>
                    @elseif($key == "avatar" && !$applicant->avatar)
                        <td>no photo</td>
                    @elseif($key == "gender")
                        <td>{{ $applicant->gender_zh_tw }}</td>
                    @elseif(!$is_vcamp && !$is_care && $key == "caring_logs")
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
