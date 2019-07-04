<?php

namespace Tests\Unit;

use App\User\User;
use Tests\TestCase;
use App\Demand\Demand;
use App\Contract\Contract;
use App\Candidature\Candidature;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContractTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_userDemand()
    {
        $contract = factory(Contract::class)->create();
        $this->assertInstanceOf(User::class, $contract->userDemand);
    }

    /** @test */
    public function it_has_userCandidature()
    {
        $contract = factory(Contract::class)->create();
        $this->assertInstanceOf(User::class, $contract->userCandidature);
    }

    /** @test */
    public function it_has_demand()
    {
        $contract = factory(Contract::class)->create();
        $this->assertInstanceOf(Demand::class, $contract->demand);
    }

    /** @test */
    public function it_has_candidature()
    {
        $contract = factory(Contract::class)->create();
        $this->assertInstanceOf(Candidature::class, $contract->candidature);
    }

    /** @test */
    public function it_has_setDate()
    {
        Event::fake();
        $contract = $this->createContract($this->user, $this->user2);
        $date = now()->addDays(7);
        $contract->setDate($date, $this->user2);
        $this->assertSame($date, $contract->be_done_at);
    }

    protected function applyCandidature($user)
    {
        $candidature = factory(Candidature::class)->raw(['owner_id' => $user->id]);

        return $user->apply($this->demand, $candidature);
    }

    protected function createContract($user1, $user2)
    {
        $candidature = $this->applyCandidature($user2);
        $this->demand->fresh()->contractCandidature($candidature);
        $contract = Contract::all()->first();

        return $contract;
    }
}
