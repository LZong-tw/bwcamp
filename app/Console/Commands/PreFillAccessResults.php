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
    protected $signature = 'access:prefill-results';
    protected $description = 'Pre-fill user access results into the user_can_access_resource_or_not_results table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::all();
        $camps = Camp::all();
        $resources = collect([ // List all resource types
            Applicant::class,
            User::class,
            Volunteer::class,
        ]);
        $actions = ["assign", "read", "create", "update", "delete"]; // List all actions
        $contexts = ['vcamp', 'vcampExport', 'onlyCheckAvailability']; // List all contexts

        foreach ($users as $user) {
            foreach ($camps as $camp) {
                foreach ($resources as $resourceClass) {
                    $resources = $resourceClass::all();
                    foreach ($resources as $resource) {
                        foreach ($actions as $action) {
                            foreach ($contexts as $context) {
                                $accessible = $user->getAccessibleResult($resource, $action, $camp, $context);

                                // Check if entry already exists to prevent duplicate records
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
                            }
                        }
                    }
                }
            }
        }

        $this->info('Pre-filled access results successfully.');
    }
}
