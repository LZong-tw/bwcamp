<div class="jumbotron mt-3 p-4" id="ioi-search">
    <!-- Happiness is not something readymade. It comes from your own actions. - Dalai Lama -->
    <div class="alert-primary mb-3 border border-secondary rounded col-8 py-2">
        <span>查詢條件：@if($queryRoles != "")
                {{ "(" }}@foreach($queryRoles as $role)
                    {{ $role->batch?->name ?? "不分梯" }}: {{ $role->section }}-{{ $role->position }}@if(!$loop->last || str_contains($queryStr, "(1 = 1)")) or @endif
                @endforeach
            @elseif ($queryRoles !="" && request()->isMethod('post'))
               {{ "( " }}
            @endif
            {{ str_replace("(1 = 1)", "未分組 )", $queryStr) ?? "無" }}
        </span>
    </div>
    @if($isShowVolunteers || $camp->table != "ceocamp")
        <ioi-search></ioi-search>
    @else
        @php
            $applicants = $camp->applicants;
            if ($currentBatch) {
                $applicants = $applicants->where('batch_id', $currentBatch->id);
            }
            $applicants_id = $applicants->pluck('id');
            $table = "\\App\\Models\\" . ucfirst($camp->table);
            $specificData = $table::whereIn('applicant_id', $applicants_id)->get();
            $industries = $specificData->pluck('industry')->unique();
            $industryOther = null;
            foreach ($industries as $key => $industry) {
                if ($industry == "其他" || $industry == "其它") {
                    $industryOther = $industry;
                    unset($industries[$key]);
                }
            }
        @endphp
        <h5>套用篩選條件</h5>
        <form action="" method="post">
            @csrf
            <input type="hidden" name="ceocamp_sets_learner" value="1">
            <div>組別：
                <span>
                    <label class="align-items-center"><input type="checkbox" name="group_id[]" value="na" @checked(is_array(old('group_id')) ? in_array("na", old('group_id')) : false)> 未分組</label>
                    {{-- todo: 待組織職務轉至梯次後做簡化 --}}
                    @if ($groups && !$isShowVolunteers)
                        @forelse($groups as $group)
                            @if(!$currentUser->canAccessResource($group, 'read', $camp))
                                @continue
                            @endif
                            @if ($currentBatch && $currentBatch->id == $group->batch->id)
                                <label class="align-items-center"><input type="checkbox" name="group_id[]" value="{{ $group->id }}" @checked(is_array(old('group_id')) ? in_array($group->id, old('group_id')) : false) class="ml-2"> {{ $group->alias }}</label>
                            @elseif (!$currentBatch)
                                <label class="align-items-center"><input type="checkbox" name="group_id[]" value="{{ $group->id }}" @checked(is_array(old('group_id')) ? in_array($group->id, old('group_id')) : false) class="ml-2"> {{ $group->batch->name }}梯 {{ $group->alias }}</label>
                            @endif
                        @empty
                        @endforelse
                    @elseif ($groups)
                        @forelse($groups as $group)
                            @if ($group->position != 'root')
                                <label class="align-items-center"><input type="checkbox" name="group_id[]" value="{{ $group->id }}" @checked(is_array(old('group_id')) ? in_array($group->id, old('group_id')) : false) class="ml-2"> {{ $group->section }}{{ $group->position }}</label>
                            @endif
                        @empty
                        @endforelse
                    @endif
                </span>
            </div>
            <div>性別：
                <span>
                    <label class="align-items-center"><input type="checkbox" name="gender[]" value="M" @checked(is_array(old('gender')) ? in_array("M", old('gender')) : false)> 男</label>
                    <label class="align-items-center"><input type="checkbox" name="gender[]" value="F" class="ml-2"  @checked(is_array(old('gender')) ? in_array("F", old('gender')) : false)> 女</label>
                </span>
            </div>
            <div>年齡：
                <span class="align-items-center">
                    <label class="align-items-center"><input type="checkbox" name="age[]" value="(age <= 30)" @checked(is_array(old('age')) ? in_array("(age <= 30)", old('age')) : false)> <= 30</label>
                    <label class="align-items-center"><input type="checkbox" name="age[]" value="(age >= 31 and age <= 40)" class="ml-2" @checked(is_array(old('age')) ? in_array("(age >= 31 and age <= 40)", old('age')) : false)> 31 ~ 40</label>
                    <label class="align-items-center"><input type="checkbox" name="age[]" value="(age >= 41 and age <= 50)" class="ml-2" @checked(is_array(old('age')) ? in_array("(age >= 41 and age <= 50)", old('age')) : false)> 41 ~ 50</label>
                    <label class="align-items-center"><input type="checkbox" name="age[]" value="(age >= 51 and age <= 60)" class="ml-2" @checked(is_array(old('age')) ? in_array("(age >= 51 and age <= 60)", old('age')) : false)> 51 ~ 60</label>
                    <label class="align-items-center"><input type="checkbox" name="age[]" value="(age >= 61 and age <= 70)" class="ml-2" @checked(is_array(old('age')) ? in_array("(age >= 61 and age <= 70)", old('age')) : false)> 61 ~ 70</label>
                    <label class="align-items-center"><input type="checkbox" name="age[]" value="(age >= 71)" class="ml-2" @checked(is_array(old('age')) ? in_array("(age >= 71)", old('age')) : false)> >= 71</label>
                </span>
            </div>
            <div class="d-flex align-items-center mb-2">
                <span>產業別：</span>
                <select name="industry[]" id="" class="form-control col-4">
                    <option value="" @selected(is_array(old('industry')) ? in_array("", old('industry')) : false)>請選擇</option>
                    @if ($industries)
                        @forelse($industries as $industry)
                            <option value="{{ $industry }}" @selected(is_array(old('industry')) ? in_array($industry, old('industry')) : false)>{{ $industry }}</option>
                        @empty
                        @endforelse
                    @endif
                    <option value="{{ $industryOther }}">{{ $industryOther }}</option>
                </select>
            </div>
            <div class="align-items-center">參加形式：
                <label class="align-items-center"><input type="checkbox" name="participation_mode[]" value="實體" @checked(is_array(old('participation_mode')) ? in_array("實體", old('participation_mode')) : false)> 實體</label>
                <label class="align-items-center"><input type="checkbox" name="participation_mode[]" value="線上" class="ml-2" @checked(is_array(old('participation_mode')) ? in_array("線上", old('participation_mode')) : false)> 線上</label>
                <label class="align-items-center"><input type="checkbox" name="participation_mode[]" value="皆可" class="ml-2" @checked(is_array(old('participation_mode')) ? in_array("皆可", old('participation_mode')) : false)> 皆可</label>
            </div>
            <div class="d-flex align-items-center mb-2">
                <span>學員姓名：</span>
                <input type="text" class="form-control col-4" name="applicants_name[]" value="{{ !is_null(old("applicants_name")) ? old("applicants_name")[0] : null }}">
            </div>
            <div class="d-flex  align-items-center mb-2">
                <span>推薦人姓名：</span>
                <input type="text" class="form-control col-4" name="introducer_name[]" value="{{ !is_null(old("introducer_name")) ? old("introducer_name")[0] : null }}">
            </div>
            <input type="submit" value="搜尋" class="btn btn-primary">
            <input type="reset" value="清除篩選條件 - 顥示所有@if($isShowLearners && $isShowVolunteers)關懷員@elseif($isShowVolunteers)義工@else學員@endif" class="ml-3 btn btn-danger"  onclick="window.location=window.location.href">
        </form>
    @endif
    <div class="alert alert-primary mt-3 mb-0">
        @if ($groups && !$isShowVolunteers)
            @php
                foreach ($groups as $key => $group) {
                    if ($currentBatch && $currentBatch->id != $group->batch->id) {
                        $groups->forget($key);
                    }
                }
            @endphp
            @forelse($groups->values() as $key => $group)
                @if(!$currentUser->canAccessResource($group, 'read', $camp))
                    @continue
                @endif
                @if($key % 4 == 0)<div class="row">@endif
                    <span class="col-3">
                        @if ($currentBatch && $currentBatch->id == $group->batch->id)
                            {{ $group->alias }}: <span class="text-primary">{{ $applicants->filter(function ($applicant) use ($group) { return
                    $applicant->group_id == $group->id && $applicant->gender == 'M'; })->count() }}</span> / <span class="text-danger">{{ $applicants->filter(function ($applicant) use ($group) { return
                    $applicant->group_id == $group->id && $applicant->gender == 'F'; })->count() }}</span> / <span class="text-success">{{ $applicants->filter(function ($applicant) use ($group) { return
                    $applicant->group_id == $group->id; })->count() }}</span>
                            <br>
                        @elseif (!$currentBatch)
                            {{ $group->batch->name }}&nbsp;-&nbsp;{{ $group->alias }}: <span class="text-primary">{{ $applicants->filter(function ($applicant) use ($group) { return
                    $applicant->group_id == $group->id && $applicant->gender == 'M'; })->count() }}</span> / <span class="text-danger">{{ $applicants->filter(function ($applicant) use ($group) { return
                    $applicant->group_id == $group->id && $applicant->gender == 'F'; })->count() }}</span> / <span class="text-success">{{ $applicants->filter(function ($applicant) use ($group) { return
                    $applicant->group_id == $group->id; })->count() }}</span>
                            <br>
                        @endif
                    </span>
                @if($key % 4 == 3 || $loop->last)</div>@endif
            @empty
            @endforelse
        @endif
    </div>
</div>

