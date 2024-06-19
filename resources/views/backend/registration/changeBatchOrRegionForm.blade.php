@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 修改梯次 / 區域</h2>
    @if($candidate)
        @if(isset($message))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif
        @if(isset($error))
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endif
        <p>
            <h5>{{ $candidate->name }}({{ $candidate->gender }})</h5>
            報名序號：{{ $candidate->applicant_id }} <br>
            區域：{{ $candidate->region }} <br>
            梯次：
            @foreach ($batches as $batch)
                @if($candidate->batch_id == $batch->id) {{ $batch->name }}梯 @endif
            @endforeach <br>
            <form target="_blank" action="{{ route("queryview", $candidate->batch_id) }}" method="post" class="d-inline">
                @csrf
                <input type="hidden" name="sn" value="{{ $candidate->applicant_id }}">
                <input type="hidden" name="name" value="{{ $candidate->name }}">
                <input type="hidden" name="isBackend" value="目前為後台檢視狀態。">
                <button class="btn btn-info" style="margin-top: 10px">檢視報名資料</button>
            </form>
        </p>
        <form action="{{ route("changeBatchOrRegion", $campFullData->id) }}" method="post" class="form-horizontal">
            @csrf
            <input type="hidden" name="id" value="{{ $candidate->applicant_id ?? $candidate->id }}">
            梯次：
            <select class="form-control" name=batch size=1>
                @foreach ($batches as $batch)
                    <option value='{{ $batch->id}}' @if($candidate->batch_id == $batch->id) selected @endif>{{ $batch->name }}梯</option>
                @endforeach
            </select>
            <br>
            區域：{{-- <input type="text" name="region" class="form-control" value="{{ $candidate->region }}"> --}}
            {{-- （區域列表：台北、桃園、新竹、台中、嘉義、台南、高雄、海外） --}}
            <select name="region_id" class="form-control" required>
                <option value=''>請選擇</option>
                @foreach ($regions as $r)
                    <option value="{{ $r->id }}" @if($candidate->region_id == $r->id || $candidate->region == $r->name) selected @endif>{{ $r->name }}</option>
                @endforeach
            </select>
            <br><br>
            <input type="submit" class="btn btn-success" value="送出修改">
        </form><br>
        <a href="{{ route("changeBatchOrRegionGET", $campFullData->id) }}" class="btn btn-primary">下一筆</a>
    @elseif (count($result))
        @if(isset($message))
            <div class="alert alert-success" role="alert">
                {!! $message !!}
            </div>
            <a href="{{ route("changeBatchOrRegionGET", $campFullData->id) }}" class="btn btn-primary mb-3">下一批</a>
        @endif
        @if(isset($error))
            <div class="alert alert-danger" role="alert">
                {!! $error !!}
            </div>
            <a href="{{ route("changeBatchOrRegionGET", $campFullData->id) }}" class="btn btn-primary mb-3">下一批</a>
        @endif
        <table class="table table-bordered">
            <tr>
                <th>姓名（性別）</th>
                <th>報名序號</th>
                <th>區域</th>
                <th>梯次</th>
                <th>檢視報名資料</th>
            </tr>
            @foreach ($result as $c)
                @if (is_string($c))
                    <tr>
                        <td colspan="5">
                            {{ $c }}
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $c->name }}（{{ $c->gender }}）</td>
                        <td>{{ $c->applicant_id }}</td>
                        <td>
                            <select class="form-control" name=batch_id size=1 onchange="document.getElementById('{{ $c->applicant_id }}_batch_id').value=this.value">
                                @foreach ($batches as $batch)
                                    <option value='{{ $batch->id}}' @if($c->batch_id == $batch->id) selected @endif>{{ $batch->name }}梯</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="region_id" class="form-control" onchange="document.getElementById('{{ $c->applicant_id }}_region_id').value=this.value">
                                <option value=''>請選擇</option>
                                @foreach ($regions as $r)
                                    <option value="{{ $r->id }}" @if($c->region_id == $r->id || $c->region == $r->name) selected @endif>{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <form target="_blank" action="{{ route("queryview", $c->batch_id) }}" method="post">
                                @csrf
                                <input type="hidden" name="sn" value="{{ $c->applicant_id }}">
                                <input type="hidden" name="name" value="{{ $c->name }}">
                                <input type="hidden" name="isBackend" value="目前為後台檢視狀態。">
                                <button class="btn btn-info" style="margin-top: 10px">檢視報名資料</button>
                            </form>
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
        <form action="{{ route("massChangeBatchOrRegion", $campFullData->id) }}" method="post">
            @csrf
            @foreach ($result as $c)
                @if (is_string($c))
                    @continue
                @endif
                <input type="hidden" name="applicant_id[]" id="{{ $c->applicant_id }}_id" value="{{ $c->applicant_id }}">
                <input type="hidden" name="batch_id_new[]" id="{{ $c->applicant_id }}_batch_id" value="{{ $c->batch_id }}">
                <input type="hidden" name="region_id_new[]" id="{{ $c->applicant_id }}_region_id" value="{{ $c->region_id }}">
            @endforeach
            <input type="submit" class="btn btn-success" value="送出修改">
        </form>
    @else
        <p>
            查無資料，請 <a href="{{ route("changeBatchOrRegionGET", $campFullData->id) }}" class="btn btn-primary">重新執行</a>。
        </p>
    @endif
@endsection
