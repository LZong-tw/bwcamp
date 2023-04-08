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
            <th rowspan="2" class="align-middle col-3">這些東西包含什麼</th>
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
            @if($resource["name"] && $resource["description"])
                <tr>
                    <td>
                        {{ $resource["name"] }}
                        <input type="hidden" name="resources_name[{{ $resource["class"] }}]" value="{{ $resource["name"] }}">
                    </td>
                    <td>{{ $resource["description"] ?? '' }}</td>
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
                    document.getElementsByName(this.name).forEach(item => {
                        if (item != this) {
                            item.checked = false;
                            item.parentNode.classList.remove("bg-success");
                        }
                    });
                }
            });
        }
    })();
</script>
