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
    <h2>{{ $camp->abbreviation }} 建立梯次</h2>
    <form action="{{ route("addBatch", $camp->id) }}" method="POST">
        @csrf
        <table class="table table-bordered table-hover" id="form">
            <tr>
                <th width="82px">名稱</th>
                <th width="65px">錄取編號前綴</th>
                <th>梯次開始日</th>
                <th>梯次結束日</th>
                <th>允許前台報名</th>
                <th width="90px">是否延後截止報名</th>
                <th>報名延後截止日</th>
                <th>地點名</th>
                <th>地點</th>
                <th>報到日</th>
                <th>電話</th>
                <th>刪除</th>
            </tr>
            <tr>
                <td>
                    <input type="text" name="name[]" id="" class="form-control" required>
                </td>
                <td>
                    <input type="text" name="admission_suffix[]" maxlength="1" class="form-control" required>
                </td>
                <td>
                    <input type="date" name="batch_start[]" id="" class="form-control" required>
                </td>
                <td>
                    <input type="date" name="batch_end[]" id="" class="form-control" required>
                </td>
                <td>
                    <select name="is_appliable[]" id="" class="form-control">
                        <option value="1">是</option>
                        <option value="0">否</option>
                    </select>
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
                    <input type="date" name="check_in_day[]" id="" class="form-control">
                </td>
                <td>
                    <input type="text" name="tel[]" id="" class="form-control">
                </td>
                <td>
                    －
                </td>
            </tr>
        </table>
        <a href="#" class="btn btn-primary float-right" onclick="addRow()">新增梯次</a>
        <input type="submit" class="btn btn-success" value="送出">
    </form>
    <script>
        let ele = ` <tr>  
                        <td>  
                            <input type="text" name="name[]" id="" class="form-control" required>  
                        </td>  
                        <td>  
                            <input type="text" name="admission_suffix[]" length="1" class="form-control" required>  
                        </td>  
                        <td>  
                            <input type="date" name="batch_start[]" id="" class="form-control" required>  
                        </td>  
                        <td>  
                            <input type="date" name="batch_end[]" id="" class="form-control" required>  
                        </td>  
                        <td>
                            <select name="is_appliable[]" id="" class="form-control">
                                <option value="1">是</option>
                                <option value="0">否</option>
                            </select>
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
                            <input type="date" name="check_in_day[]" id="" class="form-control">  
                        </td>  
                        <td>  
                            <input type="text" name="tel[]" id="" class="form-control">  
                        </td>  
                        <td> 
                            <a href="#" class="btn btn-danger" onclick="this.parentNode.parentNode.remove()">Ｘ</a>
                        </td> 
                    </tr>`;
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