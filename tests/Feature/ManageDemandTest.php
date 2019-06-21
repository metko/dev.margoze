<?php

namespace Tests\Feature;

use App\User\User;
use Tests\TestCase;
use App\Demand\Demand;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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

    /** @test */
    public function it_owner_can_mark_his_demand_has_contracted()
    {
        $this->debug();
        $this->actingAs($this->user)->post(route('demands.contracted', $this->demand->id));
        $this->assertTrue($this->demand->fresh()->isContracted());
    }

    /** @test */
    public function it_owner_cant_mark_his_demand_has_contracted_owned_by_others()
    {
        $this->actingAs($this->user2)->post(route('demands.contracted', $this->demand->id));
        $this->assertFalse($this->demand->fresh()->isContracted());
    }

    /** @test */
    public function an_owner_can_update_his_demand()
    {
        $this->actingAs($this->user)->patch(route('demands.update', $this->demand), $this->demandAttr());

        $this->assertDatabaseHas('demands', $this->demandAttr());
    }

    /** @test */
    public function a_member_cant_update_a_demand_not_owned_by_him()
    {
        $this->actingAs($this->user2)->patch(route('demands.update', $this->demand), $this->demandAttr())
            ->assertStatus(403);

        $this->assertDatabaseMissing('demands', $this->demandAttr());
    }

    /** @test */
    public function a_owner_can_soft_delete_his_demand()
    {
        $this->debug();
        $this->actingAs($this->user)->delete(route('demands.delete', $this->demand));
        $this->assertCount(1, Demand::all());
        $this->assertCount(2, DB::table('demands')->get());
    }

    /** @test */
    public function a_member_cant_soft_delete_a_demand_not_owned_by_him()
    {
        $this->actingAs($this->user2)->delete(route('demands.delete', $this->demand));
        $this->assertCount(2, Demand::all());
        $this->assertCount(2, DB::table('demands')->get());
    }

    /** @test */
    public function a_owner_can_permanently_delete_his_demand()
    {
        $this->debug();
        $this->actingAs($this->user)->delete(route('demands.destroy', $this->demand));
        $this->assertCount(1, Demand::all());
        $this->assertCount(1, DB::table('demands')->get());
    }

    /** @test */
    public function a_member_cant_permanently_delete_a_demand_not_owned_by_him()
    {
        $this->actingAs($this->user2)->delete(route('demands.destroy', $this->demand));
        $this->assertCount(2, Demand::all());
        $this->assertCount(2, DB::table('demands')->get());
    }

    /** @test */
    public function a_owner_can_restore_his_demand()
    {
        $this->actingAs($this->user)->delete(route('demands.delete', $this->demand));
        $this->assertCount(1, Demand::all());
        $this->actingAs($this->user)->post(route('demands.restore', $this->demand));
        $this->assertCount(2, Demand::all());
    }

    /** @test */
    public function a_member_cant_restore_a_demand_not_owned_by_him()
    {
        $this->actingAs($this->user)->delete(route('demands.delete', $this->demand));
        $this->assertCount(1, Demand::all());
        $this->actingAs($this->user2)->post(route('demands.restore', $this->demand));
        $this->assertCount(1, Demand::all());
    }

    protected function demandAttr()
    {
        return  [
            'title' => 'New title',
            'description' => 'blablablablablablabla',
            'be_done_at' => Carbon::tomorrow()->toDateTimeString(),
        ];
    }
}
