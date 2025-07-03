<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Camp;
use App\Services\Permission\CacheInvalidationService;
use Illuminate\Console\Command;

class ClearPermissionCache extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'permission:clear-cache 
                            {--user= : Clear cache for specific user ID}
                            {--camp= : Clear cache for specific camp ID}
                            {--all : Clear all permission cache}
                            {--stats : Show cache statistics}';

    /**
     * The console command description.
     */
    protected $description = 'Clear permission cache with various options';

    private CacheInvalidationService $cacheInvalidation;

    /**
     * Create a new command instance.
     */
    public function __construct(CacheInvalidationService $cacheInvalidation)
    {
        parent::__construct();
        $this->cacheInvalidation = $cacheInvalidation;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('stats')) {
            $this->showCacheStats();
            return 0;
        }

        if ($this->option('all')) {
            return $this->clearAllCache();
        }

        if ($userId = $this->option('user')) {
            return $this->clearUserCache($userId);
        }

        if ($campId = $this->option('camp')) {
            return $this->clearCampCache($campId);
        }

        $this->info('No specific option provided. Use --help to see available options.');
        return 0;
    }

    /**
     * Clear all permission cache
     */
    private function clearAllCache(): int
    {
        $this->info('Clearing all permission cache...');
        
        try {
            \Cache::flush();
            $this->info('✅ All cache cleared successfully.');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to clear cache: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Clear cache for specific user
     */
    private function clearUserCache(int $userId): int
    {
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("❌ User with ID {$userId} not found.");
            return 1;
        }

        $this->info("Clearing cache for user: {$user->name} (ID: {$userId})");

        try {
            $this->cacheInvalidation->invalidateAllUserCache($user);
            $this->info('✅ User cache cleared successfully.');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to clear user cache: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Clear cache for specific camp
     */
    private function clearCampCache(int $campId): int
    {
        $camp = Camp::find($campId);
        
        if (!$camp) {
            $this->error("❌ Camp with ID {$campId} not found.");
            return 1;
        }

        $this->info("Clearing cache for camp: {$camp->name} (ID: {$campId})");

        try {
            $this->cacheInvalidation->invalidateCampCache($camp);
            $this->info('✅ Camp cache cleared successfully.');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to clear camp cache: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Show cache statistics
     */
    private function showCacheStats(): void
    {
        $this->info('📊 Permission Cache Statistics');
        $this->line('');

        try {
            $stats = $this->cacheInvalidation->getCacheStats();

            if (isset($stats['error'])) {
                $this->error('❌ ' . $stats['error']);
                return;
            }

            $this->table(['Metric', 'Value'], [
                ['Cache Store', $stats['cache_store'] ?? 'unknown'],
                ['Total Permission Keys', $stats['total_permission_cache_keys'] ?? 'N/A'],
                ['User Permission Keys', $stats['user_permission_cache_keys'] ?? 'N/A'],
                ['Last Updated', $stats['timestamp'] ?? 'unknown']
            ]);

            if (isset($stats['message'])) {
                $this->warn('⚠️  ' . $stats['message']);
            }

        } catch (\Exception $e) {
            $this->error('❌ Failed to get cache stats: ' . $e->getMessage());
        }
    }
}