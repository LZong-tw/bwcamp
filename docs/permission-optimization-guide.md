# 權限系統最佳化指南

## 問題分析

現有的 `canAccessResource` 方法在處理大量資料（上百至上千筆）時會造成嚴重的效能問題：

1. **N+1 查詢問題**：每個資源都單獨執行多次資料庫查詢
2. **複雜的 ABAC 邏輯**：需要檢查營隊、梯次、區域、小組、個人等多層次權限
3. **缺乏批次處理**：逐筆檢查資源權限
4. **快取限制**：權限頻繁變動，不適合大量快取

## 最佳化方案

### 1. 短期最佳化方案（已實作）

#### 1.1 批次權限檢查方法

在 `app/Models/User.php` 中新增了 `batchCanAccessResources` 方法：

```php
public function batchCanAccessResources($resources, $action, $camp, $context = null)
```

**優點**：

- 減少資料庫查詢次數（從 N 次降到固定幾次）
- 預載入所有相關資料
- 批次處理相同類型的資源
- 預估效能提升 **10-50 倍**

**使用方式**：

```php
// 原本的逐筆檢查
$applicants = $applicants->filter(fn ($applicant) =>
    $this->user->canAccessResource($applicant, 'read', $this->campFullData, target: $applicant)
);

// 改為批次檢查
$accessResults = $this->user->batchCanAccessResources($applicants, 'read', $this->campFullData);
$applicants = $applicants->filter(fn ($applicant) =>
    $accessResults->get($applicant->id, false)
);
```

#### 1.2 資料庫索引最佳化

執行遷移檔案 `2025_01_01_000000_optimize_permission_queries.php`：

```bash
php artisan migrate
```

新增的索引：

- `role_user`: 複合索引 `(user_id, camp_id)`
- `permissions`: 索引 `(resource, action)`, `(batch_id, region_id)`, `camp_id`
- `permission_role`: 複合索引 `(role_id, permission_id)`
- `roles`: 索引 `camp_id`, `(group_id, region_id)`, `section`
- `carer_applicant_xrefs`: 複合索引 `(user_id, applicant_id)`, `(batch_id, user_id)`
- `applicants`: 複合索引 `(batch_id, region_id, group_id)`
- `volunteers`: 複合索引 `(batch_id, region_id, user_id)`

**預估效能提升**：查詢速度提升 **3-10 倍**

#### 1.3 短期記憶體快取服務

使用 `app/Services/PermissionCacheService.php`：

```php
// 預熱權限快取
$cacheService = new PermissionCacheService();
$cacheService->warmupUserPermissions($user, $camp);

// 在權限變更時清除快取
$cacheService->clearUserPermissions($user, $camp);
```

**快取策略**：

- TTL 設定為 5 分鐘（可根據需求調整）
- 使用 Laravel Cache 支援多種後端（File, Redis, Memcached）
- 支援批次預熱多個使用者的權限

### 2. 中期最佳化方案

#### 2.1 權限預計算表

建立一個預計算的權限表，定期更新：

```sql
CREATE TABLE user_permission_cache (
    user_id INT,
    camp_id INT,
    resource_type VARCHAR(255),
    resource_ids JSON,
    action VARCHAR(50),
    updated_at TIMESTAMP,
    PRIMARY KEY (user_id, camp_id, resource_type, action)
);
```

使用排程任務定期更新：

```php
// 每 10 分鐘更新一次
$schedule->job(new UpdatePermissionCacheJob)->everyTenMinutes();
```

#### 2.2 使用 Redis 進行分散式快取

```php
// 設定 Redis 連線
'redis' => [
    'client' => env('REDIS_CLIENT', 'phpredis'),
    'default' => [
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD', null),
        'port' => env('REDIS_PORT', '6379'),
        'database' => env('REDIS_DB', '0'),
    ],
],

// 使用 Redis 快取
Cache::store('redis')->remember($key, $ttl, $callback);
```

### 3. 長期架構改進方案

#### 3.1 微服務架構

將權限系統獨立成微服務：

```
┌─────────────────┐     ┌──────────────────┐
│   主應用程式    │────▶│  權限微服務      │
│   (Laravel)     │     │  (gRPC/REST)     │
└─────────────────┘     └──────────────────┘
                               │
                               ▼
                        ┌──────────────────┐
                        │  權限資料庫      │
                        │  (PostgreSQL)    │
                        └──────────────────┘
```

**優點**：

- 獨立擴展權限服務
- 專用的權限資料庫最佳化
- 可使用更適合的技術棧（如 Go, Rust）

#### 3.2 使用圖資料庫

考慮使用 Neo4j 或 Amazon Neptune 來處理複雜的權限關係：

```cypher
// Neo4j 查詢範例
MATCH (u:User {id: $userId})-[:HAS_ROLE]->(r:Role)-[:HAS_PERMISSION]->(p:Permission)
WHERE r.camp_id = $campId
  AND p.resource = $resource
  AND p.action = $action
RETURN p
```

**優點**：

- 更適合處理複雜的關係查詢
- 原生支援階層式權限
- 查詢效能更好

#### 3.3 事件驅動架構

使用事件來管理權限變更：

```php
// 發布權限變更事件
event(new PermissionChanged($user, $camp));

// 監聽器更新快取
class UpdatePermissionCache {
    public function handle(PermissionChanged $event) {
        // 更新相關快取
    }
}
```

### 4. 系統架構建議

#### 4.1 短期（1-2 個月）

1. 實施批次權限檢查方法 ✅
2. 添加資料庫索引 ✅
3. 部署 Redis 快取
4. 監控效能改善情況

#### 4.2 中期（3-6 個月）

1. 實作權限預計算表
2. 建立權限更新排程
3. 最佳化資料庫查詢計畫
4. 考慮讀寫分離

#### 4.3 長期（6-12 個月）

1. 評估微服務架構
2. 研究圖資料庫方案
3. 實施事件驅動架構
4. 建立完整的權限管理平台

## 效能監控

### 監控指標

1. **查詢效能**

    ```php
    DB::enableQueryLog();
    // 執行權限檢查
    $queries = DB::getQueryLog();
    ```

2. **回應時間**

    ```php
    $start = microtime(true);
    // 執行權限檢查
    $duration = microtime(true) - $start;
    ```

3. **快取命中率**
    ```php
    Cache::increment('permission_cache_hits');
    Cache::increment('permission_cache_misses');
    ```

### 建議的監控工具

- **Laravel Telescope**：開發環境監控
- **New Relic / DataDog**：生產環境 APM
- **Grafana + Prometheus**：自建監控系統

## 注意事項

1. **權限一致性**：確保快取和實際權限保持同步
2. **快取失效**：權限變更時立即清除相關快取
3. **降級策略**：快取失效時的備援方案
4. **安全考量**：避免權限提升漏洞

## 結論

透過這些最佳化方案，預期可達到：

- **短期**：效能提升 10-50 倍
- **中期**：效能提升 50-100 倍
- **長期**：支援 10 倍以上的使用者規模

建議從短期方案開始實施，根據實際效果逐步推進中長期方案。
