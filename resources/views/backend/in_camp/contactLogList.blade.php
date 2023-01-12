@extends('backend.master')
@section('content')
    <style>
        .card-link{
            color: #3F86FB!important;
        }
        .card-link:hover{
            color: #33B2FF!important;
        }
    </style>
    <h4>學員關懷記錄</h4>
    <h5 class="d-inline-block">關懷記錄列表：{{ $applicant->name }}　</h5>
    
    <a href="{{ route('showAddContactLogs', [$camp_id, $applicant->id]) }}" class="btn btn-success d-inline-block" style="margin-bottom: 10px">新增關懷記錄</a>

    @if(\Session::has('message'))
        <div class='alert alert-success' role='alert'>
            {{ \Session::get('message') }}
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>內容</th>
            <th>記錄者</th>
            <th>記錄時間</th>
            <th>修改時間</th>
            <th scope="col" class="text-nowrap">修改　　</th>
            <th scope="col" class="text-nowrap">刪除　　</th>
        </tr>
        @foreach($contactlogs as $contactlog)
            <tr>
                <td>{{ $contactlog->id }}</td>
                <td>{{ $contactlog->notes }}</td>
                <td>{{ $contactlog->takenby_name }}</td>
                <td>{{ $contactlog->created_at }}</td>
                <td>{{ $contactlog->updated_at }}</td>
                <td>
                    <a href="{{ route('showModifyContactLog', [$camp_id, $contactlog->id]) }}" class="btn btn-primary">修改</a>
                </td>
                <td>
                    <form action="{{ route('removeContactLog', $camp_id) }}" method="post">
                        @csrf
                        <input type="hidden" name="contactlog_id" value="{{ $contactlog->id }}">
                        <input type="hidden" name="camp_id" value="{{ $camp_id }}">
                        <!--input type="submit" class="btn btn-danger" value="刪除">
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter">刪除</button-->
                        <button type="button" class="btn btn-danger"  onclick="confirmdelete(this.closest('form'));">刪除</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

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

    <form action="{{ route('showAttendeeInfo', $camp_id) }}" method="post" class="form-horizontal">
        @csrf
        <input type="hidden" name="snORadmittedSN" value="{{ $applicant->id }}">
        <input type="submit" class="btn btn-secondary float-right" value="返回學員資料頁面">
    </form>

    <script>
        function confirmdelete(form) {
            if (confirm('確認刪除？')) {
                form.submit();
            }
        }
    </script>
@endsection