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
    <h2>{{ $camp->abbreviation }} 新增組織</h2>

    <table class="table table-bordered">
        <tr>
            <td>
            @if($sec_tg == "null")
                目前組織：
                @foreach($orgs as $org)
                    @if($org->position == 'root')
                        <br>{{ $org->section }}： 
                    @else
                        {{ $org->position }}、
                    @endif
                @endforeach
            @else
                {{ $sec_tg }}目前職務：
                @foreach($orgs as $org)
                    @if($org->section == $sec_tg)
                        @if($org->position == 'root')
                            <br>{{ $org->section }}： 
                        @else 
                            {{ $org->position }}、
                        @endif
                    @endif
                @endforeach
            @endif
            </td>
        </tr>
    </table>

    <form action="{{ route("addOrgs", $camp->id) }}" method="POST">
        @csrf
        <table class="table table-bordered table-hover" id="org">
            <tr>
                <th>大組名稱</th>
                <th>職務名稱</th>
                <th>刪除</th>
            </tr>
            <tr>
                @if($sec_tg == "null")
                <td>
                    <input type="text" name="section[]" id="" class="form-control" required>
                    <a href="#" class="btn btn-primary float-right" onclick="addPosition(0)">新增職務</a>
                </td>
                @else
                <td>
                    <input type=hidden name="section[]" id="" class="form-control" value="{{ $sec_tg }}">
                    {{ $sec_tg }}
                    <a href="#" class="btn btn-primary float-right" onclick="addPosition(0)">新增職務</a>
                </td>
                @endif
                <td>
                    <table class="table table-bordered table-hover" id="sec0">
                        <tr>
                            <td>
                                <input type="text" name="position[0][]" id="" class="form-control" required>
                            </td>
                            <td>
                                －
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    －
                </td>
            </tr>
        </table>
        @if($sec_tg == "null")
        <a href="#" class="btn btn-primary float-right" onclick="addSection()">新增大組</a>
        @endif
        <input type="submit" class="btn btn-success" value="確認送出">
        <a href="{{ route('showOrgs', $camp->id) }}" class="btn btn-danger">取消新增</a>
    </form>
    <script>
            /*function addSection(){
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
            }*/
            function addSection(){
                var tbl = document.getElementById("org");
                var lastRow = tbl.rows.length; 
                sec_num = tbl.rows.length - 1;
                var rowNode = tbl.insertRow(lastRow);
                var cellNode = rowNode.insertCell();
                lastRow = tbl.rows[tbl.rows.length - 1];
                lastRow.innerHTML = genEle3(sec_num);
            }
            function addPosition(sec_num){
                // count current table row
                // add position indexed by rows - 2
                var tid = "sec" + sec_num;
                console.log(tid);
                var tbl = document.getElementById(tid); 
                var lastRow = tbl.rows.length;  //number
                var rowNode = tbl.insertRow(lastRow);
                var cellNode = rowNode.insertCell();
                lastRow = tbl.rows[tbl.rows.length - 1];    //object
                lastRow.innerHTML = genEle4(sec_num);
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
            function genEle3(sec_cnt){
                let sec_ele1 = `<tr>  
                        <td>  
                            <input type="text" name="section[]" id="" class="form-control" required>
                            <a href="#" class="btn btn-primary float-right" onclick="addPosition(`;
                let sec_ele2 = `)">新增職務</a>  
                        </td>  
                        <td>  
                            <table class="table table-bordered table-hover" id="sec`;
                let sec_ele3 =`">
                            <tr>
                                <td>
                                    <input type="text" name="position[0`;
                let sec_ele4 = `][]" id="" class="form-control" required>
                                </td>
                                <td>
                                    －
                                </td>
                            </tr>
                            </table>
                        </td>
                        <td> 
                            <a href="#" class="btn btn-danger" onclick="this.parentNode.parentNode.remove()">Ｘ</a>
                        </td>   
                    </tr>`;
                ele = sec_ele1 + sec_cnt + sec_ele2 + sec_cnt + sec_ele3 + sec_cnt + sec_ele4;
                return ele;
            }
            function genEle4(sec_cnt){
                let pos_ele1 = `<tr>
                        <td>    
                            <input type="text" name="position[`;
                let pos_ele2 = `][]" id="sec`;
                let pos_ele3 = `" class="form-control" required>
                        </td>
                        <td> 
                            <a href="#" class="btn btn-danger" onclick="this.parentNode.parentNode.remove()">Ｘ</a>
                        </td>
                    </tr>`;
                ele = pos_ele1 + sec_cnt + pos_ele2 + sec_cnt + pos_ele3;
                return ele;
            }
    </script>
@endsection