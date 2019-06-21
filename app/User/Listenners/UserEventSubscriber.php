<?php

namespace App\User\Listenners;

use App\User\User;
use App\User\Events\UserBanned;
use App\User\Events\UserDeleted;
use App\User\Events\UserUpdated;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use App\User\Events\UserSuspendAccount;
use Illuminate\Support\Facades\Request;
use App\User\Events\UserPasswordUpdated;
use App\User\Events\UserSuspendedAccount;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\User\Notifications\UserBannedNotification;
use App\User\Notifications\UserCreatedNotification;
use App\User\Notifications\AccountSuspendedNotification;
use App\User\Notifications\UserCreatedAdminNotification;
use App\User\Notifications\UserPasswordUpdatedNotification;

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

        $events->listen(
            'App\User\Events\UserUpdated',
            'App\User\Listenners\UserEventSubscriber@handleUserUpdated'
        );
        $events->listen(
            'App\User\Events\UserDeleted',
            'App\User\Listenners\UserEventSubscriber@handleUserDeleted'
        );

        $events->listen(
            'App\User\Events\UserSuspendedAccount',
            'App\User\Listenners\UserEventSubscriber@handleUserSuspendAccount'
        );

        $events->listen(
            'App\User\Events\UserPasswordUpdated',
            'App\User\Listenners\UserEventSubscriber@handleUserPasswordUpdated'
        );

        $events->listen(
            'App\User\Events\UserBanned',
            'App\User\Listenners\UserEventSubscriber@handleUserBanned'
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
     * @param Logout $event
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
     * @param Registered $event
     */
    public function handleUserRegistered(Registered $event)
    {
        $admins = $this->getAdmins();
        $when = now()->addSeconds(8);
        $event->user->notify((new UserCreatedNotification($event->user))->delay($when));

        foreach ($admins as $admin) {
            $when = $when->addSeconds(5);
            $admin->notify((new UserCreatedAdminNotification($event->user))->delay($when));
        }
    }

    public function handleSendEmailVerificationNotification(Registered $event)
    {
        if ($event->user instanceof MustVerifyEmail && !$event->user->hasVerifiedEmail()) {
            $event->user->sendEmailVerificationNotification();
        }
    }

    /**
     * Handle the User login event.
     *
     * @param UserUpdated $event
     */
    public function handleUserUpdated(UserUpdated $event)
    {
    }

    /**
     * Handle the User login event.
     *
     * @param UserDeleted $event
     */
    public function handleUserDeleted(UserDeleted $event)
    {
    }

    /**
     * Handle the User login event.
     *
     * @param UserSuspendAccount $event
     */
    public function handleUserSuspendAccount(UserSuspendedAccount $event)
    {
        $event->user->notify((new AccountSuspendedNotification($event->user))
                        ->delay(now()->addSeconds(10)));
    }

    public function handleUserPasswordUpdated(UserPasswordUpdated $event)
    {
        $event->user->notify((new UserPasswordUpdatedNotification($event->user))
                ->delay(now()->addSeconds(10)));
    }

    public function handleUserBanned(UserBanned $event)
    {
        $event->user->notify((new UserBannedNotification($event->user))
                ->delay(now()->addSeconds(10)));
    }

    protected function getAdmins()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('slug', '=', 'admin');
        })->get();
    }
}
