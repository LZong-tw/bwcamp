<?php

namespace App\Services\Permission;

class PermissionConstants
{
    // Permission ranges
    const RANGE_ALL = 0;
    const RANGE_VOLUNTEER_LARGE_GROUP = 1;
    const RANGE_LEARNER_GROUP = 2;
    const RANGE_PERSON = 3;

    // Actions
    const ACTION_READ = 'read';
    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';

    // Contexts
    const CONTEXT_VCAMP = 'vcamp';
    const CONTEXT_VCAMP_EXPORT = 'vcampExport';
    const CONTEXT_ONLY_CHECK_AVAILABILITY = 'onlyCheckAvailability';

    // Position patterns
    const POSITION_CARING_GROUP = '%關懷小組%';
    const POSITION_CARING_SERVICE = '%關懷服務組%';
    const POSITION_CARING_SHORT = '%關服組%';

    // Cache settings
    const CACHE_TTL = 600; // 10 minutes
    const CACHE_PREFIX = 'permission_';
}