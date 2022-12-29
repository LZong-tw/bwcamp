@extends('vendor.laratrust.panel.layout')

@section('title', '職務清單')

@section('content')
  <div class="flex flex-col">
{{--    <a--}}
{{--      href="{{route('laratrustCustom.roles.create', ["camp_id" => $camp_id])}}"--}}
{{--      class="self-end bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"--}}
{{--    >--}}
{{--      + 新增職務--}}
{{--    </a>--}}
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
      <div class="mt-4 align-middle inline-block w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
        <table class="w-full">
          <thead>
            <tr>
              <th class="th">Id</th>
              <th class="th">職務名稱</th>
              <th class="th">系統名稱</th>
              <th class="th"># 權限</th>
              <th class="th"></th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach ($roles as $role)
                @if($role->position == 'root')
                    @continue
                @endif
                <tr>
                  <td class="td text-sm leading-5 text-gray-900">
                    {{$role->getKey()}}
                  </td>
                  <td class="td text-sm leading-5 text-gray-900">
                    {{$role->display_name ?? $role->section . ' ' . $role->position }}
                  </td>
                  <td class="td text-sm leading-5 text-gray-900">
                    {{$role->name}}
                  </td>
                  <td class="td text-sm leading-5 text-gray-900">
                    {{$role->permissions_count}}
                  </td>
                  <td class="flex justify-end px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                    @if (\Laratrust\Helper::roleIsEditable($role))
                    <a href="{{route('laratrustCustom.roles.edit', ["camp_id" => $camp_id, $role->getKey()])}}" class="text-blue-600 hover:text-blue-900">編輯</a>
                    @else
                    <a href="{{route('laratrustCustom.roles.show', ["camp_id" => $camp_id, $role->getKey()])}}" class="text-blue-600 hover:text-blue-900">Details</a>
                    @endif
                    <form
                      action="{{route('laratrustCustom.roles.destroy', ["camp_id" => $camp_id, $role->getKey()])}}"
                      method="POST"
                      onsubmit="return confirm('Are you sure you want to delete the record?');"
                    >
                      @method('DELETE')
                      @csrf
                      <button
                        type="submit"
                        class="{{\Laratrust\Helper::roleIsDeletable($role) ? 'text-red-600 hover:text-red-900' : 'text-gray-600 hover:text-gray-700 cursor-not-allowed'}} ml-4"
                        @if(!\Laratrust\Helper::roleIsDeletable($role)) disabled @endif
                      >刪除</button>
                    </form>
                  </td>
                </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
{{--  共 {{ $roles->total() }} 筆--}}
  {{ $roles->links('vendor.laratrust.panel.pagination') }}
@endsection
