<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Camp;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Applicant;
use App\Models\Vcamp;
use App\Models\Batch;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase; // Or DatabaseTransactions

class UserPermissionLogicTest extends TestCase
{
    // If you need to interact with a real DB for some tests, uncomment one of these.
    // For pure unit tests with full mocking, it might not be needed.
    // use RefreshDatabase;

    protected User $user;
    protected Mockery\MockInterface $campMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = new User(['id' => 1, 'name' => 'Test User']);

        // Basic camp mock, can be customized per test
        $this->campMock = Mockery::mock(Camp::class);
        $this->campMock->id = 1;
        $this->campMock->shouldReceive('getAttribute')->with('id')->andReturn(1); // For $camp->id access
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Helper to call protected/private methods for testing.
     */
    protected function callProtectedMethod($object, $methodName, array $parameters = [])
    {
        $className = get_class($object);
        $reflection = new \ReflectionClass($className);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }

    // --- Tests for getOrLoadCampContext ---

    public function test_getOrLoadCampContext_returnsCachedContextOnSubsequentCalls()
    {
        // Mock User's relations
        $rolesRelationMock = Mockery::mock();
        $rolesRelationMock->shouldReceive('where')->with('camp_id', $this->campMock->id)->andReturnSelf();
        $rolesRelationMock->shouldReceive('with')->with('permissions')->andReturnSelf();
        $rolesRelationMock->shouldReceive('get')->once()->andReturn(collect([])); // DB query happens once

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldReceive('roles')->andReturn($rolesRelationMock);

        // Mock Camp's relations
        $campBatchesRelationMock = Mockery::mock();
        $campBatchesRelationMock->shouldReceive('exists')->andReturn(false);
        $this->campMock->shouldReceive('batchs')->andReturn($campBatchesRelationMock);

        // First call - should load from "DB" (mocks)
        $this->callProtectedMethod($this->user, 'getOrLoadCampContext', [$this->campMock]);

        // Second call - should return from in-memory cache, roles()->get() should not be called again
        $context = $this->callProtectedMethod($this->user, 'getOrLoadCampContext', [$this->campMock]);

        $this->assertIsArray($context);
        $this->assertArrayHasKey('parsed_permissions', $context);
        // roles()->get() was asserted to be called once above.
    }

    public function test_getOrLoadCampContext_loadsDataCorrectly_noRoles()
    {
        $rolesRelationMock = Mockery::mock();
        $rolesRelationMock->shouldReceive('where')->with('camp_id', $this->campMock->id)->andReturnSelf();
        $rolesRelationMock->shouldReceive('with')->with('permissions')->andReturnSelf();
        $rolesRelationMock->shouldReceive('get')->andReturn(collect([]));

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldReceive('roles')->andReturn($rolesRelationMock);

        $campBatchesRelationMock = Mockery::mock();
        $campBatchesRelationMock->shouldReceive('exists')->andReturn(false);
        $this->campMock->shouldReceive('batchs')->andReturn($campBatchesRelationMock);

        $context = $this->callProtectedMethod($this->user, 'getOrLoadCampContext', [$this->campMock]);

        $this->assertEquals(collect(), $context['camp_roles']);
        $this->assertEquals(collect(), $context['parsed_permissions']);
        $this->assertEquals([], $context['user_group_ids']);
        $this->assertEquals([], $context['cared_learner_ids_in_camp']);
    }

    public function test_getOrLoadCampContext_loadsDataCorrectly_withRolesAndPermissions()
    {
        $permission1Mock = Mockery::mock(Permission::class);
        $permission1Mock->id = 101;
        $permission1Mock->resource = 'TestResource';
        $permission1Mock->action = 'read';
        $permission1Mock->range_parsed = 0;
        $permission1Mock->description = 'Read TestResource';
        $permission1Mock->shouldReceive('getAttribute')->with('id')->andReturn(101);
        $permission1Mock->shouldReceive('getAttribute')->with('resource')->andReturn('TestResource');
        $permission1Mock->shouldReceive('getAttribute')->with('action')->andReturn('read');
        $permission1Mock->shouldReceive('getAttribute')->with('range_parsed')->andReturn(0);
        $permission1Mock->shouldReceive('getAttribute')->with('description')->andReturn('Read TestResource');


        $role1Mock = Mockery::mock(Role::class);
        $role1Mock->id = 1;
        $role1Mock->group_id = 10;
        $role1Mock->region_id = null;
        $role1Mock->batch_id = 20;
        $role1Mock->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $role1Mock->shouldReceive('getAttribute')->with('group_id')->andReturn(10);
        $role1Mock->shouldReceive('getAttribute')->with('region_id')->andReturn(null);
        $role1Mock->shouldReceive('getAttribute')->with('batch_id')->andReturn(20);
        $role1Mock->shouldReceive('getAttribute')->with('permissions')->andReturn(collect([$permission1Mock]));
        $role1Mock->permissions = collect([$permission1Mock]); // For pluck

        $rolesCollection = collect([$role1Mock]);

        $rolesRelationMock = Mockery::mock();
        $rolesRelationMock->shouldReceive('where')->with('camp_id', $this->campMock->id)->andReturnSelf();
        $rolesRelationMock->shouldReceive('with')->with('permissions')->andReturnSelf();
        $rolesRelationMock->shouldReceive('get')->andReturn($rolesCollection);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldReceive('roles')->andReturn($rolesRelationMock);

        $campBatchesRelationMock = Mockery::mock();
        $campBatchesRelationMock->shouldReceive('exists')->andReturn(false);
        $this->campMock->shouldReceive('batchs')->andReturn($campBatchesRelationMock);

        $context = $this->callProtectedMethod($this->user, 'getOrLoadCampContext', [$this->campMock]);

        $this->assertEquals($rolesCollection, $context['camp_roles']);
        $this->assertCount(1, $context['parsed_permissions']);
        $this->assertEquals('TestResource', $context['parsed_permissions']->first()['resource']);
        $this->assertEquals([10], $context['user_group_ids']);
        $this->assertEquals([20], $context['user_batch_ids']);
        $this->assertEquals([], $context['user_region_ids']);
    }

    public function test_getOrLoadCampContext_parsesPermissionsCorrectly_overrideLogic()
    {
        $permReadOwn = Mockery::mock(Permission::class);
        $permReadOwn->id = 1; $permReadOwn->resource = 'Data'; $permReadOwn->action = 'read'; $permReadOwn->range_parsed = 3;
        $permReadOwn->shouldReceive('getAttribute')->zeroOrMoreTimes()->with('id')->andReturn(1);
        $permReadOwn->shouldReceive('getAttribute')->zeroOrMoreTimes()->with('resource')->andReturn('Data');
        $permReadOwn->shouldReceive('getAttribute')->zeroOrMoreTimes()->with('action')->andReturn('read');
        $permReadOwn->shouldReceive('getAttribute')->zeroOrMoreTimes()->with('range_parsed')->andReturn(3);
        $permReadOwn->shouldReceive('getAttribute')->zeroOrMoreTimes()->with('description')->andReturn('');


        $permReadAll = Mockery::mock(Permission::class);
        $permReadAll->id = 2; $permReadAll->resource = 'Data'; $permReadAll->action = 'read'; $permReadAll->range_parsed = 0; // Broader
        $permReadAll->shouldReceive('getAttribute')->zeroOrMoreTimes()->with('id')->andReturn(2);
        $permReadAll->shouldReceive('getAttribute')->zeroOrMoreTimes()->with('resource')->andReturn('Data');
        $permReadAll->shouldReceive('getAttribute')->zeroOrMoreTimes()->with('action')->andReturn('read');
        $permReadAll->shouldReceive('getAttribute')->zeroOrMoreTimes()->with('range_parsed')->andReturn(0);
        $permReadAll->shouldReceive('getAttribute')->zeroOrMoreTimes()->with('description')->andReturn('');


        $roleMock = Mockery::mock(Role::class);
        $roleMock->id = 1; $roleMock->group_id = null; $roleMock->region_id = null; $roleMock->batch_id = null;
        $roleMock->shouldReceive('getAttribute')->zeroOrMoreTimes();
        $roleMock->permissions = collect([$permReadOwn, $permReadAll]); // Order matters for some collection ops if not sorted
        $roleMock->shouldReceive('getAttribute')->with('permissions')->andReturn(collect([$permReadOwn, $permReadAll]));


        $rolesRelationMock = Mockery::mock();
        $rolesRelationMock->shouldReceive('where->with->get')->andReturn(collect([$roleMock]));
        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldReceive('roles')->andReturn($rolesRelationMock);

        $campBatchesRelationMock = Mockery::mock();
        $campBatchesRelationMock->shouldReceive('exists')->andReturn(false);
        $this->campMock->shouldReceive('batchs')->andReturn($campBatchesRelationMock);

        $context = $this->callProtectedMethod($this->user, 'getOrLoadCampContext', [$this->campMock]);

        $this->assertCount(1, $context['parsed_permissions']);
        $this->assertEquals(0, $context['parsed_permissions']->first()['range_parsed']); // Broader permission should win
    }

    public function test_getOrLoadCampContext_loadsCaredLearnersCorrectly()
    {
        $rolesRelationMock = Mockery::mock();
        $rolesRelationMock->shouldReceive('where->with->get')->andReturn(collect([])); // No roles for simplicity here

        $batch1 = Mockery::mock(Batch::class); $batch1->id = 100;
        $batch1->shouldReceive('getAttribute')->with('id')->andReturn(100);
        $campBatchesCollection = collect([$batch1]);

        $campBatchesRelationMock = Mockery::mock();
        $campBatchesRelationMock->shouldReceive('exists')->andReturn(true);
        $campBatchesRelationMock->shouldReceive('pluck')->with('id')->andReturn($campBatchesCollection->pluck('id'));

        $this->campMock->shouldReceive('batchs')->andReturn($campBatchesRelationMock);

        $applicant1 = Mockery::mock(Applicant::class); $applicant1->id = 1001;
        $applicant1->shouldReceive('getAttribute')->with('id')->andReturn(1001);
        $caredLearnersCollection = collect([$applicant1]);

        $caresLearnersRelationMock = Mockery::mock();
        $caresLearnersRelationMock->shouldReceive('whereIn')->with('applicants.batch_id', $campBatchesCollection->pluck('id'))->andReturnSelf();
        $caresLearnersRelationMock->shouldReceive('pluck')->with('applicants.id')->andReturn($caredLearnersCollection->pluck('id'));

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldReceive('roles')->andReturn($rolesRelationMock);
        $this->user->shouldReceive('caresLearners')->andReturn($caresLearnersRelationMock);

        $context = $this->callProtectedMethod($this->user, 'getOrLoadCampContext', [$this->campMock]);
        $this->assertEquals([1001], $context['cared_learner_ids_in_camp']);
    }

    public function test_getOrLoadCampContext_aggregatesIdsFromMultipleRolesCorrectly()
    {
        $role1 = Mockery::mock(Role::class);
        $role1->id = 1; $role1->group_id = 10; $role1->region_id = 100; $role1->batch_id = 1000;
        $role1->permissions = collect();
        $role1->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $role1->shouldReceive('getAttribute')->with('group_id')->andReturn(10);
        $role1->shouldReceive('getAttribute')->with('region_id')->andReturn(100);
        $role1->shouldReceive('getAttribute')->with('batch_id')->andReturn(1000);
        $role1->shouldReceive('getAttribute')->with('permissions')->andReturn(collect());

        $role2 = Mockery::mock(Role::class);
        $role2->id = 2; $role2->group_id = 11; $role2->region_id = null; $role2->batch_id = 1001;
        $role2->permissions = collect();
        $role2->shouldReceive('getAttribute')->with('id')->andReturn(2);
        $role2->shouldReceive('getAttribute')->with('group_id')->andReturn(11);
        $role2->shouldReceive('getAttribute')->with('region_id')->andReturn(null);
        $role2->shouldReceive('getAttribute')->with('batch_id')->andReturn(1001);
        $role2->shouldReceive('getAttribute')->with('permissions')->andReturn(collect());

        $role3 = Mockery::mock(Role::class); // Duplicate group_id, distinct region_id
        $role3->id = 3; $role3->group_id = 10; $role3->region_id = 101; $role3->batch_id = null;
        $role3->permissions = collect();
        $role3->shouldReceive('getAttribute')->with('id')->andReturn(3);
        $role3->shouldReceive('getAttribute')->with('group_id')->andReturn(10);
        $role3->shouldReceive('getAttribute')->with('region_id')->andReturn(101);
        $role3->shouldReceive('getAttribute')->with('batch_id')->andReturn(null);
        $role3->shouldReceive('getAttribute')->with('permissions')->andReturn(collect());

        $rolesCollection = collect([$role1, $role2, $role3]);

        $rolesRelationMock = Mockery::mock();
        $rolesRelationMock->shouldReceive('where')->with('camp_id', $this->campMock->id)->andReturnSelf();
        $rolesRelationMock->shouldReceive('with')->with('permissions')->andReturnSelf();
        $rolesRelationMock->shouldReceive('get')->andReturn($rolesCollection);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldReceive('roles')->andReturn($rolesRelationMock);

        $campBatchesRelationMock = Mockery::mock();
        $campBatchesRelationMock->shouldReceive('exists')->andReturn(false);
        $this->campMock->shouldReceive('batchs')->andReturn($campBatchesRelationMock);

        $context = $this->callProtectedMethod($this->user, 'getOrLoadCampContext', [$this->campMock]);

        $this->assertCount(2, $context['user_group_ids']);
        $this->assertContains(10, $context['user_group_ids']);
        $this->assertContains(11, $context['user_group_ids']);

        $this->assertCount(2, $context['user_region_ids']);
        $this->assertContains(100, $context['user_region_ids']);
        $this->assertContains(101, $context['user_region_ids']);

        $this->assertCount(2, $context['user_batch_ids']);
        $this->assertContains(1000, $context['user_batch_ids']);
        $this->assertContains(1001, $context['user_batch_ids']);
    }

    public function test_getOrLoadCampContext_parsesMultipleDistinctPermissions()
    {
        $perm1 = Mockery::mock(Permission::class);
        $perm1->id = 1; $perm1->resource = 'ResourceA'; $perm1->action = 'read'; $perm1->range_parsed = 0; $perm1->description = '';
        $perm1->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $perm1->shouldReceive('getAttribute')->with('resource')->andReturn('ResourceA');
        $perm1->shouldReceive('getAttribute')->with('action')->andReturn('read');
        $perm1->shouldReceive('getAttribute')->with('range_parsed')->andReturn(0);
        $perm1->shouldReceive('getAttribute')->with('description')->andReturn('');

        $perm2 = Mockery::mock(Permission::class);
        $perm2->id = 2; $perm2->resource = 'ResourceB'; $perm2->action = 'write'; $perm2->range_parsed = 1; $perm2->description = '';
        $perm2->shouldReceive('getAttribute')->with('id')->andReturn(2);
        $perm2->shouldReceive('getAttribute')->with('resource')->andReturn('ResourceB');
        $perm2->shouldReceive('getAttribute')->with('action')->andReturn('write');
        $perm2->shouldReceive('getAttribute')->with('range_parsed')->andReturn(1);
        $perm2->shouldReceive('getAttribute')->with('description')->andReturn('');


        $roleMock = Mockery::mock(Role::class);
        $roleMock->id = 1; $roleMock->group_id = null; $roleMock->region_id = null; $roleMock->batch_id = null;
        $roleMock->permissions = collect([$perm1, $perm2]);
        $roleMock->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $roleMock->shouldReceive('getAttribute')->with('group_id')->andReturn(null);
        $roleMock->shouldReceive('getAttribute')->with('region_id')->andReturn(null);
        $roleMock->shouldReceive('getAttribute')->with('batch_id')->andReturn(null);
        $roleMock->shouldReceive('getAttribute')->with('permissions')->andReturn(collect([$perm1, $perm2]));


        $rolesRelationMock = Mockery::mock();
        $rolesRelationMock->shouldReceive('where->with->get')->andReturn(collect([$roleMock]));
        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldReceive('roles')->andReturn($rolesRelationMock);

        $campBatchesRelationMock = Mockery::mock();
        $campBatchesRelationMock->shouldReceive('exists')->andReturn(false);
        $this->campMock->shouldReceive('batchs')->andReturn($campBatchesRelationMock);

        $context = $this->callProtectedMethod($this->user, 'getOrLoadCampContext', [$this->campMock]);

        $this->assertCount(2, $context['parsed_permissions']);
        $this->assertTrue($context['parsed_permissions']->contains(function ($p) {
            return $p['resource'] === 'ResourceA' && $p['action'] === 'read' && $p['range_parsed'] === 0;
        }));
        $this->assertTrue($context['parsed_permissions']->contains(function ($p) {
            return $p['resource'] === 'ResourceB' && $p['action'] === 'write' && $p['range_parsed'] === 1;
        }));
    }


    // --- Tests for getAccessibleResult ---

    public function test_getAccessibleResult_returnsFalseIfResourceIsNull()
    {
        // Mock getOrLoadCampContext to ensure it's called but its internal logic isn't the focus here
        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => collect(), // Empty permissions
            // ... other context keys if needed by early checks in getAccessibleResult
        ]);

        $result = $this->user->getAccessibleResult(null, 'read', $this->campMock);
        $this->assertFalse($result);
    }

    public function test_getAccessibleResult_rangeParsed_0_allowsAccess()
    {
        $parsedPermissions = collect([
            ['resource' => 'App\Models\SomeResource', 'action' => 'read', 'range_parsed' => 0, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'camp_roles' => collect(),
            'user_group_ids' => [],
            'user_region_ids' => [],
            'user_batch_ids' => [],
            'cared_learner_ids_in_camp' => []
        ]);

        $resourceMock = Mockery::mock('App\Models\SomeResource');

        $result = $this->user->getAccessibleResult($resourceMock, 'read', $this->campMock);
        $this->assertTrue($result);
    }

    public function test_getAccessibleResult_vcampExportContextSwitch()
    {
        $mainCampMock = Mockery::mock(Camp::class);
        $mainCampMock->id = 2;
        $mainCampMock->shouldReceive('getAttribute')->with('id')->andReturn(2);

        $vcampMock = Mockery::mock(Vcamp::class);
        $vcampMock->shouldReceive('getAttribute')->with('mainCamp')->andReturn($mainCampMock);
        $vcampMock->mainCamp = $mainCampMock; // For property access

        $targetMock = new \stdClass(); // Using stdClass for simplicity
        $targetMock->camp = Mockery::mock(Camp::class); // This is the VCamp's "camp" attribute
        $targetMock->camp->id = 99; // ID of the VCamp model itself, not the main camp

        // Mock Vcamp::query()->find()
        // This is a bit tricky, might need to mock the Vcamp model's newQuery method or use a proper model mock.
        // For simplicity, let's assume Vcamp::query()->find() can be influenced or this part is tested in integration.
        // Here, we'll focus on the call to getOrLoadCampContext being made twice.

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();

        // First call for the original camp
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->once()->ordered()->andReturn([
            'parsed_permissions' => collect([
                // No permission for SomeResource initially
            ]),
            'camp_roles' => collect(), 'user_group_ids' => [], 'user_region_ids' => [], 'user_batch_ids' => [], 'cared_learner_ids_in_camp' => []
        ]);
        // Second call for the mainCamp after context switch
        $this->user->shouldReceive('getOrLoadCampContext')->with($mainCampMock)->once()->ordered()->andReturn([
            'parsed_permissions' => collect([
                 ['resource' => 'App\Models\SomeResource', 'action' => 'read', 'range_parsed' => 0, 'description' => '']
            ]),
            'camp_roles' => collect(), 'user_group_ids' => [], 'user_region_ids' => [], 'user_batch_ids' => [], 'cared_learner_ids_in_camp' => []
        ]);

        // Mock Vcamp static query
        $vcampQueryMock = Mockery::mock('overload:App\Models\Vcamp'); // Overload Vcamp
        $vcampQueryMock->shouldReceive('query->find')->with($targetMock->camp->id)->andReturn($vcampMock);


        $resourceMock = Mockery::mock('App\Models\SomeResource');
        $result = $this->user->getAccessibleResult($resourceMock, 'read', $this->campMock, 'vcampExport', $targetMock);

        $this->assertTrue($result); // Assuming the permission exists in the mainCamp context
    }

    public function test_getAccessibleResult_rangeParsed_1_groupAccess_allowed()
    {
        $resourceMock = Mockery::mock('App\Models\ResourceWithGroup'); // Generic resource with group_id
        $resourceMock->shouldReceive('getAttribute')->with('group_id')->andReturn(10);

        $parsedPermissions = collect([
            ['resource' => 'App\Models\ResourceWithGroup', 'action' => 'read', 'range_parsed' => 1, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'user_group_ids' => [10, 11], // User is in group 10
            'camp_roles' => collect(),
            'user_region_ids' => [],
            'user_batch_ids' => [],
            'cared_learner_ids_in_camp' => []
        ]);

        $result = $this->user->getAccessibleResult($resourceMock, 'read', $this->campMock);
        $this->assertTrue($result);
    }

    public function test_getAccessibleResult_rangeParsed_1_groupAccess_denied_differentGroup()
    {
        $resourceMock = Mockery::mock('App\Models\ResourceWithGroup');
        $resourceMock->shouldReceive('getAttribute')->with('group_id')->andReturn(12); // Resource in group 12

        $parsedPermissions = collect([
            ['resource' => 'App\Models\ResourceWithGroup', 'action' => 'read', 'range_parsed' => 1, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'user_group_ids' => [10, 11], // User not in group 12
            'camp_roles' => collect(),
            'user_region_ids' => [],
            'user_batch_ids' => [],
            'cared_learner_ids_in_camp' => []
        ]);

        $result = $this->user->getAccessibleResult($resourceMock, 'read', $this->campMock);
        $this->assertFalse($result);
    }

    public function test_getAccessibleResult_rangeParsed_1_groupAccess_denied_resourceHasNoGroup()
    {
        $resourceMock = Mockery::mock('App\Models\ResourceWithoutGroup');
        $resourceMock->shouldReceive('getAttribute')->with('group_id')->andReturn(null);

        $parsedPermissions = collect([
            ['resource' => 'App\Models\ResourceWithoutGroup', 'action' => 'read', 'range_parsed' => 1, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'user_group_ids' => [10, 11],
            'camp_roles' => collect(),
            'user_region_ids' => [],
            'user_batch_ids' => [],
            'cared_learner_ids_in_camp' => []
        ]);

        $result = $this->user->getAccessibleResult($resourceMock, 'read', $this->campMock);
        $this->assertFalse($result);
    }

    public function test_getAccessibleResult_rangeParsed_1_groupAccess_denied_userNotInAnyGroup()
    {
        $resourceMock = Mockery::mock('App\Models\ResourceWithGroup');
        $resourceMock->shouldReceive('getAttribute')->with('group_id')->andReturn(10);

        $parsedPermissions = collect([
            ['resource' => 'App\Models\ResourceWithGroup', 'action' => 'read', 'range_parsed' => 1, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'user_group_ids' => [], // User is in no groups
            'camp_roles' => collect(),
            'user_region_ids' => [],
            'user_batch_ids' => [],
            'cared_learner_ids_in_camp' => []
        ]);

        $result = $this->user->getAccessibleResult($resourceMock, 'read', $this->campMock);
        $this->assertFalse($result);
    }

    // Range Parsed = 2 (Region Access)
    public function test_getAccessibleResult_rangeParsed_2_regionAccess_allowed()
    {
        $resourceMock = Mockery::mock('App\Models\ResourceWithRegion');
        $resourceMock->shouldReceive('getAttribute')->with('region_id')->andReturn(20);

        $parsedPermissions = collect([
            ['resource' => 'App\Models\ResourceWithRegion', 'action' => 'read', 'range_parsed' => 2, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'user_region_ids' => [20, 21], // User is in region 20
            'camp_roles' => collect(),
            'user_group_ids' => [],
            'user_batch_ids' => [],
            'cared_learner_ids_in_camp' => []
        ]);

        $result = $this->user->getAccessibleResult($resourceMock, 'read', $this->campMock);
        $this->assertTrue($result);
    }

    public function test_getAccessibleResult_rangeParsed_2_regionAccess_denied_differentRegion()
    {
        $resourceMock = Mockery::mock('App\Models\ResourceWithRegion');
        $resourceMock->shouldReceive('getAttribute')->with('region_id')->andReturn(22); // Resource in region 22

        $parsedPermissions = collect([
            ['resource' => 'App\Models\ResourceWithRegion', 'action' => 'read', 'range_parsed' => 2, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'user_region_ids' => [20, 21], // User not in region 22
            'camp_roles' => collect(),
            'user_group_ids' => [],
            'user_batch_ids' => [],
            'cared_learner_ids_in_camp' => []
        ]);

        $result = $this->user->getAccessibleResult($resourceMock, 'read', $this->campMock);
        $this->assertFalse($result);
    }

    public function test_getAccessibleResult_rangeParsed_2_regionAccess_denied_resourceHasNoRegion()
    {
        $resourceMock = Mockery::mock('App\Models\ResourceWithoutRegion');
        $resourceMock->shouldReceive('getAttribute')->with('region_id')->andReturn(null);

        $parsedPermissions = collect([
            ['resource' => 'App\Models\ResourceWithoutRegion', 'action' => 'read', 'range_parsed' => 2, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'user_region_ids' => [20, 21],
            'camp_roles' => collect(),
            'user_group_ids' => [],
            'user_batch_ids' => [],
            'cared_learner_ids_in_camp' => []
        ]);

        $result = $this->user->getAccessibleResult($resourceMock, 'read', $this->campMock);
        $this->assertFalse($result);
    }

    public function test_getAccessibleResult_rangeParsed_2_regionAccess_denied_userNotInAnyRegion()
    {
        $resourceMock = Mockery::mock('App\Models\ResourceWithRegion');
        $resourceMock->shouldReceive('getAttribute')->with('region_id')->andReturn(20);

        $parsedPermissions = collect([
            ['resource' => 'App\Models\ResourceWithRegion', 'action' => 'read', 'range_parsed' => 2, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'user_region_ids' => [], // User is in no regions
            'camp_roles' => collect(),
            'user_group_ids' => [],
            'user_batch_ids' => [],
            'cared_learner_ids_in_camp' => []
        ]);

        $result = $this->user->getAccessibleResult($resourceMock, 'read', $this->campMock);
        $this->assertFalse($result);
    }

    // Range Parsed = 3 (Batch Access)
    public function test_getAccessibleResult_rangeParsed_3_batchAccess_allowed()
    {
        $resourceMock = Mockery::mock('App\Models\ResourceWithBatch'); // Generic resource with batch_id
        $resourceMock->shouldReceive('getAttribute')->with('batch_id')->andReturn(30);

        $parsedPermissions = collect([
            ['resource' => 'App\Models\ResourceWithBatch', 'action' => 'read', 'range_parsed' => 3, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'user_batch_ids' => [30, 31], // User is in batch 30
            'camp_roles' => collect(),
            'user_group_ids' => [],
            'user_region_ids' => [],
            'cared_learner_ids_in_camp' => []
        ]);

        $result = $this->user->getAccessibleResult($resourceMock, 'read', $this->campMock);
        $this->assertTrue($result);
    }

    public function test_getAccessibleResult_rangeParsed_3_batchAccess_denied_differentBatch()
    {
        $resourceMock = Mockery::mock('App\Models\ResourceWithBatch');
        $resourceMock->shouldReceive('getAttribute')->with('batch_id')->andReturn(32); // Resource in batch 32

        $parsedPermissions = collect([
            ['resource' => 'App\Models\ResourceWithBatch', 'action' => 'read', 'range_parsed' => 3, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'user_batch_ids' => [30, 31], // User not in batch 32
            'camp_roles' => collect(),
            'user_group_ids' => [],
            'user_region_ids' => [],
            'cared_learner_ids_in_camp' => []
        ]);

        $result = $this->user->getAccessibleResult($resourceMock, 'read', $this->campMock);
        $this->assertFalse($result);
    }

    public function test_getAccessibleResult_rangeParsed_3_batchAccess_denied_resourceHasNoBatch()
    {
        $resourceMock = Mockery::mock('App\Models\ResourceWithoutBatch');
        $resourceMock->shouldReceive('getAttribute')->with('batch_id')->andReturn(null);

        $parsedPermissions = collect([
            ['resource' => 'App\Models\ResourceWithoutBatch', 'action' => 'read', 'range_parsed' => 3, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'user_batch_ids' => [30, 31],
            'camp_roles' => collect(),
            'user_group_ids' => [],
            'user_region_ids' => [],
            'cared_learner_ids_in_camp' => []
        ]);

        $result = $this->user->getAccessibleResult($resourceMock, 'read', $this->campMock);
        $this->assertFalse($result);
    }

    public function test_getAccessibleResult_rangeParsed_3_batchAccess_denied_userNotInAnyBatch()
    {
        $resourceMock = Mockery::mock('App\Models\ResourceWithBatch');
        $resourceMock->shouldReceive('getAttribute')->with('batch_id')->andReturn(30);

        $parsedPermissions = collect([
            ['resource' => 'App\Models\ResourceWithBatch', 'action' => 'read', 'range_parsed' => 3, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'user_batch_ids' => [], // User is in no batches
            'camp_roles' => collect(),
            'user_group_ids' => [],
            'user_region_ids' => [],
            'cared_learner_ids_in_camp' => []
        ]);

        $result = $this->user->getAccessibleResult($resourceMock, 'read', $this->campMock);
        $this->assertFalse($result);
    }

    // Range Parsed = 4 (Cared Learner Access)
    public function test_getAccessibleResult_rangeParsed_4_caredLearnerAccess_allowed()
    {
        $applicantMock = Mockery::mock(Applicant::class);
        $applicantMock->shouldReceive('getAttribute')->with('id')->andReturn(40); // Applicant ID

        $parsedPermissions = collect([
            ['resource' => Applicant::class, 'action' => 'read', 'range_parsed' => 4, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'cared_learner_ids_in_camp' => [40, 41], // User cares for applicant 40
            'camp_roles' => collect(),
            'user_group_ids' => [],
            'user_region_ids' => [],
            'user_batch_ids' => []
        ]);

        $result = $this->user->getAccessibleResult($applicantMock, 'read', $this->campMock);
        $this->assertTrue($result);
    }

    public function test_getAccessibleResult_rangeParsed_4_caredLearnerAccess_denied_notCaredFor()
    {
        $applicantMock = Mockery::mock(Applicant::class);
        $applicantMock->shouldReceive('getAttribute')->with('id')->andReturn(42); // Applicant ID 42

        $parsedPermissions = collect([
            ['resource' => Applicant::class, 'action' => 'read', 'range_parsed' => 4, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'cared_learner_ids_in_camp' => [40, 41], // User does not care for applicant 42
            'camp_roles' => collect(),
            'user_group_ids' => [],
            'user_region_ids' => [],
            'user_batch_ids' => []
        ]);

        $result = $this->user->getAccessibleResult($applicantMock, 'read', $this->campMock);
        $this->assertFalse($result);
    }

    public function test_getAccessibleResult_rangeParsed_4_caredLearnerAccess_denied_noCaredLearners()
    {
        $applicantMock = Mockery::mock(Applicant::class);
        $applicantMock->shouldReceive('getAttribute')->with('id')->andReturn(40);

        $parsedPermissions = collect([
            ['resource' => Applicant::class, 'action' => 'read', 'range_parsed' => 4, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'cared_learner_ids_in_camp' => [], // User cares for no learners
            'camp_roles' => collect(),
            'user_group_ids' => [],
            'user_region_ids' => [],
            'user_batch_ids' => []
        ]);

        $result = $this->user->getAccessibleResult($applicantMock, 'read', $this->campMock);
        $this->assertFalse($result);
    }

    public function test_getAccessibleResult_rangeParsed_4_caredLearnerAccess_denied_resourceNotApplicant()
    {
        $nonApplicantResourceMock = Mockery::mock('App\Models\SomeOtherResource'); // Not an Applicant model

        $parsedPermissions = collect([
            // Permission is for Applicant::class, but resource is SomeOtherResource
            ['resource' => Applicant::class, 'action' => 'read', 'range_parsed' => 4, 'description' => '']
        ]);

        $this->user = Mockery::mock(User::class, [['id' => 1]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('getOrLoadCampContext')->with($this->campMock)->andReturn([
            'parsed_permissions' => $parsedPermissions,
            'cared_learner_ids_in_camp' => [40],
            'camp_roles' => collect(),
            'user_group_ids' => [],
            'user_region_ids' => [],
            'user_batch_ids' => []
        ]);

        // Even if the permission *name* matched SomeOtherResource, range 4 is specific to Applicant
        $result = $this->user->getAccessibleResult($nonApplicantResourceMock, 'read', $this->campMock);
        $this->assertFalse($result);
    }

    // --- Tests for canAccessResource ---
    public function test_canAccessResource_returnsFromDbCacheIfHit()
    {
        $resourceMock = Mockery::mock('App\Models\SomeResource');
        $resourceClass = get_class($resourceMock);

        $ucaronrMock = new \stdClass(); // Mock for Ucaronr model
        $ucaronrMock->can_access = true;

        $queryBuilderMock = Mockery::mock();
        // Mock the chain of where clauses
        $queryBuilderMock->shouldReceive('where')->with('camp_id', $this->campMock->id)->andReturnSelf();
        $queryBuilderMock->shouldReceive('where')->with('accessible_type', $resourceClass)->andReturnSelf();
        $queryBuilderMock->shouldReceive('where')->with('context', null)->andReturnSelf(); // Assuming context is null
        // Mock the closure based wheres for nullable fields
        $queryBuilderMock->shouldReceive('where')->with(Mockery::type(\Closure::class))->times(3)->andReturnSelf();
        $queryBuilderMock->shouldReceive('first')->andReturn($ucaronrMock);

        $userCanAccessResultRelationMock = Mockery::mock();
        $userCanAccessResultRelationMock->shouldReceive('__call')->andReturnUsing(function($method, $args) use ($queryBuilderMock) {
            // Forward calls like 'where' to the queryBuilderMock if needed, or directly mock the chain
            if (method_exists($queryBuilderMock, $method)) {
                return call_user_func_array([$queryBuilderMock, $method], $args);
            }
            return $queryBuilderMock; // Fallback
        });
         // Specifically mock the methods called on the relation
        $userCanAccessResultRelationMock->shouldReceive('where')->with('camp_id', $this->campMock->id)->andReturn($queryBuilderMock);


        $this->user = Mockery::mock(User::class, [['id'=>1]])->makePartial();
        $this->user->shouldReceive('canAccessResult')->andReturn($userCanAccessResultRelationMock);
        // We don't want getAccessibleResult to be called if DB cache hits
        // So, if we were to spy on it, it should not be called.

        $result = $this->user->canAccessResource($resourceMock, 'read', $this->campMock);
        $this->assertTrue($result);
    }

    public function test_canAccessResource_calculatesAndStoresToDbCacheIfMiss()
    {
        $resourceMock = Mockery::mock('App\Models\SomeResource');
        $resourceClass = get_class($resourceMock);
        $expectedUserId = 1; // User ID for the mock, should match the one in makePartial

        // This mock handles the chain for cache checking: $this->canAccessResult()->where(...)->first()
        $userCanAccessResultRelationMock = Mockery::mock();

        // Expectations for the cache query part (cache miss)
        $userCanAccessResultRelationMock->shouldReceive('where')->with('camp_id', $this->campMock->id)->ordered()->andReturnSelf();
        $userCanAccessResultRelationMock->shouldReceive('where')->with('accessible_type', $resourceClass)->ordered()->andReturnSelf();
        $userCanAccessResultRelationMock->shouldReceive('where')->with('context', null)->ordered()->andReturnSelf();
        $userCanAccessResultRelationMock->shouldReceive('where')->with(Mockery::type(\Closure::class))->times(3)->ordered()->andReturnSelf();
        $userCanAccessResultRelationMock->shouldReceive('first')->once()->ordered()->andReturn(null); // Cache miss

        // Expectation for the updateOrCreate call on the same relation mock
        $userCanAccessResultRelationMock->shouldReceive('updateOrCreate')
            ->once()
            ->with(
                Mockery::on(function($attributes) use ($resourceClass, $expectedUserId) {
                    $correct_user_id = isset($attributes['user_id']) && $attributes['user_id'] === $expectedUserId;
                    $correct_camp_id = isset($attributes['camp_id']) && $attributes['camp_id'] === $this->campMock->id;
                    $correct_type = isset($attributes['accessible_type']) && $attributes['accessible_type'] === $resourceClass;
                    $correct_context = array_key_exists('context', $attributes) && $attributes['context'] === null;
                    $correct_batch_id = array_key_exists('batch_id', $attributes) && $attributes['batch_id'] === null;
                    $correct_region_id = array_key_exists('region_id', $attributes) && $attributes['region_id'] === null;
                    $correct_accessible_id = array_key_exists('accessible_id', $attributes) && $attributes['accessible_id'] === null;

                    return $correct_user_id && $correct_camp_id && $correct_type && $correct_context &&
                           $correct_batch_id && $correct_region_id && $correct_accessible_id;
                }),
                ['can_access' => 1] // Value to create/update with, derived from $result = true
            )->andReturn(new \stdClass()); // Mocked Ucaronr instance

        // Mock User model and its methods
        $this->user = Mockery::mock(User::class, [['id' => $expectedUserId]])->makePartial();
        $this->user->shouldAllowMockingProtectedMethods();
        $this->user->shouldReceive('canAccessResult')->andReturn($userCanAccessResultRelationMock);
        $this->user->shouldReceive('getAccessibleResult')
            ->once()
            ->with($resourceMock, 'read', $this->campMock, null, null, null)
            ->andReturn(true); // This leads to can_access: 1 in updateOrCreate

        $result = $this->user->canAccessResource($resourceMock, 'read', $this->campMock);
        $this->assertTrue($result);
    }

    // Add more tests for:
    // - Different branches within getOrLoadCampContext (permissions parsing, different role/permission setups)
    // - All switch cases and fallback logic in getAccessibleResult
    //      - range_parsed = 1, 2, 3 with various conditions for $resource, $target, and user's context
    //      - fallback conditions when $forInspect is not found
    // - Cache key generation in canAccessResource for different $resource types (Applicant, User, Volunteer)
    // - Edge cases like invalid camp, null resource, etc.
}
