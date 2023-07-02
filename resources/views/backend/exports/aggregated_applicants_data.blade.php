<table>
    <thead>
        <tr>
            @foreach($columns as $key => $column)
                <th>{{ $column }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($applicants as $applicant)
            <tr>
                @foreach($columns as $key => $column)
                    <td>{{ $applicant->$key }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
