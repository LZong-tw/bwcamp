# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a comprehensive Buddhist camp management system (福智營隊管理系統) for managing various educational camps including youth camps, teacher camps, business camps, and volunteer programs. The system handles the complete lifecycle from registration to check-in and post-camp management.

## Development Commands

### Core Laravel Commands
```bash
# Database migrations
php artisan migrate

# Clear and cache configuration
php artisan config:cache

# Queue management (run as su)
php artisan queue:restart

# Testing
# Local testing (if running directly)
./vendor/bin/phpunit
./vendor/bin/pest

# Docker environment testing
docker exec -it bwcamp ./vendor/bin/phpunit
docker exec -it bwcamp ./vendor/bin/pest

# Run specific test files
docker exec -it bwcamp ./vendor/bin/pest tests/Feature/ApplicantTransferTest.php
docker exec -it bwcamp ./vendor/bin/phpunit tests/Feature/ApplicantTransferTest.php

# Run specific test methods
docker exec -it bwcamp ./vendor/bin/pest --filter="test_name"

# Frontend development
npm run dev         # Development server with Vite
npm run build       # Production build
```

### Application Management
```bash
# After each update, run in sequence:
php artisan migrate
php artisan config:cache
php artisan queue:restart  # As su/root user
```

## Architecture Overview

### Camp Type System
The application uses a multi-table strategy for different camp types:
- **Ycamp** - College/University Student Camps (大專營)
- **Tcamp** - Teacher Life Growth Camps (教師生命成長營)  
- **Ecamp** - Business/Enterprise Camps (企業營)
- **Ceocamp** - CEO/Executive Camps (菁英營)
- **Acamp** - Youth Excellence Camps (卓越青年營)
- **Icamp** - Prayer/Pilgrimage Groups (祈願請法團)
- Other specialized camps: Hcamp, Wcamp, Scamp, Lrcamp, Utcamp, Actcamp

Each camp type has its own Model and form structure with shared `applicants` table for participant data.

### Key Service Classes
- **ApplicantService** - Handles registration, payment status, sign-in/out
- **CampDataService** - Camp configuration, form processing, regional logic
- **BackendService** - Administrative operations and data management
- **MailService** - Email notifications and communication
- **PaymentflowService** - Payment processing with bank barcode generation
- **UserService** - User management and authentication

### Permission System
Complex role-based access control using Laratrust:
- Hierarchical permissions with camp/batch/group/individual scopes
- Regional access control for geographic organization
- Dynamic resource-action-range permissions
- Cached permission evaluation for performance

### Data Organization
- **Camps** - Main camp entities with yearly variants
- **Batches** - Sessions within camps (梯次)
- **Regions** - Geographic organization areas
- **Groups & Numbers** - Participant organization
- **CampOrg** - Organizational structure for volunteer roles

## Key Laravel Features Used

### Packages
- **Laratrust** - Role and permission management
- **Laravel Excel** - Data import/export functionality  
- **Laravel Telescope** - Application monitoring and debugging
- **Livewire** - Interactive frontend components
- **Google Sheets integration** - External data synchronization
- **Milon Barcode** - Payment barcode generation
- **Laravel Media Library** - File upload management

### Database Patterns
- Soft deletes for applicant data preservation
- Polymorphic relationships for flexible camp associations
- Complex foreign key relationships between camps, batches, and participants
- Database observers for automated data integrity

### Queue System
Email notifications and heavy operations use Laravel's queue system:
- Queued jobs for email sending (SendApplicantMail, SendCheckInMail)
- Background processing for data imports/exports
- Resource access verification jobs

## Testing Structure

- **Feature Tests** - Located in `tests/Feature/` for integration testing
- **Unit Tests** - Located in `tests/Unit/` for component testing  
- **Pest Framework** - Modern testing framework alongside PHPUnit
- **Test Environment** - Uses SQLite in-memory database for testing

Run tests with:
```bash
./vendor/bin/phpunit  # Traditional PHPUnit
./vendor/bin/pest     # Modern Pest framework
```

## Domain-Specific Features

### Payment Integration
- Bank virtual account system for payments
- Multi-payment method support (convenience stores, banks)
- Early bird pricing logic with deadline management
- Automatic payment verification and status updates

### Regional Management  
- Geographic-based organization across Taiwan
- Complex regional assignment logic varies per camp type
- Region-specific volunteer permissions and access control

### Buddhist Camp Features
- Dharma class tracking (廣論研討班) for participant history
- Buddhist activity participation records
- Volunteer care system with detailed contact logging
- Traditional Chinese interface with Buddhist terminology

### Communication System
- Queued email system for reliable delivery
- Multiple notification types: admission, rejection, check-in
- Contact logging for participant care tracking
- Custom mail composition tools for administrators

## File Structure Notes

- **Models** - Each camp type has dedicated Model class
- **Controllers** - Organized by functionality (Backend, Camp, CheckIn, etc.)
- **Services** - Business logic separated into service classes
- **Views** - Blade templates organized by feature areas
- **Migrations** - Extensive migration history for camp system evolution

## Important Configuration

- **Queue Driver** - Configured for reliable email delivery
- **Cache System** - Used for permission checking and statistics
- **Media Storage** - Configured for participant file uploads
- **Google Sheets API** - For external data integration

## Development Notes

- Always run migrations after pulling updates
- Queue system requires restart after code changes
- Permission changes require cache clearing
- Regional logic varies significantly between camp types
- Payment processing requires careful testing with bank integration