<?php

use App\Models\User;
use App\Models\Camp;
use App\Models\Applicant;
use App\Services\Permission\PerformanceAnalyzer;
use App\Services\Permission\EnhancedPermissionService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->camp = Camp::factory()->create();
    $this->analyzer = app(PerformanceAnalyzer::class);
    $this->permissionService = app(EnhancedPermissionService::class);
    
    // Enable query logging for performance testing
    DB::enableQueryLog();
});

afterEach(function () {
    Cache::flush();
    DB::flushQueryLog();
});

describe('Performance Analysis', function () {
    it('shows batch checks are faster than individual checks', function () {
        // Clear cache to ensure fair testing
        Cache::flush();
        
        $applicants = Applicant::factory()->count(20)->create();
        
        $comparison = $this->analyzer->compareBatchVsIndividual(
            $this->user,
            $applicants->toArray(),
            'read',
            $this->camp
        );
        
        expect($comparison)->toHaveKey('individual');
        expect($comparison)->toHaveKey('batch');
        expect($comparison)->toHaveKey('performance_gain');
        
        // Batch should be faster or equal
        expect($comparison['batch']['time_ms'])->toBeLessThanOrEqual(
            $comparison['individual']['time_ms']
        );
        
        // Should have performance gain data
        expect($comparison['performance_gain'])->toHaveKey('improvement_percentage');
        expect($comparison['performance_gain']['improvement_percentage'])->toBeFloat();
    });

    it('uses fewer queries for batch operations', function () {
        $applicants = Applicant::factory()->count(10)->create();
        
        $comparison = $this->analyzer->compareBatchVsIndividual(
            $this->user,
            $applicants->toArray(),
            'read',
            $this->camp
        );
        
        // Batch should use same or fewer queries
        expect($comparison['batch']['queries'])->toBeLessThanOrEqual(
            $comparison['individual']['queries']
        );
    });

    it('generates comprehensive performance reports', function () {
        $applicants = Applicant::factory()->count(5)->create();
        
        $report = $this->analyzer->generatePerformanceReport(
            $this->user,
            $applicants->toArray(),
            'read',
            $this->camp
        );
        
        expect($report)->toBeString();
        expect($report)->toContain('權限檢查效能分析報告');
        expect($report)->toContain('資源數量');
        expect($report)->toContain('逐個檢查');
        expect($report)->toContain('批次檢查');
        expect($report)->toContain('效能提升');
    });

    it('handles different scales efficiently', function () {
        $sizes = [5, 10, 20];
        $previousTime = 0;
        
        foreach ($sizes as $size) {
            $applicants = Applicant::factory()->count($size)->create();
            
            $startTime = microtime(true);
            $results = $this->permissionService->canAccessResourceBatch(
                $this->user,
                $applicants->toArray(),
                'read',
                $this->camp
            );
            $endTime = microtime(true);
            
            $executionTime = ($endTime - $startTime) * 1000;
            
            expect($results)->toHaveCount($size);
            
            // Execution time should scale reasonably (not exponentially)
            if ($previousTime > 0) {
                $timeRatio = $executionTime / $previousTime;
                $sizeRatio = $size / ($sizes[array_search($size, $sizes) - 1]);
                
                // Time increase should be roughly linear or sublinear
                expect($timeRatio)->toBeLessThan($sizeRatio * 2);
            }
            
            $previousTime = $executionTime;
        }
    });
});

describe('Cache Performance', function () {
    it('improves performance with cache', function () {
        $applicants = Applicant::factory()->count(10)->create();
        
        // First run - no cache
        Cache::flush();
        $startTime = microtime(true);
        $firstResults = $this->permissionService->canAccessResourceBatch(
            $this->user,
            $applicants->toArray(),
            'read',
            $this->camp
        );
        $firstTime = (microtime(true) - $startTime) * 1000;
        
        // Second run - with cache
        $startTime = microtime(true);
        $secondResults = $this->permissionService->canAccessResourceBatch(
            $this->user,
            $applicants->toArray(),
            'read',
            $this->camp
        );
        $secondTime = (microtime(true) - $startTime) * 1000;
        
        // Results should be the same
        expect($firstResults)->toBe($secondResults);
        
        // Second run should be faster or equal
        expect($secondTime)->toBeLessThanOrEqual($firstTime);
    });

    it('handles cache misses gracefully', function () {
        $applicants = Applicant::factory()->count(5)->create();
        
        // Mix of cached and uncached requests
        $results1 = $this->permissionService->canAccessResourceBatch(
            $this->user,
            [$applicants[0], $applicants[1]],
            'read',
            $this->camp
        );
        
        $results2 = $this->permissionService->canAccessResourceBatch(
            $this->user,
            $applicants->toArray(),
            'read',
            $this->camp
        );
        
        // Results for cached items should be consistent
        expect($results2[0])->toBe($results1[0]);
        expect($results2[1])->toBe($results1[1]);
    });
});