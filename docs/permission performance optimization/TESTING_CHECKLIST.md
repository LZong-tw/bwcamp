# 權限系統測試與檢查清單

## 🧪 測試階段規劃

### **階段 1: 基礎功能驗證**
### **階段 2: 效能測試**
### **階段 3: 快取機制測試**
### **階段 4: 整合測試**
### **階段 5: 生產環境驗證**

---

## 🔍 階段 1: 基礎功能驗證

### **1.1 服務註冊檢查**

```bash
# 檢查服務是否正確註冊
php artisan tinker

# 在 tinker 中執行
app(App\Services\Permission\EnhancedPermissionService::class);
app(App\Services\Permission\PermissionCache::class);
app(App\Services\Permission\CacheInvalidationService::class);
app(App\Services\Permission\PermissionFactory::class);

# 如果沒有錯誤，表示服務註冊成功
```

### **1.2 基本權限檢查**

```php
// 在 tinker 中測試基本權限功能
$user = User::first();
$camp = Camp::first();
$applicant = Applicant::first();

// 測試新系統
$result1 = $user->canAccessResource($applicant, 'read', $camp);
echo "新系統結果: " . ($result1 ? 'true' : 'false') . "\n";

// 測試舊系統對比
$result2 = $user->canAccessResourceLegacy($applicant, 'read', $camp);
echo "舊系統結果: " . ($result2 ? 'true' : 'false') . "\n";

// 結果應該一致
echo "結果一致: " . ($result1 === $result2 ? 'Yes' : 'No') . "\n";
```

### **1.3 批次權限檢查**

```php
// 測試批次檢查功能
$applicants = Applicant::take(10)->get();

$batchResults = $user->canAccessResourceBatch($applicants->toArray(), 'read', $camp);
echo "批次檢查結果數量: " . count($batchResults) . "\n";

// 驗證批次結果與單個檢查一致
foreach ($applicants as $key => $applicant) {
    $singleResult = $user->canAccessResource($applicant, 'read', $camp);
    $batchResult = $batchResults[$key];
    
    if ($singleResult !== $batchResult) {
        echo "不一致發現在索引 {$key}\n";
    }
}
echo "批次檢查驗證完成\n";
```

---

## ⚡ 階段 2: 效能測試

### **2.1 執行單元測試**

```bash
# 執行權限相關測試
php artisan test tests/Unit/PermissionServiceTest.php -v
php artisan test tests/Feature/PermissionPerformanceTest.php -v

# 檢查測試結果
echo $?  # 應該回傳 0 表示成功
```

### **2.2 效能基準測試**

```php
// 在 tinker 中執行效能分析
$analyzer = app(App\Services\Permission\PerformanceAnalyzer::class);
$user = User::first();
$camp = Camp::first();
$applicants = Applicant::take(50)->get();

$report = $analyzer->generatePerformanceReport(
    $user, 
    $applicants->toArray(), 
    'read', 
    $camp
);

echo $report;
```

### **2.3 大量資料測試**

```php
// 測試大量資料的處理能力
$applicants = Applicant::take(500)->get();

$startTime = microtime(true);
$results = $user->canAccessResourceBatch($applicants->toArray(), 'read', $camp);
$endTime = microtime(true);

$executionTime = ($endTime - $startTime) * 1000;
echo "500個資源批次檢查時間: {$executionTime}ms\n";

// 應該在 100ms 以內完成
if ($executionTime < 100) {
    echo "✅ 效能測試通過\n";
} else {
    echo "❌ 效能測試未達標\n";
}
```

---

## 🗄️ 階段 3: 快取機制測試

### **3.1 快取基本功能**

```bash
# 檢查快取統計
php artisan permission:clear-cache --stats

# 清除所有快取
php artisan permission:clear-cache --all

# 再次檢查統計（應該顯示 0 個快取鍵）
php artisan permission:clear-cache --stats
```

### **3.2 快取失效測試**

```php
// 測試快取失效機制
$user = User::first();
$camp = Camp::first();
$applicant = Applicant::first();

// 第一次檢查（建立快取）
$result1 = $user->canAccessResource($applicant, 'read', $camp);

// 修改權限相關資料
$role = $user->roles()->where('camp_id', $camp->id)->first();
if ($role) {
    $role->touch(); // 觸發觀察者
}

// 再次檢查（應該重新計算）
$result2 = $user->canAccessResource($applicant, 'read', $camp);

echo "快取失效測試完成\n";
```

### **3.3 Observer 觸發測試**

```php
// 測試 Observer 是否正確觸發
Log::info('開始 Observer 測試');

$role = Role::first();
$role->update(['updated_at' => now()]); // 應該觸發 RoleObserver

$permission = Permission::first();
$permission->update(['updated_at' => now()]); // 應該觸發 PermissionObserver

// 檢查日誌
tail -f storage/logs/laravel.log | grep "cache invalidated"
```

---

## 🔗 階段 4: 整合測試

### **4.1 現有功能相容性**

```bash
# 檢查現有的權限檢查是否正常工作
# 找出所有使用 canAccessResource 的地方
grep -r "canAccessResource" app/ --include="*.php" | head -10

# 在瀏覽器中測試這些頁面是否正常運作
```

### **4.2 Web 介面測試**

1. **學員清單頁面**
   - 訪問學員清單
   - 確認只顯示有權限的學員
   - 檢查載入速度是否有改善

2. **資料匯出功能**
   - 執行學員資料匯出
   - 確認匯出的資料正確
   - 檢查匯出速度

3. **權限控制按鈕**
   - 檢查編輯/刪除按鈕是否根據權限顯示
   - 測試不同權限等級的使用者

### **4.3 API 端點測試**

```bash
# 如果有 API 端點使用權限檢查
curl -H "Authorization: Bearer YOUR_TOKEN" \
     -X GET "http://your-domain/api/applicants" \
     -w "Time: %{time_total}s\n"

# 檢查回應時間和資料正確性
```

---

## 🚀 階段 5: 生產環境驗證

### **5.1 部署前檢查**

```bash
# 1. 確保所有測試通過
php artisan test

# 2. 檢查語法錯誤
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. 檢查 PHP 語法
find app/ -name "*.php" -exec php -l {} \; | grep -v "No syntax errors"

# 4. 檢查依賴
composer validate
```

### **5.2 漸進式部署**

1. **測試環境部署**
   ```bash
   # 部署到測試環境
   git checkout Permission-Performance-Optimization-trail
   composer install --no-dev
   php artisan migrate --pretend  # 檢查是否有遷移
   ```

2. **監控測試環境**
   ```bash
   # 監控日誌
   tail -f storage/logs/laravel.log | grep -E "(permission|cache|error)"
   
   # 檢查效能
   ab -n 100 -c 10 http://test-domain/applicants
   ```

3. **生產環境部署**
   - 在低峰時段部署
   - 準備快速回滾計劃
   - 即時監控系統狀態

### **5.3 生產環境監控**

```bash
# 1. 監控快取命中率
php artisan permission:clear-cache --stats

# 2. 監控效能指標
# 使用 Laravel Telescope 或 APM 工具

# 3. 監控錯誤日誌
tail -f storage/logs/laravel.log | grep ERROR

# 4. 監控記憶體使用
free -m
```

---

## 📊 性能基準

### **預期效能目標**

| 項目 | 目標值 | 測試方法 |
|-----|--------|----------|
| 單個權限檢查 | < 10ms | 使用 PerformanceAnalyzer |
| 批次檢查 (100個) | < 50ms | 批次API測試 |
| 快取命中率 | > 80% | 快取統計命令 |
| 記憶體增加 | < 20% | 記憶體監控 |

### **回滾條件**

如果出現以下情況，立即回滾：
- 權限檢查結果不正確
- 效能比原系統差
- 出現致命錯誤
- 記憶體使用量暴增

---

## 🛠️ 除錯工具

### **快速除錯命令**

```bash
# 1. 檢查服務狀態
php artisan tinker --execute="var_dump(app(App\Services\Permission\EnhancedPermissionService::class));"

# 2. 檢查快取狀態
php artisan permission:clear-cache --stats

# 3. 清除問題快取
php artisan permission:clear-cache --all

# 4. 檢查日誌
tail -100 storage/logs/laravel.log | grep -i permission

# 5. 檢查觀察者註冊
php artisan route:list | grep -i observer
```

### **常見問題排查**

1. **服務無法注入**
   - 檢查 `AppServiceProvider` 註冊
   - 清除設定快取：`php artisan config:clear`

2. **權限結果不一致**
   - 使用除錯模式：`canAccessResource(..., null, null, true)`
   - 檢查快取鍵是否正確

3. **效能沒有改善**
   - 確認 Redis 正確配置
   - 檢查快取命中率

4. **觀察者沒有觸發**
   - 檢查觀察者註冊
   - 確認模型事件正常觸發

---

## ✅ 測試完成確認

完成所有測試後，確認以下檢查點：

- [ ] 所有單元測試通過
- [ ] 效能測試達到目標
- [ ] 快取機制正常工作
- [ ] 現有功能無異常
- [ ] 生產環境監控正常
- [ ] 文件和指南完整

**測試通過後，你的權限系統就可以安全地在生產環境使用了！** 🚀