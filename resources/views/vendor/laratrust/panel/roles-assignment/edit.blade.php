@extends('vendor.laratrust.panel.layout')

@section('title', "修改" . (new $userModel)->resourceNameInMandarin)

@section('content')
    <div>
    </div>
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-32">
            <form
                method="POST"
                action="{{route('laratrustCustom.roles-assignment.update', ['camp_id' => $camp_id, 'roles_assignment' => $user->getKey(), 'model' => $modelKey])}}"
                class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200 p-8"
            >
                @csrf
                @method('PUT')
                <label class="block">
                    <span class="text-gray-700">Name</span>
                    <input
                        class="form-input mt-1 block w-full bg-gray-200 text-gray-600"
                        name="name"
                        placeholder="this-will-be-the-code-name"
                        value="{{$user->name ?? 'The model doesn\'t have a `name` attribute'}}"
                        readonly
                        autocomplete="off"
                    >
                </label>
                <span class="block text-gray-700 mt-4">職務</span>
                <div class="flex flex-wrap justify-start mb-4">
                    @foreach ($roles as $role)
                        <label class="inline-flex items-center mr-6 my-2 text-sm" style="flex: 1 0 20%;">
                            <input
                                type="checkbox"
                                @if ($role->assigned && !$role->isRemovable)
                                    class="form-checkbox focus:shadow-none focus:border-transparent text-gray-500 h-4 w-4"
                                @else
                                    class="form-checkbox h-4 w-4"
                                @endif
                                name="roles[]"
                                value="{{$role->getKey()}}"
                                {!! $role->assigned ? 'checked' : '' !!}
                                {!! $role->assigned && !$role->isRemovable ? 'onclick="return false;"' : '' !!}
                            >
                            <span class="ml-2 {!! $role->assigned && !$role->isRemovable ? 'text-gray-600' : '' !!}">
                                @if($role->batch){{ $role->batch->name }}:&nbsp;@endif{{ $role->camp?->abbreviation }}{{ $role->display_name ?? $role->section . " " . $role->position }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @if ($permissions)
                    <span class="block text-gray-700 mt-4">權限</span>
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
                        href="{{route("laratrustCustom.roles-assignment.index", ['camp_id' => $camp_id, 'model' => $modelKey])}}"
            class="btn btn-red mr-4"
          >
            取消
          </a>
          <button class="btn btn-blue" type="submit">儲存</button>
        </div>
      </form>
    </div>
  </div>
@endsection
