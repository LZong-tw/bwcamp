<?php
// 手動測試腳本 - 在 Laravel Tinker 中執行

// 1. 測試 Service 類別
use App\Services\ApplicantTransferService;
use App\Models\Applicant;
use App\Models\Batch;
use App\Models\Camp;

$service = new ApplicantTransferService();
echo "✅ ApplicantTransferService 類別存在\n";

// 2. 測試基本資料
$camps = Camp::limit(2)->get();
if ($camps->count() >= 2) {
    echo "✅ 測試營隊資料存在\n";
    echo "營隊1: " . $camps[0]->fullName . " (表格: " . $camps[0]->table . ")\n";
    echo "營隊2: " . $camps[1]->fullName . " (表格: " . $camps[1]->table . ")\n";
} else {
    echo "❌ 需要至少 2 個營隊進行測試\n";
}

// 3. 測試梯次資料
$batches = Batch::with('camp')->limit(2)->get();
if ($batches->count() >= 2) {
    echo "✅ 測試梯次資料存在\n";
    foreach ($batches as $batch) {
        echo "梯次: " . $batch->name . " (" . $batch->camp->fullName . ")\n";
    }
} else {
    echo "❌ 需要至少 2 個梯次進行測試\n";
}

// 4. 測試申請人資料
$applicants = Applicant::with('batch.camp')->limit(3)->get();
if ($applicants->count() >= 1) {
    echo "✅ 測試申請人資料存在\n";
    foreach ($applicants as $applicant) {
        echo "申請人: " . $applicant->name . " (梯次: " . $applicant->batch->name . ")\n";
    }
} else {
    echo "❌ 需要至少 1 個申請人進行測試\n";
}

// 5. 測試路由
echo "\n=== 路由測試 ===\n";
$routes = collect(app('router')->getRoutes())->map(function($route) {
    return $route->getName();
})->filter()->values();

if ($routes->contains('api.applicant.transfer')) {
    echo "✅ Transfer API 路由存在\n";
} else {
    echo "❌ Transfer API 路由不存在\n";
}

if ($routes->contains('api.batches.available')) {
    echo "✅ Available Batches API 路由存在\n";
} else {
    echo "❌ Available Batches API 路由不存在\n";
}

// 6. 測試控制器方法
echo "\n=== 控制器測試 ===\n";
$controller = new App\Http\Controllers\BackendController();

if (method_exists($controller, 'transferApplicant')) {
    echo "✅ transferApplicant 方法存在\n";
} else {
    echo "❌ transferApplicant 方法不存在\n";
}

if (method_exists($controller, 'getAvailableBatches')) {
    echo "✅ getAvailableBatches 方法存在\n";
} else {
    echo "❌ getAvailableBatches 方法不存在\n";
}

echo "\n=== 測試完成 ===\n";