<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogUserActivity
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(object $event): void
    {
        // Determine the action based on the event type
        $action = $event instanceof Login ? 'login' : 'logout';

        // Create a log entry
        Log::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'details' => request()->ip(), // Store the IP address or other details as needed
        ]);
    }
}
