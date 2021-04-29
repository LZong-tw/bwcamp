<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        //
        $levels = '';
        $camp_ids = '';
        $function_ids = '';
        foreach($event->user->getPermission('all') as $role){
            $levels .= $role->level . ", ";
            $camp_ids .= $role->camp_id . ", ";
            $function_ids .= $role->function_id . ", ";
        }
        session(
            [
                'levels' => $levels,
                'camp_ids' => $camp_ids,
                'function_ids' => $function_ids,
            ]
        );
    }
}
