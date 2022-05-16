@extends('backend.master')
@section('content')
@include('..partials.counties_areas_script')
<style>
    .card-link{
        color: #3F86FB!important;
    }
    .card-link:hover{
        color: #33B2FF!important;
    }
</style>
<h2>報名檢視及下載</h2>
<form method=post action="{{ route('getRegistrationList', $campFullData->id) }}" class="form-inline" name="Camp">
    @csrf
    <table class="table table-responsive">
        <tr>
            <td>
                <0> 
                    按報名日期降冪排序 <input type="checkbox" name="orderByCreatedAtDesc" id="" value="1" @if(old('orderByCreatedAtDesc')) checked @endif><br>
                    設定下載 <input type="checkbox" name="download" id=""><br>
                    僅已取消名單 <input type="checkbox" name="show_cancelled" id="" value="1" @if(old('show_cancelled')) checked @endif>
            </td>
        </tr>
        <tr>
            <td align=left valign=middle nowrap>
                <1> 區域：
                @if($campFullData->table == "hcamp")
                    <input class="btn btn-primary" type=submit name=region value='全區'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='北部'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='東部'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='中部'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='南部'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='金馬'>&nbsp;
                @elseif($campFullData->table == "acamp")
                    <input class="btn btn-primary" type=submit name=region value='全區'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='北苑'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='北區'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='基隆'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='桃區'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='竹區'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='中區'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='雲嘉'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='台南'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='高屏'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='其他'>&nbsp;
                @elseif($campFullData->table == "ceocamp" || $campFullData->table == "ceovcamp")
                    <input class="btn btn-primary" type=submit name=region value='全區'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='北區'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='竹區'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='中區'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='高區'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='其他'>&nbsp;
                @elseif($campFullData->table == "ecamp")
                    <input class="btn btn-primary" type=submit name=region value='全區'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='台北'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='桃園'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='新竹'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='中區'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='雲嘉'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='台南'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='高區'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='其他'>&nbsp;
                @else
                    <input class="btn btn-primary" type=submit name=region value='全區'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='台北'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='桃園'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='新竹'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='台中'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='雲嘉'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='台南'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='高雄'>&nbsp;
                    <input class="btn btn-primary" type=submit name=region value='其他'>&nbsp;
                @endif
            </td>
        </tr>
        <tr>
            <td align=left valign=middle nowrap>
                <2> 學程：  
                @if($campFullData->table == "tcamp")
                    <input class="btn btn-warning" type=submit name=school_or_course value='教育部'>&nbsp;
                    <input class="btn btn-warning" type=submit name=school_or_course value='教育局/處'>&nbsp;
                    <input class="btn btn-warning" type=submit name=school_or_course value='大專校院'>&nbsp;
                    <input class="btn btn-warning" type=submit name=school_or_course value='高中職'>&nbsp;
                    <input class="btn btn-warning" type=submit name=school_or_course value='國中'>&nbsp;
                    <input class="btn btn-warning" type=submit name=school_or_course value='國小'>&nbsp;
                    <input class="btn btn-warning" type=submit name=school_or_course value='幼教'>&nbsp;
                    <input class="btn btn-warning" type=submit name=school_or_course value='無'>&nbsp;
                @elseif($campFullData->table == "hcamp")
                    <input class="btn btn-warning" type=submit name=education value='國小五年級'>&nbsp;
                    <input class="btn btn-warning" type=submit name=education value='國小六年級'>&nbsp;
                    <input class="btn btn-warning" type=submit name=education value='國中一年級'>&nbsp;
                    <input class="btn btn-warning" type=submit name=education value='國中二年級'>&nbsp;
                    <input class="btn btn-warning" type=submit name=education value='國中三年級'>&nbsp;
                    <input class="btn btn-warning" type=submit name=education value='高中一年級'>&nbsp;
                    <input class="btn btn-warning" type=submit name=education value='高中二年級'>&nbsp;
                    <input class="btn btn-warning" type=submit name=education value='高中三年級'>&nbsp;
                @endif
            </td>
        </tr>
        <tr>
            <td align=left valign=middle nowrap>
                <3> 梯次：    
                @foreach ($batches as $batch)
                    <input class="btn btn-secondary" type=submit name=batch value='{{ $batch->name }}'>&nbsp;
                @endforeach
            </td>
        </tr>
        <tr>
            <td align=left valign=middle nowrap>
                <4> 縣市：    
                <select class="form-control" name=county size=1 onChange='Address(this.options[this.options.selectedIndex].value); document.Camp.address.value=this.options[this.options.selectedIndex].value;'>
                    @if(old('county')) <option value='{{ old('county') }}'>{{ old('county') }}</option> @endif
                    <option value=''>- 請選縣市 -</option>
                    <option value='臺北市'>臺北市</option>
                    <option value='新北市'>新北市</option>
                    <option value='臺中市'>臺中市</option>
                    <option value='臺南市'>臺南市</option>
                    <option value='高雄市'>高雄市</option>
                    <option value='基隆市'>基隆市</option>
                    <option value='宜蘭縣'>宜蘭縣</option>
                    <option value='桃園市'>桃園市</option>
                    <option value='新竹市'>新竹市</option>
                    <option value='新竹縣'>新竹縣</option>
                    <option value='苗栗縣'>苗栗縣</option>
                    <option value='南投縣'>南投縣</option>
                    <option value='彰化縣'>彰化縣</option>
                    <option value='雲林縣'>雲林縣</option>
                    <option value='嘉義市'>嘉義市</option>
                    <option value='嘉義縣'>嘉義縣</option>
                    <option value='屏東縣'>屏東縣</option>
                    <option value='澎湖縣'>澎湖縣</option>
                    <option value='臺東縣'>臺東縣</option>
                    <option value='花蓮縣'>花蓮縣</option>
                    <option value='金門縣'>金門縣</option>
                    <option value='連江縣'>連江縣</option>
                    <option value='南海諸島'>南海諸島</option>
                    <option value='其他'>其他</option>
                </select>&nbsp;&nbsp;

                <select class="form-control" id=subarea name=subarea size=1 onChange='document.Camp.zipcode.value=this.options[this.options.selectedIndex].value; document.Camp.address.value=MyAddress(document.Camp.county.value, this.options[this.options.selectedIndex].text);'>
                    @if(old('address')) <option value='{{ substr(old('address'), 9) }}'>{{ substr(old('address'), 9) }}</option> @endif
                    <option value=''>- 再選區鄉鎮 -</option>
                </select>&nbsp;
                <input type="hidden" name="zipcode">
                <input type="hidden" name="address">
                <input class="btn btn-danger" type="reset" name="reset" value="清除">&nbsp;
                <input class="btn btn-success" type="submit" value="查詢">
            </td>
        </tr>
    </table>
</form>
@if(isset($applicants))
    <h3>查詢條件：{{ $query }}</h3>
    共 {{ $applicants->count() }} 筆資料
    <table class="table table-responsive">
        <thead>
            <tr>
                <th>報名序號</th>
                <th>姓名</th>
                @if($campFullData->table != "hcamp")
                    <th>梯次</th>
                @endif
                <th>組別</th>
                <th>區域</th>
                <th>學程</th>
                @if($campFullData->table == "ycamp")
                    <th>學校</th>
                    <th>學校所在縣市</th>
                    <th>系級</th>
                @endif
                @if($campFullData->table == "tcamp")
                    <th>職稱</th>
                    <th>單位</th>
                @endif
                <th>繳費狀況</th>
                <th>已取消</th>
                <th>報名時間</th>
            </tr>
        </thead>
        @forelse ($applicants as $applicant)
            <tr>
                <td>{{ $applicant->sn }}</td>
                <td>{{ $applicant->name }}</td>
                @if($campFullData->table != "hcamp")
                    <td>{{ $applicant->bName }}</td>
                @endif
                <td>{{ $applicant->group . $applicant->number }}</td>
                <td>{{ $applicant->region }}</td>
                <td>{{ $campFullData->table == "tcamp" ? $applicant->school_or_course : $applicant->system }}</td>
                @if($campFullData->table == "ycamp")
                    <td>{{ $applicant->school }}</td>
                    <td>{{ $applicant->school_location }}</td>
                    <td>{{ $applicant->department }}{{ $applicant->grade }}</td>
                @endif
                @if($campFullData->table == "tcamp")
                    <td>{{ $applicant->title }}</td>
                    <td>{{ $applicant->unit }}</td>
                @endif
                <td>
                    @if($applicant->is_paid == "否")
                        {!! '<a style="color: red">否</a>' !!}
                    @elseif($applicant->is_paid == "是")
                        {!! '<a style="color: green">是</a>' !!}    
                    @else
                        {{ $applicant->is_paid }}
                    @endif
                </td>
                <td>{!! $applicant->is_cancelled == "是" ? '<a style="color: red">是 (' . $applicant->deleted_at . ')</a>' : '<a style="color: green">否</a>' !!}</td>
                <td>{{ $applicant->created_at }}</td>
            </tr>
        @empty
            查詢的條件沒有資料
        @endforelse
    </table>
@endif
@endsection