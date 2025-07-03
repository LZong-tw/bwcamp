<?php

namespace App\Services\Permission;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PerformanceAnalyzer
{
    private EnhancedPermissionService $permissionService;

    public function __construct(EnhancedPermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * 比較批次檢查和逐個檢查的效能差異
     */
    public function compareBatchVsIndividual(User $user, array $resources, string $action, $camp, array $context = []): array
    {
        $results = [
            'individual' => $this->measureIndividualChecks($user, $resources, $action, $camp, $context),
            'batch' => $this->measureBatchCheck($user, $resources, $action, $camp, $context),
            'resource_count' => count($resources)
        ];

        // 計算效能差異
        $results['performance_gain'] = [
            'time_saved_ms' => $results['individual']['time_ms'] - $results['batch']['time_ms'],
            'queries_saved' => $results['individual']['queries'] - $results['batch']['queries'],
            'cache_efficiency' => $results['batch']['cache_hits'] / count($resources) * 100,
            'improvement_percentage' => (($results['individual']['time_ms'] - $results['batch']['time_ms']) / $results['individual']['time_ms']) * 100
        ];

        Log::info('Permission performance comparison', $results);

        return $results;
    }

    /**
     * 測量逐個檢查的效能
     */
    private function measureIndividualChecks(User $user, array $resources, string $action, $camp, array $context = []): array
    {
        $startTime = microtime(true);
        $startQueries = DB::getQueryLog();
        
        // 清除查詢日誌
        DB::flushQueryLog();
        DB::enableQueryLog();

        $results = [];
        $cacheHits = 0;

        foreach ($resources as $key => $resource) {
            $queryCountBefore = count(DB::getQueryLog());
            
            $result = $this->permissionService->canAccessResource($user, $resource, $action, $camp, $context);
            $results[$key] = $result;
            
            $queryCountAfter = count(DB::getQueryLog());
            
            // 如果沒有新查詢，表示命中快取
            if ($queryCountBefore === $queryCountAfter) {
                $cacheHits++;
            }
        }

        $endTime = microtime(true);
        $totalQueries = count(DB::getQueryLog());

        return [
            'time_ms' => round(($endTime - $startTime) * 1000, 2),
            'queries' => $totalQueries,
            'cache_hits' => $cacheHits,
            'cache_miss' => count($resources) - $cacheHits,
            'results' => $results
        ];
    }

    /**
     * 測量批次檢查的效能
     */
    private function measureBatchCheck(User $user, array $resources, string $action, $camp, array $context = []): array
    {
        $startTime = microtime(true);
        
        // 清除查詢日誌
        DB::flushQueryLog();
        DB::enableQueryLog();

        $results = $this->permissionService->canAccessResourceBatch($user, $resources, $action, $camp, $context);

        $endTime = microtime(true);
        $totalQueries = count(DB::getQueryLog());

        // 計算快取命中率
        $cacheHits = $this->estimateCacheHits($user, $resources, $action, $camp, $context);

        return [
            'time_ms' => round(($endTime - $startTime) * 1000, 2),
            'queries' => $totalQueries,
            'cache_hits' => $cacheHits,
            'cache_miss' => count($resources) - $cacheHits,
            'results' => $results
        ];
    }

    /**
     * 估算快取命中次數
     */
    private function estimateCacheHits(User $user, array $resources, string $action, $camp, array $context = []): int
    {
        $cache = app(PermissionCache::class);
        $hits = 0;

        foreach ($resources as $resource) {
            $cacheKey = $cache->getCacheKey($user, $resource, $action, $camp, $context);
            if ($cache->get($cacheKey) !== null) {
                $hits++;
            }
        }

        return $hits;
    }

    /**
     * 生成效能報告
     */
    public function generatePerformanceReport(User $user, array $resources, string $action, $camp, array $context = []): string
    {
        $comparison = $this->compareBatchVsIndividual($user, $resources, $action, $camp, $context);
        
        $report = "## 權限檢查效能分析報告\n\n";
        $report .= "**資源數量**: {$comparison['resource_count']}\n\n";
        
        $report .= "### 逐個檢查\n";
        $report .= "- **執行時間**: {$comparison['individual']['time_ms']}ms\n";
        $report .= "- **資料庫查詢**: {$comparison['individual']['queries']} 次\n";
        $report .= "- **快取命中**: {$comparison['individual']['cache_hits']} 次\n";
        $report .= "- **快取失誤**: {$comparison['individual']['cache_miss']} 次\n\n";
        
        $report .= "### 批次檢查\n";
        $report .= "- **執行時間**: {$comparison['batch']['time_ms']}ms\n";
        $report .= "- **資料庫查詢**: {$comparison['batch']['queries']} 次\n";
        $report .= "- **快取命中**: {$comparison['batch']['cache_hits']} 次\n";
        $report .= "- **快取失誤**: {$comparison['batch']['cache_miss']} 次\n\n";
        
        $report .= "### 效能提升\n";
        $report .= "- **時間節省**: {$comparison['performance_gain']['time_saved_ms']}ms\n";
        $report .= "- **查詢節省**: {$comparison['performance_gain']['queries_saved']} 次\n";
        $report .= "- **快取效率**: " . round($comparison['performance_gain']['cache_efficiency'], 2) . "%\n";
        $report .= "- **整體提升**: " . round($comparison['performance_gain']['improvement_percentage'], 2) . "%\n\n";
        
        // 建議
        if ($comparison['performance_gain']['improvement_percentage'] > 50) {
            $report .= "🚀 **建議**: 批次檢查效能顯著優於逐個檢查，強烈建議使用批次API\n";
        } elseif ($comparison['performance_gain']['improvement_percentage'] > 20) {
            $report .= "✅ **建議**: 批次檢查有明顯效能優勢，建議在處理多個資源時使用\n";
        } else {
            $report .= "📊 **建議**: 兩種方式效能相近，可根據程式碼可讀性選擇\n";
        }

        return $report;
    }

    /**
     * 模擬不同規模的效能測試
     */
    public function benchmarkDifferentSizes(User $user, $resourceFactory, string $action, $camp): array
    {
        $sizes = [1, 10, 50, 100, 500, 1000];
        $results = [];

        foreach ($sizes as $size) {
            // 生成測試資源
            $resources = [];
            for ($i = 0; $i < $size; $i++) {
                $resources[] = $resourceFactory();
            }

            $comparison = $this->compareBatchVsIndividual($user, $resources, $action, $camp);
            $results[$size] = [
                'individual_time' => $comparison['individual']['time_ms'],
                'batch_time' => $comparison['batch']['time_ms'],
                'improvement' => $comparison['performance_gain']['improvement_percentage'],
                'queries_saved' => $comparison['performance_gain']['queries_saved']
            ];
        }

        return $results;
    }
}