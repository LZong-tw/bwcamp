@extends('vendor.laratrust.panel.layout')

@section('title', '權限清單')

@section('content')
  <div class="flex flex-col">
    @if (config('laratrust.panel.create_permissions'))
    <a
      href="{{ route('laratrustCustom.permissions.create', compact("camp_id")) }}"
      class="self-end bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
    >
      + 新增權限
    </a>
    @endif
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
      <div class="mt-4 align-middle inline-block w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
        <table class="w-full">
          <thead>
            <tr>
              <th class="th">Id</th>
              <th class="th">顯示名稱</th>
              <th class="th">可操作資源</th>
              <th class="th">動作</th>
              <th class="th">範圍</th>
              <th class="th">Description</th>
              <th class="th"></th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach ($permissions as $permission)
            <tr>
              <td class="td text-sm leading-5 text-gray-900">
                {{$permission->getKey()}}
              </td>
              <td class="td text-sm leading-5 text-gray-900">
                {{$permission->display_name}}
              </td>
                <td class="td text-sm leading-5 text-gray-900">
                    @foreach($models as $model)
                        @if($model["class"] == $permission->resource) {{ $model["name"] }} @endif
                    @endforeach
                </td>
                <td class="td text-sm leading-5 text-gray-900">
                    {{
                        match ($permission->action) {
                            'create' => '新增',
                            'update' => '修改',
                            'read' => '查詢',
                            'delete' => '刪除',
                            default => '未知',
                        }
                    }}
                </td>
                <td class="td text-sm leading-5 text-gray-900">
                    {{
                        match ($permission->range) {
                            'na' => '未指定',
                            'person' => '限個別學員',
                            'learner_group' => '限學員小組',
                            'volunteer_large_group' => '限義工大組',
                            'all' => '全部',
                            default => '未知',
                        }
                    }}
                </td>
              <td class="td text-sm leading-5 text-gray-900">
                {{$permission->description}}
              </td>
              <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                <a href="{{ route('laratrustCustom.permissions.edit', ["camp_id" => $camp_id, $permission->getKey()]) }}" class="text-blue-600 hover:text-blue-900">編輯</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  共 {{ $permissions->total() }} 筆
  {{ $permissions->links('vendor.laratrust.panel.pagination') }}
@endsection
