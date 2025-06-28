# 申請人更換營隊/梯次功能實作計畫

## 📋 專案概述

為福智營隊管理系統新增「幫 applicant 更換營隊或梯次」的功能，允許管理員在特定條件下將已報名的申請人轉移到其他合適的梯次。

## 🔍 現有系統分析

### 核心資料模型關聯
- **Applicant** → **Batch** → **Camp** （申請人 → 梯次 → 營隊）
- 關鍵欄位：
  - `applicant.batch_id` - 申請人所屬梯次
  - `batch.camp_id` - 梯次所屬營隊
  - `applicant.is_admitted` - 錄取狀態
  - `applicant.admitted_at` - 錄取時間

### 重要檔案位置
- **Applicant 模型**: `/home/lzong/bwcamp/app/Models/Applicant.php`
- **Camp 模型**: `/home/lzong/bwcamp/app/Models/Camp.php`
- **Batch 模型**: `/home/lzong/bwcamp/app/Models/Batch.php`
- **主要控制器**: 
  - `app/Http/Controllers/CampController.php`
  - `app/Http/Controllers/BackendController.php`

## 🎯 業務邏輯與驗證規則設計

### 1. 基本驗證規則（簡化版）
- ✅ 申請人必須存在
- ✅ 目標梯次必須存在
- ✅ 不允許更換到相同梯次
- ✅ 權限驗證：只有具備 Applicant Write 權限的使用者可操作

### 2. 營隊類型轉換支援
- ✅ 同營隊類型內的梯次可互換（如：大專營 A 梯 → 大專營 B 梯）
- ✅ 不同營隊類型間支援轉換（如：大專營 → 教師營）

### 3. 跨營隊轉換處理邏輯 🆕

#### 同營隊類型轉換（如：大專營 A 梯 → 大專營 B 梯）
- **保留所有特殊欄位資料**：直接複製到新梯次
- **重置狀態欄位**：錄取、繳費、出席狀態等

#### 跨營隊類型轉換（如：大專營 → 教師營）
- **保留基礎資料**：個人資訊、聯絡方式、介紹人等
- **清空特殊欄位**：刪除原營隊特殊表格記錄
- **不自動填入**：目標營隊特殊欄位留空，由管理員後續手動填寫
- **設置提醒標記**：標示為跨營隊轉換，需要人工完善資料

#### 資料處理策略
1. **基礎層 (Applicants 表)**：
   - 保留：姓名、性別、生日、聯絡資訊、介紹人資料
   - 重置：`is_admitted`、`is_paid`、`is_attend`、`group_id`、`number_id`
   - 更新：`batch_id`、`fee`

2. **特殊層 (營隊表)**：
   - **同類型**：完整複製特殊欄位資料
   - **跨類型**：刪除原記錄，不創建新記錄（留空）

3. **標記機制**：
   - 新增 `transfer_note` 或使用現有備註欄位
   - 記錄轉換來源：「從 XX營 轉入，需補完資料」

### 4. 費用處理邏輯
```php
// 根據目標梯次營隊重新計算費用
$targetCamp = $targetBatch->camp;
$newFee = $targetCamp->getSetFeeAttribute(); // 考慮早鳥價
```

### 5. 資料一致性保證
- 使用資料庫交易確保原子性操作
- 更新相關聯表格（交通、住宿、簽到記錄等）
- 記錄操作日誌供後續查核

## 🛠 實作步驟規劃

### 步驟 1: ✅ 分析現有 applicant 相關的程式碼結構和資料庫模型
**狀態**: 已完成
- 已分析 Applicant、Camp、Batch 模型結構
- 了解資料庫關聯和業務邏輯

### 步驟 2: ✅ 了解營隊和梯次的資料結構與關聯
**狀態**: 已完成
- 確認 Applicant → Batch → Camp 關聯
- 了解各營隊特殊表格結構

### 步驟 3: ✅ 設計更換營隊/梯次的業務邏輯和驗證規則
**狀態**: 已完成
- 定義完整的驗證規則
- 設計資料處理邏輯

### 步驟 3.1: ✅ 重新設計支援不同營隊類型間轉換的業務邏輯
**狀態**: 已完成
- 分析各營隊特殊表格結構差異
- 簡化為留空處理策略
- 建立跨營隊資料處理架構

### 步驟 3.2: ✅ 更新驗證規則為最小必要驗證
**狀態**: 已完成
- 移除軟刪除檢查
- 移除報名期限和可報名狀態檢查
- 簡化為基本存在性和權限驗證

### 步驟 4: ✅ 實作後端 API 端點處理更換請求
**狀態**: 已完成

**已實作內容**:
```php
// Route: POST /api/applicant/transfer
// 新增服務類別：
// - ApplicantTransferService：主要轉換邏輯
```

**已完成功能**:
- ✅ 基本參數驗證（申請人存在、目標梯次存在、非相同梯次）
- ✅ 權限驗證（使用 canAccessResource 檢查來源和目標營隊權限）
- ✅ 營隊類型相同/不同判斷
- ✅ 同類型：保留所有特殊資料
- ✅ 跨類型：僅保留基礎資料，特殊欄位清空
- ✅ 資料庫交易處理
- ✅ 轉換標記和備註（在 expectation 欄位）
- ✅ 操作日誌記錄
- ✅ Group 和 Carer 資訊清除
- ✅ 詳細變更追蹤

**主要檔案修改**:
- `app/Services/ApplicantTransferService.php` (新增)
- `app/Http/Controllers/BackendController.php` (新增 API 端點)
- `routes/api.php` (新增路由)

### 步驟 5: ✅ 建立前端介面供管理員操作
**狀態**: 已完成
**實作位置**: 後台學員詳情頁面

**已實作功能**:
- ✅ 在學員詳情頁面新增「轉換營隊/梯次」按鈕
- ✅ 權限控制：只有有權限的用戶才看到按鈕
- ✅ 模態框介面提供營隊/梯次選擇
- ✅ 即時載入可用梯次列表
- ✅ 轉換確認機制
- ✅ 操作結果回饋（成功/失敗訊息）
- ✅ 支援所有營隊類型的學員詳情頁

**主要檔案修改**:
- `resources/views/components/transfer/applicant-transfer-button.blade.php` (新增)
- `resources/views/components/transfer/applicant-transfer-modal.blade.php` (新增)
- `resources/views/backend/in_camp/attendeeInfo*.blade.php` (5個檔案，整合轉換按鈕)

### 步驟 6: ✅ 撰寫相關測試確保功能正確性
**狀態**: 已完成

**已實作測試**:
- ✅ **Feature Tests**: 完整的申請人轉換測試套件
  - 同營隊類型轉換測試（資料完整保留）
  - 跨營隊類型轉換測試（特殊欄位清空）
  - 權限驗證測試
  - Group 和 Carer 清除測試
  - API 端點測試（認證、驗證、回應格式）
  - 邊界情況測試（申請人不存在、梯次不存在、相同梯次等）
  - 梯次開始時間驗證測試

**主要檔案修改**:
- `tests/Feature/ApplicantTransferTest.php` (新增，包含 14 個測試案例)
- `CLAUDE.md` (更新 Docker 測試指令說明)

## ⚠️ 風險評估與注意事項

### 高風險操作
- 可能影響已錄取學員的分組和座號
- 繳費記錄需要重新確認
- 相關通知和報表需要更新

### 安全考量
- 嚴格的權限控制
- 操作日誌記錄
- 關鍵操作二次確認

### 資料完整性
- 保持營隊特殊欄位資料
- 確保關聯表格一致性
- 提供回滾機制

## 📝 後續開發注意事項

1. **權限管理**: 確保只有具備適當權限的管理員可以執行此操作
2. **日誌記錄**: 詳細記錄每次轉移操作，包括操作人、時間、原因等
3. **通知機制**: 考慮是否需要通知申請人梯次變更
4. **報表更新**: 確保相關統計報表能正確反映變更後的資料
5. **測試覆蓋**: 涵蓋各種邊界情況和錯誤處理

## 🔄 開發狀態追蹤

- [x] 系統分析和設計
- [x] 跨營隊轉換邏輯設計（簡化版：留空由人工處理）
- [x] 簡化驗證規則為最小必要驗證
- [x] 後端 API 實作
- [x] 前端介面開發
- [x] 測試撰寫和驗證
- [x] 權限系統修正 🆕
- [ ] 上線部署

## 🔒 權限系統修正 🆕

### 發現的問題
- 原先使用未定義的 `Gate::allows('applicant-write')` 和 `Gate::allows('applicant-read')`
- 系統實際使用 Laratrust + 自定義 `canAccessResource()` 方法

### 修正內容
- ✅ 將 `transferApplicant` API 改用 `canAccessResource()` 檢查權限
- ✅ 加入來源營隊和目標營隊的雙重權限檢查
- ✅ 移除未定義的 Gate 呼叫
- ✅ 符合系統現有的權限檢查慣例

### 修正檔案
- `app/Http/Controllers/BackendController.php` (修正權限檢查邏輯)

## 📋 完整實作總結 🆕

### 新增檔案 (Untracked Files)
```
✅ CLAUDE.md                                    # Claude Code 專案說明文件
✅ app/Services/ApplicantTransferService.php    # 申請人轉換核心業務邏輯
✅ applicant-transfer-plan.md                   # 本開發計畫文件
✅ resources/views/components/transfer/         # 轉換功能前端元件目錄
   ├── applicant-transfer-button.blade.php     # 轉換按鈕元件
   └── applicant-transfer-modal.blade.php      # 轉換模態框元件
✅ tests/Feature/ApplicantTransferTest.php      # 完整測試套件 (14個測試案例)
```

### 修改檔案 (Modified Files)
```
✅ app/Http/Controllers/BackendController.php   # 新增 API 端點 + 權限修正
✅ app/Http/Controllers/CampController.php      # (其他修改)
✅ resources/views/backend/in_camp/attendeeInfo.blade.php      # 整合轉換按鈕
✅ resources/views/backend/in_camp/attendeeInfoAcamp.blade.php  # 整合轉換按鈕
✅ resources/views/backend/in_camp/attendeeInfoCeocamp.blade.php # 整合轉換按鈕
✅ resources/views/backend/in_camp/attendeeInfoEcamp.blade.php   # 整合轉換按鈕
✅ resources/views/backend/in_camp/attendeeInfoYcamp.blade.php   # 整合轉換按鈕
✅ routes/api.php                              # 新增轉換 API 路由
✅ tests/Feature/ApplicantTest.php             # (其他測試修改)
✅ tests/Feature/ExampleTest.php               # (其他測試修改)
```

### 核心功能特色
- 🔄 **跨營隊轉換支援**：自動判斷營隊類型，處理資料保留/清空邏輯
- 🔒 **完整權限控制**：來源和目標營隊雙重權限檢查
- 🧹 **資料清理機制**：自動清除 group、number、carer 關聯
- 📝 **詳細變更追蹤**：完整記錄所有變更內容
- 🔧 **交易安全性**：資料庫交易確保原子性操作
- 📊 **操作日誌**：記錄轉換操作供後續查核
- 🧪 **完整測試覆蓋**：涵蓋功能、權限、邊界情況測試

### 統計資料
- **程式碼修改**: 10 個檔案，+268/-90 行
- **新增檔案**: 6 個檔案
- **測試案例**: 14 個完整測試
- **支援營隊**: 所有主要營隊類型 (Ycamp, Tcamp, Ecamp, Acamp, Ceocamp 等)

## 📊 營隊轉換矩陣 🆕

| 源營隊 → 目標營隊 | Ycamp | Tcamp | Ecamp | Hcamp | Acamp | Ceocamp |
|:---:|:---:|:---:|:---:|:---:|:---:|:---:|
| **Ycamp** | ✅ | 🔄 | 🔄 | ❌ | 🔄 | 🔄 |
| **Tcamp** | 🔄 | ✅ | 🔄 | ❌ | 🔄 | 🔄 |
| **Ecamp** | 🔄 | 🔄 | ✅ | ❌ | 🔄 | ✅ |
| **Hcamp** | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ |
| **Acamp** | 🔄 | 🔄 | 🔄 | ❌ | ✅ | 🔄 |
| **Ceocamp** | 🔄 | 🔄 | ✅ | ❌ | 🔄 | ✅ |

**圖例**：
- ✅ 同營隊類型，保留所有特殊資料
- 🔄 跨營隊類型，清空特殊欄位留待人工填寫
- ❌ 不建議轉換（差異過大或業務邏輯不符）

---

**建立日期**: 2025-06-28  
**最後更新**: 2025-06-28 (完成完整功能開發 + 權限系統修正)  
**負責開發**: Claude Code Assistant