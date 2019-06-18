<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageDemandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_guest_cant_create_a_demand()
    {
        $demand = factory(\App\Demand::class)->raw();
        $this->post(route('demands.post'), $demand)
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /** @test */
    public function a_demand_must_have_some_fields()
    {
        $demand = factory(\App\Demand::class)->raw();
        $this->actingAs($this->user)->post(route('demands.post'), $demand);
        $this->assertDatabaseHas('demands', ['title' => $demand['title']]);
    }

    /** @test */
    public function a_demand_belongs_to_an_user()
    {
        $this->assertInstanceOf(\App\User::class, $this->demand->owner);
    }
}
