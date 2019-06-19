<?php

namespace Tests\Unit;

use App\User\User;
use Tests\TestCase;
use App\Demand\Demand;
use App\Candidature\Candidature;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CandidatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_demand()
    {
        $candidature = factory(Candidature::class)->create(['demand_id' => $this->demand]);
        $this->assertInstanceOf(Demand::class, $candidature->demand);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $candidature = factory(Candidature::class)->create(['demand_id' => $this->demand]);
        $this->assertInstanceOf(User::class, $candidature->owner);
    }
}
