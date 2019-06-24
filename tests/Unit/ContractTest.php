<?php

namespace Tests\Unit;

use App\User\User;
use Tests\TestCase;
use App\Demand\Demand;
use App\Contract\Contract;
use App\Candidature\Candidature;
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
}
