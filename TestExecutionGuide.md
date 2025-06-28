# Applicant Transfer 測試執行指南

## 📋 總覽

本指南提供 Applicant Transfer（申請人轉換營隊/梯次）功能的完整測試執行方法，包含單元測試、整合測試和端到端測試的詳細步驟。

## 🎯 測試範圍

### 測試類型
- **單元測試**: 服務類別方法測試
- **整合測試**: API 端點和資料庫整合
- **端到端測試**: 完整用戶流程測試
- **前端測試**: UI 組件和互動測試

### 測試檔案
- `tests/Feature/ApplicantTransferTest.php` - 核心功能測試
- `tests/Feature/ApplicantTransferFrontendTest.php` - 前端整合測試
- `tests/Feature/ApplicantTransferSimpleTest.php` - 基本驗證測試

## 🚀 快速開始

### 前置準備
```bash
# 確保 Docker 容器正在運行
docker ps | grep bwcamp

# 進入容器 (如果需要)
docker exec -it bwcamp bash
```

### 基本測試執行
```bash
# 執行所有 Applicant Transfer 測試
docker exec bwcamp ./vendor/bin/pest tests/Feature/ApplicantTransfer*.php

# 或使用 PHPUnit
docker exec bwcamp ./vendor/bin/phpunit tests/Feature/ApplicantTransferTest.php
```

## 🔧 測試執行方法

### 1. 自動化測試

#### 全套測試
```bash
# 執行所有轉換相關測試
docker exec bwcamp ./vendor/bin/pest tests/Feature/ApplicantTransfer*.php

# 詳細輸出模式
docker exec bwcamp ./vendor/bin/pest -v tests/Feature/ApplicantTransferTest.php

# 包含測試覆蓋率
docker exec bwcamp ./vendor/bin/pest --coverage tests/Feature/ApplicantTransfer*.php
```

#### 分類測試
```bash
# 只執行端到端測試
docker exec bwcamp ./vendor/bin/pest --filter="end_to_end"

# 只執行 API 測試
docker exec bwcamp ./vendor/bin/pest --filter="api.*transfer"

# 只執行權限測試
docker exec bwcamp ./vendor/bin/pest --filter="permission"

# 只執行前端測試
docker exec bwcamp ./vendor/bin/pest tests/Feature/ApplicantTransferFrontendTest.php

# 只執行跨營隊測試
docker exec bwcamp ./vendor/bin/pest --filter="cross.*camp"
```

#### 特定測試方法
```bash
# 測試同營隊類型轉換
docker exec bwcamp ./vendor/bin/pest --filter="it_can_transfer_applicant_between_same_camp_type_batches"

# 測試跨營隊類型轉換
docker exec bwcamp ./vendor/bin/pest --filter="it_can_transfer_applicant_between_different_camp_types"

# 測試驗證規則
docker exec bwcamp ./vendor/bin/pest --filter="it_throws_exception_when_transferring_to_same_batch"

# 測試完整 API 工作流程
docker exec bwcamp ./vendor/bin/pest --filter="end_to_end_api_transfer_with_complete_workflow"
```

### 2. 手動測試

#### 環境檢查
```bash
# 進入 Laravel Tinker
docker exec -it bwcamp php artisan tinker

# 執行環境檢查腳本
include 'manual_test_script.php'
```

#### API 功能測試
```bash
# 在 Tinker 中執行 API 測試
docker exec -it bwcamp php artisan tinker

# 執行 API 測試腳本
include 'api_test_script.php'
```

## 🛠 故障排除

### 常見問題解決

#### 1. SQLite 遷移錯誤
```bash
# 錯誤: "SQLite doesn't support multiple calls to dropColumn"
# 解決方案:

# 清除測試資料庫
docker exec bwcamp rm -f database/testing.sqlite

# 重新建立測試環境
docker exec bwcamp php artisan migrate:fresh --env=testing

# 確保測試表格存在
docker exec bwcamp php artisan migrate --env=testing
```

#### 2. 測試資料庫連接問題
```bash
# 檢查資料庫連接
docker exec -it bwcamp php artisan tinker
DB::connection()->getPdo()

# 如果失敗，重新設定測試環境
docker exec bwcamp php artisan config:cache
docker exec bwcamp php artisan migrate:fresh
```

#### 3. 權限相關錯誤
```bash
# 檢查用戶權限
docker exec -it bwcamp php artisan tinker
$user = User::first()
$user->roles
$user->permissions

# 檢查營隊存取權限
$camp = Camp::first()
$user->canAccessResource('write', 'applicant', $camp)
```

#### 4. 測試資料不足
```bash
# 檢查測試資料
docker exec -it bwcamp php artisan tinker

# 檢查營隊數量
Camp::count()

# 檢查梯次數量  
Batch::count()

# 檢查申請人數量
Applicant::count()

# 如果資料不足，執行 seeder
docker exec bwcamp php artisan db:seed
```

### 替代測試方法

#### 方法 1: 使用開發資料庫測試 (謹慎使用)
```bash
# 備份現有資料庫
docker exec bwcamp cp database/database.sqlite database/database_backup.sqlite

# 使用開發資料庫執行測試
docker exec bwcamp php artisan test --filter="ApplicantTransfer"

# 測試完成後恢復備份 (如有必要)
docker exec bwcamp cp database/database_backup.sqlite database/database.sqlite
```

#### 方法 2: 單獨建立測試資料
```bash
# 進入 Tinker 建立測試資料
docker exec -it bwcamp php artisan tinker

# 建立測試營隊
$yCamp = Camp::create(['table' => 'ycamp', 'fullName' => '大專營測試', 'abbreviation' => 'YT'])
$tCamp = Camp::create(['table' => 'tcamp', 'fullName' => '教師營測試', 'abbreviation' => 'TT'])

# 建立測試梯次
$yBatch = Batch::create(['camp_id' => $yCamp->id, 'name' => 'A梯測試', 'batch_start' => '2025-07-01'])
$tBatch = Batch::create(['camp_id' => $tCamp->id, 'name' => 'B梯測試', 'batch_start' => '2025-07-15'])

# 建立測試申請人
$applicant = Applicant::create([
    'batch_id' => $yBatch->id,
    'name' => '測試學員A',
    'is_admitted' => true,
    'is_paid' => false
])
```

## 📊 測試結果驗證

### 成功標準

#### 自動化測試
- ✅ 所有基本轉換操作成功完成
- ✅ 資料完整性在所有情境下維持
- ✅ 權限執行正確
- ✅ 完整稽核追蹤產生
- ✅ 前端組件正確渲染
- ✅ API 回應格式一致
- ✅ 錯誤處理提供有意義的回饋

#### 手動測試驗證點
```bash
# 檢查轉換前後的資料變化
# 轉換前
$applicant = Applicant::find(1)
$originalBatchId = $applicant->batch_id
$originalAdmitted = $applicant->is_admitted

# 執行轉換
$service = new App\Services\ApplicantTransferService()
$result = $service->transferApplicant(1, 2)

# 轉換後驗證
$applicant->refresh()
$applicant->batch_id !== $originalBatchId  // 梯次已更改
$applicant->is_admitted === false          // 錄取狀態已重置
$result['success'] === true                // 轉換成功
```

### 效能基準
- API 回應時間: < 500ms
- 資料庫交易時間: < 200ms  
- UI 回應性: 立即回饋用戶操作

## 🔍 深度測試指南

### 完整端到端測試流程

#### 1. 同營隊類型轉換測試
```bash
# 手動執行完整流程
docker exec -it bwcamp php artisan tinker

# 設定測試情境
$yCamp = Camp::where('table', 'ycamp')->first()
$sourceBatch = Batch::where('camp_id', $yCamp->id)->first()
$targetBatch = Batch::where('camp_id', $yCamp->id)->where('id', '!=', $sourceBatch->id)->first()
$applicant = Applicant::where('batch_id', $sourceBatch->id)->first()

# 記錄原始狀態
$originalData = $applicant->toArray()

# 執行轉換
$service = new App\Services\ApplicantTransferService()
$result = $service->transferApplicant($applicant->id, $targetBatch->id)

# 驗證結果
$applicant->refresh()
echo "轉換成功: " . ($result['success'] ? 'Yes' : 'No')
echo "同營隊類型: " . ($result['is_same_camp_type'] ? 'Yes' : 'No')
echo "新梯次ID: " . $applicant->batch_id
```

#### 2. 跨營隊類型轉換測試
```bash
# 跨營隊轉換 (例: 大專營 → 教師營)
docker exec -it bwcamp php artisan tinker

$yCamp = Camp::where('table', 'ycamp')->first()
$tCamp = Camp::where('table', 'tcamp')->first()
$sourceBatch = Batch::where('camp_id', $yCamp->id)->first()
$targetBatch = Batch::where('camp_id', $tCamp->id)->first()
$applicant = Applicant::where('batch_id', $sourceBatch->id)->first()

# 執行跨營隊轉換
$service = new App\Services\ApplicantTransferService()
$result = $service->transferApplicant($applicant->id, $targetBatch->id)

# 驗證跨營隊特殊處理
$applicant->refresh()
echo "轉換成功: " . ($result['success'] ? 'Yes' : 'No')
echo "跨營隊類型: " . ($result['is_same_camp_type'] ? 'No' : 'Yes')
echo "轉換備註: " . $applicant->expectation
```

#### 3. 權限測試
```bash
# 測試不同權限等級的用戶
docker exec -it bwcamp php artisan tinker

# 建立測試用戶
$adminUser = User::first()
$limitedUser = User::skip(1)->first()

# 模擬不同用戶的轉換請求
auth()->login($adminUser)
// 執行轉換測試

auth()->login($limitedUser)  
// 執行權限限制測試
```

### API 端點測試

#### 使用 cURL 測試
```bash
# 取得可用梯次列表
curl -X GET \
  -H "Accept: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -b "laravel_session=your_session_cookie" \
  "http://localhost/api/batches/available"

# 執行轉換
curl -X POST \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -b "laravel_session=your_session_cookie" \
  -d '{"applicant_id": 1, "target_batch_id": 2}' \
  "http://localhost/api/applicant/transfer"
```

## 📝 測試記錄與報告

### 測試執行記錄
```bash
# 產生測試報告
docker exec bwcamp ./vendor/bin/pest tests/Feature/ApplicantTransfer*.php --coverage-text

# 將結果輸出到檔案
docker exec bwcamp ./vendor/bin/pest tests/Feature/ApplicantTransfer*.php > test_results.txt 2>&1
```

### 日誌檢查
```bash
# 檢查應用程式日誌
docker exec bwcamp tail -f storage/logs/laravel.log

# 檢查轉換操作日誌
docker exec -it bwcamp php artisan tinker
Log::info('手動測試開始')
// 執行測試
// 檢查日誌記錄
```

## 🔄 持續整合建議

### CI/CD 管道中的測試
```yaml
# GitHub Actions 或類似 CI 設定範例
test_applicant_transfer:
  steps:
    - name: Setup Database
      run: |
        php artisan migrate:fresh --env=testing
        php artisan db:seed --env=testing
    
    - name: Run Unit Tests
      run: ./vendor/bin/pest tests/Feature/ApplicantTransfer*.php
      
    - name: Run Integration Tests  
      run: php artisan test --filter="ApplicantTransfer"
```

### 測試資料清理
```bash
# 測試完成後清理
docker exec -it bwcamp php artisan tinker

# 清除測試資料
Applicant::where('name', 'LIKE', '測試%')->delete()
Batch::where('name', 'LIKE', '%測試%')->delete() 
Camp::where('fullName', 'LIKE', '%測試%')->delete()
```

## ⚠️ 注意事項

### 安全考量
- 永遠不要在生產資料上執行測試而不備份
- 使用獨立的測試資料庫憑證
- 在所有測試情境中驗證權限執行
- 測試 SQL 注入和 XSS 漏洞

### 資料隱私
- 使用匿名或合成測試資料
- 避免在測試期間記錄敏感資訊  
- 完成後清理測試資料

### 效能考量
- 監控測試執行時間
- 使用資料庫交易進行測試隔離
- 避免在測試中產生過多日誌

---

**總結**: 本指南提供了 Applicant Transfer 功能的全面測試策略。結合自動化和手動測試方法，確保功能在所有情境下都能正常運作。建議依序執行測試，從基本功能驗證開始，逐步進行完整的端到端測試。