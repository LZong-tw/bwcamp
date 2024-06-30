<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Applicant;
use App\Models\Ucaronr;
use App\Models\Volunteer;
use App\Models\Camp;

class PreFillAccessResults extends Command
{
    protected $signature = 'access:prefill-results {user?} {camp?}';
    protected $description = 'Pre-fill user access results into the user_can_access_resource_or_not_results table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $userId = $this->argument('user');
        $campId = $this->argument('camp');

        $users = $userId ? User::where('id', $userId)->get() : User::all();
        $camps = $campId ? Camp::where('id', $campId)->get() : Camp::all();
        $resourceClasses = [Applicant::class, User::class, Volunteer::class];
        $actions = ["assign", "read", "create", "update", "delete"];
        $contexts = ['vcamp', 'vcampExport', 'onlyCheckAvailability'];

        $totalSteps = $users->count() * $camps->count() * count($resourceClasses) * count($actions) * count($contexts);

        $this->info('Starting to pre-fill access results...');
        $bar = $this->output->createProgressBar($totalSteps);
        $bar->start();

        foreach ($users as $user) {
            $this->info("\nProcessing user: {$user->id} - {$user->name}");
            foreach ($camps as $camp) {
                $this->info("\nProcessing camp: {$camp->id} - {$camp->name}");

                foreach ($resourceClasses as $resourceClass) {
                    $resources = $resourceClass::all();
                    foreach ($resources as $resource) {
                        foreach ($actions as $action) {
                            foreach ($contexts as $context) {
                                $accessible = $user->getAccessibleResult($resource, $action, $camp, $context);

                                $existing = Ucaronr::where('user_id', $user->id)
                                    ->where('camp_id', $camp->id)
                                    ->where('accessible_id', $resource->id)
                                    ->where('accessible_type', $resourceClass)
                                    ->where('context', $context)
                                    ->first();

                                if (!$existing) {
                                    Ucaronr::insert([
                                        'user_id' => $user->id,
                                        'camp_id' => $camp->id,
                                        'batch_id' => $resource->batch_id ?? null,
                                        'region_id' => $resource->region_id ?? null,
                                        'accessible_id' => $resource->id,
                                        'accessible_type' => $resourceClass,
                                        'context' => $context,
                                        'can_access' => $accessible,
                                    ]);
                                }

                                $bar->advance();
                            }
                        }
                    }
                }
            }
        }

        $bar->finish();
        $this->info('Pre-filled access results successfully.');
    }
}
