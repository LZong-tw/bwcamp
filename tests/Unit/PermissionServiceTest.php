<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Camp;
use App\Models\Applicant;
use App\Services\Permission\EnhancedPermissionService;
use App\Services\Permission\PermissionCache;
use App\Services\Permission\PermissionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class PermissionServiceTest extends TestCase
{
    use RefreshDatabase;

    private EnhancedPermissionService $permissionService;
    private User $user;
    private Camp $camp;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->permissionService = new EnhancedPermissionService(
            new PermissionCache(),
            new PermissionFactory(new PermissionCache())
        );
        
        $this->user = User::factory()->create();
        $this->camp = Camp::factory()->create();
    }

    /** @test */
    public function it_caches_permission_results()
    {
        // Clear cache first
        Cache::flush();
        
        $applicant = Applicant::factory()->create();
        
        // First call - should hit database
        $result1 = $this->permissionService->canAccessResource($this->user, $applicant, 'read', $this->camp);
        
        // Second call - should hit cache
        $result2 = $this->permissionService->canAccessResource($this->user, $applicant, 'read', $this->camp);
        
        // Results should be the same
        $this->assertEquals($result1, $result2);
    }

    /** @test */
    public function it_handles_batch_permission_checks()
    {
        $applicants = Applicant::factory()->count(5)->create();
        
        $results = $this->permissionService->canAccessResourceBatch($this->user, $applicants->toArray(), 'read', $this->camp);
        
        $this->assertCount(5, $results);
        $this->assertIsArray($results);
    }

    /** @test */
    public function it_handles_null_resources()
    {
        $result = $this->permissionService->canAccessResource($this->user, null, 'read', $this->camp);
        $this->assertFalse($result);
    }

    /** @test */
    public function it_fails_closed_on_exceptions()
    {
        // Test with invalid resource that might cause exceptions
        $result = $this->permissionService->canAccessResource($this->user, 'InvalidResource', 'read', $this->camp);
        $this->assertFalse($result);
    }
}