<?php

namespace Tests\Unit;

use App\User\User;
use Tests\TestCase;
use App\Demand\Demand;
use App\Demand\DemandStatus;
use App\Demand\DemandCategory;
use App\Candidature\Candidature;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DemandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_an_owner()
    {
        $this->assertInstanceOf(User::class, $this->demand->owner);
    }

    /** @test */
    public function it_has_candidatures()
    {
        $candidature = factory(Candidature::class)->create([
            'owner_id' => $this->user->id,
            'demand_id' => $this->demand->id,
        ]);

        $this->assertInstanceOf(Candidature::class, $this->demand->candidatures->first());
        $this->assertTrue($this->demand->candidatures->first()->is($candidature));
    }

    /** @test */
    public function it_has_status()
    {
        $demand = factory(Demand::class)->create();
        $this->assertInstanceOf(DemandStatus::class, $demand->status);
    }

    /** @test */
    public function it_has_hasStatus()
    {
        $demand = factory(Demand::class)->create();
        $this->assertTrue($demand->hasStatus('default'));
    }

    /** @test */
    public function it_has_hasCategory()
    {
        $demand = factory(Demand::class)->create(['category_id' => $this->demandCategory1->id]);
        $this->assertTrue($demand->hasCategory($this->demandCategory1->slug));
    }

    /** @test */
    public function it_has_category()
    {
        $demand = factory(Demand::class)->create();
        $this->assertInstanceOf(DemandCategory::class, $demand->category);
    }

    /** @test */
    public function it_has_contracted_and_isContracted()
    {
        $this->demand->contracted();
        $this->assertTrue($this->demand->isContracted());
    }

    /** @test */
    public function it_has_contractCandidature()
    {
        $candidature = factory(Candidature::class)->raw();
        $candidature = $this->user2->apply($this->demand, $candidature);
        $this->demand->contractCandidature($candidature);
        $this->assertTrue($this->demand->fresh()->isContracted());
    }
}
