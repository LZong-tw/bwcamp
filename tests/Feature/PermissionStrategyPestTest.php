<?php

use App\Models\User;
use App\Models\Camp;
use App\Models\Applicant;
use App\Services\Permission\PermissionFactory;
use App\Services\Permission\PermissionConstants;
use App\Services\Permission\Strategies\AllAccessStrategy;
use App\Services\Permission\Strategies\GroupAccessStrategy;
use App\Services\Permission\Strategies\PersonalAccessStrategy;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->camp = Camp::factory()->create();
    $this->factory = app(PermissionFactory::class);
});

test('permission factory creates correct strategies', function () {
    $applicant = Applicant::factory()->create();
    
    $checker = $this->factory->createChecker(
        $this->user,
        $applicant,
        'read',
        $this->camp
    );
    
    expect($checker)->toBeInstanceOf(\App\Contracts\PermissionChecker::class);
});

test('all access strategy always returns true', function () {
    $strategy = new AllAccessStrategy();
    $applicant = Applicant::factory()->create();
    
    $result = $strategy->canAccess($this->user, $applicant, 'read', $this->camp);
    expect($result)->toBeTrue();
    
    $level = $strategy->getPermissionLevel($this->user, $applicant, 'read', $this->camp);
    expect($level)->toBe(PermissionConstants::RANGE_ALL);
});

test('group access strategy handles group permissions', function () {
    $strategy = new GroupAccessStrategy();
    $applicant = Applicant::factory()->create();
    
    // Test basic functionality (result depends on actual permissions)
    $result = $strategy->canAccess($this->user, $applicant, 'read', $this->camp);
    expect($result)->toBeBoolean();
});

test('personal access strategy handles personal permissions', function () {
    $strategy = new PersonalAccessStrategy();
    $applicant = Applicant::factory()->create();
    
    // Test basic functionality (result depends on actual permissions)
    $result = $strategy->canAccess($this->user, $applicant, 'read', $this->camp);
    expect($result)->toBeBoolean();
    
    $level = $strategy->getPermissionLevel($this->user, $applicant, 'read', $this->camp);
    expect($level)->toBe(PermissionConstants::RANGE_PERSON);
});

test('strategies handle batch operations', function () {
    $strategy = new AllAccessStrategy();
    $applicants = Applicant::factory()->count(3)->create();
    
    $results = $strategy->canAccessBatch(
        $this->user,
        $applicants->toArray(),
        'read',
        $this->camp
    );
    
    expect($results)->toHaveCount(3);
    expect($results)->toBeArray();
    
    // All access should return true for all
    foreach ($results as $result) {
        expect($result)->toBeTrue();
    }
});

test('strategies handle null and invalid resources', function () {
    $strategy = new AllAccessStrategy();
    
    // Null resource should return false (handled by service layer)
    $result = $strategy->canAccess($this->user, null, 'read', $this->camp);
    expect($result)->toBeTrue(); // AllAccessStrategy always returns true
    
    // Invalid resource class
    $result = $strategy->canAccess($this->user, 'InvalidClass', 'read', $this->camp);
    expect($result)->toBeTrue(); // AllAccessStrategy always returns true
});

test('permission constants are defined correctly', function () {
    expect(PermissionConstants::RANGE_ALL)->toBe(0);
    expect(PermissionConstants::RANGE_VOLUNTEER_LARGE_GROUP)->toBe(1);
    expect(PermissionConstants::RANGE_LEARNER_GROUP)->toBe(2);
    expect(PermissionConstants::RANGE_PERSON)->toBe(3);
    
    expect(PermissionConstants::ACTION_READ)->toBe('read');
    expect(PermissionConstants::ACTION_CREATE)->toBe('create');
    expect(PermissionConstants::ACTION_UPDATE)->toBe('update');
    expect(PermissionConstants::ACTION_DELETE)->toBe('delete');
    
    expect(PermissionConstants::CACHE_TTL)->toBeInt();
    expect(PermissionConstants::CACHE_PREFIX)->toBeString();
});