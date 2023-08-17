@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 現場手動繳費 / 修改繳費資料</h2>
    @if($applicant)
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
            <h5>{{ $applicant->name }}({{ $applicant->gender }})</h5>
            梯次：{{ $applicant->batch->name }} <br>
            分區：{{ $applicant->region }} <br>
            報名序號：{{ $applicant->id }} <br>
            錄取序號：{{ $applicant->group.$applicant->number }} <br>
            @if($campFullData->table=='ycamp')
                去/回程交通：{{ $applicant->traffic->depart_from ?? "尚未登記" }}/{{ $applicant->traffic->back_to ?? "尚未登記" }}<br>
                應繳金額：{{ $applicant->traffic->fare ?? 0 }} <br>
                轉帳繳費：{{ $applicant->traffic->deposit ?? 0 }} <br>
                現金繳費：{{ $applicant->traffic->cash ?? 0 }} <br>
            @else
            應繳金額：{{ $applicant->fee ?? 0 }} <br>
            已繳金額：{{ $applicant->deposit ?? 0 }} <br>
            繳費狀態：{{ $applicant->payment_status }}
            @endif
        </p>
        <hr>
        @if(!$applicant->is_attend)
            學員參加意願：<label class="text-danger">不參加。</label>勿改。
            <br>
            <a href="{{ route('modifyAccountingGET', $campFullData->id) }}" class="btn btn-primary">下一筆</a>
        @elseif($campFullData->table=='ycamp')
            @php 
                $cash=0 
            @endphp
            <form action="{{ route("modifyAccounting", $campFullData->id) }}" method="post" class="form-horizontal">
                @csrf
                <input type="hidden" name="id" value="{{ $applicant->id }}">

                <div class='row form-group required'>
                    <label for='inputDepartFrom' class='col-md-2 control-label text-md-right'>修改去程交通</label>
                    <div class="col-md-4">
                        <select required class='form-control' name='depart_from' id='inputDepartFrom'>
                            <option value='自往' selected>自往</option>
                            <option value='火車站接駁車' >火車站接駁車</option>
                            <option value='台北專車' >台北專車</option>
                            <option value='桃園專車' >桃園專車</option>
                            <option value='新竹專車' >新竹專車</option>
                            <option value='台中專車' >台中專車</option>
                            <option value='台南專車' >台南專車</option>
                            <option value='高雄專車' >高雄專車</option>
                        </select>
                        <div class="invalid-feedback">
                            請選擇去程交通
                        </div>
                    </div>
                </div>
                <div class='row form-group required'>
                    <label for='inputBackTo' class='col-md-2 control-label text-md-right'>修改回程交通</label>
                    <div class="col-md-4">
                        <select required class='form-control' name='back_to' id='inputBackTo'>
                            <option value='自回' selected>自回</option>
                            <option value='火車站接駁車' >火車站接駁車</option>
                            <option value='台北專車' >台北專車</option>
                            <option value='桃園專車' >桃園專車</option>
                            <option value='新竹專車' >新竹專車</option>
                            <option value='台中專車' >台中專車</option>
                            <option value='台南專車' >台南專車</option>
                            <option value='高雄專車' >高雄專車</option>
                        </select>
                        <div class="invalid-feedback">
                            請選擇回程交通
                        </div>
                    </div>
                </div>

                <div class='row form-group required'>
                    <label for='inputCash' class='col-md-2 control-label text-md-right'>修改現金繳費<br>金額</label>
                    <div class='col-md-10'>
                        <input type="text" class="form-control" name="cash" value=0 placeholder="填寫現場手動（現金）繳費金額" required><br>
                        <div class="invalid-feedback">
                            請填寫輸入現場手動（現金）繳費金額
                        </div>
                    </div>
                </div>
                <div class='row form-group required'>
                    <label for='inputModifyMethod' class='col-md-2 control-label text-md-right'>修改方式</label>
                    <div class='col-md-10'>
                        <label class=radio-inline>
                            <input type=radio required name='is_add' value=replace checked> 覆寫現金繳費
                            <div class="invalid-feedback">
                                請選擇修改方式
                            </div>
                        </label> 
                        <label class=radio-inline>
                            <input type=radio required name='is_add' value=add > 加入現金繳費
                            <div class="invalid-feedback">
                                &nbsp;
                            </div>
                        </label> 
                    </div>
                </div>
            <input type="submit" class="btn btn-success" id="confirmaccounting" value="確認修改">
            </form>
            <br>
            <a href="{{ route('modifyAccountingGET', $campFullData->id) }}" class="btn btn-primary">下一筆</a>
        @else
            @if(!$applicant->showCheckInInfo || $applicant->deposit > $applicant->fee)
                <form action="{{ route("modifyAccounting", $campFullData->id) }}" method="post" class="form-horizontal">
                    @csrf
                    <input type="hidden" name="id" value="{{ $applicant->applicant_id }}">
                    確認報名序號或錄取序號：<input type="text" class="form-control" name="double_check" placeholder="輸入報名序號或錄取序號" required><br>
                    <input type="submit" class="btn btn-success" value="後台繳費 / 繳費金額調整">
                </form>
            @endif
        @endif
    @else
        <p>
            查無資料，請 <a href="{{ route("modifyAccountingGET", $campFullData->id) }}" class="btn btn-primary">重新執行</a>。
        </p>
    @endif

    <script>
        @if(isset($applicant->traffic))
            {{-- 回填交通選項 --}}
            (function() {
                let traffic_data = JSON.parse('{!! $applicant->traffic !!}');
                let selects = document.getElementsByTagName('select');
                console.log(traffic_data);
                for (var i = 0; i < selects.length; i++){
                    if(typeof traffic_data[selects[i].name] !== "undefined"){
                        selects[i].value = traffic_data[selects[i].name];
                    }
                }
                let texts = document.getElementsByName('cash');
                console.log(texts[0].name);
                console.log(texts[0].value);
                texts[0].value = traffic_data[texts[0].name];
            })();
        @endif
    </script>

@endsection
