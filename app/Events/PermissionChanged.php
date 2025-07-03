<?php

namespace App\Events;

use App\Models\User;
use App\Models\Camp;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public ?Camp $camp;
    public string $changeType;
    public array $changeDetails;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, ?Camp $camp, string $changeType, array $changeDetails = [])
    {
        $this->user = $user;
        $this->camp = $camp;
        $this->changeType = $changeType;
        $this->changeDetails = $changeDetails;
    }
}