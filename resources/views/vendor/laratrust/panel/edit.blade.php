@extends('vendor.laratrust.panel.layout')

@section('title', $model ? "修改{$typeInMandarin}" : "新{$typeInMandarin}")

@section('content')
    <div>
    </div>
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 lg:-mx-8 @if($type != 'role') lg:px-32 sm:px-6 @endif">
            <form
                x-data="laratrustForm()"
                x-init="{!! $model ? '' : '$watch(\'displayName\', value => onChangeDisplayName(value))'!!}"
                method="POST"
                action="{{$model ?
                    route("laratrustCustom.{$type}s.update", ["camp_id" => $camp_id, $model->getKey()]) :
                    route("laratrustCustom.{$type}s.store", compact("camp_id"))
                }}"
                class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200 @if($type != 'role') p-8 @endif"
            >
                @csrf
                @if ($model)
                    @method('PUT')
                @endif
                <label class="block">
                    <span class="text-gray-700">系統名稱</span>
                    <input
                        class="form-input mt-1 block w-full bg-gray-200 text-gray-600 @error('name') border-red-500 @enderror"
                        name="name"
                        placeholder="將在新增後自動產生"
                        value="{{ old('name', $model ? $model->name : '') }}"
                        readonly
                        autocomplete="off"
                    >
                    @error('name')
                    <div class="text-red-500 text-sm mt-1">{{ $message}} </div>
                    @enderror
                </label>

                @if($type === 'role')
                    <label class="block mt-4">
                        <span class="text-gray-700">職務名稱</span>
                        <div class="mt-1 block w-full">@if($model->batch){{ $model->batch->name }}:&nbsp;@endif{{ $model->display_name ?? $model->section . ' ' . $model->position }}</div>
                    </label>
                @endif

                @if($type == 'permission')
                    <label class="block my-4">
                        <span class="text-gray-700">顯示名稱</span>
                        <input
                            class="form-input mt-1 block w-full"
                            name="display_name"
                            placeholder="顯示名稱"
                            x-model="displayName"
                            autocomplete="on"
                            required
                        >
                    </label>

                    <label class="block my-4">
                        <div class="text-gray-700">可操作資源</div>
                        @if($modelsAvailable ?? false)
                            <select x-model="resource" name="resource" id="" class='form-select' required>
                                <option value="">請選擇</option>
                                @foreach($modelsAvailable as $key => $availableModel)
                                    <option value="{{ $availableModel['class'] }}" @if($model?->resource == $availableModel['class']) checked @endif>{{ $availableModel["name"] }}</option>
                                @endforeach
                            </select>
                        @else
                            資源不存在或發生錯誤，請洽營隊窗口
                        @endif
                    </label>

                    <label class="block my-4">
                        <div class="text-gray-700">範圍</div>
                        <input type="radio" name="range" id="" value="na" @if($model?->range == 'na' || !$model) checked @endif> 不指定
                        <input type="radio" name="range" id="" value="person" @if($model?->range == 'person') checked @endif> 限個別學員
                        <input type="radio" name="range" id="" value="learner_group" @if($model?->range == 'learner_group') checked @endif> 限學員小組
                        <input type="radio" name="range" id="" value="volunteer_large_group" @if($model?->range == 'volunteer_large_group') checked @endif> 限義工大組
                        <input type="radio" name="range" id="" value="all" @if($model?->range == 'all') checked @endif> 全部
                    </label>

                    <label class="block my-4">
                        <div class="text-gray-700">動作</div>
                        <input type="radio" name="action" id="" value="create" @if($model?->action == "create") {{ "checked" }} @endif required>新增</option>
                        <input type="radio" name="action" id="" value="read" @if($model?->action == "read") {{ "checked" }} @endif required>查詢</option>
                        <input type="radio" name="action" id="" value="assign" @if($model?->action == "assign") {{ "checked" }} @endif required>指派</option>
                        <input type="radio" name="action" id="" value="update" @if($model?->action == "update") {{ "checked" }} @endif required>修改</option>
                        <input type="radio" name="action" id="" value="delete" @if($model?->action == "delete") {{ "checked" }} @endif required>刪除</option>
                    </label>
                @endif

                <label class="block my-4">
                    <span class="text-gray-700">Description</span>
                    <textarea
                        class="form-textarea mt-1 block w-full"
                        rows="3"
                        name="description"
                        placeholder="Some description for the {{$type}}"
                    >{{ $model->description ?? old('description') }}</textarea>
                </label>
                @if($type == 'role')
                    @error('range')
                        <div class="text-red-500 text-sm mt-1">{{ $message}} </div>
                    @enderror
                    @error('resources')
                        <div class="text-red-500 text-sm mt-1">{{ $message}} </div>
                    @enderror
                    <table class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th rowspan="2" class="align-middle">義工可以操作的東西</th>
                                <th rowspan="2" class="align-middle">這些東西包含什麼</th>
                                <th colspan="5">他可以做什麼動作</th>
                                <th colspan="5">這個動作的影響範圍</th>
                            </tr>
                            <tr>
                                <th>指派</th>
                                <th>瀏覽</th>
                                <th>新增</th>
                                <th>修改</th>
                                <th>刪除</th>

                                <th>不指定</th>
                                <th>限學員小組</th>
                                <th>限義工大組</th>
                                <th>限個別學員</th>
                                <th>全部</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($availableResources as $key => $resource)
                                @if ($key != 0 && $key % 14 == 0)
                                    <tr>
                                        <th rowspan="2" class="align-middle">義工可以操作的東西</th>
                                        <th rowspan="2" class="align-middle">這些東西包含什麼</th>
                                        <th colspan="5">他可以做什麼動作</th>
                                        <th colspan="5">這個動作的影響範圍</th>
                                    </tr>
                                    <tr>
                                        <th>指派</th>
                                        <th>瀏覽</th>
                                        <th>新增</th>
                                        <th>修改</th>
                                        <th>刪除</th>

                                        <th>不指定</th>
                                        <th>限學員小組</th>
                                        <th>限義工大組</th>
                                        <th>限個別學員</th>
                                        <th>全部</th>
                                    </tr>
                                @endif
                                @if($resource["name"])
                                    <tr>
                                        <td>
                                            {{ $resource["name"] }}
                                            <input type="hidden" name="resources_name[{{ $resource["class"] }}]" value="{{ $resource["name"] }}">
                                        </td>
                                        <td>{{ $resource["description"] ?? '說明' }}</td>
                                        @foreach(["assign", "read", "create", "update", "delete"] as $action)
                                            <td>
                                                <input type="checkbox" name="resources[{{ $resource["class"] }}][]" value="{{ $action }}" class="checkbox" @checked($complete_permissions->where('resource', $resource["class"])->where('action', $action)->count())>
                                            </td>
                                        @endforeach
                                        @foreach(["na", "learner_group", "volunteer_large_group", "person", "all"] as $range)
                                            <td>
                                                <input type="radio" name="range[{{ $resource["class"] }}]" class="radio" value="{{ $range }}" @checked(old('range' . "[{$resource["class"]}]") == $range || $complete_permissions->where('resource', $resource["class"])->where('range', $range)->count())>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <span class="block text-gray-700">權限</span>
                    <div class="flex flex-wrap justify-start mb-4">
                        @foreach ($permissions as $permission)
                            <label class="inline-flex items-center mr-6 my-2 text-sm" style="flex: 1 0 20%;">
                                <input
                                    type="checkbox"
                                    class="form-checkbox h-4 w-4"
                                    name="permissions[]"
                                    value="{{$permission->getKey()}}"
                                    {!! $permission->assigned ? 'checked' : '' !!}
                                >
                                <span class="ml-2">{{$permission->display_name ?? $permission->name}}</span>
                            </label>
                        @endforeach
                    </div>
                @endif
                <div class="flex justify-end">
                    <a
                        href="{{route("laratrustCustom.{$type}s.index", compact("camp_id"))}}"
                        class="btn btn-red mr-4"
                    >
                        取消
                    </a>
                    <button class="btn btn-blue" type="submit">儲存</button>
                </div>
            </form>
        </div>
    </div>
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
        window.laratrustForm = function () {
            return {
                displayName: '{{ $model->display_name ?? old('display_name') }}',
                {{--name: '{{ $model->name ?? old('name') }}',--}}
                {{--toKebabCase(str) {--}}
                {{--    return str &&--}}
                {{--        str--}}
                {{--            .match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)--}}
                {{--            .map(x => x.toLowerCase())--}}
                {{--            .join('-')--}}
                {{--            .trim();--}}
                {{--},--}}
                onChangeDisplayName(value) {
                    // this.name = this.toKebabCase(value);
                }
            }
        }
    </script>
@endsection
