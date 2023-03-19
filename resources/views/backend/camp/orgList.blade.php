@extends('backend.master')
@vite('resources/js/app.js')
@section('content')
    <style>
        .card-link{
            color: #3F86FB!important;
        }
        .card-link:hover{
            color: #33B2FF!important;
        }
    </style>
    <h2 class="d-inline-block">{{ $camp->abbreviation }} 組織列表　</h2><br>
    @if(\Session::has('message'))
        <div class='alert alert-success' role='alert'>
            {{ \Session::get('message') }}
        </div>
    @endif
    <br>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>梯次</th>
            <th>大組名稱</th>
            <th>職務名稱</th>
            <th>修改</th>
            <th>刪除</th>
            <th>新增</th>
        </tr>
        @foreach($orgs as $org)
            <tr>
                <td>{{ $org->id }}</td>
                <td>{{ $org->batch?->name ?? "未分梯" }}</td>
                @if($org->position == 'root')
                <td class="font-weight-bold">{{ $org->section }}</td>
                @else
                <td class="text-muted">{{ $org->section }}</td>
                @endif
                @if($org->position == 'root')
                    <td>（大組）</td>
                    <td>
                        <a href="{{ route('showModifyOrg', [$camp->id, $org->id]) }}" class="btn btn-primary">修改</a>
                    </td>
                    <td>
                        <form action="{{ route('removeOrg') }}" method="post">
                            @csrf
                            <input type="hidden" name="org_id" value="{{ $org->id }}">
                            <input type="hidden" name="org_section" value="{{ $org->section }}">
                            <input type="hidden" name="org_position" value="{{ $org->position }}">
                            <input type="hidden" name="camp_id" value="{{ $camp->id }}">
                            <!--input type="submit" class="btn btn-danger" value="刪除">
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter">刪除</button-->
                            @if(!$num_users[$org->id])
                                <button type="button" class="btn btn-danger"  onclick="confirmdelete(this.closest('form'));">刪除</button>
                            @endif
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('showAddOrgs', [$camp->id, $org->id]) }}" class="btn btn-success">新增職務</a>
                    </td>
                @else
                    <td>{{ $org->position }}</td>
                    <td><a href="{{ route('showModifyOrg', [$camp->id, $org->id]) }}" class="btn btn-primary">修改</a></td>
                    <td>
                        <form action="{{ route('removeOrg') }}" method="post">
                            @csrf
                            <input type="hidden" name="org_id" value="{{ $org->id }}">
                            <input type="hidden" name="org_section" value="{{ $org->section }}">
                            <input type="hidden" name="org_position" value="{{ $org->position }}">
                            <input type="hidden" name="camp_id" value="{{ $camp->id }}">
                            <!--input type="submit" class="btn btn-danger" value="刪除">
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter">刪除</button-->
                            @if(!$num_users[$org->id])
                                <button type="button" class="btn btn-danger"  onclick="confirmdelete(this.closest('form'));">刪除</button>
                            @endif
                        </form>
                    </td>
                    <td></td>
                @endif
            </tr>
        @endforeach
    </table>
    @if(count($orgs))  
    <h6 class='text-info'>＊大組中無職務，才可刪除該大組</h6>
    <h6 class='text-info'>＊某個職務無人被指定，才可刪除該職務</h6>
    @endif


{{--    
    <style>
        .modal-dialog {
            z-index: 9999!important;
        }
        .modal-backdrop {
            display: none;
            z-index: -1!important;
        }
    </style>

    <!-- Modal：刪除互動視窗 -->

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">確認刪除？</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!--div class="modal-body">
                    確認刪除？
                </div-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-danger">確認刪除</button>
                </div>
            </div>
        </div>
    </div>
--}}

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a href="{{ route('showAddOrgs', [$camp->id, 0]) }}" class="btn btn-success d-inline-block" style="margin-bottom: 10px">新增組織</a>
            </div>
            <div class="col-md-9">
            </div>
        </div>
    </div>

    @if (!count($orgs))      
        <form action="{{ route('copyOrgs', $camp->id) }}" method="post">
            @csrf
            <div class="container">
                <div class="row">
                    <h5>複製現有營隊組織</h5>
                </div>
                <div class="row">
                    <div class="col-md-2 text-md-right">
                        選擇要複製的營隊
                    </div>
                    <div class="col-md-8">
                        <select class='form-control' name='camp2copy' id='inputCamp2Copy' onchange='showOrgSel()'>
                        <option value=''>- 請選擇 -</option>
                        @foreach($camp_list as $item)
                        @if($item->id != $camp->id)
                        <option value='{{$item->id}}'> {{$item->id}} {{$item->fullName}} </option>
                        @endif
                        @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary">複製</button><br><br>
                    </div>
                </div>
                <div class="row">
                    <table class="table table-bordered" style="display:none" id="org2copy">
                        <tr>
                            <td>
                                Hello World!
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </form>
    @endif

    <script>
        function showOrgSel(){
            var camp_sel = document.getElementById("inputCamp2Copy");   //obj
            var camp_id_sel = camp_sel.options[camp_sel.selectedIndex].value;   //val
            console.log(camp_id_sel);
           //console.log(org_sel.style.display);

            axios({
                method: 'post',
                url: '/semi-api/getOrgSel',
                data: {
                    //applicant_ids: window.applicant_ids,
                    camp_id_sel: camp_sel.options[camp_sel.selectedIndex].value,
                },
                responseType: 'json'
            })
            .then(function (response) {
                if (response.data.status === 'success') {
                    //window.location.reload();
                    console.log(response.data);
                }
                else {
                    var org_sel = document.getElementById("org2copy");
                    org_sel.style.display = "block";
                    //console.log(org_sel.style.display);
                    let ele = ``;
                    if (Object.keys(response.data).length == 0) {
                        ele = `<tr><td>營隊`+ camp_id_sel + `尚未建立組織，請選擇其它營隊</td><tr>`;
                    } else {
                        ele = `<tr><td>欲複製營隊的組織：`;
                        for (let i = 0; i < Object.keys(response.data).length ; i++) {
                            if(response.data[i]['position']=='root') {
                                ele = ele + `<br>`+ response.data[i]['section'] + `：`;
                            }
                            else {
                                ele = ele + response.data[i]['position'] + `、`;
                            }
                        }
                        ele = ele + `</td></tr>`;
                    }    
                    console.log(ele);
                    org_sel.innerHTML = ele;
                }
            });
        }
        function confirmdelete(form) {
            if (confirm('確認刪除？')) {
                form.submit();
            }
        }
    </script>
@endsection