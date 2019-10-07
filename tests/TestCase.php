<?php

namespace Tests;

use App\User\User;
use App\Demand\Demand;
use App\Category\Category;
use App\Candidature\Candidature;
use Metko\Metkontrol\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        Queue::fake();
        //error_log('setup testcase');
        $this->user = factory(User::class)->create(['username' => 'user1', 'email' => 'user1@gmail.com', 'password' => Hash::make('password')]);
        $this->user2 = factory(User::class)->create(['username' => 'user2', 'email' => 'user2@gmail.com', 'password' => Hash::make('password')]);
        $this->user3 = factory(User::class)->create(['username' => 'user3', 'email' => 'user3@gmail.com', 'password' => Hash::make('password')]);
        $this->admin = factory(User::class)->create(['username' => 'admin1', 'email' => 'admin1@gmail.com', 'password' => Hash::make('password')]);
        $this->admin2 = factory(User::class)->create(['username' => 'admin2', 'email' => 'admin2@gmail.com', 'password' => Hash::make('password')]);

        $this->category1 = factory(Category::class)->create(['name' => 'Categorie 1', 'slug' => 'categorie-1']);
        $this->category2 = factory(Category::class)->create(['name' => 'Categorie 2', 'slug' => 'categorie-2']);

        $this->demand = factory(Demand::class)->create(['owner_id' => $this->user->id, 'category_id' => $this->category1->id, 'status' => 'default', 'valid_until' => now()->addMonths(1)]);
        $this->demand2 = factory(Demand::class)->create(['owner_id' => $this->user2->id, 'category_id' => $this->category2->id, 'status' => 'default', 'valid_until' => now()->addMonths(1)]);

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
        $this->post('/register', [
            'email' => 'toto@gmail.com',
            'password' => 'leopoldine',
            'password_confirmation' => 'leopoldine',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'commune_id' => 1,
            'district_id' => 2,
            'date_of_birth' => \date('Y-m-d'),
            'phone_1' => '0692665544',
        ]);
        // dd(User::all());

        return User::all()->last();
    }

    public function makeContract($userDemand, $userCandidature)
    {
        $demand = $this->createDemand($userDemand);
        $candidature = $this->createCandidature($userCandidature);
        $candidature = $userCandidature->apply($demand, $candidature);
        $contract = $demand->fresh()->contractCandidature($candidature);
        $contract->be_done_at = now()->addMinutes(1);
        $contract->wait_for_validate = true;
        $contract->save();

        return $contract;
    }

    public function adjustBeDoneAt($contract, $futur = false)
    {
        if ($futur) {
            $contract->be_done_at = now()->addMinutes(1);
        } else {
            $contract->be_done_at = now()->subMinutes(1);
        }
        $contract->save();
    }

    public function createDemand($owner)
    {
        return factory(Demand::class)->create(['owner_id' => $owner->id]);
    }

    public function createCandidature($owner)
    {
        return factory(Candidature::class)->raw(['owner_id' => $owner->id]);
    }

    public function createSubsciption($user, $plan)
    {
        $user->subscriptions()->create([
           'user_id' => $user->id,
           'name' => 'main',
           'stripe_id' => 'sub_FwmMtvddmwRaob',
           'stripe_status' => 'active',
           'stripe_plan' => $plan,
           'quantity' => 1,
           'trial_ends_at' => null,
           'ends_at' => null,
       ]);

        return $user;
    }
}
