<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Camp;
use App\Models\Applicant;
use App\Services\Permission\PerformanceAnalyzer;
use App\Services\Permission\EnhancedPermissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PermissionPerformanceTest extends TestCase
{
    use RefreshDatabase;

    private PerformanceAnalyzer $analyzer;
    private User $user;
    private Camp $camp;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->analyzer = new PerformanceAnalyzer(
            app(EnhancedPermissionService::class)
        );
        
        $this->user = User::factory()->create();
        $this->camp = Camp::factory()->create();
        
        // Enable query logging
        DB::enableQueryLog();
    }

    /** @test */
    public function batch_check_is_faster_than_individual_checks()
    {
        // 清除快取確保公平測試
        Cache::flush();
        
        // 創建測試資料
        $applicants = Applicant::factory()->count(50)->create();
        
        $comparison = $this->analyzer->compareBatchVsIndividual(
            $this->user,
            $applicants->toArray(),
            'read',
            $this->camp
        );
        
        // 批次檢查應該比逐個檢查快
        $this->assertLessThan(
            $comparison['individual']['time_ms'],
            $comparison['batch']['time_ms'],
            '批次檢查應該比逐個檢查更快'
        );
        
        // 批次檢查應該使用更少的資料庫查詢
        $this->assertLessThanOrEqual(
            $comparison['individual']['queries'],
            $comparison['batch']['queries'],
            '批次檢查應該使用更少的資料庫查詢'
        );
        
        // 效能提升應該大於 0
        $this->assertGreaterThan(
            0,
            $comparison['performance_gain']['improvement_percentage'],
            '批次檢查應該有效能提升'
        );
    }

    /** @test */
    public function cache_improves_performance_significantly()
    {
        $applicants = Applicant::factory()->count(20)->create();
        
        // 第一次執行 - 沒有快取
        Cache::flush();
        $firstRun = $this->analyzer->compareBatchVsIndividual(
            $this->user,
            $applicants->toArray(),
            'read',
            $this->camp
        );
        
        // 第二次執行 - 有快取
        $secondRun = $this->analyzer->compareBatchVsIndividual(
            $this->user,
            $applicants->toArray(),
            'read',
            $this->camp
        );
        
        // 有快取的執行應該更快
        $this->assertLessThan(
            $firstRun['batch']['time_ms'],
            $secondRun['batch']['time_ms'],
            '快取應該顯著提升效能'
        );
        
        // 有快取的執行應該有更多快取命中
        $this->assertGreaterThan(
            $firstRun['batch']['cache_hits'],
            $secondRun['batch']['cache_hits'],
            '第二次執行應該有更多快取命中'
        );
    }

    /** @test */
    public function performance_scales_with_resource_count()
    {
        $benchmarks = $this->analyzer->benchmarkDifferentSizes(
            $this->user,
            fn() => Applicant::factory()->create(),
            'read',
            $this->camp
        );
        
        // 檢查不同規模的效能表現
        $previousSize = null;
        foreach ($benchmarks as $size => $benchmark) {
            if ($previousSize !== null) {
                // 隨著資源數量增加，批次檢查的優勢應該越明顯
                $this->assertGreaterThanOrEqual(
                    0,
                    $benchmark['improvement'],
                    "規模 {$size} 的批次檢查應該有效能優勢"
                );
                
                // 對於大量資源，批次檢查應該節省大量查詢
                if ($size >= 100) {
                    $this->assertGreaterThan(
                        0,
                        $benchmark['queries_saved'],
                        "大量資源時應該節省資料庫查詢"
                    );
                }
            }
            $previousSize = $size;
        }
        
        // 輸出基準測試結果
        echo "\n=== 效能基準測試結果 ===\n";
        foreach ($benchmarks as $size => $benchmark) {
            echo sprintf(
                "資源數: %4d | 逐個: %6.2fms | 批次: %6.2fms | 提升: %5.1f%% | 節省查詢: %3d\n",
                $size,
                $benchmark['individual_time'],
                $benchmark['batch_time'],
                $benchmark['improvement'],
                $benchmark['queries_saved']
            );
        }
    }

    /** @test */
    public function generates_comprehensive_performance_report()
    {
        $applicants = Applicant::factory()->count(25)->create();
        
        $report = $this->analyzer->generatePerformanceReport(
            $this->user,
            $applicants->toArray(),
            'read',
            $this->camp
        );
        
        // 檢查報告內容
        $this->assertStringContainsString('權限檢查效能分析報告', $report);
        $this->assertStringContainsString('資源數量', $report);
        $this->assertStringContainsString('逐個檢查', $report);
        $this->assertStringContainsString('批次檢查', $report);
        $this->assertStringContainsString('效能提升', $report);
        $this->assertStringContainsString('建議', $report);
        
        // 輸出報告
        echo "\n" . $report;
    }

    /** @test */
    public function batch_check_handles_mixed_permission_levels()
    {
        // 創建不同權限等級的資源
        $resources = [
            Applicant::factory()->create(['id' => 1]), // 可能有個人權限
            Applicant::factory()->create(['id' => 2]), // 可能有群組權限
            Applicant::factory()->create(['id' => 3]), // 可能有完全權限
        ];
        
        $comparison = $this->analyzer->compareBatchVsIndividual(
            $this->user,
            $resources,
            'read',
            $this->camp
        );
        
        // 確保批次檢查正確處理混合權限等級
        $this->assertEquals(
            count($resources),
            count($comparison['batch']['results']),
            '批次檢查應該回傳所有資源的結果'
        );
        
        $this->assertEquals(
            count($resources),
            count($comparison['individual']['results']),
            '逐個檢查應該回傳所有資源的結果'
        );
        
        // 兩種方法的結果應該一致
        foreach ($resources as $key => $resource) {
            $this->assertEquals(
                $comparison['individual']['results'][$key],
                $comparison['batch']['results'][$key],
                "資源 {$key} 的權限檢查結果應該一致"
            );
        }
    }
}