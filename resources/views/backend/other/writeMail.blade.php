@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 寄送自定郵件</h2>
    <h5>
        寄送目標：
        @if(request()->target == 'all')
            全體錄取人士
        @elseif(request()->target == 'batch') 
            {{ \App\Models\Batch::find(request()->batch_id)->name }} 梯次錄取人士
        @elseif(request()->target == 'group_id') 
            {{ \App\Models\Batch::find(request()->batch_id)->name }} 梯次 {{ request()->group_no }} 組錄取人士
        @endif
    </h5>
    <form action="{{ route("sendMail", $campFullData->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="target" value="{{ request()->target }}">
        <input type="hidden" name="batch_id" value="{{ request()->batch_id }}">
        <input type="hidden" name="group_id" value="{{ request()->group_id }}">
        <input type="hidden" name="camp_id" value="{{ $campFullData->id }}">
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label text-md-left'>主旨</label>
            <div class='col-md-11'>
                <input type='text' name='subject' value='' class='form-control' placeholder='請填寫主旨' required>
            </div>
            <div class="invalid-feedback">
                請填寫主旨
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-1 control-label text-md-left'>內文</label>
            <div class='col-md-11'>
                <textarea class='form-control' rows=20 name='content' required></textarea>
            </div>
            <div class="invalid-feedback">
                請填寫內文
            </div>
        </div>
        <div class='row form-group' id="file">
            <label for='inputName' class='col-md-1 control-label text-md-left'>附件1</label>
            <div class="input-group mb-3 col-md-11">
                <input type="file" name='attachment1' id="customFile">
            </div>
        </div> 
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label text-md-left'>附件2</label>
            <div class="input-group mb-3 col-md-11">
                <input type="file" name='attachment2' id="customFile2">
            </div>
        </div> 
        <div class='row form-group'>
            <label for='inputName' class='col-md-1 control-label text-md-left'>附件3</label>
            <div class="input-group mb-3 col-md-11">
                <input type="file" name='attachment3' id="customFile3">
            </div>
        </div>    
        {{-- <a href="#" onclick="duplicates();" id="add" class="btn btn-primary">+</a>     --}}
        <input type="submit" value="寄出" class="btn btn-success">
    </form>
    <script>
        {{-- let ele = document.getElementById("file");
        ele.id = "";
        function duplicates(){
            let add = document.getElementById("add");
            let br = document.createElement('br');
            let a = add.parentNode.insertBefore(ele, add.previousSibling);
            let b = add.parentNode.insertBefore(br, add.previousSibling);
            console.log(a, b);
        }--}}
    </script>
@endsection