# 測試環境設置指南

本文件說明如何設置和使用 BWCamp 專案的測試環境。

## 快速開始

### 1. 自動化設置

使用提供的腳本快速設置測試環境：

```bash
# 給予執行權限（首次使用）
chmod +x scripts/setup-test-env.sh

# 執行設置腳本
./scripts/setup-test-env.sh
```

### 2. 手動設置

如果需要手動設置測試環境：

```bash
# 啟動Docker容器
docker-compose up -d

# 清除快取
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear

# 執行遷移
docker-compose exec app php artisan migrate --force

# 植入測試種子資料
docker-compose exec app php artisan db:seed --class=TestDataSeeder --force
```

## 測試資料結構

### 預設測試資料

TestDataSeeder 會建立以下測試資料：

- **用戶**：
  - Admin User (admin@test.com)
  - Regular User (user@test.com)
  - 密碼：password

- **營隊**：
  - 測試青年營 2024 (ycamp)
  - 測試tcamp營 2024 (tcamp)
  - 測試ecamp營 2024 (ecamp)
  - 測試ceocamp營 2024 (ceocamp)
  - 測試acamp營 2024 (acamp)

- **申請者**：
  - 每個營隊都有不同狀態的申請者（pending、admitted、paid）

## 執行測試

### 基本測試指令

```bash
# 執行所有測試
docker-compose exec app php artisan test

# 使用PHPUnit
docker-compose exec app ./vendor/bin/phpunit

# 使用Pest
docker-compose exec app ./vendor/bin/pest
```

### 特定測試

```bash
# 執行特定測試檔案
docker-compose exec app ./vendor/bin/pest tests/Feature/ApplicantTransferTest.php

# 執行特定測試方法
docker-compose exec app ./vendor/bin/pest --filter="test_name"

# 執行特定測試套件
docker-compose exec app php artisan test --testsuite=Feature
docker-compose exec app php artisan test --testsuite=Unit
```

### 測試覆蓋率

```bash
# 生成HTML覆蓋率報告
docker-compose exec app ./vendor/bin/phpunit --coverage-html coverage

# 生成文字覆蓋率報告
docker-compose exec app ./vendor/bin/phpunit --coverage-text
```

## 測試環境配置

### 資料庫設置

- **資料庫類型**：SQLite (記憶體)
- **設定檔**：`.env.testing`
- **自動重置**：每次測試後自動重置資料庫

### 快取設置

- **快取驅動**：array（記憶體）
- **會話驅動**：array（記憶體）
- **郵件驅動**：log（記錄到日誌）

### 佇列設置

- **佇列連線**：sync（同步執行）
- **適用於測試**：立即執行任務，不需要背景處理

## 測試基類

所有測試都繼承自 `Tests\TestCase`，提供以下功能：

- **RefreshDatabase**：每次測試後重置資料庫
- **自動種子**：測試開始時自動植入種子資料
- **標準設置**：提供一致的測試環境

## 常見問題

### 1. 測試失敗：資料庫連線問題

```bash
# 確認Docker容器運行
docker-compose ps

# 重新啟動容器
docker-compose down && docker-compose up -d
```

### 2. 測試失敗：找不到測試資料

```bash
# 重新植入種子資料
docker-compose exec app php artisan db:seed --class=TestDataSeeder --force
```

### 3. 快取問題

```bash
# 清除所有快取
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

## 最佳實踐

### 1. 測試隔離

- 每個測試都會重置資料庫
- 使用 Factory 建立測試資料
- 避免測試之間的相依性

### 2. 資料建立

```php
// 使用 Factory
$user = User::factory()->create();

// 使用 Factory 建立特定資料
$camp = Camp::factory()->create([
    'fullName' => '特定營隊名稱',
    'year' => 2024,
]);
```

### 3. 斷言

```php
// 資料庫斷言
$this->assertDatabaseHas('users', ['email' => 'test@example.com']);

// 響應斷言
$response = $this->get('/api/camps');
$response->assertStatus(200);
$response->assertJsonStructure(['data']);
```

### 4. 測試組織

- **Unit 測試**：測試單一功能或方法
- **Feature 測試**：測試完整的功能流程
- **Integration 測試**：測試多個組件的整合

## 開發工作流程

1. **開發前**：執行 `./scripts/setup-test-env.sh`
2. **開發中**：頻繁執行相關測試
3. **提交前**：執行完整測試套件
4. **CI/CD**：自動化測試流程

## 擴展測試資料

如需新增測試資料，編輯 `database/seeders/TestDataSeeder.php`：

```php
// 新增特定營隊類型的測試資料
$newCamp = Camp::factory()->create([
    'table' => 'newcamp',
    'fullName' => '新營隊類型 2024',
]);
```

## 除錯技巧

### 1. 查看測試中的資料

```php
// 在測試中輸出資料
dump($user->toArray());
dd($camps->pluck('fullName'));
```

### 2. 檢查測試資料庫

```php
// 檢查資料是否正確建立
$this->assertDatabaseCount('users', 2);
$this->assertDatabaseCount('camps', 5);
```

### 3. 測試日誌

```bash
# 查看測試期間的日誌
docker-compose exec app tail -f storage/logs/laravel.log
```