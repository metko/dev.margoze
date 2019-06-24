<?php

namespace Tests;

use App\User\User;
use App\Demand\Demand;
use Metko\Metkontrol\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        error_log('setup testcase');
        $this->user = factory(User::class)->create(['username' => 'user1', 'email' => 'user1@gmail.com', 'password' => Hash::make('password')]);
        $this->user2 = factory(User::class)->create(['username' => 'user2', 'email' => 'user2@gmail.com', 'password' => Hash::make('password')]);
        $this->user3 = factory(User::class)->create(['username' => 'user3', 'email' => 'user3@gmail.com', 'password' => Hash::make('password')]);
        $this->admin = factory(User::class)->create(['username' => 'admin1', 'email' => 'admin1@gmail.com', 'password' => Hash::make('password')]);
        $this->admin2 = factory(User::class)->create(['username' => 'admin2', 'email' => 'admin2@gmail.com', 'password' => Hash::make('password')]);
        $this->demand = factory(Demand::class)->create(['owner_id' => $this->user->id]);
        $this->demand2 = factory(Demand::class)->create(['owner_id' => $this->user2->id]);
        $this->role = factory(Role::class)->create(['name' => 'Admin']);
        $this->role = factory(Role::class)->create(['name' => 'Member']);
        $this->user->attachRole('member');
        $this->user2->attachRole('member');
        $this->user3->attachRole('member');
        $this->admin->attachRole('admin');
        $this->admin2->attachRole('admin');
    }

    public function debug()
    {
        $this->withoutExceptionHandling();
    }

    public function SignIn()
    {
        $data = ['email' => $this->user->email, 'password' => 'password'];
        $this->post('login', $data);
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
