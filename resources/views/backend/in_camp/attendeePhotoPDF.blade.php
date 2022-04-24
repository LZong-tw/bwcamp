<style>
    table, table.table td, table.table th{
        border: 1px solid black;
        border-collapse: collapse;
        /* padding: 10px; */
        position:relative;
    }
</style>
@if(isset($applicants))
    <h3>義工名冊：</h3>
    共 {{ $applicants->count() }} 筆資料
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>報名序號</th>
                <th>姓名</th>
                <th>照片</th>
                <th>組別</th>
                <th>區域</th>
                @if($campFullData->table == "tcamp")
                    <th>職稱</th>
                    <th>單位</th>
                @endif
                <th>已取消</th>
                <th>報名時間</th>
            </tr>
        </thead>
        @forelse ($applicants as $applicant)
            <tr>
                <td>{{ $applicant->sn }}</td>
                <td>{{ $applicant->name }}</td>
                @if($applicant->avatar)
                <td><img src="data:image/png;base64, {{ base64_encode(\Storage::disk('local')->get($applicant->avatar)) }}" width=80 alt="{{ $applicant->name }}"></td>
                @else
                <td>no photo</td>
                @endif
                <td>{{ $applicant->group . $applicant->number }}</td>
                <td>{{ $applicant->region }}</td>
                @if($campFullData->table == "tcamp")
                    <td>{{ $applicant->title }}</td>
                    <td>{{ $applicant->unit }}</td>
                @endif
                <td>{!! $applicant->is_cancelled == "是" ? '<a style="color: red">是 (' . $applicant->deleted_at . ')</a>' : '<a style="color: green">否</a>' !!}</td>
                <td>{{ $applicant->created_at }}</td>
            </tr>
        @empty
            查詢的條件沒有資料
        @endforelse
    </table>
@endif