# Applicant Transfer Test Suite Documentation

## Overview

This document describes the comprehensive end-to-end test suite for the Applicant Transfer feature in the Buddhist camp management system.

## Test Coverage

### 1. Core Service Tests (`ApplicantTransferTest.php`)
- **Basic Transfer Logic**: Same camp type vs cross camp type transfers
- **Data Validation**: Applicant existence, batch existence, permission checks
- **Data Preservation**: Handling of special camp data during transfers
- **Relationship Management**: Clearing of group, number, and carer assignments
- **Transaction Safety**: Database transaction rollback on failure
- **Audit Logging**: Comprehensive operation logging

### 2. API Integration Tests (`ApplicantTransferTest.php`)
- **Authentication**: Ensuring only authenticated users can access endpoints
- **Request Validation**: Proper validation of required fields
- **Response Format**: Consistent API response structure
- **Permission-based Access**: Different access levels for different users
- **Concurrent Requests**: Handling of simultaneous transfer attempts

### 3. Frontend Integration Tests (`ApplicantTransferFrontendTest.php`)
- **UI Component Rendering**: Transfer button display based on permissions
- **Modal Interactions**: Batch selection and form submission
- **AJAX Requests**: Proper handling of asynchronous requests
- **Error Handling**: User-friendly error messages
- **CSRF Protection**: Security token validation
- **Cross-camp Compatibility**: UI works for all camp types

### 4. End-to-End Workflow Tests
- **Complete Transfer Workflow**: Full user journey from UI to database
- **Permission-based Access Control**: Role-based feature access
- **Data Integrity**: Comprehensive data consistency checks
- **Cross-camp Type Transfers**: Special handling for different camp types
- **Fee Recalculation**: Automatic fee updates based on target camp
- **Audit Trail**: Complete operation tracking

## Test Scenarios

### 1. Same Camp Type Transfer (e.g., 大專營 A梯 → 大專營 B梯)
```php
// Preserves all special camp data
// Resets status fields (admitted, paid, attend)
// Clears group/number assignments
// Updates batch_id and fee
```

### 2. Cross Camp Type Transfer (e.g., 大專營 → 教師營)
```php
// Preserves basic applicant data
// Clears all special camp data
// Resets status fields
// Adds transfer note in expectation field
// Clears group/number/carer assignments
```

### 3. Permission-based Access
```php
// Admin users: Full access to all camps
// Limited users: Access only to permitted camps
// Unauthorized users: No transfer functionality visible
```

### 4. Error Handling
```php
// Invalid applicant ID
// Invalid target batch ID
// Transfer to same batch
// Transfer to past/started batch
// Insufficient permissions
// Database transaction failures
```

## Running the Tests

### All Transfer Tests
```bash
# Run all applicant transfer tests
docker exec -it bwcamp ./vendor/bin/pest tests/Feature/ApplicantTransferTest.php
docker exec -it bwcamp ./vendor/bin/pest tests/Feature/ApplicantTransferFrontendTest.php

# Using PHPUnit
docker exec -it bwcamp ./vendor/bin/phpunit tests/Feature/ApplicantTransferTest.php
docker exec -it bwcamp ./vendor/bin/phpunit tests/Feature/ApplicantTransferFrontendTest.php
```

### Specific Test Categories
```bash
# Core service tests only
docker exec -it bwcamp ./vendor/bin/pest --filter="transfer.*applicant.*between"

# API integration tests only
docker exec -it bwcamp ./vendor/bin/pest --filter="api.*transfer"

# Frontend tests only
docker exec -it bwcamp ./vendor/bin/pest --filter="frontend"

# End-to-end tests only
docker exec -it bwcamp ./vendor/bin/pest --filter="end_to_end"

# Permission tests only
docker exec -it bwcamp ./vendor/bin/pest --filter="permission"
```

### Individual Test Methods
```bash
# Specific test methods
docker exec -it bwcamp ./vendor/bin/pest --filter="end_to_end_api_transfer_with_complete_workflow"
docker exec -it bwcamp ./vendor/bin/pest --filter="end_to_end_permission_based_access_control"
docker exec -it bwcamp ./vendor/bin/pest --filter="end_to_end_cross_camp_data_preservation"
```

## Test Data Setup

### Required Models and Factories
- `User::factory()` - For authentication and permission testing
- `Camp::factory()` - For different camp types (ycamp, tcamp, ecamp, etc.)
- `Batch::factory()` - For transfer target/source batches
- `Applicant::factory()` - For transfer subjects
- Special camp models (Ycamp, Tcamp, etc.) - For cross-camp testing

### Database Tables
The tests automatically create required tables:
- `ycamp`, `tcamp`, `ecamp`, `acamp`, `ceocamp` - Special camp data
- Standard Laravel tables for users, camps, batches, applicants

## Expected Test Results

### Success Criteria
- ✅ All basic transfer operations complete successfully
- ✅ Data integrity maintained across all scenarios
- ✅ Proper permission enforcement
- ✅ Complete audit trail generation
- ✅ Frontend components render correctly
- ✅ API responses follow consistent format
- ✅ Error handling provides meaningful feedback

### Coverage Metrics
- **Service Layer**: 100% coverage of ApplicantTransferService
- **API Layer**: 100% coverage of transfer endpoints
- **Frontend**: All UI components and interactions
- **Database**: All data transformations and relationships
- **Security**: Permission checks and authentication

## Troubleshooting

### Common Issues
1. **Missing Database Tables**: Tests automatically create required special camp tables
2. **Permission Mocking**: Frontend tests use mocked permission checks
3. **Date Dependencies**: Tests use Carbon for consistent date handling
4. **Transaction Rollback**: Failed tests should not affect database state

### Debug Commands
```bash
# Run tests with verbose output
docker exec -it bwcamp ./vendor/bin/pest -v tests/Feature/ApplicantTransferTest.php

# Run specific failing test
docker exec -it bwcamp ./vendor/bin/pest --filter="specific_test_name" -v

# Check test database state
docker exec -it bwcamp php artisan tinker
```

## Maintenance

### Adding New Camp Types
1. Add new camp type to `createMultipleCampsWithBatches()` helper
2. Add table creation in `setupTestDatabase()` method
3. Add new test cases in `runCrossTypeTransferTest()`

### Extending Test Coverage
1. Add new test methods following naming convention: `test_*` or `/** @test */`
2. Use descriptive test names that explain the scenario
3. Follow AAA pattern: Arrange, Act, Assert
4. Include both positive and negative test cases

## Integration with CI/CD

### Automated Testing
```bash
# Add to CI pipeline
php artisan test --filter="ApplicantTransfer"
./vendor/bin/pest tests/Feature/ApplicantTransfer*.php
```

### Test Reporting
- Tests generate detailed logs for debugging
- Coverage reports help identify untested code paths
- Failed tests provide specific error messages and context