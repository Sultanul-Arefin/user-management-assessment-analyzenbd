<?php

namespace App\Listeners;

use App\Events\UserCreatedEvent;
use App\Models\UserAddress;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreatedEvent $event)
    {
        // Retrieve the user from the event
        $user = $event->user;

        $address = UserAddress::create([
            'user_id' => $user->id,
            'address' => request()->address
        ]);
    }
}
