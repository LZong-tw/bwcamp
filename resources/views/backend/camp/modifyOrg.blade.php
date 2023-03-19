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
    <h2>{{ $camp->abbreviation }} 修改組織職務 </h2>
    <form action="{{ route("modifyOrg", [$camp->id, $org->id]) }}" method="POST">
        @csrf
        <div class='row form-group'>
            <label for='inputSec' class='col-md-2 control-label'>大組名稱</label>
            @if($org->position == 'root')
            <div class='col-md-6'>
                <input type="string" name="section" id="" class='form-control' value="{{ $org->section ?? "" }}">
            </div>
            @else
            <div class='col-md-6'>
                <input type='hidden' name='section' value='{{ $org->section }}'>
                {{ $org->section }}
            </div>
            @endif
        </div>
        @if($org->position != 'root')
        <div class='row form-group'>
            <label for='inputPos' class='col-md-2 control-label'>職務名稱</label>
            <div class='col-md-6'>
                <input type="string" name="position" id="" class='form-control' value="{{ $org->position ?? "" }}">
            </div>
            <div class='ml-3 mt-3'>
                @include('backend.camp.permission_table')
            </div>
        </div>
        @else
        <input type='hidden' name='position' value='{{ $org->position }}'>
        @endif
        <input type="submit" class="btn btn-success" value="確認修改">
        <a href="{{ route('showOrgs', $camp->id) }}" class="btn btn-danger">取消修改</a>
    </form>
    <script>
        (function () {
            let checkboxes = document.getElementsByClassName("checkbox");
            let radios = document.getElementsByClassName("radio");
            for (let i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked && checkboxes[i].parentNode.classList.add("bg-success");
                checkboxes[i].addEventListener("change", function () {
                    if (this.checked) {
                        this.parentNode.classList.add("bg-success");
                    }
                    else {
                        let checked_actions = 0;
                        document.getElementsByName(this.name).forEach(item => checked_actions ||= item.checked);
                        if (!checked_actions) {
                            for (let i = 0; i < radios.length; i++) {
                                let action = this.name.replace("[", "").replace("]", "").replace("[", "").replace("]", "").replace("resources", "");
                                let range = radios[i].name.replace("[", "").replace("]", "").replace("range", "");
                                if (range == action) {
                                    radios[i].checked = false;
                                    radios[i].parentNode.classList.remove("bg-success");
                                }
                            }
                        }
                        this.parentNode.classList.remove("bg-success");
                    }
                });
            }
            for (let i = 0; i < radios.length; i++) {
                radios[i].checked && radios[i].parentNode.classList.add("bg-success");
                radios[i].addEventListener("change", function () {
                    if (this.checked) {
                        this.parentNode.classList.add("bg-success");
                    }
                    else {
                        this.parentNode.classList.remove("bg-success");
                    }
                });
            }
        })();
    </script>
@endsection
