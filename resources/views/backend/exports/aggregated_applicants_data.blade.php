<table>
    <thead>
        @foreach($columns as $key => $column)
            <th>{{ $column }}</th>
        @endforeach
    </thead>
    <tbody>
        @foreach($applicants as $applicant)
            @foreach($columns as $key => $column)
                <td>{{ $applicant->$key }}</td>
            @endforeach
        @endforeach
    </tbody>
</table>
