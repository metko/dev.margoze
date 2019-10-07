<?php

namespace Tests\Feature;

use App\User\User;
use Tests\TestCase;
use App\Credit\Credit;
use App\Credit\Exceptions\NoCreditsAvailable;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageCreditsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function a_new_subscriber_has_credits_guid_by_plan()
    {
        $user = factory(User::class)->create();
        $user->subscribe();
        $this->assertInstanceOf(Credit::class, $user->fresh()->credits);
    }

    /** @test */
    public function a_new_user_earn_basic_credit()
    {
        $user = $this->createUser();
        $this->assertInstanceOf(Credit::class, $user->fresh()->credits);
        $this->assertTrue($user->credits->demands_valid_for == config('credits.default.demands_valid_for'));
    }

    /** @test */
    public function set_type_credit_good_value()
    {
        $this->createSubsciption($this->user, 'professionnel');
        $this->user->credit();
        $this->assertTrue($this->user->credits->demands_valid_for == config('credits.professionnel.demands_valid_for'));
    }

    /** @test */
    public function apply_candidature_consume_credits()
    {
        $demand = $this->createDemand($this->user);
        $candidature = $this->createCandidature($this->user2);
        $this->user2->apply($demand, $candidature);

        $this->assertTrue($this->user2->credits->candidatures_count == config('credits.default.candidatures_count') - 1);
    }

    /** @test */
    public function user_cant_apply_candidature_if_doesnt_have_credits()
    {
        $this->expectException(NoCreditsAvailable::class);
        $demand = $this->createDemand($this->user);
        $candidature = $this->createCandidature($this->user2);
        $this->user2->credits->update(['candidatures_count' => 0]);
        $this->user2->apply($demand, $candidature);
    }

    /** @test */
    public function make_contract_consume_credits()
    {
        $this->createSubsciption($this->user, 'basic');
        $this->user->credit();
        $contract = $this->makeContract($this->user, $this->user2);
        $this->assertTrue($this->user->fresh()->credits->contracts_count == config('credits.basic.contracts_count') - 1);
    }

    /** @test */
    public function user_cant_have_contract_if_doesnt_have_credits()
    {
        $this->expectException(NoCreditsAvailable::class);
        $this->user->credits->update(['contracts_count' => 0]);
        $contract = $this->makeContract($this->user, $this->user2);
    }

    /** @test */
    public function a_user_can_be_credited()
    {
        $user = factory(User::class)->create();
        $user->subscribe();
        $this->assertInstanceOf(Credit::class, $user->fresh()->credits);
    }
}
