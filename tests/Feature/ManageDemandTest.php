<?php

namespace Tests\Feature;

use App\User\User;
use Tests\TestCase;
use App\Demand\Demand;
use App\Sector\Sector;
use App\Category\Category;
use Illuminate\Support\Carbon;
use App\Candidature\Candidature;
use Metko\Galera\Facades\Galera;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CantContactUser;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageDemandTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

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
        $this->debug();
        $demand = factory(Demand::class)->raw();
        $this->actingAs($this->user)->post(route('demands.post'), $demand);
        $this->assertDatabaseHas('demands', ['title' => $demand['title']]);
    }

    /** @test */
    public function a_new_demand_has_a_statut_set_to_default()
    {
        $demand = factory(Demand::class)->raw();
        $this->actingAs($this->user)->post(route('demands.post'), $demand);
        $demand = Demand::all()->last();
        $this->assertTrue($demand->hasStatus('default'));
    }

    /** @test */
    public function a_new_demand_must_have_a_category()
    {
        $demand = factory(Demand::class)->raw();
        $this->actingAs($this->user)->post(route('demands.post'), $demand);
        $demand = Demand::all()->last();
        $this->assertInstanceOf(Category::class, $demand->category);
    }

    /** @test */
    public function a_new_demand_must_have_a_sector()
    {
        $demand = factory(Demand::class)->raw();
        $this->actingAs($this->user)->post(route('demands.post'), $demand);
        $demand = Demand::all()->last();
        $this->assertInstanceOf(Sector::class, $demand->sector);
    }

    /** @test */
    public function a_new_demand_is_valid_for_one_month()
    {
        $date = now()->addMonths(1);
        $demand = factory(Demand::class)->raw();
        $this->actingAs($this->user)->post(route('demands.post'), $demand);
        $this->assertDatabaseHas('demands', ['valid_until' => $date]);
    }

    /** @test */
    public function a_demand_belongs_to_an_user()
    {
        $this->assertInstanceOf(User::class, $this->demand->owner);
    }

    /** @test */
    public function an_owner_can_mark_his_demand_has_contracted()
    {
        $this->debug();
        $this->actingAs($this->user)->post(route('demands.contracted', $this->demand->id));
        $this->assertTrue($this->demand->fresh()->isContracted());
    }

    /** @test */
    public function a_guest_cant_mark_a_demand_has_contracted_owned_by_others()
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

    /** @test */
    public function a_member_who_post_a_demand_can_contact_ones_who_sent_a_candidature()
    {
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $candidature = $this->user2->apply($this->demand, $candidature);
        $this->actingAs($this->user)->post(route('demands.contact', [
            'demand' => $this->demand->id,
            'userCandidature' => $this->user2->id,
        ]), ['message' => 'Hello ca va ?']);
        $this->assertCount(1, Galera::allConversations());
        $this->assertCount(1, Galera::allMessages());
    }

    /** @test */
    public function contact_a_wrong_user_on_a_demand_thrwo_exception()
    {
        $this->debug();
        $this->expectException(CantContactUser::class);
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $candidature = $this->user2->apply($this->demand, $candidature);
        $this->actingAs($this->user)->post(route('demands.contact', [
            'demand' => $this->demand->id,
            'userCandidature' => 3,
        ]), ['message' => 'Hello ca va ?']);
        $this->assertCount(0, Galera::allConversations());
    }

    /** @test */
    public function a_candidature_user_who_contact_a_demand_user_throw_exeption()
    {
        $this->debug();
        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $candidature = $this->user2->apply($this->demand, $candidature);
        $this->actingAs($this->user2)->post(route('demands.contact', [
            'demand' => $this->demand->id,
            'userCandidature' => 3,
        ]), ['message' => 'Hello ca va ?']);
        $this->assertCount(0, Galera::allConversations());
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
