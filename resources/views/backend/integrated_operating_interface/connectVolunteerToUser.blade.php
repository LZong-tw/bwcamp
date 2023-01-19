@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 義工授權</h2>
    <div class="alert alert-info pb-1 pt-3"><h4>即將指派<span class="text-danger font-weight-bold">{{ $group->section }}{{ $group->position }}</span>予以下人員，並執行相關流程，請針對各人員資料再次進行檢查或處理</h4></div>
    <form action="{{ route("userConnectionPOST", $campFullData->id) }}" method="post" class="form-horizontal">
        @csrf
        <input type="hidden" name="group_id" value="{{ $group->id }}">
        <div>
            <div class="row border-left border-right border-top border-info ml-0 mr-0 rounded-top">
                <span class="col-1 border border-info py-2 bg-info text-white">資料類別</span>
                <span class="col-1 border border-info py-2 bg-info text-white">ID</span>
                <span class="col-1 border border-info py-2 bg-info text-white">姓名</span>
                <span class="col border border-info py-2 bg-info text-white">Email</span>
                <span class="col-2 border border-info py-2 bg-info text-white">手機</span>
                <span class="col border border-info py-2 bg-info text-white" style="border-top-right-radius: 0.25em">將執行</span>
            </div>
            @foreach($list as $index => $candidate)
                <div class="row border-left border-right border-info @if($loop->last) border-bottom rounded-bottom @endif ml-0 mr-0">
                    <span class="col-1 border border-info py-2" @if($loop->last) style="border-bottom-left-radius: 0.25em" @endif>{{ $candidate["type"] }}</span>
                    <span class="col-1 border border-info py-2">{{ $candidate["data"]->id }}</span>
                    <span class="col-1 border border-info py-2">{{ $candidate["data"]->name }}</span>
                    <span class="col border border-info py-2">{{ $candidate["data"]->email }}</span>
                    <span class="col-2 border border-info py-2">{{ $candidate["data"]->mobile }}</span>
                    <span class="col border border-info py-2" @if($loop->last) style="border-bottom-right-radius: 0.25em" @endif>
                        @if ($candidate["action"])
                            {{ $candidate["action"] }}
                            <input type="hidden" name="candidates[{{ $index }}][type]" value="user">
                            <input type="hidden" name="candidates[{{ $index }}][id]" value="{{ $candidate["data"]->id }}">
                            <input type="hidden" name="candidates[{{ $index }}][uses_user_id]" id="" value="{{ $candidate["data"]->id }}">
                        @else
                            @php
                                $occurrences = \App\Models\User::where('email', 'like', "%". $candidate["data"]->email . "%")
                                                ->orWhere('name', 'like', "%". $candidate["data"]->name . "%")
    //                                            ->orWhereLike('mobile', "%". $candidate["data"]->mobile . "%")
                                                ->get();
                            @endphp
                            @if ($occurrences->count() > 0)
                                使用 <br>
                            @endif
                            <input type="hidden" name="candidates[{{ $index }}][type]" value="applicant">
                            <input type="hidden" name="candidates[{{ $index }}][id]" value="{{ $candidate["data"]->id }}">
                            @forelse($occurrences as $occurrence)
                                <label><input type="radio" name="candidates[{{ $index }}][uses_user_id]" id="" value="{{ $occurrence->id }}" required @if($occurrence->email == $candidate["data"]->user?->email) checked disabled @elseif($candidate["data"]->user) disabled @endif> {{ $occurrence->name }}({{ $occurrence->email }})</label> <br>
                            @empty
                                自動建立新帳號，並指派職務至此帳號<br>
                                帳號：{{ $candidate["data"]->email }}<br>
                                密碼：{{ $candidate["data"]->mobile }}<br>
                                <input type="hidden" name="candidates[{{ $index }}][uses_user_id]" id="" value="generation_needed" @if($candidate["data"]->user) disabled @endif>
    {{--                            密碼：{{ $candidate["data"]->birthyear }}{{ sprintf("%02d", $candidate["data"]->birthmonth) }}{{ sprintf("%02d", $candidate["data"]->birthday) }}<br>--}}
                            @endforelse
                            @if ($occurrences->count() > 0 && !$candidate["data"]->user)
                                或<label><input type="radio" name="candidates[{{ $index }}][uses_user_id]" id="" value="generation_needed_custom" required> 手動建立帳號</label><br>
                                帳號：<input type="email" name="candidates[{{ $index }}][email]" id="" placeholder="Email" class="form-control"><br>
                                密碼：<input type="text" name="candidates[{{ $index }}][password]" id="" placeholder="任意密碼" class="form-control"><br>
                                做為此人員之登入帳號，並指派職務至此帳號
                            @endif
                        @endif
                    </span>
                </div>
            @endforeach
            <input type="submit" class="btn btn-success mt-3" value="送出">
        </div>
    </form>
    <script>
        (function() {

        })();
    </script>
@endsection
