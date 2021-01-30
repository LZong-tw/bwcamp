## 待實作

- 報名資料下載效能檢查
- 退款功能
- 報到統計需加上各梯次統計圖
- PaymentForm.blade.php, PaymentFormPDF.blade.php 移出 backend
- 使用 Queue 郵寄所有信件
- 後台可修改 Email、姓名、身份證字號
- 營隊管理：
    - 刪除營隊：刪除前，檢查是否有梯次資料，若無則放行，否則無法動作
    - 刪除梯次：刪除前，檢查是否有報名資料，若無則放行，否則無法動作
- 權限功能完善：各區限制（只能下載自己梯次、可以檢視和改到別梯次）
- 查詢功能下載全區資料，依日期顯示是否已報到
    (Optional)
    - 整體：2 以上可看 
    - 各區：3 以下
- 輔導組表格
- 不同營隊不同郵寄地址
- 營隊資料跨年度統計
- 文件編寫
    1. https://redoc.ly/
    2. https://readthedocs.org/
        - 實際為 Sphinx 的託管服務
    3. https://github.com/code-lts/doctum

- 未來：
    1. 教師營：按學程顯示名單，勾選人員後進入批次錄取列表
    2. 交通資料表架構調整
    3. 後台可變更報名者繳費資訊
    4. 後台變更報名者不可變動的資料的功能
    5. 倒數計時？ https://motionmailapp.com

## 資料設定及參考文件

- 每次更新後需執行的指令：
    - php artisan migrate
    - php artisan config:cache
    - php artisan queue:restart (本指令要以 su 執行)

- 權限分級：以功能區分
    - 總部（所有營隊所有功能，1）
    - 主辦區（當屆營隊所有功能，2）
    - 各區輔導行政(當屆營隊功能限制，3)
    - 輔導員（當屆營隊功能限制、唯讀，4）
    - 交通（僅當屆營隊交通統計唯讀，5）
    - 報到（name = 報到, level = 6）
    **大教聯（3，但僅能下載大專教職資料）**

- 錄取編號首字母設定：各梯次不同字首，格式：1 字母 + 2 組別 + 2 編號
    - 台北(A)、桃園(B)、新竹(C)、台中(D)、雲林(E)、嘉義(F)、台南(G)、高雄(H)

- 報到 QR code 資料格式：` {"applicant_id":報名人ID} ` (JSON, Serialized JS Array)
    
- Queue 指令：php artisan queue:work --daemon --quiet --queue=default --delay=15 --sleep=3 --tries=3
    - https://learnku.com/articles/3729/use-laravel-queue-to-understand-the-knowledge

- 上海銀行條碼格式：Barcode 39
- 條碼產生器文件：https://github.com/milon/barcode
- JS QRcode 掃瞄器：https://ithelp.ithome.com.tw/articles/10206308
    - 蘋果系列問題：https://github.com/schmich/instascan/issues/182#issuecomment-443388022
    - 蘋果系列問題：https://github.com/JoseCDB/instascan/commit/a016f8b05a6ee18362084184afa0398945fa83aa
    - 測試設定：https://stackoverflow.com/questions/34197653/getusermedia-in-chrome-47-without-using-https/34198101#34198101
    - 備案：https://github.com/nimiq/qr-scanner
- PDF 產生器文件：https://github.com/barryvdh/laravel-dompdf

## 報到系統使用教學注意事項

1. Line 瀏覽器無法使用 QR code，必須直接使用 Chrome 或 Safari 開啟網頁
2. iOS 建議使用 Safari，因   
    A. 部分 iOS 使用 Safari 會無法登入顯示 419，且使用 Chrome 會無法觸發詢問相機使用需求視窗，此狀況只能使用 Safari，並在手機設定中將 Safari 的「阻擋 cookie」功能關閉
    B. 部分 iOS 需調整相機權限，否則無法使用 QR code
3. 相機曾開啟，後來又打不開的情況，進入多工選單將瀏覽器強制關閉後，再重啟瀏覽器即可排除

## 已完成功能

- 手機號碼搜尋方式調整（-）
- Safari 無法登入（419）
- 即時報到統計詳細資料效能改善
- 查詢功能下載全區資料，依日期顯示是否已報到，需修正，日期以報到資料為準
- 營隊管理功能
- custom 寄信功能：指定錄取梯次、組別
- 查詢功能下載全區資料，依日期顯示是否已報到
- custom 寄信功能：全體錄取人士
- 後台現場手動繳費、修改繳費資料：各梯次的人皆可執行
- 報名網頁查詢中，可下載學員報到通知單及 QR code
- 銷帳資料表新增手機號碼
- 報到系統：即時顯示有多少學員已完成、未完成報到，總 → 梯次
- 組別樣板：姓名、身份證、縣市、服務學校
- 帳號管理要能指定權限
- 報到系統：已錄取已繳費的人才可報到
- 錄取相關：新增群組寄送報到通知單功能
- 使用 Queue 郵寄報到通知
- 查詢功能下載全區資料，加上「銷帳編號」欄位
- 下載資料移除未使用欄位
- 組別異動要檢查繳費資料
- 報名名單新增銷帳帳號
- 後台報到統計
- 報到系統：
    1. 可以分天報到 -> 使用單一報到表格儲存報到記錄
    2. QR code 報到條碼
- home 頁 null object handler
- 最終報名截止日：教師營 12/31
- 展開全部按鈕 
- URL 怪異 bug: "http"
- 銷帳資料下載
- 報名資料下載是否已繳費補正
- 錄取頁面完善
- 使用 Queue 郵寄錄取通知
- **重大 bug: Queue 郵寄無法正確取得資料**
- 組別名單：新增欄位：繳費與否
- Email 文字修改
- 權限確認：寄發全組錄取通知信**僅限總部**
- 銷帳資料表修改：姓名、營隊名 + 梯次名、實繳金額、應繳金額
- 銷帳資料頁
- 錄取頁面及檢視個人資料顯示報名日期
- 繳費資料表、銷帳資料表
- **自動銷帳（定期檢查是否有新資料，若有便將 txt 解析後存入資料庫，不可重複讀寫)**
- Email 錄取通知單文字修改、附件文字修改
- 錄取編號首字母設定：各梯次不同字首，格式：1 字母 + 2 組別 + 2 編號
    - 台北(A)、桃園(B)、新竹(C)、台中(D)、雲林(E)、嘉義(F)、台南(G)、高雄(H)
- 組別名單：顯示全部、分區、校群，檢視時可下載
- 錄取通知加上繳費單附件
- 錄取後生成繳費帳號
- 梯次：新增欄位（地點名稱、地址、報到時間、聯絡電話）
- 任教學程統計
- 後台可變更報名者區域及梯次
- 查詢及下載：全部、各區、各校群資料查詢及下載，需顯示所有欄位
- 新增批次錄取
- 各營隊後台功能過濾
- 組別名單
- 群組錄取通知信
- 報名人新增欄位：願意收到福智文教基金會相關活動資訊
- 報名後發送通知信
- 報名序號寄至信箱
- 大專營報名後自動分配地區：台北、桃園、新竹、台中、嘉義、台南、高雄、海外
- Checkbox 無法正確回填資料
- 所有未填寫或格式不符的欄位都應出現提醒文字
- 檢查 Email 是否填寫一致
- 逾期不得報名，除非從後台進入