<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Demand\Demand;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageDemandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_guest_cant_create_a_demand()
    {
        $demand = factory(Demand::class)->raw();
        $this->post(route('demands.post'), $demand)
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /** @test */
    public function a_demand_must_have_some_fields()
    {
        $demand = factory(Demand::class)->raw();
        $this->actingAs($this->user)->post(route('demands.post'), $demand);
        $this->assertDatabaseHas('demands', ['title' => $demand['title']]);
    }

    /** @test */
    public function a_demand_belongs_to_an_user()
    {
        $this->assertInstanceOf(User::class, $this->demand->owner);
    }
}
