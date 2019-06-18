<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DemandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_an_owner()
    {
        $this->assertInstanceOf(\App\User::class, $this->demand->owner);
    }

    /** @test */
    public function it_has_candidatures()
    {
        $candidature = factory(\App\Candidature::class)->create([
            'owner_id' => $this->user->id,
            'demand_id' => $this->demand->id,
        ]);

        $this->assertInstanceOf(\App\Candidature::class, $this->demand->candidatures->first());
        $this->assertTrue($this->demand->candidatures->first()->is($candidature));
    }
}
