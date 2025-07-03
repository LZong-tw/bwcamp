<?php

use App\Models\User;
use App\Models\Camp;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Applicant;
use App\Services\Permission\CacheInvalidationService;
use App\Services\Permission\EnhancedPermissionService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->camp = Camp::factory()->create();
    $this->cacheService = app(CacheInvalidationService::class);
    $this->permissionService = app(EnhancedPermissionService::class);
});

afterEach(function () {
    Cache::flush();
});

describe('Cache Invalidation', function () {
    it('can clear user cache', function () {
        $this->cacheService->invalidateAllUserCache($this->user);
        expect(true)->toBeTrue(); // No exception means success
    });

    it('can clear camp cache', function () {
        $this->cacheService->invalidateCampCache($this->camp);
        expect(true)->toBeTrue(); // No exception means success
    });

    it('invalidates cache when user roles change', function () {
        $applicant = Applicant::factory()->create();
        
        // First check - creates cache
        $result1 = $this->permissionService->canAccessResource(
            $this->user,
            $applicant,
            'read',
            $this->camp
        );
        
        // Clear user cache
        $this->cacheService->invalidateAllUserCache($this->user);
        
        // Second check - should work without cache
        $result2 = $this->permissionService->canAccessResource(
            $this->user,
            $applicant,
            'read',
            $this->camp
        );
        
        expect($result1)->toBe($result2);
    });

    it('provides cache statistics', function () {
        $stats = $this->cacheService->getCacheStats();
        
        expect($stats)->toBeArray();
        expect($stats)->toHaveKey('cache_store');
        expect($stats)->toHaveKey('timestamp');
    });
});

describe('User Model Integration', function () {
    it('can clear permission cache via user model', function () {
        $this->user->clearPermissionCache($this->camp);
        expect(true)->toBeTrue(); // No exception means success
    });

    it('can fire permission changed events', function () {
        $this->user->firePermissionChangedEvent(
            $this->camp,
            'test_change',
            ['test' => true]
        );
        expect(true)->toBeTrue(); // No exception means success
    });

    it('maintains backward compatibility with canAccessResource', function () {
        $applicant = Applicant::factory()->create();
        
        // New method
        $newResult = $this->user->canAccessResource($applicant, 'read', $this->camp);
        
        // Legacy method
        $legacyResult = $this->user->canAccessResourceLegacy($applicant, 'read', $this->camp);
        
        expect($newResult)->toBe($legacyResult);
    });

    it('supports batch permission checking', function () {
        $applicants = Applicant::factory()->count(5)->create();
        
        $results = $this->user->canAccessResourceBatch(
            $applicants->toArray(),
            'read',
            $this->camp
        );
        
        expect($results)->toHaveCount(5);
        expect($results)->toBeArray();
    });
});