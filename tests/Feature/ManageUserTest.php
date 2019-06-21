<?php

namespace Tests\Feature;

use App\User\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User\Notifications\UserCreatedNotification;
use App\User\Notifications\SendEmailUserCreatedNotification;

class ManageUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_account()
    {
        $this->debug();
        $user = $this->createUser();
        $this->assertDatabaseHas('users', ['username' => 'toto']);
    }

    /** @test */
    public function a_user_just_logged_save_the_last_connexion_time_and_ip()
    {
        $time = date(now());
        $ip = '127.0.0.1';
        $this->signIn();
        $this->assertSame($time, $this->user->fresh()->last_signin_at);
        $this->assertSame($ip, $this->user->fresh()->last_ip);
    }

    /** @test */
    public function a_user_just_loggout_save_the_last_connexion_time()
    {
        $time = date(now());
        $this->signIn();
        $this->post('logout');
        $this->assertSame($time, $this->user->fresh()->last_active_at);
    }

    /** @test */
    public function a_new_registred_user_send_email_notification_to_admin_and_store_in_DB()
    {
        Notification::fake();
        $user = $this->createUser();
        Notification::assertSentTo(
            $this->admin,
            SendEmailUserCreatedNotification::class,
            function ($notification, $channels) use ($user) {
                return $notification->user->id === $user->id;
            }
        );
        Notification::assertSentTo(
            $user,
            UserCreatedNotification::class,
            function ($notification, $channels) use ($user) {
                return $notification->user->id === $user->id;
            }
        );
    }

    public function createUser()
    {
        $this->post('register', [
            'username' => 'toto',
            'email' => 'toto@gmail.com',
            'password' => 'leopoldine',
            'password_confirmation' => 'leopoldine',
        ]);

        return User::all()->last();
    }
}
