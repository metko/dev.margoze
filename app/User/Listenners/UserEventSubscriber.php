<?php

namespace App\User\Listenners;

use App\User\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\User\Notifications\UserCreatedNotification;
use App\User\Notifications\UserCreatedAdminNotification;

class UserEventSubscriber
{
    public $user;

    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\User\Listenners\UserEventSubscriber@handleUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\User\Listenners\UserEventSubscriber@handleUserLogout'
        );

        $events->listen(
            'Illuminate\Auth\Events\Registered',
            'App\User\Listenners\UserEventSubscriber@handleUserRegistered'
        );

        $events->listen(
            'Illuminate\Auth\Events\Registered',
            'App\User\Listenners\UserEventSubscriber@handleSendEmailVerificationNotification'
     );
    }

    /**
     * Create the event listener.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Handle the User login event.
     *
     * @param Login $event
     */
    public function handleUserLogin(Login $event)
    {
        $event->user->update([
            'last_signin_at' => \date(now()),
            'last_ip' => Request::getClientIp(),
        ]);
    }

    /**
     * Handle the User logout event.
     *
     * @param Login $event
     */
    public function handleUserLogout(Logout $event)
    {
        $event->user->update([
            'last_active_at' => \date(now()),
         ]);
    }

    /**
     * Handle the User Registered event.
     * Send an email to all admin role.
     *
     * @param Login $event
     */
    public function handleUserRegistered(Registered $event)
    {
        $admin = User::whereHas('roles', function ($query) {
            $query->where('slug', '=', 'admin');
        })->get();
        $when = now()->addSeconds(8);
        $event->user->notify((new UserCreatedNotification($event->user))->delay($when));
        foreach ($admin as $ad) {
            $when = $when->addSeconds(5);
            Notification::route('mail', $ad->email)
                        ->notify((new UserCreatedAdminNotification($event->user))->delay($when));
        }
    }

    public function handleSendEmailVerificationNotification(Registered $event)
    {
        if ($event->user instanceof MustVerifyEmail && !$event->user->hasVerifiedEmail()) {
            $event->user->sendEmailVerificationNotification();
        }
    }
}
