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
    <h2>{{ $camp->abbreviation }} 批次新增梯次</h2>
    <form action="{{ route("addBatch", $camp->id) }}" method="POST">
        @csrf
        <table class="table table-bordered table-hover" id="form">
            <tr>
                <th scope="col" class="text-nowrap">名稱</th>
                <th scope="col" class="text-nowrap">錄取編號<br>前綴</th>
                <th scope="col" class="text-nowrap">梯次開始日</th>
                <th scope="col" class="text-nowrap">梯次結束日</th>
                <th scope="col" class="text-nowrap">允許前台<br>報名</th>
                <th scope="col" class="text-nowrap">是否延後<br>截止報名</th>
                <th scope="col" class="text-nowrap">報名延後截止日</th>
                <th scope="col" class="text-nowrap">報到日</th>
                <th scope="col" class="text-nowrap">地點</th>
                <th scope="col" class="text-nowrap">地址</th>
                <th scope="col" class="text-nowrap">電話</th>
                <th scope="col" class="text-nowrap">學員組數</th>
                <th scope="col" class="text-nowrap">新增或<br>刪除</th>
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
                    <input type="date" name="check_in_day[]" id="" class="form-control">
                </td>
                <td>
                    <input type="text" name="locationName[]" id="" class="form-control">
                </td>
                <td>
                    <input type="text" name="location[]" id="" class="form-control">
                </td>
                <td>
                    <input type="text" name="tel[]" id="" class="form-control">
                </td>
                <td>
                    <input type="number" name="num_groups[]" id="" class="form-control">
                </td>
                <td>
                    <a href="#" class="btn btn-primary float-right" onclick="addRow()">+</a>
                </td>
            </tr>
        </table>
        <!--a href="#" class="btn btn-primary float-right" onclick="addRow()">新增梯次</a-->
        <input type="submit" class="btn btn-success" value="確認送出">
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
                            <input type="date" name="check_in_day[]" id="" class="form-control">  
                        </td>  
                        <td>  
                            <input type="text" name="locationName[]" id="" class="form-control">  
                        </td>  
                        <td>  
                            <input type="text" name="location[]" id="" class="form-control">  
                        </td>  
                        <td>  
                            <input type="text" name="tel[]" id="" class="form-control">  
                        </td>  
                        <td>  
                            <input type="number" name="num_groups[]" id="" class="form-control">  
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