@extends('backend.master')
@section('content')
    <style>
        .card-link{
            color: #3F86FB!important;
        }
        .card-link:hover{
            color: #33B2FF!important;
        }
        /* customize */
        .form-group.required .control-label:after {
            content: "＊";
            color: red;
        }
    </style>
    <h2>{{ $camp->abbreviation }} 建立場次</h2>
    <form action="{{ route("addBatch", $camp->id) }}" method="POST">
        @csrf
        <table class="table table-bordered table-hover" id="form">
            <tr>
                <th>名稱</th>
                <th>錄取編號前綴</th>
                <th>場次開始日</th>
                <th>場次結束日</th>
                <th>是否延後截止報名</th>
                <th>報名延後截止日</th>
                <th>地點名</th>
                <th>地點</th>
                <th>報到日</th>
                <th>電話</th>
            </tr>
            <tr>
                <td>
                    <input type="text" name="name[]" id="" class="form-control">
                </td>
                <td>
                    <input type="text" name="admission_suffix[]" length="1" class="form-control">
                </td>
                <td>
                    <input type="date" name="batch_start[]" id="" class="form-control">
                </td>
                <td>
                    <input type="date" name="batch_end[]" id="" class="form-control">
                </td>
                <td>
                    <select name="is_late_registration_end[]" id="" class="form-control">
                        <option value="0">否</option>
                        <option value="1">是</option>
                    </select>
                </td>
                <td>
                    <input type="date" name="late_registration_end[]" id="" class="form-control">
                </td>
                <td>
                    <input type="text" name="locationName[]" id="" class="form-control">
                </td>
                <td>
                    <input type="text" name="location[]" id="" class="form-control">
                </td>
                <td>
                    <input type="text" name="check_in_day[]" id="" class="form-control">
                </td>
                <td>
                    <input type="text" name="tel[]" id="" class="form-control">
                </td>
            </tr>
        </table>
        <a href="#" class="btn btn-primary" onclick="addRow()">新增場次</a>
        <br><br>
        <input type="submit" class="btn btn-success" value="送出">
    </form>
    <script>
        let ele = '<tr>' + 
                '<td>' + 
                    '<input type="text" name="name[]" id="" class="form-control">' + 
                '</td>' + 
                '<td>' + 
                    '<input type="text" name="admission_suffix[]" length="1" class="form-control">' + 
                '</td>' + 
                '<td>' + 
                    '<input type="date" name="batch_start[]" id="" class="form-control">' + 
                '</td>' + 
                '<td>' + 
                    '<input type="date" name="batch_end[]" id="" class="form-control">' + 
                '</td>' + 
                '<td>' + 
                    '<select name="is_late_registration_end[]" id="" class="form-control">' + 
                        '<option value="0">否</option>' + 
                        '<option value="1">是</option>' + 
                    '</select>' + 
                '</td>' + 
                '<td>' + 
                    '<input type="date" name="late_registration_end[]" id="" class="form-control">' + 
                '</td>' + 
                '<td>' + 
                    '<input type="text" name="locationName[]" id="" class="form-control">' + 
                '</td>' + 
                '<td>' + 
                    '<input type="text" name="location[]" id="" class="form-control">' + 
                '</td>' + 
                '<td>' + 
                    '<input type="text" name="check_in_day[]" id="" class="form-control">' + 
                '</td>' + 
                '<td>' + 
                    '<input type="text" name="tel[]" id="" class="form-control">' + 
                '</td>' + 
            '</tr>';
            function addRow(){
                var tbl = document.getElementById("form"); 
                var lastRow = tbl.rows.length; 
                var rowNode = tbl.insertRow(lastRow);
                var cellNode = rowNode.insertCell();
                lastRow = tbl.rows[tbl.rows.length - 1];
                lastRow.innerHTML = ele;
            }
    </script>
@endsection