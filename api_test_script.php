<?php
// API 測試腳本 - 在 Laravel Tinker 中執行

use App\Models\User;
use App\Models\Applicant;
use App\Models\Batch;
use App\Models\Camp;

echo "=== API 端點測試 ===\n";

// 1. 建立測試用戶
$user = User::first();
if (!$user) {
    echo "❌ 沒有用戶可以測試\n";
    return;
}
echo "✅ 使用用戶: " . $user->name . "\n";

// 2. 取得測試資料
$applicant = Applicant::with('batch.camp')->first();
if (!$applicant) {
    echo "❌ 沒有申請人可以測試\n";
    return;
}

$targetBatch = Batch::where('id', '!=', $applicant->batch_id)
    ->where('batch_start', '>', now()->toDateString())
    ->first();

if (!$targetBatch) {
    echo "❌ 沒有可轉換的目標梯次\n";
    return;
}

echo "申請人: " . $applicant->name . " (目前梯次: " . $applicant->batch->name . ")\n";
echo "目標梯次: " . $targetBatch->name . "\n";

// 3. 測試 getAvailableBatches API
echo "\n--- 測試 getAvailableBatches ---\n";
try {
    $controller = new App\Http\Controllers\BackendController();
    
    // 模擬認證用戶
    auth()->login($user);
    
    $request = new Illuminate\Http\Request();
    $response = $controller->getAvailableBatches($request);
    
    echo "✅ getAvailableBatches 成功執行\n";
    $data = $response->getData(true);
    echo "回傳梯次數量: " . count($data['batches'] ?? []) . "\n";
    
} catch (Exception $e) {
    echo "❌ getAvailableBatches 錯誤: " . $e->getMessage() . "\n";
}

// 4. 測試 transferApplicant API
echo "\n--- 測試 transferApplicant ---\n";
try {
    $controller = new App\Http\Controllers\BackendController();
    
    $request = new Illuminate\Http\Request([
        'applicant_id' => $applicant->id,
        'target_batch_id' => $targetBatch->id
    ]);
    
    $response = $controller->transferApplicant($request);
    
    echo "✅ transferApplicant 成功執行\n";
    $data = $response->getData(true);
    echo "轉換結果: " . ($data['success'] ? '成功' : '失敗') . "\n";
    echo "訊息: " . ($data['message'] ?? 'N/A') . "\n";
    
} catch (Exception $e) {
    echo "❌ transferApplicant 錯誤: " . $e->getMessage() . "\n";
}

// 5. 驗證資料變更
echo "\n--- 驗證資料變更 ---\n";
$applicant->refresh();
echo "申請人新梯次ID: " . $applicant->batch_id . "\n";
echo "目標梯次ID: " . $targetBatch->id . "\n";

if ($applicant->batch_id == $targetBatch->id) {
    echo "✅ 資料轉換成功\n";
} else {
    echo "❌ 資料轉換失敗或未執行\n";
}

echo "\n=== API 測試完成 ===\n";