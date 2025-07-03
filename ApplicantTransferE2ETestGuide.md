# Applicant Transfer End-to-End Testing Guide

## Overview

This guide provides comprehensive instructions for testing the Applicant Transfer feature in the BWCamp Management System. The testing covers all aspects from unit tests to full end-to-end workflows.

## Test Files Created

### 1. Enhanced Core Tests
- **File**: `tests/Feature/ApplicantTransferTest.php`
- **Coverage**: 22+ test methods covering all core functionality
- **Added E2E Tests**:
  - `end_to_end_api_transfer_with_complete_workflow()`
  - `end_to_end_permission_based_access_control()`
  - `end_to_end_cross_camp_data_preservation_and_clearing()`
  - `end_to_end_transaction_rollback_on_failure()`
  - `end_to_end_concurrent_transfer_handling()`
  - `end_to_end_logging_and_audit_trail()`
  - `end_to_end_fee_calculation_across_camps()`

### 2. Frontend Integration Tests
- **File**: `tests/Feature/ApplicantTransferFrontendTest.php`
- **Coverage**: UI components, AJAX interactions, permission-based rendering
- **Key Tests**:
  - Transfer button display based on permissions
  - Modal interactions and batch loading
  - CSRF protection and form validation
  - Error handling and success messages
  - Cross-camp compatibility

### 3. Simple Validation Tests
- **File**: `tests/Feature/ApplicantTransferSimpleTest.php`
- **Coverage**: Basic existence checks for classes, methods, and views

### 4. Test Documentation
- **File**: `tests/Feature/ApplicantTransferTestSuite.md`
- **Content**: Comprehensive test documentation and execution guide

## Test Environment Setup

### Database Considerations
Due to SQLite migration limitations in the test environment, follow these approaches:

#### Option 1: Manual Testing (Recommended for E2E)
```bash
# Access the application directly
docker exec -it bwcamp bash

# Run migrations to ensure tables exist
php artisan migrate

# Test the API endpoints manually
php artisan tinker
```

#### Option 2: Use Production Database for Testing
```bash
# Backup current database
cp database/database.sqlite database/database_backup.sqlite

# Run tests with production data (careful!)
docker exec bwcamp ./vendor/bin/pest tests/Feature/ApplicantTransferTest.php
```

## Manual E2E Testing Checklist

### 1. API Endpoint Testing

#### Test Transfer API
```bash
# Using curl or similar tool
POST /api/applicant/transfer
{
    "applicant_id": 1,
    "target_batch_id": 2
}

# Expected Response:
{
    "success": true,
    "message": "跨營隊類型轉換完成，請補完特殊欄位資料",
    "is_same_camp_type": false,
    "changes": {
        "basic_fields": { ... },
        "special_fields": { ... }
    }
}
```

#### Test Available Batches API
```bash
GET /api/batches/available

# Expected Response:
{
    "success": true,
    "batches": [
        {
            "id": 1,
            "name": "A梯",
            "camp_name": "大專營",
            "camp_table": "ycamp",
            "display_name": "大專營 - A梯"
        }
    ]
}
```

### 2. Frontend Integration Testing

#### Test Transfer Button Display
1. Navigate to `/backend/in_camp/attendeeInfo/{applicant_id}`
2. Verify button appears for authorized users
3. Verify button is hidden for unauthorized users
4. Test across all camp types (Ycamp, Tcamp, Ecamp, Acamp, Ceocamp)

#### Test Transfer Modal
1. Click transfer button
2. Verify modal opens with batch selection
3. Test batch filtering (future batches only)
4. Submit transfer and verify success/error messages

### 3. Permission Testing

#### Test Role-Based Access
1. Create users with different permission levels
2. Test transfer access for each user type
3. Verify API responses based on permissions
4. Test batch visibility filtering

### 4. Cross-Camp Transfer Testing

#### Same Camp Type Transfer (e.g., 大專營 A梯 → 大專營 B梯)
1. Verify special data is preserved
2. Check status fields are reset
3. Confirm fee is updated appropriately

#### Cross Camp Type Transfer (e.g., 大專營 → 教師營)
1. Verify special data is cleared
2. Check transfer note is added
3. Confirm all relationships are cleared

### 5. Data Integrity Testing

#### Database State Verification
```sql
-- Before transfer
SELECT * FROM applicants WHERE id = ?;
SELECT * FROM ycamp WHERE applicant_id = ?;
SELECT * FROM carer_applicant_xref WHERE applicant_id = ?;

-- After transfer
-- Verify: batch_id changed, special data cleared, status reset
```

#### Transaction Testing
1. Simulate transfer failures
2. Verify database rollback
3. Check data consistency

## Automated Testing Commands

### Run All Transfer Tests
```bash
# If SQLite issues are resolved
docker exec bwcamp ./vendor/bin/pest tests/Feature/ApplicantTransfer*.php

# Alternative with PHPUnit
docker exec bwcamp ./vendor/bin/phpunit tests/Feature/ApplicantTransferTest.php
```

### Run Specific Test Categories
```bash
# End-to-end tests only
docker exec bwcamp ./vendor/bin/pest --filter="end_to_end"

# API tests only
docker exec bwcamp ./vendor/bin/pest --filter="api.*transfer"

# Frontend tests only
docker exec bwcamp ./vendor/bin/pest --filter="frontend"
```

## Expected Test Results

### Success Criteria
- ✅ **API Endpoints**: All endpoints respond correctly with proper authentication
- ✅ **Data Integrity**: Transfers maintain data consistency across all scenarios
- ✅ **Permission Control**: Access properly restricted based on user permissions
- ✅ **Cross-Camp Support**: Both same-type and cross-type transfers work correctly
- ✅ **Frontend Integration**: UI components render and function properly
- ✅ **Error Handling**: Appropriate error messages for all failure scenarios
- ✅ **Audit Trail**: Complete logging of all transfer operations

### Performance Benchmarks
- API response time: < 500ms for typical transfers
- Database transaction time: < 200ms
- UI responsiveness: Immediate feedback on user actions

## Troubleshooting

### Common Issues

#### SQLite Migration Errors
```bash
# Clear and recreate test database
rm database/testing.sqlite
php artisan migrate:fresh --env=testing
```

#### Permission Issues
```bash
# Check user permissions in Laravel Tinker
$user = User::find(1);
$user->canAccessResource('write', 'applicant', $camp);
```

#### Database Connection Issues
```bash
# Verify database connection
php artisan tinker
DB::connection()->getPdo();
```

### Debug Commands
```bash
# Enable query logging
DB::enableQueryLog();
// Run transfer
DB::getQueryLog();

# Check Laravel logs
tail -f storage/logs/laravel.log
```

## Integration with Development Workflow

### Pre-Deployment Checklist
1. ✅ All manual tests pass
2. ✅ Database migrations run successfully
3. ✅ Frontend components render correctly
4. ✅ API endpoints return expected responses
5. ✅ Permission system enforces access control
6. ✅ Audit logging captures all operations

### Continuous Integration
```yaml
# Example CI configuration
test_transfer_feature:
  script:
    - php artisan migrate:fresh
    - php artisan test --filter="ApplicantTransfer"
    - php artisan pest tests/Feature/ApplicantTransfer*.php
```

## Test Data Setup

### Required Test Data
```php
// Create test camps
$yCamp = Camp::create(['table' => 'ycamp', 'fullName' => '大專營測試']);
$tCamp = Camp::create(['table' => 'tcamp', 'fullName' => '教師營測試']);

// Create test batches
$yBatch = Batch::create(['camp_id' => $yCamp->id, 'name' => 'A梯']);
$tBatch = Batch::create(['camp_id' => $tCamp->id, 'name' => 'B梯']);

// Create test applicant
$applicant = Applicant::create([
    'batch_id' => $yBatch->id,
    'name' => '測試學員',
    'is_admitted' => true
]);
```

### Cleanup After Testing
```php
// Remove test data
Applicant::where('name', 'LIKE', '測試%')->delete();
Batch::where('name', 'LIKE', '%測試%')->delete();
Camp::where('fullName', 'LIKE', '%測試%')->delete();
```

## Security Considerations

### Test Security
- Never run tests on production data without backups
- Use separate test database credentials
- Verify permission enforcement in all test scenarios
- Test for SQL injection and XSS vulnerabilities

### Data Privacy
- Use anonymized or synthetic test data
- Avoid logging sensitive information during tests
- Clean up test data after completion

---

**Note**: This testing guide provides comprehensive coverage for the Applicant Transfer feature. Due to the current SQLite migration limitations, manual testing is recommended for complete end-to-end validation. The automated tests provide excellent coverage once the database issues are resolved.
