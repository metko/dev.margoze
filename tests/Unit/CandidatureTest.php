<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CandidatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_demand()
    {
        $candidature = factory(\App\Candidature::class)->create();
        $this->assertInstanceOf(\App\Demand::class, $candidature->demand);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $candidature = factory(\App\Candidature::class)->create();
        $this->assertInstanceOf(\App\User::class, $candidature->owner);
    }
}
