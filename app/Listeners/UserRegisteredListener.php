<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Registered;

class UserRegisteredListener
{
    public function handle(Registered $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $user->wallet()->create([
            'balance' => 0,
        ]);
    }
}
