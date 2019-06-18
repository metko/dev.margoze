<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageCandidatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_send_a_candidature_to_a_demand()
    {
        $candidature = factory(\App\Candidature::class)->raw([
            'owner_id' => $this->user->id,
            'demand_id' => $this->demand->id,
        ]);
        $this->actingAs($this->user)->post(route('demands.apply', $this->demand->id), $candidature);
        $this->assertCount(1, $this->demand->candidatures);
    }
}
