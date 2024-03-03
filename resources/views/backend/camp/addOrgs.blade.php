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

    {{-- 無上層 --}}
    @if($org_tg->id == 0)
    <form action="{{ route('addOrgs', $camp->id) }}" method="POST">
        @csrf
        <table class="table table-bordered" id="org">
            <tr>
                <th>選擇梯次</th>
                <th>選擇區域</th>
                <th>選擇組織上層</th>
                <th>新增大組/小組/職務名稱</th>
                <th>新增或刪除行</th>
            </tr>
            <tr class="align-middle">
                <td class="align-middle">
                <select required class='form-control' name='batch_id[0]' id='inputBatchId'>
                    <option value=''>- 請選擇 -</option>
                    <option value='0'>不限</option>
                    @foreach($batches as $batch)
                        <option value='{{$batch->id}}'>{{$batch->name}}</option>
                    @endforeach
                </select>
                </td>
                <td class="align-middle">
                <select required class='form-control' name='region_id[0]' id='inputRegionId'>
                    <option value=''>- 請選擇 -</option>
                    <option value='0'>不限</option>
                    @foreach($regions as $region)
                        <option value='{{$region->id}}'>{{$region->name}}</option>
                    @endforeach
                </select>
                </td>
                <td class="align-middle">
                <input type="hidden" name="prev_id[0]" id='inputPrevId' value="{{$org_tg->id}}">
                <input type="hidden" name="section[0]" id='inputSection' value="{{$org_tg->section}}">
                {{$org_tg->section}}
                </td>
                <td class="align-middle">
                    <input required type="text" name="position[0]" id="" class="form-control">
                </td>
                <td class="align-middle">
                    <a href="#" class="btn btn-primary" onclick="addLine(1)">+</a>
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
    {{-- 有上層 --}}
    @else
    <form action="{{ route('addOrgs', $camp->id) }}" method="POST">
        @csrf
        <table class="table table-bordered" id="org">
            <tr>
                <th>選擇梯次</th>
                <th>選擇區域</th>
                <th>選擇組織上層</th>
                <th>新增大組/小組/職務名稱</th>
                <th>新增或刪除行</th>
            </tr>
            <tr class="align-middle">
                <td class="align-middle">
                    <input type="hidden" name="batch_id[0]" id='inputBatchId' value="{{$batch_tg->id}}">{{$batch_tg->name}}
                </td>
                <td class="align-middle">
                    <input type="hidden" name="region_id[0]" id='inputRegionId' value="{{$region_tg->id}}">{{$region_tg->name}}
                </td>
                <td class="align-middle">
                <input type="hidden" name="prev_id[0]" id='inputPrevId' value="{{$org_tg->id}}">
                <input type="hidden" name="section[0]" id='inputSection' value="{{$org_tg->section}}.{{$org_tg->position}}">{{$org_tg->section}}.{{$org_tg->position}}
                </td>
                <td class="align-middle">
                    <input required type="text" name="position[0]" id="" class="form-control">
                </td>
                <td class="align-middle">
                    <a href="#" class="btn btn-primary" onclick="addLine(2)">+</a>
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
    @endif
    <script>
        var g_pos_idx = 1;  //讓pos_num不重複
        function addLine(mode) {
            //console.log(mode);
            var tbl = document.getElementById("org");
            var lastRow = tbl.rows.length;
            //pos_num = tbl.rows.length - 1;
            pos_num = g_pos_idx;
            g_pos_idx = g_pos_idx + 1;
            var rowNode = tbl.insertRow(lastRow);
            var cellNode = rowNode.insertCell();
            lastRow = tbl.rows[tbl.rows.length - 1];
            console.log(mode);
            if (mode==1) {
                lastRow.innerHTML = genEle1(pos_num);
            } else {
                lastRow.innerHTML = genEle2(pos_num);
            }
            var sid = "pos" + pos_num;
            //console.log(sid);
            //console.log(lastRow.innerHTML);
        }
        @if($org_tg->id==0)
        function genEle1(pos_num) {
            let pos_ele1 = `<tr>
                <td class="align-middle">
                <select required class='form-control' name='batch_id[`;
            let pos_ele2 = `]' id='inputBatchId'>
                    <option value=''>- 請選擇 -</option>
                    <option value='0'>不限</option>
                    @foreach($batches as $batch)
                        <option value='{{$batch->id}}'>{{$batch->name}}</option>
                    @endforeach
                </select>
                </td>
                <td class="align-middle">
                <select required class='form-control' name='region_id[`;
            let pos_ele3 = `]' id='inputRegionId'>
                    <option value=''>- 請選擇 -</option>
                    <option value='0'>不限</option>
                    @foreach($regions as $region)
                        <option value='{{$region->id}}'>{{$region->name}}</option>
                    @endforeach
                </select>
                </td>
                <td class="align-middle">
                    <input type="hidden" name="prev_id[`;
            let pos_ele4 = `]" id='inputPrevId' value="{{$org_tg->id}}">
                    <input type="hidden" name="section[`;
            let pos_ele5 = `]" id='inputSection' value="{{$org_tg->section}}">
                    {{$org_tg->section}}
                </td>
                <td class="align-middle">
                    <input required type="text" name="position[`;
            let pos_ele6 = `]" id="" class="form-control" required>
                </td>
                <td class="align-middle">
                    <a href="#" class="btn btn-danger" onclick="this.parentNode.parentNode.remove()">Ｘ</a>
                </td>
                </tr>`;
            ele = pos_ele1 + pos_num + pos_ele2 + pos_num + pos_ele3 + pos_num + pos_ele4 + pos_num + pos_ele5 + pos_num + pos_ele6;
            return ele;
        }
        @else
        function genEle2(pos_num) {
            let pos_ele1 = `<tr>
                <td class="align-middle">
                <input type="hidden" name="batch_id[`;
            let pos_ele2 = `]" id='inputBatchId' value="{{$batch_tg->id}}">
                {{$batch_tg->name}}
                </td>
                <td class="align-middle">
                <input type="hidden" name="region_id[`;
            let pos_ele3 = `]" id='inputRegionId' value="{{$region_tg->id}}">
                {{$region_tg->name}}
                </td>
                <td class="align-middle">
                <input type="hidden" name="prev_id[`;            
            let pos_ele4 = `]" id='inputPrevId' value="{{$org_tg->id}}">
                <input type="hidden" name="section[`;
            let pos_ele5 = `]" id='inputSection' value="{{$org_tg->section}}.{{$org_tg->position}}">
                {{$org_tg->section}}.{{$org_tg->position}}
                </td>
                <td class="align-middle">
                    <input required type="text" name="position[`;
            let pos_ele6 = `]" id="" class="form-control" required>
                </td>
                <td class="align-middle">
                    <a href="#" class="btn btn-danger" onclick="this.parentNode.parentNode.remove()">Ｘ</a>
                </td>
                </tr>`;
            ele = pos_ele1 + pos_num + pos_ele2 + pos_num + pos_ele3 + pos_num + pos_ele4 + pos_num + pos_ele5 + pos_num + pos_ele6;
            return ele;
        }
        @endif
    </script>
@stop
