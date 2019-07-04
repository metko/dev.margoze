<?php

namespace Tests\Feature;

use App\User\User;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\User\Notifications\UserBannedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User\Notifications\UserCreatedNotification;
use App\User\Notifications\AccountSuspendedNotification;
use App\User\Notifications\UserPasswordUpdatedNotification;

class ManageUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_account()
    {
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
    public function a_new_registred_user_send_email_notification_to_user_and_store_in_DB()
    {
        //Notification::fake();
        //$this->debug();
        $user = $this->createUser();
        Notification::assertSentTo(
            $user,
            UserCreatedNotification::class,
            function ($notification, $channels) use ($user) {
                return $notification->user->id === $user->id;
            }
        );
    }

    /** @test */
    public function a_member_can_update_his_account_information()
    {
        $attributes = $this->getAttributes();
        $this->debug();
        $this->actingAs($this->user)
            ->post(route('users.update', $this->user->id), $attributes);
        $this->assertDatabaseHas('users', $attributes);
    }

    /** @test */
    public function a_member_cant_update_an_account_information_who_belongs_to_another()
    {
        $attributes = $this->getAttributes();
        $this->actingAs($this->user2)
            ->post(route('users.update', $this->user->id), $attributes);
        $this->assertDatabaseMissing('users', $attributes);
    }

    /** @test */
    public function a_user_can_suspend_his_account()
    {
        $now = now();
        Notification::fake();
        $this->actingAs($this->user)
            ->delete(route('users.suspend', $this->user->id));
        $this->assertTrue($this->user->fresh()->isSuspended());
    }

    /** @test */
    public function suspended_an_account_send_an_email_notification_with_link()
    {
        Notification::fake();
        $this->debug();
        $this->actingAs($this->user)
            ->delete(route('users.suspend', $this->user->id));
        $user = $this->user;
        Notification::assertSentTo(
                $user,
                AccountSuspendedNotification::class,
                function ($notification, $channels) use ($user) {
                    // $mailData = $notification->toMail($user)->toArray();
                    return $notification->user->id === $user->id;
                }
            );
    }

    /** @test */
    public function banned_an_account_send_an_email_notification()
    {
        Notification::fake();
        $this->user->ban();
        $user = $this->user;
        Notification::assertSentTo(
                $user,
                UserBannedNotification::class,
                function ($notification, $channels) use ($user) {
                    // $mailData = $notification->toMail($user)->toArray();
                    return $notification->user->id === $user->id;
                }
            );
    }

    /** @test */
    public function a_user_can_update_his_password()
    {
        $password = 'newpassword';
        $attributes = [
            'password' => $password,
            'password_confirmation' => "$password",
        ];
        $this->actingAs($this->user)
                ->post(route('users.update.password', $this->user->id), $attributes);
        $this->assertTrue(Hash::check($password, $this->user->fresh()->password));
    }

    /** @test */
    public function updated_a_password_generate_a_notification()
    {
        Notification::fake();
        $password = 'newpassword';
        $attributes = [
            'password' => $password,
            'password_confirmation' => "$password",
        ];
        $this->actingAs($this->user)
                ->post(route('users.update.password', $this->user->id), $attributes);
        $user = $this->user;
        Notification::assertSentTo(
                    $user,
                    UserPasswordUpdatedNotification::class,
                    function ($notification, $channels) use ($user) {
                        return $notification->user->id === $user->id;
                    }
            );
    }

    /** @test */
    public function a_new_password_must_be_validated()
    {
        $password = 'newpassword';
        $attributes = [
            'password' => $password,
            'password_confirmation' => 'dddd',
        ];
        $this->actingAs($this->user)
                ->post(route('users.update.password', $this->user->id), $attributes);
        $this->assertFalse(Hash::check($password, $this->user->fresh()->password));
        $attributes = [
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ];
        $this->actingAs($this->user)
                ->post(route('users.update.password', $this->user->id), $attributes);
        $this->assertFalse(Hash::check($password, $this->user->fresh()->password));
        $attributes = [
            'password' => 'pass',
            'password_confirmation' => '',
        ];
        $this->actingAs($this->user)
                ->post(route('users.update.password', $this->user->id), $attributes);
        $this->assertFalse(Hash::check($password, $this->user->fresh()->password));
    }

    /** @test */
    public function a_user_can_be_banned()
    {
        Notification::fake();
        $this->user->ban();
        $this->assertTrue($this->user->fresh()->isbanned());
    }

    public function getAttributes()
    {
        return [
            'username' => 'PatiDich',
            'email' => 'pati@pati.com',
            'first_name' => 'Patricia',
            'last_name' => 'Dichtchekenian',
            'biography' => 'Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI, quando um impressor desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de modelos de tipos. Lorem Ipsum sobreviveu não só a cinco séculos, como também ao salto para a editoração eletrônica, permanecendo essencialmente inalterado.',
            'adress_1' => '1000 rua ferreira Araujo',
            'sector' => 'Pinheiros',
            'postal' => '97400',
            'city' => 'Sao paulo',
            'phone_1' => '0692 30 37 76',
            'date_of_birth' => Carbon::createFromFormat('Y-m-d H', '1975-05-21 22')->toDateTimeString(),
        ];
    }

    // TODO A VER
    // /** @test */
    // public function a_new_registred_user_send_email_notification_to_admin()
    // {
    //     Notification::fake();
    //     $this->debug();
    //     $user = $this->createUser();
    //     Notification::assertSentTo(
    //         $this->admin,
    //         UserCreatedAdminNotification::class,
    //         function ($notification, $channels) use ($user) {
    //             return $notification->user->id === $user->id;
    //         }
    //     );
    // }
}
