# E2E 測試使用教學

## 概述

本專案使用 Playwright 進行端到端 (E2E) 測試，專為福智營隊管理系統設計。Playwright 提供快速、穩定且功能豐富的瀏覽器自動化測試。

## 安裝與設定

### 前置需求
- Node.js 16+ 
- Laravel 開發環境已設定
- 測試資料庫已配置

### 安裝步驟
```bash
# 安裝 Playwright (專案使用 Yarn)
yarn add --dev @playwright/test

# 安裝瀏覽器
npx playwright install
```

## 執行測試

### 基本命令（Laravel 在本機執行）
```bash
# 執行所有 E2E 測試
yarn test:e2e

# 使用 UI 模式執行（推薦用於開發）
yarn test:e2e:ui

# 顯示瀏覽器視窗執行測試
yarn test:e2e:headed

# 查看測試報告
yarn test:e2e:report
```

### Docker 環境執行
如果你的 Laravel 在 Docker 容器中執行，使用以下命令：

```bash
# 確保 Docker 容器正在運行
docker ps  # 檢查容器狀態和埠口映射

# 執行 Docker 環境的 E2E 測試（假設應用暴露在 8080 埠口）
yarn test:e2e:docker

# 使用 UI 模式執行 Docker 環境測試（推薦，可以看到測試過程）
yarn test:e2e:docker-ui

# 如果你的 Docker 應用使用不同埠口，手動指定：
DOCKER_APP_URL=http://localhost:3000 yarn test:e2e:docker

# 查看測試報告
yarn test:e2e:report
```

### 進階命令
```bash
# 只執行特定測試檔案
npx playwright test example.spec.js

# 只執行特定瀏覽器
npx playwright test --project=chromium

# 執行特定測試案例
npx playwright test --grep "login functionality"

# 除錯模式
npx playwright test --debug

# 產生測試程式碼
npx playwright codegen http://localhost:8000
```

## 測試結構

### 目錄結構
```
tests/
├── e2e/
│   ├── helpers/
│   │   └── laravel-helpers.js    # Laravel 專用助手函數
│   ├── auth/
│   │   ├── login.spec.js         # 登入相關測試
│   │   └── registration.spec.js  # 註冊相關測試
│   ├── camps/
│   │   ├── camp-list.spec.js     # 營隊列表測試
│   │   └── camp-registration.spec.js # 營隊報名測試
│   └── admin/
│       └── dashboard.spec.js     # 管理員介面測試
├── Feature/                      # Laravel Feature 測試
└── Unit/                         # Laravel Unit 測試
```

### 基本測試結構
```javascript
const { test, expect } = require('@playwright/test');
const { loginAs, fillLaravelForm } = require('./helpers/laravel-helpers');

test.describe('測試群組名稱', () => {
  
  test.beforeEach(async ({ page }) => {
    // 每個測試前執行的設定
    await page.goto('/');
  });

  test('測試案例名稱', async ({ page }) => {
    // 測試步驟
    await page.click('button');
    await expect(page.locator('.result')).toBeVisible();
  });

});
```

## Laravel 專用助手函數

### 登入助手
```javascript
// 以不同角色登入
await loginAs(page, 'admin');    // 管理員
await loginAs(page, 'manager');  // 管理者
await loginAs(page, 'user');     // 一般使用者
```

### 表單填寫助手
```javascript
// 自動處理 CSRF 和不同表單元素類型
await fillLaravelForm(page, {
  name: '測試姓名',
  email: 'test@example.com',
  gender: 'male',           // select 選項
  newsletter: true,         // checkbox
  age_group: 'adult'        // radio button
}, 'form.registration-form'); // 可指定表單選擇器
```

### Livewire 組件等待
```javascript
// 等待 Livewire 初始化
await waitForLivewire(page);

// 等待特定 Livewire 組件
await waitForLivewire(page, 'camp-registration');
```

### 檔案上傳
```javascript
await uploadFile(page, 'input[type="file"]', './test-files/document.pdf');
```

### 通知訊息等待
```javascript
// 等待成功訊息
await waitForToast(page, 'success');

// 等待任何通知
await waitForToast(page);
```

## 常見測試案例

### 使用者認證測試
```javascript
test('使用者登入流程', async ({ page }) => {
  await page.goto('/login');
  
  await fillLaravelForm(page, {
    email: 'user@example.com',
    password: 'password123'
  });
  
  await page.click('button[type="submit"]');
  await page.waitForURL(/\/dashboard/);
  
  await expect(page.locator('.user-menu')).toBeVisible();
});
```

### 營隊報名測試
```javascript
test('營隊報名流程', async ({ page }) => {
  await loginAs(page, 'user');
  await page.goto('/camps');
  
  // 選擇營隊
  await page.click('.camp-card:first-child .register-btn');
  
  // 填寫報名表單
  await fillLaravelForm(page, {
    participant_name: '王小明',
    phone: '0912345678',
    emergency_contact: '王媽媽',
    emergency_phone: '0987654321',
    dietary_restrictions: 'vegetarian'
  });
  
  await page.click('button:has-text("提交報名")');
  await waitForToast(page, 'success');
  
  await expect(page.locator('.registration-success')).toBeVisible();
});
```

### 管理員功能測試
```javascript
test('管理員營隊管理', async ({ page }) => {
  await loginAs(page, 'admin');
  await page.goto('/admin/camps');
  
  // 新增營隊
  await page.click('button:has-text("新增營隊")');
  
  await fillLaravelForm(page, {
    name: '測試營隊',
    start_date: '2024-07-01',
    end_date: '2024-07-03',
    max_participants: '50',
    registration_deadline: '2024-06-15'
  });
  
  await page.click('button:has-text("儲存")');
  await waitForToast(page, 'success');
  
  // 驗證營隊已新增
  await expect(page.locator('td:has-text("測試營隊")')).toBeVisible();
});
```

### 響應式設計測試
```javascript
test('手機版介面測試', async ({ page }) => {
  await page.setViewportSize({ width: 375, height: 667 });
  await page.goto('/');
  
  // 測試手機選單
  await page.click('.mobile-menu-toggle');
  await expect(page.locator('.mobile-nav')).toBeVisible();
  
  // 測試營隊卡片在手機上的顯示
  await page.goto('/camps');
  const campCards = page.locator('.camp-card');
  await expect(campCards.first()).toBeVisible();
});
```

## 測試環境設定

### 環境變數設定
測試時使用 `.env.testing` 檔案：
```env
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
MAIL_MAILER=log
QUEUE_CONNECTION=sync
```

### Docker 環境設定

#### 1. 檢查 Docker 埠口映射
```bash
# 查看你的 Laravel 應用暴露的埠口
docker ps

# 輸出範例：
# CONTAINER ID   IMAGE     PORTS                    NAMES
# abc123def456   app       0.0.0.0:8080->80/tcp     bwcamp_app
```

#### 2. 設定測試 URL
根據你的 Docker 設定，修改測試 URL：

```bash
# 方法 1：使用環境變數（推薦）
export DOCKER_APP_URL=http://localhost:8080
yarn test:e2e:docker-ui

# 方法 2：使用 .env.e2e 檔案
echo "DOCKER_APP_URL=http://localhost:8080" > .env.e2e
echo "SKIP_WEB_SERVER=true" >> .env.e2e
```

#### 3. 常見 Docker 埠口配置
```bash
# Nginx 代理 (常見)
DOCKER_APP_URL=http://localhost:8080

# 直接 PHP 服務 
DOCKER_APP_URL=http://localhost:9000

# 自定義埠口
DOCKER_APP_URL=http://localhost:3000
```

### 測試資料準備
```javascript
test.beforeEach(async ({ page }) => {
  // 重置資料庫
  await page.request.post('/test/reset-database');
  
  // 建立測試資料
  await page.request.post('/test/seed-data');
});
```

## 最佳實踐

### 1. 測試設計原則
- **獨立性**：每個測試應該能獨立執行
- **可重複性**：測試結果應該一致
- **明確性**：測試目的和步驟要清楚

### 2. 選擇器最佳實踐
```javascript
// 好的選擇器
await page.click('[data-test="submit-button"]');
await page.locator('[aria-label="關閉對話框"]');

// 避免使用容易變動的選擇器
await page.click('.btn.btn-primary.mx-2'); // 避免
```

### 3. 等待策略
```javascript
// 使用適當的等待方法
await page.waitForSelector('.loading', { state: 'hidden' });
await page.waitForURL('/success');
await page.waitForFunction(() => window.dataLoaded);
```

### 4. 錯誤處理
```javascript
test('處理網路錯誤', async ({ page }) => {
  // 模擬網路問題
  await page.route('**/api/camps', route => route.abort());
  
  await page.goto('/camps');
  await expect(page.locator('.error-message')).toBeVisible();
});
```

## 調試與故障排除

### 1. 視覺化調試
```bash
# 使用 UI 模式
npm run test:e2e:ui

# 使用調試模式
npx playwright test --debug
```

### 2. 截圖和錄影
測試失敗時會自動產生：
- 截圖：`test-results/` 目錄
- 錄影：`test-results/` 目錄
- 追蹤檔案：可用 Playwright Inspector 查看

### 3. 常見問題

#### 元素找不到
```javascript
// 增加等待時間
await page.waitForSelector('.element', { timeout: 10000 });

// 檢查元素是否存在
if (await page.locator('.element').count() > 0) {
  await page.click('.element');
}
```

#### 測試執行緩慢
```javascript
// 配置檔案中調整並行數
workers: process.env.CI ? 1 : 4
```

#### Livewire 相關問題
```javascript
// 確保 Livewire 完全載入
await waitForLivewire(page);
await page.waitForFunction(() => !window.Livewire.isOffline);
```

#### Docker 相關問題

**連接被拒絕 (ECONNREFUSED)**
```bash
# 1. 檢查 Docker 容器是否運行
docker ps

# 2. 檢查埠口映射是否正確
docker port <容器名>

# 3. 測試埠口是否可造訪
curl http://localhost:8080

# 4. 檢查防火牆設定
sudo ufw status
```

**測試找不到頁面 (404 錯誤)**
```bash
# 確認正確的 URL
echo $DOCKER_APP_URL

# 在瀏覽器中手動測試
open http://localhost:8080

# 檢查 Docker 日誌
docker logs <容器名>
```

**UI 模式無法顯示瀏覽器**
```bash
# 確保 X11 forwarding（Linux）
export DISPLAY=:0

# macOS 使用 XQuartz
brew install --cask xquartz

# Windows WSL2 使用 VcXsrv 或 X410
```

## 持續整合 (CI/CD)

### GitHub Actions 範例
```yaml
name: E2E Tests
on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '18'
          
      - name: Install dependencies
        run: |
          yarn install
          npx playwright install --with-deps
          
      - name: Setup Laravel
        run: |
          composer install
          php artisan key:generate
          php artisan migrate
          
      - name: Run E2E tests
        run: yarn test:e2e
        
      - name: Upload test results
        uses: actions/upload-artifact@v3
        if: failure()
        with:
          name: playwright-report
          path: playwright-report/
```

## 測試覆蓋範圍建議

### 核心功能測試
- ✅ 使用者註冊/登入/登出
- ✅ 營隊瀏覽和搜尋
- ✅ 營隊報名流程
- ✅ 付款流程
- ✅ 使用者資料管理

### 管理員功能測試
- ✅ 營隊建立和編輯
- ✅ 報名者管理
- ✅ 簽到系統
- ✅ 報表產生
- ✅ 權限管理

### 跨瀏覽器測試
- ✅ Chrome/Chromium
- ✅ Firefox
- ✅ Safari (Webkit)
- ✅ 手機瀏覽器

## 進階主題

### 1. API 測試整合
```javascript
test('API 和 UI 整合測試', async ({ page, request }) => {
  // 透過 API 建立資料
  await request.post('/api/camps', {
    data: { name: 'API 測試營隊' }
  });
  
  // 在 UI 中驗證
  await page.goto('/camps');
  await expect(page.locator('text=API 測試營隊')).toBeVisible();
});
```

### 2. 效能測試
```javascript
test('頁面載入效能', async ({ page }) => {
  await page.goto('/camps');
  
  const performanceMetrics = await page.evaluate(() => {
    const navigation = performance.getEntriesByType('navigation')[0];
    return {
      loadTime: navigation.loadEventEnd - navigation.loadEventStart,
      domContentLoaded: navigation.domContentLoadedEventEnd - navigation.domContentLoadedEventStart
    };
  });
  
  expect(performanceMetrics.loadTime).toBeLessThan(3000);
});
```

### 3. 無障礙測試
```javascript
// 安裝 @axe-core/playwright
const { injectAxe, checkA11y } = require('axe-playwright');

test('無障礙測試', async ({ page }) => {
  await page.goto('/');
  await injectAxe(page);
  await checkA11y(page);
});
```

## 資源與參考

- [Playwright 官方文件](https://playwright.dev/)
- [Laravel 測試文件](https://laravel.com/docs/testing)
- [Livewire 測試](https://laravel-livewire.com/docs/testing)
- [專案的 Feature 測試範例](./tests/Feature/)

---

## 快速開始指南

### Docker 環境（推薦）

1. **確認 Docker 運行狀態**
   ```bash
   docker ps  # 查看容器和埠口
   ```

2. **安裝 Playwright 瀏覽器**
   ```bash
   npx playwright install
   ```

3. **執行測試（帶視覺界面）**
   ```bash
   # 根據你的埠口調整 URL
   yarn test:e2e:docker-ui
   ```

4. **如果需要調整埠口**
   ```bash
   DOCKER_APP_URL=http://localhost:你的埠口 yarn test:e2e:docker-ui
   ```

### 本機環境

1. **啟動 Laravel**
   ```bash
   php artisan serve
   ```

2. **執行測試**
   ```bash
   yarn test:e2e:ui
   ```

---

**提示**：
- 🎯 推薦使用 `yarn test:e2e:docker-ui` 可以看到測試過程
- 🔍 如果連接失敗，先用瀏覽器造訪 `http://localhost:埠口` 確認應用正常
- 📊 測試完成後可用 `yarn test:e2e:report` 查看詳細報告
- 🐛 遇到問題請參考上方的故障排除章節
