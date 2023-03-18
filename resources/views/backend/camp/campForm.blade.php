@extends('backend.master')
@section('content')
    <style>
        .card-link{
            color: #3F86FB!important;
        }
        .card-link:hover{
            color: #33B2FF!important;
        }
        /* customize */
        .form-group.required .control-label:after {
            content: "＊";
            color: red;
        }
    </style>
    <h2>{{ $action }}營隊</h2>
    <form action="{{ $actionURL }}" method="POST">
        @csrf
        <div class='row form-group required'>
            <!--營隊類型=資料表名稱-->
            <label for='inputName' class='col-md-2 control-label'>類型</label>
            <div class='col-md-6'>
                <select name="table" id="" class='form-control' required>
                    <option value="">請選擇</option>
                    <option value="acamp" @if(isset($camp) && $camp->table == "acamp") selected @endif>卓青營</option>
                    <option value="ceocamp" @if(isset($camp) && $camp->table == "ceocamp") selected @endif>菁英營</option>
                    <option value="ceovcamp" @if(isset($camp) && $camp->table == "ceovcamp") selected @endif>菁英營義工</option>
                    <option value="ecamp" @if(isset($camp) && $camp->table == "ecamp") selected @endif>企業營</option>
                    <option value="evcamp" @if(isset($camp) && $camp->table == "evcamp") selected @endif>企業營義工</option>
                    <option value="hcamp" @if(isset($camp) && $camp->table == "hcamp") selected @endif>快樂營</option>
                    <option value="tcamp" @if(isset($camp) && $camp->table == "tcamp") selected @endif>教師營</option>
                    <option value="utcamp" @if(isset($camp) && $camp->table == "utcamp") selected @endif>大專教師營</option>
                    <option value="ycamp" @if(isset($camp) && $camp->table == "ycamp") selected @endif>大專營</option>
                    <option value="coupon" @if(isset($camp) && $camp->table == "coupon") selected @endif>優惠碼/劵</option>
                </select>
            </div>
        </div>

        <!--年度-->
        <div class='row form-group required'>
            <label for='inputName' class='col-md-2 control-label'>年度(西元)</label>
            <div class="col-md-6">
                <input type='number' required class='form-control' name='year' min='{{ \Carbon\Carbon::now()->subYears(5)->year }}' max='{{ \Carbon\Carbon::now()->addYears(5)->year }}' value={{ $camp->year ?? "" }} placeholder=''>
                <div class="invalid-feedback">
                    未填寫或日期不正確
                </div>
            </div>
        </div>

        <div class='row form-group required'>
            <label for='inputName' class='col-md-2 control-label'>名稱(全名)</label>
            <div class='col-md-6'>
                <input type="text" name="fullName" id="" class='form-control' required value="{{ $camp->fullName ?? "" }}">
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-2 control-label'>名稱(簡稱)</label>
            <div class='col-md-6'>
                <input type="text" name="abbreviation" id="" class='form-control' required value="{{ $camp->abbreviation ?? "" }}">
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-2 control-label'>測試用</label>
            <div class='col-md-6'>
                <input type="radio" name="test" id="" required value="0" @if(isset($camp->test) && !$camp->test) checked @endif> 否
                <input type="radio" name="test" id="" required value="1" @if(isset($camp->test) && $camp->test) checked @endif> 是
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-2 control-label'>網站網址</label>
            <div class='col-md-6'>
                <input type="text" name="site_url" id="" class='form-control' value="{{ $camp->site_url ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-2 control-label'>圖示</label>
            <div class='col-md-6'>
                <input type="text" name="icon" id="" class='form-control' value="{{ $camp->icon ?? "" }}">
            </div>
        </div>
        @if($vcamps ?? false)
            <div class='row form-group'>
                <label for='inputName' class='col-md-2 control-label'>關聯之義工營</label>
                <div class='col-md-6'>
                    <select name="vcamp_id" id="" class='form-control'>
                        <option value="">請選擇</option>
                        @if($camp->vcamp)
                            <option value="{{ $camp->vcamp->id }}" selected>{{ $camp->vcamp->fullName }}</option>
                        @endif
                        @foreach($vcamps as $vcamp)
                            @if($camp->vcamp && $camp->vcamp->id == $vcamp->id)
                                @continue
                            @endif
                            <option value="{{ $vcamp->id }}">{{ $vcamp->fullName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        <div class='row form-group'>
            <label for='inputName' class='col-md-2 control-label'>Variant</label>
            <div class='col-md-6'>
                <input type="text" name="variant" id="" class='form-control' value="{{ $camp->variant ?? "" }}">
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-2 control-label'>營隊模式</label>
            <div class='col-md-6'>
                <select name="mode" id="" class='form-control' required>
                    <option value="實體" @selected($camp->mode ?? false)>實體</option>
                    <option value="線上" @selected($camp->mode ?? false)>線上</option>
                    <option value="實體＋線上" @selected($camp->mode ?? false)>實體＋線上</option>
                </select>
            </div>
        </div>
        <div class='row form-group'>
            <label class='col-md-2 control-label'>正行日期</label>
            <div class='col-md-6'>
                系統允許各梯次有不同舉辦時間，請於梯次設定處設定。
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-2 control-label'>權限開放日</label>
            <div class='col-md-6'>
                <input type="date" name="access_start" id="" class='form-control' required value="{{ $camp->access_start ?? "" }}">
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-2 control-label'>權限關閉日</label>
            <div class='col-md-6'>
                <input type="date" name="access_end" id="" class='form-control' required value="{{ $camp->access_end ?? "" }}">
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-2 control-label'>報名開始日</label>
            <div class='col-md-6'>
                <input type="date" name="registration_start" id="" class='form-control' required value="{{ $camp->registration_start ?? "" }}">
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-2 control-label'>報名結束日</label>
            <div class='col-md-6'>
                <input type="date" name="registration_end" id="" class='form-control' required value="{{ $camp->registration_end ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-2 control-label'>後台報名結束日</label>
            <div class='col-md-6'>
                <input type="date" name="final_registration_end" id="" class='form-control' value="{{ $camp->final_registration_end ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-2 control-label'>報名資料修改截止日</label>
            <div class='col-md-6'>
                <input type="date" name="modifying_deadline" id="" class='form-control' value="{{ $camp->modifying_deadline ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-2 control-label'>取消截止日</label>
            <div class='col-md-6'>
                <input type="date" name="cancellation_deadline" id="" class='form-control' value="{{ isset($camp->cancellation_deadline) ? $camp->cancellation_deadline->format('Y-m-d') : "" }}">
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputName' class='col-md-2 control-label'>錄取公佈日</label>
            <div class='col-md-6'>
                <input type="date" name="admission_announcing_date" id="" class='form-control' required value="{{ $camp->admission_announcing_date ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-2 control-label'>是否需回覆參加</label>
            <div class='col-md-6'>
                <input type="radio" name="needed_to_reply_attend" id="" required value="1" @if(isset($camp->needed_to_reply_attend) && $camp->needed_to_reply_attend) checked @endif> 是
                <input type="radio" name="needed_to_reply_attend" id="" required value="0" @if(isset($camp->needed_to_reply_attend) && !$camp->needed_to_reply_attend) checked @endif> 否
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-2 control-label'>回覆參加截止日</label>
            <div class='col-md-6'>
                <input type="date" name="admission_confirming_end" id="" class='form-control' value="{{ $camp->admission_confirming_end ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-2 control-label'>繳費開始日</label>
            <div class='col-md-6'>
                <input type="text" name="payment_startdate" id="" class='form-control' value="{{ $camp->payment_startdate ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-2 control-label'>繳費截止日</label>
            <div class='col-md-6'>
                <input type="text" name="payment_deadline" id="" class='form-control' value="{{ $camp->payment_deadline ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-2 control-label'>營隊費用</label>
            <div class='col-md-6'>
                <input type="number" name="fee" id="" class='form-control' value="{{ $camp->fee ?? "" }}">
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-2 control-label'>是否有早鳥優惠</label>
            <div class='col-md-6'>
                <select name="has_early_bird" id="" class='form-control' required>
                    <option value="0" @if(isset($camp) && !$camp->has_early_bird) selected @endif>否</option>
                    <option value="1" @if(isset($camp) && $camp->has_early_bird) selected @endif>是</option>
                </select>
            </div>
        </div>
        <div class='row form-group'>
            <label for='inputName' class='col-md-2 control-label'>營隊早鳥費用</label>
            <div class='col-md-6'>
                <input type="number" name="early_bird_fee" id="" class='form-control' value="{{ $camp->early_bird_fee ?? "0" }}">
            </div>
        </div>

        <div class='row form-group'>
            <label for='inputName' class='col-md-2 control-label'>早鳥最後一日</label>
            <div class='col-md-6'>
                <input type="date" name="early_bird_last_day" id="" class='form-control' value="{{ $camp->early_bird_last_day ?? "" }}">
            </div>
        </div>
        @if($action == "建立")
            <input type="submit" class="btn btn-success" value="確認建立">
        @else
            <input type="submit" class="btn btn-success" value="確認修改">
        @endif
    </form>
@endsection
