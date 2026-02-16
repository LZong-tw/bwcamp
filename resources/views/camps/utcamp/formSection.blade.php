<div class='row form-group required'>
    <label for='inputIsCivilCertificate' class='col-md-2 control-label text-md-right'>是否申請公務員研習時數</label>
    <div class="col-md-10">
        <div class="form-check form-check-inline">
            <label class="form-check-label">          
                <input type="radio" name="is_civil_certificate" value="1" required onclick="id_setRequired(this)">&nbsp;是&nbsp;&nbsp;&nbsp;
                <input type="radio" name="is_civil_certificate" value="0" required onclick="id_setRequired(this)">&nbsp;否&nbsp;&nbsp;&nbsp;
                <div class="invalid-feedback">請選擇一項</div>
            </label>
        </div>
    </div>
</div>
<div class='row form-group idno-sec' style="display: none;">
    <label class='col-md-2 text-md-right'></label>
    <div class='col-md-10'>
        <div class='row form-group required'>
            <label for='inputID' class='col-md-2 control-label text-md-right'>身份證字號</label>
            <div class='col-md-10'>
                <input type='text' name='idno' value='' class='form-control' id='inputID' placeholder='僅作為申請研習時數用'>
                <div class="invalid-feedback">
                    未填寫身份證字號（申請時數或研習證明用）
                </div>
            </div>
        </div>
    </div>
</div>

<div class='row form-group required'>
    <label for='inputIsBwfoceCertificate' class='col-md-2 control-label text-md-right'>是否申請基金會研習數位證明書</label>
    <div class="col-md-10">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input type="radio" name="is_bwfoce_certificate" value="1" required>&nbsp;是&nbsp;&nbsp;&nbsp;
                <input type="radio" name="is_bwfoce_certificate" value="0" required>&nbsp;否&nbsp;&nbsp;&nbsp;
                <div class="invalid-feedback">請選擇一項</div>
            </label>
        </div>
    </div>
</div>
<div class='row form-group required'>
    <label for='inputInvoiceType' class='col-md-2 control-label text-md-right'>活動費發票開立</label>
    <div class='col-md-10'>
        <div class="form-check form-check-inline"> 
            <label class="form-check-label">         
                <input required type="radio" name="invoice_type" value="單位發票"  onclick="taxid_setRequired(this)">
                &nbsp;單位發票&nbsp;&nbsp;&nbsp;
                <input required type="radio" name="invoice_type" value="個人發票" onclick="taxid_setRequired(this)">
                &nbsp;個人發票&nbsp;&nbsp;&nbsp;
                <input required type="radio" name="invoice_type" value="捐贈福智文教基金會" onclick="taxid_setRequired(this)">
                &nbsp;捐贈福智文教基金會&nbsp;&nbsp;&nbsp;
                <div class="invalid-feedback">未選擇活動費發票開立</div>
            </label>
        </div>
    </div>
</div>
<div class='row form-group taxid-sec' style="display: none;">
    <label class='col-md-2 text-md-right'></label>
    <div class='col-md-10'>
        <div class='row form-group required'>
            <label for='inputTaxID' class='col-md-2 control-label text-md-right'>統一編號</label>
            <div class='col-md-10'>
                <input type='text' name='taxid' value='' class='form-control' id='inputTaxID' placeholder='開立活動費發票用'>
                <div class="invalid-feedback">
                    未填寫統一編號
                </div>
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputInvoiceTitle' class='col-md-2 control-label text-md-right'>抬頭</label>
            <div class='col-md-10'>
                <input type='text' name='invoice_title' value='' class='form-control' id='inputInvoiceTitle' placeholder='開立活動費發票用'>
                <div class="invalid-feedback">
                    未填寫抬頭
                </div>
            </div>
        </div>
    </div>
</div>
<div class='row form-group required'>
    <label for='inputRoomType' class='col-md-2 control-label text-md-right'>住宿選項</label>
    <div class='col-md-10'>
        <div class='row form-group'>
            <div class='col-md-12 text-primary'>
                依房型不同，*每人活動費*如下：<br>
                1.單人房(1大床)：早鳥優惠NT$9,800元／原價NT$14,000元<br>
                2.雙人房(1大床或2床)：早鳥優惠NT$6,600元／原價 NT$9,000元<br>
                3.兩人同行優惠(僅能選雙人房)：優惠價NT$5,000元<br>
                *早鳥優惠截止日期：{{ $camp_data->early_bird_last_day }}<br>
                *兩人同行優惠截止日期：{{ $camp_data->discount_last_day }}<br>
                <a href="https://bwfoce.wixsite.com/ufscamp#comp-kvff5c8s" target="_blank"><u>活動費用詳細說明請按此</u></a>
            </div>
        </div>
        <div class="row form-check">
            <label class="form-check-label">
                @foreach($fare_room as $key => $value)
                <input required class="form-check-input" type="radio" name="room_type" value={{ $key }} onclick='changeRoom(this)'>
                &nbsp;{{ $key }}({{ $value }})&nbsp;&nbsp;&nbsp;<br>
                @endforeach
                <div class="invalid-feedback">
                    未選擇住宿需求
                </div>
            </label>
        </div>
    </div>
</div>

<div class='row form-group companion-sec' style='display:none'>
    <label class='col-md-2 text-md-right'></label>
    <div class='col-md-10'>
        <div class='row form-group required'>
            <label class='col-md-2 control-label text-md-right'>同行人姓名</label>
            <div class='col-md-10'>
                <input type='text' class='form-control' name='companion_name' id='inputCompanionName' value=''>
                <div class="invalid-feedback">
                    未填寫同行人姓名
                </div>
            </div>
        </div>
        <div class='row form-group required'>
            <label class='col-md-2 control-label text-md-right'>希望同房</label>
            <div class='col-md-10'>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" name="companion_as_roommate" id="inputRoommateY" value="1">
                        &nbsp;是&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="companion_as_roommate" id="inputRoommateN" value="0">
                        &nbsp;否&nbsp;&nbsp;&nbsp;
                        <div class="invalid-feedback">請選擇一項</div>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class='row form-group'>
    <label class='col-md-2 control-label text-md-right'>接駁服務</label>
    <div class='col-md-10'>
        <div class='row form-group'>
            <div class='col-md-12 text-primary'>
            【新竹高鐵站接駁車資訊】<br>
            {{$camp_data->batch_start}}({{$camp_data->batch_start_weekday}}) 往麻布山林：新竹高鐵站出口4，12:30發車。<br>
            {{$camp_data->batch_end}}({{$camp_data->batch_end_weekday}}) 往新竹高鐵站：麻布山林山，17:10發車。
            </div>
        </div>
        <div class='row form-group required'>
            <label for='inputDepartFrom' class='col-md-2 control-label'>
                去程選項
            </label>
            <div class='col-md-10'>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        @foreach($fare_depart_from as $key => $value)
                        <input type="radio" name="depart_from" value={{ $key }} required>
                        &nbsp;{{ $key }}&nbsp;&nbsp;&nbsp;
                        @endforeach
                        <div class="invalid-feedback">未選擇去程接駁需求</div>
                    </label>
                </div>
            </div>
            <br>
            <label for='inputBackTo' class='col-md-2 control-label'>
                回程選項
            </label>
            <div class='col-md-10'>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        @foreach($fare_depart_from as $key => $value)
                        <input required type="radio" name="back_to" value={{ $key }} >
                        &nbsp;{{ $key }}&nbsp;&nbsp;&nbsp;
                        @endforeach
                        <div class="invalid-feedback">未選擇回程接駁需求</div>
                    </label>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
//身份證字號欄位必填與否
function id_setRequired(ele) {
    const idnoSection = document.getElementsByClassName('idno-sec')[0];
    const idnoInput = document.getElementById('inputID');

    //if(ele.value == "一般教師研習時數" || ele.value == "公務員研習時數") {
    if(ele.value == "1") {
        idnoSection.style.display = '';
        idnoInput.required = true;
    }
    else {
        idnoSection.style.display = 'none';
        idnoInput.required = false;
    }
}
//統編與抬頭欄位必填與否
function taxid_setRequired(ele) {
    const taxidSection = document.getElementsByClassName('taxid-sec')[0];
    const taxidInput = document.getElementById('inputTaxID');
    const invoiceTitleInput = document.getElementById('inputInvoiceTitle');

    if(ele.value == "單位發票") {
        taxidSection.style.display = '';
        taxidInput.required = true;
        invoiceTitleInput.required = true;
    }
    else {
        taxidSection.style.display = 'none';
        taxidInput.required = false;
        invoiceTitleInput.required = false;
    }
}

function changeRoom(select_ele) {
    const companionSection = document.getElementsByClassName('companion-sec')[0];
    const companionName = document.getElementById('inputCompanionName');
    const roommateY = document.getElementById('inputRoommateY');
    const roommateN = document.getElementById('inputRoommateN');

    if (select_ele.value.includes("兩人同行")) {
        // 有同行者
        companionSection.style.display = '';
        companionName.required = true;
        roommateY.required = true;
        roommateN.required = true;
    } else {
        // 一人報名
        companionSection.style.display = 'none';
        companionName.required = false;
        roommateY.required = false;
        roommateN.required = false;
    }
};
</script>
