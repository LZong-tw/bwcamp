@extends('checkIn.master')
@section('content')
<style>
    .text-center {
        text-align: center;
    }

    #CenterDIV {
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(255, 255, 255, 0.75);
        width: 100%;
        height: 100%;
        padding-top: 200px;
        display: none;
    }

    .divFloat {
        margin: 0 auto;
        background-color: #FFF;
        color: #000;
        width: 90%;
        height: auto;
        padding: 20px;
        border: solid 1px #999;
        -webkit-border-radius: 3px;
        -webkit-box-orient: vertical;
        -webkit-transition: 200ms -webkit-transform;
        box-shadow: 0 4px 23px 5px rgba(0, 0, 0, 0.2), 0 2px 6px rgba(0, 0, 0, 0.15);
        display: block;
    }

    .footer {
        background-color: rgba(221, 221, 221, 0.80);
    }

    th.asc::after {
        content: " \25B2";
    }

    th.desc::after {
        content: " \25BC";
    }
</style>
<div class="container">
    <h2 class="mt-4 text-center">福智營隊報到系統</h2>
    <h5 class="text-center">當前報到營隊：{{ $camp->fullName }}<br>報到日期：{{ \Carbon\Carbon::today()->format('Y-m-d') }}</h5>
    <h6 class="text-center">可輸入報名序號、組別、錄取編號、姓名、手機</h6>
    <form action="/checkin/query" id="query">
        <div class="form-group input-group">
            <input type="hidden" value="{{ $camp->id }}" name="camp_id">
            <input type="text" class="form-control" name="query_str" id="" placeholder="請輸入報名序號、組別、錄取編號、姓名、手機查詢 ..." value="{{ old("query_str") }}" required>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit" id="checkinsearch">
                    Go <i class="fa fa-search"></i>
                </button>
            </div>
            <a href="javascript: toggleCamera();" id="qr-switch" class="btn btn-success ml-1">使用 QR Code</a>
        </div>
    </form>
    @if($errors->any())
        @foreach ($errors->all() as $message)
            <div class='alert alert-danger' role='alert'>
                {{ $message }}
            </div>
        @endforeach
    @endif
    @if(\Session::has('message'))
        <div class='alert alert-success' role='alert'>
            {{ \Session::get('message') }}
        </div>
    @endif
    @if(isset($applicants) && $applicants->count() > 0)
        @if($applicants->count() >= 20)
            <div class="alert alert-danger">查詢條件過於粗略，符合筆數過多，容易導致系統負荷過大。</div>
        @endif
        <a href="#" class="btn btn-primary mb-2" onclick="checkBeforeSubmit(this.id)" id="button1">集體報到</a>
        @foreach ($batches as $batch_key => $batch_name)
            <h5>梯次：{{ $batch_name }}&nbsp;&nbsp;&nbsp;<a class="text-primary">各欄皆可排序</a></h5>
            <table class="table table-bordered text-break" id="batch{{ $batch_key }}">
                <thead>
                    <tr class="table-active">
                        @if($camp->table != 'coupon')
                            <th></th>
                            <th style="width: 20%"  onclick="sortTable(1, 'batch{{ $batch_key }}')">組別@if($camp->is_vcamp())職務 @endif</th>
                            @if($camp->table != 'ceocamp' && $camp->table != 'ecamp')
                                <th style="width: 20%"  onclick="sortTable(2, 'batch{{ $batch_key }}')">編號</th>
                                <th style="width: 20%"  onclick="sortTable(3, 'batch{{ $batch_key }}')">姓名</th>
                                <th style="width: 20%"  onclick="sortTable(4, 'batch{{ $batch_key }}')">狀態</th>
                            @else
                                <th style="width: 20%"  onclick="sortTable(2, 'batch{{ $batch_key }}')">姓名</th>
                                <th style="width: 20%"  onclick="sortTable(3, 'batch{{ $batch_key }}')">狀態</th>
                            @endif
                            <th style="width: 15%">動作</th>
                        @else
                            <th style="width: 18%">流水號</th>
                            <th style="width: 18%">優惠碼</th>
                            <th style="width: 15%">狀態</th>
                            <th style="width: 25%">動作</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applicants as $applicant)
                        @if($applicant->batch->id == $batch_key)
                            <tr id="{{ $applicant->id }}">
                                <td class="align-middle center"><input type="checkbox" name="applicant_multi" id="" value="{{ $applicant->id }}" onchange="collectPeople(this.value, this.checked)"></td>
                                @if($camp->table != 'coupon')
                                    @if (!$camp->is_vcamp())
                                        <td class="align-middle">{{ $applicant->group }}</td>
                                    @else
                                        <td class="align-middle">
                                            @foreach ($applicant->user?->roles as $r)
                                                {{ $r->section . " " . $r->position }}<br>
                                            @endforeach
                                        </td>
                                    @endif
                                    @if($camp->table != 'ceocamp' && $camp->table != 'ecamp')
                                        <td class="align-middle">{{ $applicant->number ?? "--" }}</td>
                                    @endif
                                @else
                                    <td class="align-middle">{{ $applicant->group }}{{ $applicant->number }}</td>
                                @endif
                                <td class="align-middle">
                                    {{ $applicant->name }}
                                    @if(!$has_attend_data)
                                        @if($applicant->is_attend === 1)
                                            <p style='color: rgb(26, 170, 26);' class="align-middle">參加</p>
                                        @elseif($applicant->is_attend === 0)
                                            <p style='color: red;' class="align-middle">不參加</p>
                                        @elseif($applicant->is_attend === 2)
                                            <p style='color: #ffb429;' class="align-middle">尚未決定</p>
                                        @elseif($applicant->is_attend === 3)
                                            <p style='color: pink;' class="align-middle">聯絡不上</p>
                                        @elseif($applicant->is_attend === 4)
                                            <p style='color: rgb(14, 115, 58);' class="align-middle">無法全程</p>
                                        @else
                                            <p style='color: rgb(0, 132, 255);' class="align-middle">尚未聯絡</p>
                                        @endif
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @php
                                        $is_check_in = 0;
                                    @endphp
                                    @if($camp->table != 'coupon')
                                        @foreach($applicant->checkInData as $checkInData)
                                            @if($checkInData->check_in_date == \Carbon\Carbon::today()->format('Y-m-d'))
                                                @php $is_check_in = 1; @endphp
                                            @endif
                                        @endforeach
                                        {!! $is_check_in ? "<a class='text-success'>已報到</a>" : "<a class='text-danger'>未報到</a>" !!}
                                    @else
                                        @if(count($applicant->checkInData) > 0)
                                            @php $is_check_in = 1; @endphp
                                        @endif
                                        {!! $is_check_in ? "<a class='text-success'>已兌換</a>" : "<a class='text-danger'>未兌換</a>" !!}
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @php
                                        $yes = 0;
                                    @endphp
                                    @if($camp->table != 'coupon')
                                        @foreach($applicant->checkInData as $checkInData)
                                            @if($checkInData->check_in_date == \Carbon\Carbon::today()->format('Y-m-d'))
                                                @php
                                                    $yes = 1;
                                                @endphp
                                            @endif
                                        @endforeach
                                        @if($yes)
                                            <form action="/checkin/un-checkin" method="POST" class="d-inline" name="uncheckIn{{ $applicant->id }}">
                                                @csrf
                                                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                                                <input type="hidden" name="camp_id" value="{{ $applicant->camp->id }}">
                                                <input type="hidden" name="check_in_date" value="{{ $checkInData->check_in_date }} ">
                                                <input type="hidden" name="query_str" value="{{ old("query_str") }}">
                                                <input type="submit" value="取消" onclick="this.value = '取消中'; this.disabled = true; document.uncheckIn{{ $applicant->id }}.submit();" class="btn btn-danger">
                                            </form>
                                        @else
                                            <form action="/checkin/checkin" method="POST" name="checkIn{{ $applicant->id }}">
                                                @csrf
                                                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                                                <input type="hidden" name="camp_id" value="{{ $applicant->camp->id }}">
                                                <input type="hidden" name="query_str" value="{{ old("query_str") }}">
                                                <input type="submit" value="報到" onclick="this.value = '報到中'; this.disabled = true; document.checkIn{{ $applicant->id }}.submit();" class="btn btn-success" id="btn{{ $applicant->id }}">
                                            </form>
                                        @endif
                                    @else
                                        @if(count($applicant->checkInData) > 0)
                                            @php $yes = 1; @endphp
                                        @endif
                                        @if($yes)
                                            <a class="text-danger">兌換日期：{{ $applicant->checkInData[0]['created_at'] }}</a>
                                        @else
                                            <form action="/checkin/checkin" method="POST" name="checkIn{{ $applicant->id }}">
                                                @csrf
                                                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                                                <input type="hidden" name="camp_id" value="{{ $applicant->camp->id }}">
                                                <input type="hidden" name="query_str" value="{{ old("query_str") }}">
                                                <input type="submit" value="兌換" onclick="this.value = '兌換中'; this.disabled = true; document.checkIn{{ $applicant->id }}.submit();" class="btn btn-success" id="btn{{ $applicant->id }}">
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endforeach
        <a href="#" class="btn btn-primary" onclick="checkBeforeSubmit(this.id)" id="button2">集體報到</a>
        <form action="{{ route("massCheckIn") }}" method="post" id="theMultiApplicantForm">
            @csrf
            @foreach ($applicants as $applicant)
                <input type="checkbox" class="applicant_multi_values d-none" name="applicant_multi_values[]" id="applicant_multi_values_{{ $applicant->id }}" value="{{ $applicant->id }}" onchange="collectPeople(this.value, this.checked)">
            @endforeach
            <input type="hidden" name="query_str" value="{{ $query }}">
            <input type="hidden" name="camp_id" value="{{ $camp->id }}">
        </form>
    @elseif(isset($applicants) && $applicants->count() == 0)
        <div class="alert alert-danger">查無資料。</div>
    @endif
    <h6 class="text-center"><a href="{{ route("selectCamp") }}">換營隊</a></h6>
    <footer class="fixed-bottom footer pb-2 pt-2 text-center">
        <a href="/checkin/detailedStat?camp_id={{ $camp->id }}" target="_blank">今日全梯次累積報到人數 / 未報到人數： <span id="stat">查詢中</span></a>
    </footer>
    <br><br><br>
</div>
<div id="CenterDIV">
    <div class="divFloat card-body text-center">
        <h3>讀取結果</h3>
        <div id="QRmsg">

        </div>
        <input type="button" id="btClose" class="btn btn-success" value="繼續報到" onclick="document.getElementById('CenterDIV').style.display = 'none'; setCamera();"/>
    </div>
</div>
<script>
    {{-- @if(isset($applicants))
        (function() {
            @foreach ($applicants as $applicant)
                @foreach($applicant->checkInData as $checkInData)
                    @if($checkInData->check_in_date == \Carbon\Carbon::today()->format('Y-m-d'))
                        document.getElementById("{{ $applicant->id }}").classList.add("table-success");
                    @endif
                @endforeach
            @endforeach
        })();
    @endif --}}

    getData('{{ url("") }}/checkin/realtimeStat?camp_id={{ $camp->id }}');

    async function getData(url = '', data = {}) {
        let first = 1;
        while (true) {
            // Default options are marked with *
            if(first != 1){
                await new Promise(resolve => setTimeout(resolve, 3000));
            }
            first++;
            const response = await fetch(url, {
                method: 'GET', // *GET, POST, PUT, DELETE, etc.
                mode: 'cors', // no-cors, *cors, same-origin
                cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                credentials: 'same-origin', // include, *same-origin, omit
                headers: {
                'Content-Type': 'application/json'
                // 'Content-Type': 'application/x-www-form-urlencoded',
                },
                redirect: 'follow', // manual, *follow, error
                referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
            }).then((data) => data.json());

            let data = response;

            if (data.status === 401) {
                data = {'msg': '<h3 class="text-danger">權限不足，請重新登入</h3>'};
            }
            if (data.status === 500) {
                data = {'msg': '<h3 class="text-danger">發生不明錯誤，無法顯示資料</h3>'};
            }
            document.getElementById("stat").innerHTML = data.msg;
            console.log(data); // JSON data parsed by `response.json()` call
        }
    }

    function collectPeople(id, checked) {
        document.getElementById('applicant_multi_values_' + id).checked = checked;
        console.log(id, checked);
    }

    function checkBeforeSubmit(ele_id) {
        let inputElems = document.getElementsByClassName("applicant_multi_values"),
        count = 0;
        for (let i=0; i<inputElems.length; i++) {
           if (inputElems[i].checked == true){
              count++;
           }
        }
        if (count == 0 && !document.getElementById('warning-text')) {
            refNode = document.getElementById(ele_id);
            let newNode = document.createElement('p');
            newNode.innerHTML = "未勾選任何人，請在勾選後重新送出。";
            newNode.classList.add('text-danger');
            newNode.id = 'warning-text';
            refNode.parentNode.insertBefore(newNode, refNode.nextSibling);
        }
        else if (count != 0) {
            if (document.getElementById('warning-text')) {
                document.getElementById('warning-text').classList.add('d-none');
            }
            refNode = document.getElementById(ele_id);
            let newNode = document.createElement('p');
            newNode.innerHTML = "處理中，請稍候";
            newNode.classList.add('text-success');
            newNode.id = 'warning-text';
            refNode.parentNode.insertBefore(newNode, refNode.nextSibling);
            document.getElementById('theMultiApplicantForm').submit();
        }
    }

    function sortTable(columnIndex, tableID) {
        const table = document.getElementById(tableID);
        const tbody = table.querySelector('tbody');
        const thead = table.querySelector('thead');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const headers = thead.querySelectorAll('th');

        // Determine sort direction
        const isAscending = !headers[columnIndex].classList.contains('asc');

        // Remove existing sort classes
        headers.forEach(header => header.classList.remove('asc', 'desc'));

        // Add new sort class
        headers[columnIndex].classList.add(isAscending ? 'asc' : 'desc');

        // Sort the rows
        rows.sort((rowA, rowB) => {
            const cellA = rowA.cells[columnIndex].innerText.trim();
            const cellB = rowB.cells[columnIndex].innerText.trim();

            // Check if the content is a number
            if (!isNaN(cellA) && !isNaN(cellB)) {
                return isAscending ? cellA - cellB : cellB - cellA;
            }

            // Otherwise, treat as string
            return isAscending
                ? cellA.localeCompare(cellB)
                : cellB.localeCompare(cellA);
        });

        // Efficiently reorder rows in the DOM
        const fragment = document.createDocumentFragment();
        rows.forEach(row => fragment.appendChild(row));
        tbody.appendChild(fragment);
    }

    function updateSortIndicator(tableID, columnIndex, direction) {
        let table = document.getElementById(tableID);
        let headers = table.getElementsByTagName("th");

        // Remove existing indicators
        for (let i = 0; i < headers.length; i++) {
            headers[i].classList.remove("asc", "desc");
        }

        // Add indicator to the sorted column
        headers[columnIndex].classList.add(direction);
    }

</script>
<script type="text/javascript">
    let scanner;

    function toggleCamera(){
        let element = '<center><video id="scanner" style="width: 85%"></video><br><a href="javascript: window.location.reload();" class="btn btn-primary mb-2">傳統表格</a></center>';
        document.getElementById("query").innerHTML = element;
        setCamera();
    }

    function setCamera(){
        let scanner = new Instascan.Scanner({
            video: document.getElementById('scanner'),
            mirror: false
        });
        {{-- 開啟一個新的掃描
             宣告變數scanner，在html<video>標籤id為scanner的地方開啟相機預覽。
             Notice:這邊注意一定要用<video>的標籤才能使用，詳情請看他的github API的部分解釋。--}}

        scanner.addListener('scan', function(content) {
            let data = JSON.parse(content);
            console.log(data);
            postData('{{ url("") }}/checkin/by_QR?camp_id={{ $camp->id }}', {
                applicant_id: data.applicant_id,
                camp_id: {{ $camp->id }},
                coupon_code: data.coupon_code,
                _token: "{{ csrf_token() }}" })
                .then(data => {
                    if (data.status === 401) {
                        data = {'msg': '<h3 class="text-danger">權限不足，請重新登入</h3>'};
                    }
                    if (data.status === 419) {
                        data = {'msg': '<h3 class="text-danger">頁面資料過期，請重新整理</h3>'};
                    }
                    if (data.status === 500) {
                        data = {'msg': '<h3 class="text-danger">掃瞄器發生不明錯誤，無法完成操作</h3>'};
                    }
                    document.getElementById("CenterDIV").style.display = "block";
                    document.getElementById("QRmsg").innerHTML = data.msg;
                    console.log(data); // JSON data parsed by `response.json()` call
                    scanner.stop();
            });
        });
        {{-- 開始監聽掃描事件，若有監聽到印出內容。 --}}

        if(getMobileOperatingSystem() == 'Android'){
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    var selectedCam = cameras[0];
                    $.each(cameras, (i, c) => {
                        if (c.name.indexOf('back') != -1) {
                            selectedCam = c;
                            return false;
                        }
                    });
                    scanner.start(selectedCam);
                }
                else {
                    console.error('No cameras found.');
                }
            });
        }
        else{
            Instascan.Camera.getCameras().then(function(cameras) {
            {{-- 取得設備的相機數目 --}}
                if (cameras.length > 0) {
                    {{-- 若設備相機數目大於0 則先開啟第0個相機(程式的世界是從第零個開始的) --}}
                    scanner.start(cameras[1]);
                } else {
                    {{-- 若設備沒有相機數量則顯示"No cameras found"; --}}
                    {{-- 這裡自行判斷要寫什麼 --}}
                    console.error('No cameras found.');
                }
            }).catch(function(e) {
                console.error(e);
            });
        }
    }

    async function postData(url = '', data = {}) {
        // Default options are marked with *
        const response = await fetch(url, {
            method: 'POST', // *GET, POST, PUT, DELETE, etc.
            mode: 'cors', // no-cors, *cors, same-origin
            cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
            credentials: 'same-origin', // include, *same-origin, omit
            headers: {
            'Content-Type': 'application/json'
            // 'Content-Type': 'application/x-www-form-urlencoded',
            },
            redirect: 'follow', // manual, *follow, error
            referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
            body: JSON.stringify(data) // body data type must match "Content-Type" header
        });
        if (response.status === 401 || response.status === 419 || response.status === 500) {
            return response;
        }
        return response.json(); // parses JSON response into native JavaScript objects
    }

    function getMobileOperatingSystem() {
        var userAgent = navigator.userAgent || navigator.vendor || window.opera;

        // Windows Phone must come first because its UA also contains "Android"
        if (/windows phone/i.test(userAgent)) {
            return "Windows Phone";
        }

        if (/android/i.test(userAgent)) {
            return "Android";
        }

        // iOS detection from: http://stackoverflow.com/a/9039885/177710
        if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
            return "iOS";
        }

        return "unknown";
    }
</script>
@endsection
