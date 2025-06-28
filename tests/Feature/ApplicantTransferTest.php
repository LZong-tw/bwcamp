<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\ApplicantsGroup;
use App\Models\Batch;
use App\Models\Camp;
use App\Models\CarerApplicantXref;
use App\Models\GroupNumber;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Ycamp;
use App\Models\Tcamp;
use App\Models\Ecamp;
use App\Models\Acamp;
use App\Models\Ceocamp;
use App\Services\ApplicantTransferService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApplicantTransferTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $transferService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transferService = new ApplicantTransferService();
        
        // Create a test user with permissions
        $this->user = User::factory()->create();
        
        // Setup default database state for testing
        $this->setupTestDatabase();
    }
    
    private function setupTestDatabase()
    {
        // Ensure required tables exist for special camp types
        // This helps with cross-camp transfer testing
        try {
            if (!DB::getSchemaBuilder()->hasTable('ycamp')) {
                DB::statement('CREATE TABLE IF NOT EXISTS ycamp (id INTEGER PRIMARY KEY AUTOINCREMENT, applicant_id INTEGER, school VARCHAR(255), grade VARCHAR(255), created_at TIMESTAMP, updated_at TIMESTAMP)');
            }
            if (!DB::getSchemaBuilder()->hasTable('tcamp')) {
                DB::statement('CREATE TABLE IF NOT EXISTS tcamp (id INTEGER PRIMARY KEY AUTOINCREMENT, applicant_id INTEGER, teaching_experience TEXT, created_at TIMESTAMP, updated_at TIMESTAMP)');
            }
            if (!DB::getSchemaBuilder()->hasTable('ecamp')) {
                DB::statement('CREATE TABLE IF NOT EXISTS ecamp (id INTEGER PRIMARY KEY AUTOINCREMENT, applicant_id INTEGER, company VARCHAR(255), position VARCHAR(255), created_at TIMESTAMP, updated_at TIMESTAMP)');
            }
            if (!DB::getSchemaBuilder()->hasTable('acamp')) {
                DB::statement('CREATE TABLE IF NOT EXISTS acamp (id INTEGER PRIMARY KEY AUTOINCREMENT, applicant_id INTEGER, achievement TEXT, created_at TIMESTAMP, updated_at TIMESTAMP)');
            }
            if (!DB::getSchemaBuilder()->hasTable('ceocamp')) {
                DB::statement('CREATE TABLE IF NOT EXISTS ceocamp (id INTEGER PRIMARY KEY AUTOINCREMENT, applicant_id INTEGER, company_revenue VARCHAR(255), created_at TIMESTAMP, updated_at TIMESTAMP)');
            }
        } catch (\Exception $e) {
            // If tables already exist or creation fails, continue
            // This prevents test failures due to table existence
        }
    }

    /** @test */
    public function it_can_transfer_applicant_between_same_camp_type_batches()
    {
        // Arrange: Create camps of same type
        $sourceCamp = Camp::factory()->create(['table' => 'ycamp']);
        $targetCamp = Camp::factory()->create(['table' => 'ycamp']);
        
        $sourceBatch = Batch::factory()->create(['camp_id' => $sourceCamp->id]);
        $targetBatch = Batch::factory()->create(['camp_id' => $targetCamp->id]);
        
        $applicant = Applicant::factory()->create([
            'batch_id' => $sourceBatch->id,
            'is_admitted' => true,
            'is_paid' => true
        ]);

        // Act
        $result = $this->transferService->transferApplicant($applicant->id, $targetBatch->id);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertTrue($result['is_same_camp_type']);
        
        $applicant->refresh();
        $this->assertEquals($targetBatch->id, $applicant->batch_id);
        $this->assertFalse($applicant->is_admitted);
        $this->assertFalse($applicant->is_paid);
    }

    /** @test */
    public function it_can_transfer_applicant_between_different_camp_types()
    {
        // Arrange: Create camps of different types
        $sourceCamp = Camp::factory()->create(['table' => 'ycamp']);
        $targetCamp = Camp::factory()->create(['table' => 'tcamp']);
        
        $sourceBatch = Batch::factory()->create(['camp_id' => $sourceCamp->id]);
        $targetBatch = Batch::factory()->create(['camp_id' => $targetCamp->id]);
        
        $applicant = Applicant::factory()->create([
            'batch_id' => $sourceBatch->id,
            'is_admitted' => true,
            'is_paid' => true
        ]);

        // Act
        $result = $this->transferService->transferApplicant($applicant->id, $targetBatch->id);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertFalse($result['is_same_camp_type']);
        
        $applicant->refresh();
        $this->assertEquals($targetBatch->id, $applicant->batch_id);
        $this->assertFalse($applicant->is_admitted);
        $this->assertFalse($applicant->is_paid);
        $this->assertStringContains('轉入', $applicant->expectation);
    }

    /** @test */
    public function it_throws_exception_when_transferring_to_same_batch()
    {
        // Arrange
        $camp = Camp::factory()->create(['table' => 'ycamp']);
        $batch = Batch::factory()->create(['camp_id' => $camp->id]);
        $applicant = Applicant::factory()->create(['batch_id' => $batch->id]);

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('不能轉換到相同的梯次');
        
        $this->transferService->transferApplicant($applicant->id, $batch->id);
    }

    /** @test */
    public function it_throws_exception_when_applicant_not_found()
    {
        // Arrange
        $camp = Camp::factory()->create(['table' => 'ycamp']);
        $batch = Batch::factory()->create(['camp_id' => $camp->id]);

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('申請人不存在');
        
        $this->transferService->transferApplicant(999999, $batch->id);
    }

    /** @test */
    public function it_throws_exception_when_target_batch_not_found()
    {
        // Arrange
        $camp = Camp::factory()->create(['table' => 'ycamp']);
        $batch = Batch::factory()->create(['camp_id' => $camp->id]);
        $applicant = Applicant::factory()->create(['batch_id' => $batch->id]);

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('目標梯次不存在');
        
        $this->transferService->transferApplicant($applicant->id, 999999);
    }

    /** @test */
    public function it_throws_exception_when_target_batch_has_already_started()
    {
        // Arrange
        $sourceCamp = Camp::factory()->create(['table' => 'ycamp']);
        $targetCamp = Camp::factory()->create(['table' => 'ycamp']);
        
        $sourceBatch = Batch::factory()->create([
            'camp_id' => $sourceCamp->id,
            'batch_start' => now()->addDays(5)
        ]);
        
        // 目標梯次已經開始（昨天開始）
        $targetBatch = Batch::factory()->create([
            'camp_id' => $targetCamp->id,
            'batch_start' => now()->subDays(1)
        ]);
        
        $applicant = Applicant::factory()->create(['batch_id' => $sourceBatch->id]);

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('無法轉換到已經開始或結束的梯次');
        
        $this->transferService->transferApplicant($applicant->id, $targetBatch->id);
    }

    /** @test */
    public function it_allows_transfer_to_future_batch()
    {
        // Arrange
        $sourceCamp = Camp::factory()->create(['table' => 'ycamp']);
        $targetCamp = Camp::factory()->create(['table' => 'ycamp']);
        
        $sourceBatch = Batch::factory()->create([
            'camp_id' => $sourceCamp->id,
            'batch_start' => now()->addDays(5)
        ]);
        
        // 目標梯次在未來（明天開始）
        $targetBatch = Batch::factory()->create([
            'camp_id' => $targetCamp->id,
            'batch_start' => now()->addDays(10)
        ]);
        
        $applicant = Applicant::factory()->create([
            'batch_id' => $sourceBatch->id,
            'is_admitted' => true,
            'is_paid' => true
        ]);

        // Act
        $result = $this->transferService->transferApplicant($applicant->id, $targetBatch->id);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertTrue($result['is_same_camp_type']);
        
        $applicant->refresh();
        $this->assertEquals($targetBatch->id, $applicant->batch_id);
    }

    /** @test */
    public function api_transfer_requires_authentication()
    {
        // Arrange
        $camp = Camp::factory()->create(['table' => 'ycamp']);
        $batch = Batch::factory()->create(['camp_id' => $camp->id]);
        $applicant = Applicant::factory()->create(['batch_id' => $batch->id]);

        // Act
        $response = $this->postJson('/api/applicant/transfer', [
            'applicant_id' => $applicant->id,
            'target_batch_id' => $batch->id
        ]);

        // Assert
        $response->assertStatus(401);
    }

    /** @test */
    public function api_transfer_validates_required_fields()
    {
        // Act
        $response = $this->actingAs($this->user)
            ->postJson('/api/applicant/transfer', []);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['applicant_id', 'target_batch_id']);
    }

    /** @test */
    public function api_get_available_batches_requires_authentication()
    {
        // Act
        $response = $this->getJson('/api/batches/available');

        // Assert
        $response->assertStatus(401);
    }

    /** @test */
    public function api_get_available_batches_returns_batch_list()
    {
        // Arrange
        $camp1 = Camp::factory()->create(['fullName' => '大專營', 'table' => 'ycamp']);
        $camp2 = Camp::factory()->create(['fullName' => '教師營', 'table' => 'tcamp']);
        
        $batch1 = Batch::factory()->create(['camp_id' => $camp1->id, 'name' => 'A梯']);
        $batch2 = Batch::factory()->create(['camp_id' => $camp2->id, 'name' => 'B梯']);

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/batches/available');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'batches' => [
                '*' => [
                    'id',
                    'name',
                    'camp_name',
                    'camp_table',
                    'display_name'
                ]
            ]
        ]);
    }

    /** @test */
    public function it_clears_group_and_number_assignments_on_transfer()
    {
        // Arrange
        $sourceCamp = Camp::factory()->create(['table' => 'ycamp']);
        $targetCamp = Camp::factory()->create(['table' => 'ycamp']);
        
        $sourceBatch = Batch::factory()->create(['camp_id' => $sourceCamp->id]);
        $targetBatch = Batch::factory()->create(['camp_id' => $targetCamp->id]);
        
        $applicant = Applicant::factory()->create([
            'batch_id' => $sourceBatch->id,
            'group_id' => 1,
            'number_id' => 1,
            'is_admitted' => true,
            'is_paid' => true
        ]);

        // Act
        $result = $this->transferService->transferApplicant($applicant->id, $targetBatch->id);

        // Assert
        $this->assertTrue($result['success']);
        
        $applicant->refresh();
        $this->assertNull($applicant->group_id);
        $this->assertNull($applicant->number_id);
        
        // Verify changes are logged
        $this->assertArrayHasKey('group_id', $result['changes']['basic_fields']);
        $this->assertArrayHasKey('number_id', $result['changes']['basic_fields']);
        $this->assertEquals(1, $result['changes']['basic_fields']['group_id']['from']);
        $this->assertNull($result['changes']['basic_fields']['group_id']['to']);
    }

    /** @test */
    public function it_clears_carer_relationships_on_transfer()
    {
        // Arrange
        $sourceCamp = Camp::factory()->create(['table' => 'ycamp']);
        $targetCamp = Camp::factory()->create(['table' => 'ycamp']);
        
        $sourceBatch = Batch::factory()->create(['camp_id' => $sourceCamp->id]);
        $targetBatch = Batch::factory()->create(['camp_id' => $targetCamp->id]);
        
        $applicant = Applicant::factory()->create([
            'batch_id' => $sourceBatch->id,
            'is_admitted' => true,
            'is_paid' => true
        ]);

        // Create carer relationships
        $carer1 = User::factory()->create();
        $carer2 = User::factory()->create();
        
        CarerApplicantXref::create(['user_id' => $carer1->id, 'applicant_id' => $applicant->id]);
        CarerApplicantXref::create(['user_id' => $carer2->id, 'applicant_id' => $applicant->id]);
        
        // Verify carers are initially assigned
        $this->assertEquals(2, $applicant->carers()->count());

        // Act
        $result = $this->transferService->transferApplicant($applicant->id, $targetBatch->id);

        // Assert
        $this->assertTrue($result['success']);
        
        $applicant->refresh();
        $this->assertEquals(0, $applicant->carers()->count());
        
        // Verify changes are logged
        $this->assertArrayHasKey('carers_cleared', $result['changes']['basic_fields']);
        $this->assertEquals(2, $result['changes']['basic_fields']['carers_cleared']['from']);
        $this->assertEquals(0, $result['changes']['basic_fields']['carers_cleared']['to']);
    }

    /** @test */
    public function it_clears_group_and_carer_data_for_cross_type_transfer()
    {
        // Arrange: Different camp types
        $sourceCamp = Camp::factory()->create(['table' => 'ycamp']);
        $targetCamp = Camp::factory()->create(['table' => 'tcamp']);
        
        $sourceBatch = Batch::factory()->create(['camp_id' => $sourceCamp->id]);
        $targetBatch = Batch::factory()->create(['camp_id' => $targetCamp->id]);
        
        $applicant = Applicant::factory()->create([
            'batch_id' => $sourceBatch->id,
            'group_id' => 1,
            'number_id' => 1,
            'is_admitted' => true,
            'is_paid' => true
        ]);

        // Create carer relationships
        $carer = User::factory()->create();
        CarerApplicantXref::create(['user_id' => $carer->id, 'applicant_id' => $applicant->id]);
        
        // Verify initial state
        $this->assertEquals(1, $applicant->carers()->count());
        $this->assertNotNull($applicant->group_id);
        $this->assertNotNull($applicant->number_id);

        // Act
        $result = $this->transferService->transferApplicant($applicant->id, $targetBatch->id);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertFalse($result['is_same_camp_type']);
        
        $applicant->refresh();
        
        // Check group and number clearing
        $this->assertNull($applicant->group_id);
        $this->assertNull($applicant->number_id);
        
        // Check carer clearing
        $this->assertEquals(0, $applicant->carers()->count());
        
        // Verify changes are logged
        $this->assertArrayHasKey('group_id', $result['changes']['basic_fields']);
        $this->assertArrayHasKey('number_id', $result['changes']['basic_fields']);
        $this->assertArrayHasKey('carers_cleared', $result['changes']['basic_fields']);
        
        // Check expectation note is added
        $this->assertStringContains('轉入', $applicant->expectation);
    }

    // ===========================================
    // COMPREHENSIVE END-TO-END TESTS
    // ===========================================

    /** @test */
    public function end_to_end_api_transfer_with_complete_workflow()
    {
        // Arrange: Complete setup with permissions
        $user = User::factory()->create();
        $this->setupUserPermissions($user);
        
        $sourceCamp = Camp::factory()->create([
            'table' => 'ycamp',
            'fullName' => '大專營',
            'abbreviation' => 'Y'
        ]);
        $targetCamp = Camp::factory()->create([
            'table' => 'tcamp',
            'fullName' => '教師營',
            'abbreviation' => 'T'
        ]);
        
        $sourceBatch = Batch::factory()->create([
            'camp_id' => $sourceCamp->id,
            'name' => 'A梯',
            'batch_start' => Carbon::now()->addDays(10)->toDateString()
        ]);
        $targetBatch = Batch::factory()->create([
            'camp_id' => $targetCamp->id,
            'name' => 'B梯',
            'batch_start' => Carbon::now()->addDays(15)->toDateString()
        ]);
        
        $applicant = Applicant::factory()->create([
            'batch_id' => $sourceBatch->id,
            'name' => '測試學員',
            'is_admitted' => true,
            'is_paid' => true,
            'is_attend' => false,
            'group_id' => 1,
            'number_id' => 5,
            'fee' => 1000
        ]);

        // Create special camp data
        $ycampData = Ycamp::create([
            'applicant_id' => $applicant->id,
            'school' => '測試大學',
            'grade' => '大三'
        ]);

        // Create carer relationships
        $carer = User::factory()->create();
        CarerApplicantXref::create(['user_id' => $carer->id, 'applicant_id' => $applicant->id]);

        // Act: Execute complete API workflow
        // 1. Get available batches
        $batchesResponse = $this->actingAs($user)
            ->getJson('/api/batches/available');
        
        // 2. Perform transfer
        $transferResponse = $this->actingAs($user)
            ->postJson('/api/applicant/transfer', [
                'applicant_id' => $applicant->id,
                'target_batch_id' => $targetBatch->id
            ]);

        // Assert: Complete verification
        // Verify API responses
        $batchesResponse->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'batches' => [
                    '*' => ['id', 'name', 'camp_name', 'camp_table', 'display_name']
                ]
            ]);
        
        $transferResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_same_camp_type' => false
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'is_same_camp_type',
                'changes' => [
                    'basic_fields',
                    'special_fields'
                ]
            ]);
        
        // Verify database changes
        $applicant->refresh();
        $this->assertEquals($targetBatch->id, $applicant->batch_id);
        $this->assertFalse($applicant->is_admitted);
        $this->assertFalse($applicant->is_paid);
        $this->assertFalse($applicant->is_attend);
        $this->assertNull($applicant->group_id);
        $this->assertNull($applicant->number_id);
        
        // Verify special data is cleared
        $this->assertNull($applicant->ycamp);
        
        // Verify carer relationships are cleared
        $this->assertEquals(0, $applicant->carers()->count());
        
        // Verify transfer note is added
        $this->assertStringContains('從「大專營」轉入「教師營」', $applicant->expectation);
    }

    /** @test */
    public function end_to_end_permission_based_access_control()
    {
        // Test different permission scenarios
        $camps = $this->createMultipleCampsWithBatches();
        
        // User with limited permissions
        $limitedUser = User::factory()->create();
        $this->setupLimitedPermissions($limitedUser, $camps['ycamp']);
        
        // User with admin permissions
        $adminUser = User::factory()->create();
        $this->setupAdminPermissions($adminUser);
        
        $applicant = Applicant::factory()->create([
            'batch_id' => $camps['ybatch']->id
        ]);

        // Test 1: Limited user can only transfer within permitted camps
        $response = $this->actingAs($limitedUser)
            ->postJson('/api/applicant/transfer', [
                'applicant_id' => $applicant->id,
                'target_batch_id' => $camps['tbatch']->id // Different camp type
            ]);
        
        $response->assertStatus(403); // Should be forbidden
        
        // Test 2: Admin user can transfer across all camps
        $response = $this->actingAs($adminUser)
            ->postJson('/api/applicant/transfer', [
                'applicant_id' => $applicant->id,
                'target_batch_id' => $camps['tbatch']->id
            ]);
        
        $response->assertStatus(200);
        
        // Test 3: Available batches filtered by permissions
        $limitedBatchesResponse = $this->actingAs($limitedUser)
            ->getJson('/api/batches/available');
        
        $adminBatchesResponse = $this->actingAs($adminUser)
            ->getJson('/api/batches/available');
        
        $limitedBatches = $limitedBatchesResponse->json('batches');
        $adminBatches = $adminBatchesResponse->json('batches');
        
        $this->assertLessThanOrEqual(count($adminBatches), count($limitedBatches));
    }

    /** @test */
    public function end_to_end_cross_camp_data_preservation_and_clearing()
    {
        // Test comprehensive data handling across different camp types
        $testCases = [
            ['source' => 'ycamp', 'target' => 'ycamp', 'preserve_special' => true],
            ['source' => 'ycamp', 'target' => 'tcamp', 'preserve_special' => false],
            ['source' => 'ecamp', 'target' => 'ceocamp', 'preserve_special' => true], // Business camps
            ['source' => 'acamp', 'target' => 'ycamp', 'preserve_special' => false],
        ];

        foreach ($testCases as $case) {
            $this->runCrossTypeTransferTest($case['source'], $case['target'], $case['preserve_special']);
        }
    }

    /** @test */
    public function end_to_end_transaction_rollback_on_failure()
    {
        // Test that transactions properly rollback on failure
        $sourceCamp = Camp::factory()->create(['table' => 'ycamp']);
        $targetCamp = Camp::factory()->create(['table' => 'tcamp']);
        
        $sourceBatch = Batch::factory()->create(['camp_id' => $sourceCamp->id]);
        $targetBatch = Batch::factory()->create([
            'camp_id' => $targetCamp->id,
            'batch_start' => Carbon::yesterday()->toDateString() // Past date
        ]);
        
        $applicant = Applicant::factory()->create([
            'batch_id' => $sourceBatch->id,
            'is_admitted' => true,
            'group_id' => 1
        ]);

        $originalBatchId = $applicant->batch_id;
        $originalIsAdmitted = $applicant->is_admitted;
        $originalGroupId = $applicant->group_id;

        // Attempt transfer to past batch (should fail)
        try {
            $this->transferService->transferApplicant($applicant->id, $targetBatch->id);
            $this->fail('Expected exception was not thrown');
        } catch (\Exception $e) {
            // Expected exception
        }

        // Verify data remains unchanged
        $applicant->refresh();
        $this->assertEquals($originalBatchId, $applicant->batch_id);
        $this->assertEquals($originalIsAdmitted, $applicant->is_admitted);
        $this->assertEquals($originalGroupId, $applicant->group_id);
    }

    /** @test */
    public function end_to_end_concurrent_transfer_handling()
    {
        // Test handling of concurrent transfer attempts
        $user = User::factory()->create();
        $this->setupUserPermissions($user);
        
        $sourceCamp = Camp::factory()->create(['table' => 'ycamp']);
        $targetCamp1 = Camp::factory()->create(['table' => 'tcamp']);
        $targetCamp2 = Camp::factory()->create(['table' => 'ecamp']);
        
        $sourceBatch = Batch::factory()->create(['camp_id' => $sourceCamp->id]);
        $targetBatch1 = Batch::factory()->create(['camp_id' => $targetCamp1->id]);
        $targetBatch2 = Batch::factory()->create(['camp_id' => $targetCamp2->id]);
        
        $applicant = Applicant::factory()->create([
            'batch_id' => $sourceBatch->id
        ]);

        // Simulate concurrent requests
        $response1 = $this->actingAs($user)
            ->postJson('/api/applicant/transfer', [
                'applicant_id' => $applicant->id,
                'target_batch_id' => $targetBatch1->id
            ]);
        
        // Second request should fail (applicant already transferred)
        $response2 = $this->actingAs($user)
            ->postJson('/api/applicant/transfer', [
                'applicant_id' => $applicant->id,
                'target_batch_id' => $targetBatch2->id
            ]);

        $response1->assertStatus(200);
        
        // Second request should either succeed (if different batch) or handle gracefully
        $applicant->refresh();
        $this->assertNotEquals($sourceBatch->id, $applicant->batch_id);
    }

    /** @test */
    public function end_to_end_logging_and_audit_trail()
    {
        // Test comprehensive logging of transfer operations
        Log::spy();
        
        $user = User::factory()->create(['name' => '測試管理員']);
        $this->setupUserPermissions($user);
        
        $sourceCamp = Camp::factory()->create([
            'table' => 'ycamp',
            'fullName' => '大專營測試'
        ]);
        $targetCamp = Camp::factory()->create([
            'table' => 'tcamp',
            'fullName' => '教師營測試'
        ]);
        
        $sourceBatch = Batch::factory()->create(['camp_id' => $sourceCamp->id]);
        $targetBatch = Batch::factory()->create(['camp_id' => $targetCamp->id]);
        
        $applicant = Applicant::factory()->create([
            'batch_id' => $sourceBatch->id,
            'name' => '測試學員A'
        ]);

        // Execute transfer
        $this->actingAs($user)
            ->postJson('/api/applicant/transfer', [
                'applicant_id' => $applicant->id,
                'target_batch_id' => $targetBatch->id
            ]);

        // Verify logging
        Log::shouldHaveReceived('info')
            ->with('申請人轉換營隊/梯次', \Mockery::type('array'))
            ->once();
    }

    /** @test */
    public function end_to_end_fee_calculation_across_camps()
    {
        // Test fee recalculation when transferring between camps with different fees
        $user = User::factory()->create();
        $this->setupUserPermissions($user);
        
        $sourceCamp = Camp::factory()->create([
            'table' => 'ycamp',
            'setfee' => 1000
        ]);
        $targetCamp = Camp::factory()->create([
            'table' => 'tcamp',
            'setfee' => 1500
        ]);
        
        $sourceBatch = Batch::factory()->create(['camp_id' => $sourceCamp->id]);
        $targetBatch = Batch::factory()->create(['camp_id' => $targetCamp->id]);
        
        $applicant = Applicant::factory()->create([
            'batch_id' => $sourceBatch->id,
            'fee' => 1000,
            'is_paid' => true
        ]);

        // Execute transfer
        $response = $this->actingAs($user)
            ->postJson('/api/applicant/transfer', [
                'applicant_id' => $applicant->id,
                'target_batch_id' => $targetBatch->id
            ]);

        $response->assertStatus(200);
        
        $applicant->refresh();
        $this->assertEquals(1500, $applicant->fee); // Updated to target camp fee
        $this->assertFalse($applicant->is_paid); // Payment status reset
    }

    // ===========================================
    // HELPER METHODS FOR E2E TESTS
    // ===========================================

    private function setupUserPermissions(User $user)
    {
        // Mock canAccessResource method for testing
        // In real implementation, this would use Laratrust roles/permissions
        $this->mock(\App\Http\Controllers\BackendController::class)
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('canAccessResource')
            ->andReturn(true);
    }

    private function setupLimitedPermissions(User $user, Camp $allowedCamp)
    {
        // Setup limited permissions for specific camp only
        $this->mock(\App\Http\Controllers\BackendController::class)
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('canAccessResource')
            ->andReturnUsing(function($action, $resource, $camp) use ($allowedCamp) {
                return $camp->id === $allowedCamp->id;
            });
    }

    private function setupAdminPermissions(User $user)
    {
        // Setup admin permissions (access to all)
        $this->mock(\App\Http\Controllers\BackendController::class)
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('canAccessResource')
            ->andReturn(true);
    }

    private function createMultipleCampsWithBatches()
    {
        $yCamp = Camp::factory()->create(['table' => 'ycamp', 'fullName' => '大專營']);
        $tCamp = Camp::factory()->create(['table' => 'tcamp', 'fullName' => '教師營']);
        $eCamp = Camp::factory()->create(['table' => 'ecamp', 'fullName' => '企業營']);
        
        return [
            'ycamp' => $yCamp,
            'tcamp' => $tCamp,
            'ecamp' => $eCamp,
            'ybatch' => Batch::factory()->create(['camp_id' => $yCamp->id]),
            'tbatch' => Batch::factory()->create(['camp_id' => $tCamp->id]),
            'ebatch' => Batch::factory()->create(['camp_id' => $eCamp->id])
        ];
    }

    private function runCrossTypeTransferTest(string $sourceType, string $targetType, bool $shouldPreserveSpecial)
    {
        $sourceCamp = Camp::factory()->create(['table' => $sourceType]);
        $targetCamp = Camp::factory()->create(['table' => $targetType]);
        
        $sourceBatch = Batch::factory()->create(['camp_id' => $sourceCamp->id]);
        $targetBatch = Batch::factory()->create(['camp_id' => $targetCamp->id]);
        
        $applicant = Applicant::factory()->create([
            'batch_id' => $sourceBatch->id,
            'is_admitted' => true,
            'group_id' => 1
        ]);

        // Create special data based on source type
        if ($sourceType === 'ycamp') {
            Ycamp::create([
                'applicant_id' => $applicant->id,
                'school' => '測試大學'
            ]);
        }

        $result = $this->transferService->transferApplicant($applicant->id, $targetBatch->id);
        
        $this->assertTrue($result['success']);
        $this->assertEquals($shouldPreserveSpecial, $result['is_same_camp_type']);
        
        $applicant->refresh();
        
        if (!$shouldPreserveSpecial && $sourceType === 'ycamp') {
            $this->assertNull($applicant->ycamp);
        }
    }
}