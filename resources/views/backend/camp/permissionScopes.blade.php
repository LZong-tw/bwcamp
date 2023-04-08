@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 職務適用範圍</h2>
    @if(isset($message))
        @foreach ($message as $m)
            <div class="alert alert-success" role="alert">
                {{ $m }}
            </div>
        @endforeach
    @endif
    @if(isset($error))
        @foreach ($error as $e)
            <div class="alert alert-danger" role="alert">
                {{ $e }}
            </div>
        @endforeach
    @endif
    <style>
        .modal-dialog {
            z-index: 9999!important;
        }
        .modal-backdrop {
            display: none;
            z-index: -1!important;
        }
    </style>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">
        新增職務適用範圍
    </button>
    <br>
    <br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>小組及職務名稱</th>
                <th>範圍</th>
                <th>操作</th>
            </tr>
        </thead>
            <tr>
                <td>{{ $applicant?->id }}</td>
                <td>
                    {{ $applicant?->name }}({{ $applicant?->gender }})
                </td>
                <td>{{ $applicant?->region }}</td>
            </tr>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">新增職務適用範圍</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" class="d-inline">
                    <div class='row form-group required'>
                        <label for='inputName' class='col-md-4 control-label text-md-right'>小組及職務名稱</label>
                        <div class='col-md-8'>
                            <input type='text' name='name' value='' class='form-control' id='inputName' placeholder='' required >
                        </div>
                    </div>
                    <div class='row form-group required'>
                        <label for='inputName' class='col-md-4 control-label text-md-right'>適用範圍</label>
                        <div class='col-md-8'>
                            <label><input type="checkbox" name=scope[] value='廣論班' > 廣論班</label> <br/>
                            <label><input type="checkbox" name=scope[] value='校園減塑點亮計畫' > 校園減塑點亮計畫</label> <br/>
                            <label><input type="checkbox" name=scope[] value='幸福教育學等研習課程' > 幸福教育學等研習課程</label> <br/>
                            <label><input type="checkbox" name=scope[] value='其他' onchange="toggleBTCrequired()"> 其它</label> <br>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">離開</button>
            {{-- <button type="button" class="btn btn-primary">Understood</button> --}}
            </div>
        </div>
        </div>
    </div>
    <script>
    </script>
@endsection
