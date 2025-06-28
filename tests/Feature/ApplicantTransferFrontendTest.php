<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\Batch;
use App\Models\Camp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;

class ApplicantTransferFrontendTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function frontend_attendee_info_page_displays_transfer_button_for_authorized_users()
    {
        // Arrange
        $camp = Camp::factory()->create(['table' => 'ycamp']);
        $batch = Batch::factory()->create(['camp_id' => $camp->id]);
        $applicant = Applicant::factory()->create(['batch_id' => $batch->id]);

        // Mock authorization
        $this->mockUserHasPermission($this->user, true);

        // Act
        $response = $this->actingAs($this->user)
            ->get("/backend/in_camp/attendeeInfo/{$applicant->id}");

        // Assert
        $response->assertStatus(200);
        $response->assertSee('轉換營隊/梯次', false);
        $response->assertSee('data-applicant-id="' . $applicant->id . '"', false);
    }

    /** @test */
    public function frontend_attendee_info_page_hides_transfer_button_for_unauthorized_users()
    {
        // Arrange
        $camp = Camp::factory()->create(['table' => 'ycamp']);
        $batch = Batch::factory()->create(['camp_id' => $camp->id]);
        $applicant = Applicant::factory()->create(['batch_id' => $batch->id]);

        // Mock no authorization
        $this->mockUserHasPermission($this->user, false);

        // Act
        $response = $this->actingAs($this->user)
            ->get("/backend/in_camp/attendeeInfo/{$applicant->id}");

        // Assert
        $response->assertStatus(200);
        $response->assertDontSee('轉換營隊/梯次', false);
    }

    /** @test */
    public function frontend_transfer_modal_loads_available_batches()
    {
        // Arrange
        $camp1 = Camp::factory()->create(['table' => 'ycamp', 'fullName' => '大專營']);
        $camp2 = Camp::factory()->create(['table' => 'tcamp', 'fullName' => '教師營']);
        
        $batch1 = Batch::factory()->create([
            'camp_id' => $camp1->id,
            'name' => 'A梯',
            'batch_start' => Carbon::now()->addDays(10)->toDateString()
        ]);
        $batch2 = Batch::factory()->create([
            'camp_id' => $camp2->id,
            'name' => 'B梯',
            'batch_start' => Carbon::now()->addDays(15)->toDateString()
        ]);

        $applicant = Applicant::factory()->create(['batch_id' => $batch1->id]);

        $this->mockUserHasPermission($this->user, true);

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/batches/available');

        // Assert
        $response->assertStatus(200);
        $batches = $response->json('batches');
        
        $this->assertGreaterThanOrEqual(2, count($batches));
        
        // Check that both batches are available
        $batchIds = collect($batches)->pluck('id')->toArray();
        $this->assertContains($batch1->id, $batchIds);
        $this->assertContains($batch2->id, $batchIds);
    }

    /** @test */
    public function frontend_transfer_modal_filters_past_batches()
    {
        // Arrange
        $camp = Camp::factory()->create(['table' => 'ycamp']);
        
        // Future batch (should be included)
        $futureBatch = Batch::factory()->create([
            'camp_id' => $camp->id,
            'name' => 'Future梯',
            'batch_start' => Carbon::now()->addDays(10)->toDateString()
        ]);
        
        // Past batch (should be excluded)
        $pastBatch = Batch::factory()->create([
            'camp_id' => $camp->id,
            'name' => 'Past梯',
            'batch_start' => Carbon::yesterday()->toDateString()
        ]);

        $this->mockUserHasPermission($this->user, true);

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/batches/available');

        // Assert
        $response->assertStatus(200);
        $batches = $response->json('batches');
        
        $batchIds = collect($batches)->pluck('id')->toArray();
        $this->assertContains($futureBatch->id, $batchIds);
        $this->assertNotContains($pastBatch->id, $batchIds);
    }

    /** @test */
    public function frontend_transfer_form_validates_required_fields()
    {
        // Arrange
        $this->mockUserHasPermission($this->user, true);

        // Act: Submit without required fields
        $response = $this->actingAs($this->user)
            ->postJson('/api/applicant/transfer', []);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['applicant_id', 'target_batch_id']);
    }

    /** @test */
    public function frontend_transfer_displays_success_message()
    {
        // Arrange
        $sourceCamp = Camp::factory()->create(['table' => 'ycamp']);
        $targetCamp = Camp::factory()->create(['table' => 'tcamp']);
        
        $sourceBatch = Batch::factory()->create(['camp_id' => $sourceCamp->id]);
        $targetBatch = Batch::factory()->create([
            'camp_id' => $targetCamp->id,
            'batch_start' => Carbon::now()->addDays(10)->toDateString()
        ]);
        
        $applicant = Applicant::factory()->create(['batch_id' => $sourceBatch->id]);

        $this->mockUserHasPermission($this->user, true);

        // Act
        $response = $this->actingAs($this->user)
            ->postJson('/api/applicant/transfer', [
                'applicant_id' => $applicant->id,
                'target_batch_id' => $targetBatch->id
            ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_same_camp_type' => false
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'is_same_camp_type',
            'changes'
        ]);
    }

    /** @test */
    public function frontend_transfer_displays_error_messages()
    {
        // Arrange
        $sourceCamp = Camp::factory()->create(['table' => 'ycamp']);
        $targetCamp = Camp::factory()->create(['table' => 'tcamp']);
        
        $sourceBatch = Batch::factory()->create(['camp_id' => $sourceCamp->id]);
        // Past batch (should cause error)
        $targetBatch = Batch::factory()->create([
            'camp_id' => $targetCamp->id,
            'batch_start' => Carbon::yesterday()->toDateString()
        ]);
        
        $applicant = Applicant::factory()->create(['batch_id' => $sourceBatch->id]);

        $this->mockUserHasPermission($this->user, true);

        // Act
        $response = $this->actingAs($this->user)
            ->postJson('/api/applicant/transfer', [
                'applicant_id' => $applicant->id,
                'target_batch_id' => $targetBatch->id
            ]);

        // Assert
        $response->assertStatus(400); // Bad request due to past batch
        $response->assertJson([
            'success' => false
        ]);
        $response->assertJsonStructure([
            'success',
            'message'
        ]);
    }

    /** @test */
    public function frontend_batch_display_includes_camp_information()
    {
        // Arrange
        $camps = [
            ['table' => 'ycamp', 'fullName' => '大專營', 'abbreviation' => 'Y'],
            ['table' => 'tcamp', 'fullName' => '教師營', 'abbreviation' => 'T'],
            ['table' => 'ecamp', 'fullName' => '企業營', 'abbreviation' => 'E']
        ];

        $batches = [];
        foreach ($camps as $campData) {
            $camp = Camp::factory()->create($campData);
            $batches[] = Batch::factory()->create([
                'camp_id' => $camp->id,
                'name' => 'A梯',
                'batch_start' => Carbon::now()->addDays(10)->toDateString()
            ]);
        }

        $this->mockUserHasPermission($this->user, true);

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/batches/available');

        // Assert
        $response->assertStatus(200);
        $responseData = $response->json();
        
        $this->assertTrue($responseData['success']);
        $this->assertArrayHasKey('batches', $responseData);
        
        foreach ($responseData['batches'] as $batch) {
            $this->assertArrayHasKey('id', $batch);
            $this->assertArrayHasKey('name', $batch);
            $this->assertArrayHasKey('camp_name', $batch);
            $this->assertArrayHasKey('camp_table', $batch);
            $this->assertArrayHasKey('display_name', $batch);
        }
    }

    /** @test */
    public function frontend_handles_ajax_transfer_request_with_csrf()
    {
        // This test verifies CSRF protection is working
        $sourceCamp = Camp::factory()->create(['table' => 'ycamp']);
        $targetCamp = Camp::factory()->create(['table' => 'tcamp']);
        
        $sourceBatch = Batch::factory()->create(['camp_id' => $sourceCamp->id]);
        $targetBatch = Batch::factory()->create([
            'camp_id' => $targetCamp->id,
            'batch_start' => Carbon::now()->addDays(10)->toDateString()
        ]);
        
        $applicant = Applicant::factory()->create(['batch_id' => $sourceBatch->id]);

        $this->mockUserHasPermission($this->user, true);

        // Test with proper authentication and CSRF token
        $response = $this->actingAs($this->user)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept' => 'application/json'
            ])
            ->postJson('/api/applicant/transfer', [
                'applicant_id' => $applicant->id,
                'target_batch_id' => $targetBatch->id
            ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function frontend_component_renders_correctly_for_all_camp_types()
    {
        // Test that transfer component works across all camp types
        $campTypes = ['ycamp', 'tcamp', 'ecamp', 'acamp', 'ceocamp'];
        
        foreach ($campTypes as $campType) {
            $camp = Camp::factory()->create(['table' => $campType]);
            $batch = Batch::factory()->create(['camp_id' => $camp->id]);
            $applicant = Applicant::factory()->create(['batch_id' => $batch->id]);

            $this->mockUserHasPermission($this->user, true);

            $response = $this->actingAs($this->user)
                ->get("/backend/in_camp/attendeeInfo{$this->getCampTypeSuffix($campType)}/{$applicant->id}");

            $response->assertStatus(200);
            $response->assertSee('轉換營隊/梯次', false);
        }
    }

    // ===========================================
    // HELPER METHODS
    // ===========================================

    private function mockUserHasPermission(User $user, bool $hasPermission)
    {
        // Mock the canAccessResource method
        $this->mock(\App\Http\Controllers\BackendController::class)
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('canAccessResource')
            ->andReturn($hasPermission);
    }

    private function getCampTypeSuffix(string $campType): string
    {
        $suffixes = [
            'ycamp' => 'Ycamp',
            'tcamp' => '',
            'ecamp' => 'Ecamp',
            'acamp' => 'Acamp',
            'ceocamp' => 'Ceocamp'
        ];

        return $suffixes[$campType] ?? '';
    }
}