<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApplicantTransferSimpleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function applicant_transfer_api_endpoint_exists()
    {
        // Test that the API endpoints are registered
        $routes = app('router')->getRoutes();
        $routeNames = [];
        
        foreach ($routes as $route) {
            if ($route->getName()) {
                $routeNames[] = $route->getName();
            }
        }
        
        $this->assertContains('api.applicant.transfer', $routeNames);
        $this->assertContains('api.batches.available', $routeNames);
    }

    /** @test */
    public function applicant_transfer_service_class_exists()
    {
        // Test that the service class exists and is instantiable
        $this->assertTrue(class_exists('App\Services\ApplicantTransferService'));
        
        $service = new \App\Services\ApplicantTransferService();
        $this->assertInstanceOf(\App\Services\ApplicantTransferService::class, $service);
    }

    /** @test */
    public function applicant_transfer_service_has_required_methods()
    {
        // Test that the service has all required methods
        $service = new \App\Services\ApplicantTransferService();
        
        $this->assertTrue(method_exists($service, 'transferApplicant'));
    }

    /** @test */
    public function backend_controller_has_transfer_methods()
    {
        // Test that the controller has the required methods
        $controller = new \App\Http\Controllers\BackendController();
        
        $this->assertTrue(method_exists($controller, 'transferApplicant'));
        $this->assertTrue(method_exists($controller, 'getAvailableBatches'));
    }

    /** @test */
    public function applicant_transfer_views_exist()
    {
        // Test that the view components exist
        $this->assertTrue(view()->exists('components.transfer.applicant-transfer-button'));
        $this->assertTrue(view()->exists('components.transfer.applicant-transfer-modal'));
    }
}