<?php

use App\Models\User;
use App\Models\Camp;
use App\Models\Applicant;
use App\Services\Permission\EnhancedPermissionService;
use App\Services\Permission\PermissionCache;
use App\Services\Permission\CacheInvalidationService;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->camp = Camp::factory()->create();
    $this->permissionService = app(EnhancedPermissionService::class);
    $this->cacheService = app(CacheInvalidationService::class);
});

afterEach(function () {
    Cache::flush();
});

describe('Permission Service', function () {
    it('can be instantiated', function () {
        expect($this->permissionService)->toBeInstanceOf(EnhancedPermissionService::class);
    });

    it('handles null resources correctly', function () {
        $result = $this->permissionService->canAccessResource($this->user, null, 'read', $this->camp);
        expect($result)->toBeFalse();
    });

    it('caches permission results', function () {
        $applicant = Applicant::factory()->create();
        
        // Clear cache first
        Cache::flush();
        
        // First call - should hit database
        $result1 = $this->permissionService->canAccessResource($this->user, $applicant, 'read', $this->camp);
        
        // Second call - should hit cache
        $result2 = $this->permissionService->canAccessResource($this->user, $applicant, 'read', $this->camp);
        
        // Results should be the same
        expect($result1)->toBe($result2);
    });

    it('handles batch permission checks', function () {
        $applicants = Applicant::factory()->count(5)->create();
        
        $results = $this->permissionService->canAccessResourceBatch(
            $this->user, 
            $applicants->toArray(), 
            'read', 
            $this->camp
        );
        
        expect($results)->toHaveCount(5);
        expect($results)->toBeArray();
        
        // Each result should be a boolean
        foreach ($results as $result) {
            expect($result)->toBeBoolean();
        }
    });

    it('maintains consistency between single and batch checks', function () {
        $applicants = Applicant::factory()->count(3)->create();
        
        // Get batch results
        $batchResults = $this->permissionService->canAccessResourceBatch(
            $this->user,
            $applicants->toArray(),
            'read',
            $this->camp
        );
        
        // Compare with individual results
        foreach ($applicants as $key => $applicant) {
            $singleResult = $this->permissionService->canAccessResource(
                $this->user,
                $applicant,
                'read',
                $this->camp
            );
            
            expect($batchResults[$key])->toBe($singleResult);
        }
    });

    it('fails closed on exceptions', function () {
        // Test with invalid resource that might cause exceptions
        $result = $this->permissionService->canAccessResource(
            $this->user, 
            'InvalidResourceClass', 
            'read', 
            $this->camp
        );
        
        expect($result)->toBeFalse();
    });
});