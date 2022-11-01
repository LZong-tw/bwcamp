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
                <th>新增或刪除行</th>
                <th>職務名稱</th>
            </tr>
            <tr class="align-middle">
                @if($sec_tg == "null")
                <td class="align-middle">
                    <input type="text" name="section[]" id="" class="form-control" required>
                </td>
                <td class="align-middle">
                    <a href="#" class="btn btn-primary" onclick="addSection()">+</a>                
                </td>
                @else
                <td class="align-middle">
                    <input type=hidden name="section[]" id="" class="form-control" value="{{ $sec_tg }}">
                    {{ $sec_tg }}
                </td>
                <td class="align-middle">-</td>
                @endif
                <td class="align-middle">
                    <table class="table table-bordered table-hover" id="sec0">
                        <tr>
                            <td>
                                <input type="text" name="position[0][]" id="" class="form-control" required>
                            </td>
                            <td>
                                <a href="#" class="btn btn-primary" onclick="addPosition(0)">+</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--
        @if($sec_tg == "null")
        <a href="#" class="btn btn-primary float-right" onclick="addSection()">新增大組</a>
        @endif
        -->
        <input type="submit" class="btn btn-success" value="確認送出">
        <a href="{{ route('showOrgs', $camp->id) }}" class="btn btn-danger">取消新增</a>
    </form>
    <script>
        var g_sec_idx = 1;  //讓sec_num不重複
        function addSection(){
            var tbl = document.getElementById("org");
            var lastRow = tbl.rows.length; 
            //sec_num = tbl.rows.length - 1;
            sec_num = g_sec_idx;
            g_sec_idx = g_sec_idx + 1;
            var rowNode = tbl.insertRow(lastRow);
            var cellNode = rowNode.insertCell();
            lastRow = tbl.rows[tbl.rows.length - 1];
            lastRow.innerHTML = genEle1(sec_num);
            var sid = "sec" + sec_num;
            console.log(sid);
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
            lastRow.innerHTML = genEle2(sec_num);
        }
        function genEle1(sec_num){
            let sec_ele1 = `<tr>  
                    <td class="align-middle">  
                        <input type="text" name="section[`;
            let sec_ele2 = `]" id="" class="form-control" required>
                    </td>
                    <td class="align-middle"> 
                        <a href="#" class="btn btn-danger" onclick="this.parentNode.parentNode.remove()">Ｘ</a>
                    </td>   
                    <td class="align-middle">  
                        <table class="table table-bordered table-hover" id="sec`;
            let sec_ele3 =`">
                            <tr>
                                <td>
                                    <input type="text" name="position[`;
            let sec_ele4 = `][]" id="" class="form-control" required>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-primary" onclick="addPosition(`;
            let sec_ele5 = `)">+</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>`;
            ele = sec_ele1 + sec_num + sec_ele2 + sec_num + sec_ele3 + sec_num + sec_ele4 + + sec_num + sec_ele5;
            return ele;
        }
        function genEle2(sec_num){
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
            ele = pos_ele1 + sec_num + pos_ele2 + sec_num + pos_ele3;
            return ele;
        }
    </script>
@endsection