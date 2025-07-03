#!/bin/bash

# 測試環境設置腳本
# 適用於Docker環境下的Laravel測試環境初始化

set -e

echo "🔧 開始設置測試環境..."

# 檢查Docker是否運行
if ! docker-compose ps | grep -q "Up"; then
    echo "⚠️  Docker容器未運行，正在啟動..."
    docker-compose up -d
    echo "⏰ 等待服務啟動..."
    sleep 10
fi

# 清除快取
echo "🧹 清除快取..."
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan route:clear
docker-compose exec -T app php artisan view:clear

# 設置APP_KEY（如果不存在）
echo "🔑 檢查APP_KEY..."
if ! docker-compose exec -T app php artisan key:generate --show | grep -q "base64:"; then
    docker-compose exec -T app php artisan key:generate --force
fi

# 運行遷移
echo "🗄️  執行資料庫遷移..."
docker-compose exec -T app php artisan migrate --force

# 運行測試用種子資料
echo "🌱 植入測試種子資料..."
docker-compose exec -T app php artisan db:seed --force

# 安裝前端依賴（如果需要）
if [ -f "package.json" ]; then
    echo "📦 安裝前端依賴..."
    docker-compose exec -T app yarn install
fi

# 運行一個簡單的測試來驗證環境
echo "🧪 驗證測試環境..."
docker-compose exec -T app php artisan test --testsuite=Unit --stop-on-failure --no-ansi

echo "✅ 測試環境設置完成！"
echo ""
echo "🚀 可用的測試指令："
echo "   docker-compose exec app php artisan test"
echo "   docker-compose exec app ./vendor/bin/phpunit"
echo "   docker-compose exec app ./vendor/bin/pest"
echo ""
echo "🎯 針對特定測試："
echo "   docker-compose exec app ./vendor/bin/pest tests/Feature/ApplicantTransferTest.php"
echo "   docker-compose exec app ./vendor/bin/pest --filter=\"test_name\""
echo ""
echo "📊 測試覆蓋率："
echo "   docker-compose exec app ./vendor/bin/phpunit --coverage-html coverage"