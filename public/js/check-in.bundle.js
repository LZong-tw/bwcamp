let scanner;

function toggleCamera(){
    let element = '<center><video id="scanner" style="width: 85%"></video><br><a href="javascript: window.location.reload();" class="btn btn-primary mb-2">傳統表格</a></center>';
    document.getElementById("query").innerHTML = element;
    setCamera();
}

function setCamera(){
    let scanner = new Instascan.Scanner({
        video: document.getElementById('scanner')
    });

    scanner.addListener('scan', function(content) {
        let data = JSON.parse(content);
        console.log(data);
        postData('{{ url("") }}/checkin/by_QR', { 
            applicant_id: data.applicant_id, 
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
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
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