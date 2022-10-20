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
    <h2>{{ $camp->abbreviation }} 建立組織</h2>
    <form action="{{ route("addOrgs", $camp->id) }}" method="POST">
        @csrf
        <table class="table table-bordered table-hover" id="org">
            <tr>
                <th>大組名稱</th>
                <th>職務名稱</th>
                <th>刪除</th>
            </tr>
            <tr>
                <td>
                    <input type="text" name="section[]" id="" class="form-control" required>
                    <a href="#" class="btn btn-primary float-right" onclick="addPosition(0)">新增職務</a>
                </td>
                <td>
                    <input type="text" name="position[0][]" id="" class="form-control" required>
                </td>
                <td>
                    －
                </td>
            </tr>
        </table>
        <a href="#" class="btn btn-primary float-right" onclick="addSection()">新增大組</a>
        <input type="submit" class="btn btn-success" value="確認送出">
    </form>
    <script>
            function addSection(){
                var tbl = document.getElementById("org");
                var lastRow = tbl.rows.length; 
                sec_num = tbl.rows.length - 1;
                var rowNode = tbl.insertRow(lastRow);
                var cellNode = rowNode.insertCell();
                lastRow = tbl.rows[tbl.rows.length - 1];
                lastRow.innerHTML = genEle1(sec_num);
            }
            function addPosition(sec_num){
                // count current table row
                // add position indexed by rows - 2
                var tbl = document.getElementById("org"); 
                lastRow = tbl.rows[tbl.rows.length - 1];
                var curRow = tbl.rows[sec_num + 1];   //section=0 => row=1
                var curCell = curRow.cells[1];    //2nd cell: cell=1
                curCell.innerHTML = curCell.innerHTML + genEle2(sec_num);火土
            }
            function genEle1(sec_cnt){
                let sec_ele1 = `<tr>  
                        <td>  
                            <input type="text" name="section[]" id="" class="form-control" required>
                            <a href="#" class="btn btn-primary float-right" onclick="addPosition(`;
                let sec_ele2 = `)">新增職務</a>  
                        </td>  
                        <td>  
                            <input type="text" name="position[`
                let sec_ele3 = `][]" id="" class="form-control" required>  
                        </td>
                        <td> 
                            <a href="#" class="btn btn-danger" onclick="this.parentNode.parentNode.remove()">Ｘ</a>
                        </td>   
                    </tr>`;
                ele = sec_ele1 + sec_cnt + sec_ele2 + sec_cnt + sec_ele3;
                return ele;
            }
            function genEle2(sec_cnt){
                let pos_ele1 = `<input type="text" name="position[`;
                let pos_ele2 = `][]" id="" class="form-control" required>`;
                //<a href="#" class="btn btn-danger" onclick="this.remove()">Ｘ</a>`;
                ele = pos_ele1 + sec_cnt + pos_ele2;
                return ele;
            }
            document.getElementById('bottom').addEventListener('click', () => {
                document.getElementById('bottom').scrollIntoView();
            });
    </script>
@endsection