# 權限快取失效機制指南

## 🎯 概述

為了確保權限系統的資料一致性，我們實作了完整的快取失效機制。當權限相關資料變更時，系統會自動清除相關快取並可選地預熱新資料。

---

## 🔄 自動失效觸發

### **1. Model Observer 觸發**

#### Role 變更
```php
// 當角色被建立、更新、刪除時自動觸發
Role::create([...]);  // 觸發 RoleObserver::created
$role->update([...]);  // 觸發 RoleObserver::updated
$role->delete();       // 觸發 RoleObserver::deleted
```

#### Permission 變更
```php
// 當權限被建立、更新、刪除時自動觸發
Permission::create([...]);  // 觸發 PermissionObserver::created
$permission->update([...]); // 觸發 PermissionObserver::updated
$permission->delete();      // 觸發 PermissionObserver::deleted
```

#### User 變更
```php
// 當使用者狀態相關欄位變更時觸發
$user->update(['status' => 'inactive']); // 觸發 UserObserver::updated
$user->delete();                         // 觸發 UserObserver::deleted
```

### **2. 事件系統觸發**

#### 角色指派/移除
```php
// 使用者角色變更時觸發
$user->attachRole($role);   // 觸發 PermissionChanged 事件
$user->detachRole($role);   // 觸發 PermissionChanged 事件
```

---

## 🛠️ 手動快取管理

### **1. 使用 CacheInvalidationService**

```php
$cacheInvalidation = app(CacheInvalidationService::class);

// 清除使用者的特定營隊快取
$cacheInvalidation->invalidateUserCampCache($user, $camp);

// 清除使用者的所有快取
$cacheInvalidation->invalidateAllUserCache($user);

// 清除角色相關快取
$cacheInvalidation->invalidateRoleCache($role);

// 清除權限相關快取
$cacheInvalidation->invalidatePermissionCache($permission);

// 清除營隊相關快取
$cacheInvalidation->invalidateCampCache($camp);

// 批次清除多個使用者快取
$cacheInvalidation->invalidateMultipleUsers([1, 2, 3], $camp);
```

### **2. 使用 User Model 方法**

```php
// 清除使用者快取
$user->clearPermissionCache();           // 清除所有營隊
$user->clearPermissionCache($camp);      // 清除特定營隊

// 觸發權限變更事件
$user->firePermissionChangedEvent($camp, 'manual_change', [
    'reason' => 'Admin manual update'
]);
```

### **3. 使用 Artisan 命令**

```bash
# 查看快取統計
php artisan permission:clear-cache --stats

# 清除所有權限快取
php artisan permission:clear-cache --all

# 清除特定使用者快取
php artisan permission:clear-cache --user=123

# 清除特定營隊快取
php artisan permission:clear-cache --camp=456
```

---

## 📊 快取層級結構

### **1. 快取鍵格式**

```
# 使用者權限快取
permission_user_permissions_{user_id}_{camp_id}

# 權限檢查結果快取
permission_{hash(user_id_resource_action_camp_context)}

# 營隊相關快取
camp_data_{camp_id}
camp_roles_{camp_id}
```

### **2. 失效策略**

| 變更類型 | 影響範圍 | 失效策略 |
|---------|---------|---------|
| 使用者角色變更 | 特定使用者+營隊 | 精確失效 |
| 權限設定變更 | 所有相關角色的使用者 | 批次失效 |
| 角色設定變更 | 該角色的所有使用者 | 批次失效 |
| 營隊設定變更 | 該營隊的所有使用者 | 營隊級失效 |

---

## ⚡ 效能優化機制

### **1. 智能失效**

```php
// 只有相關欄位變更才觸發失效
class UserObserver {
    public function updated(User $user) {
        $permissionRelevantFields = ['email', 'status', 'active'];
        
        $hasRelevantChanges = false;
        foreach ($permissionRelevantFields as $field) {
            if ($user->isDirty($field)) {
                $hasRelevantChanges = true;
                break;
            }
        }
        
        if ($hasRelevantChanges) {
            // 只在相關欄位變更時才清除快取
        }
    }
}
```

### **2. 非同步處理**

```php
// 權限變更事件使用佇列處理
class HandlePermissionChange implements ShouldQueue {
    public function handle(PermissionChanged $event) {
        // 非同步處理快取失效和預熱
    }
}
```

### **3. 快取預熱**

```php
// 清除快取後自動預熱常用資料
$cacheInvalidation->invalidateUserCampCache($user, $camp);
$cacheInvalidation->warmupCache($user, $camp);
```

---

## 🔍 監控與除錯

### **1. 快取統計**

```php
$stats = $cacheInvalidation->getCacheStats();
/*
[
    'total_permission_cache_keys' => 1234,
    'user_permission_cache_keys' => 567,
    'cache_store' => 'redis',
    'timestamp' => '2024-01-01T12:00:00Z'
]
*/
```

### **2. 日誌記錄**

所有快取操作都會記錄詳細日誌：

```
[2024-01-01 12:00:00] INFO: User role cache invalidated {"user_id":123,"camp_id":456,"scope":"camp_specific"}
[2024-01-01 12:00:01] DEBUG: Cache cleared by pattern {"pattern":"permission_*_user_123_*","keys_count":45}
[2024-01-01 12:00:02] INFO: Cache warmed up {"user_id":123,"camp_id":456}
```

### **3. 錯誤處理**

```php
try {
    $cacheInvalidation->invalidateUserCache($user);
} catch (\Exception $e) {
    // 失敗時自動執行全面清除
    Cache::flush();
    Log::error('Cache invalidation failed, performed full flush');
}
```

---

## 🎛️ 設定選項

### **1. 快取 TTL 設定**

```php
// 在 PermissionConstants 中設定
const CACHE_TTL = 600; // 10 分鐘
```

### **2. Redis vs 非 Redis**

```php
// Redis 環境：使用精確的 pattern 清除
if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
    $redis = Redis::connection();
    $keys = $redis->keys($pattern);
    $redis->del($keys);
}
// 非 Redis 環境：使用全面清除
else {
    Cache::flush();
}
```

---

## 🚨 注意事項

### **1. 效能考量**

- **批次操作**：大量角色變更時使用批次失效
- **預熱策略**：只對活躍使用者進行快取預熱
- **失效範圍**：精確控制失效範圍避免過度清除

### **2. 資料一致性**

- **即時失效**：權限變更立即觸發快取失效
- **事務安全**：在資料庫事務提交後才執行快取操作
- **失敗處理**：快取操作失敗時執行全面清除保證一致性

### **3. 監控建議**

- **定期檢查**：使用 `--stats` 命令監控快取狀態
- **日誌分析**：分析快取命中率和失效頻率
- **效能測試**：定期測試快取失效對系統效能的影響

---

## 📋 最佳實作

### **1. 權限變更流程**

```php
// 1. 執行資料庫變更
$user->roles()->attach($role);

// 2. 權限變更會自動觸發快取失效（通過 Observer）

// 3. 可選：手動預熱重要快取
$user->getCachedPermissions($camp);
```

### **2. 大量資料匯入**

```php
// 大量匯入時暫停快取
config(['permission.cache_enabled' => false]);

// 執行大量資料操作
foreach ($users as $user) {
    $user->roles()->attach($roles);
}

// 重新啟用快取並全面清除
config(['permission.cache_enabled' => true]);
Cache::flush();
```

### **3. 除錯模式**

```php
// 開啟詳細日誌
Log::debug('Permission debug mode enabled');

// 執行權限檢查
$result = $user->canAccessResource($resource, 'read', $camp, null, null, true);

// 檢查快取狀態
$stats = app(CacheInvalidationService::class)->getCacheStats();
```

---

## 🔮 未來擴展

### **1. 分散式快取**

- 支援多伺服器環境的快取同步
- 實作快取失效的廣播機制

### **2. 快取分析**

- 快取命中率統計
- 熱點資料識別
- 自動快取優化建議

### **3. 智能預熱**

- 基於使用模式的預測性快取
- 使用者行為分析驅動的快取策略

---

**快取失效機制確保了權限系統的高效能和資料一致性！** 🚀