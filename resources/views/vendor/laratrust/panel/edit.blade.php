@extends('vendor.laratrust.panel.layout')

@section('title', $model ? "修改{$typeInMandarin}" : "新{$typeInMandarin}")

@section('content')
    <div>
    </div>
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-32">
            <form
                x-data="laratrustForm()"
                x-init="{!! $model ? '' : '$watch(\'displayName\', value => onChangeDisplayName(value))'!!}"
                method="POST"
                action="{{$model ?
                    route("laratrustCustom.{$type}s.update", ["camp_id" => $camp_id, $model->getKey()]) :
                    route("laratrustCustom.{$type}s.store", compact("camp_id"))
                }}"
                class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200 p-8"
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

                <label class="block my-4">
                    <span class="text-gray-700">顯示名稱</span>
                    <input
                        class="form-input mt-1 block w-full"
                        name="display_name"
                        placeholder="權限名稱"
                        x-model="displayName"
                        autocomplete="on"
                        required
                    >
                </label>

                @if($type == 'permission')
                    <label class="block my-4">
                        <div class="text-gray-700">可操作資源</div>
                        @if($modelsAvailable ?? false)
                            <select x-model="resource" name="resource" id="" class='form-select' required>
                                <option value="">請選擇</option>
                                @foreach($modelsAvailable as $key => $availableModel)
                                    <option value="{{ $availableModel['class'] }}" >{{ $availableModel["name"] }}</option>
                                @endforeach
                            </select>
                        @else
                            資源不存在或發生錯誤，請洽營隊窗口
                        @endif
                    </label>

                    <label class="block my-4">
                        <div class="text-gray-700">動作</div>
                        <select name="action" id="" class='form-select' required>
                            <option value="">請選擇</option>

                            <option value="create" @if($model?->action == "create") {{ "selected" }} @endif>新增</option>
                            <option value="read" @if($model?->action == "read") {{ "selected" }} @endif>查詢</option>
                            <option value="update" @if($model?->action == "update") {{ "selected" }} @endif>修改</option>
                            <option value="delete" @if($model?->action == "delete") {{ "selected" }} @endif>刪除</option>
                        </select>
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
