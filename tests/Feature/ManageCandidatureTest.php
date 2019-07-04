<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Demand\Demand;
use Illuminate\Support\Carbon;
use App\Candidature\Candidature;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use App\Demand\Exceptions\DemandAlreadyContracted;
use App\Demand\Exceptions\DemandNoLongerAvailable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Candidature\Exceptions\CandidatureAlreadySent;
use App\Candidature\Exceptions\CandidatureBelongsToOwnerDemand;
use App\Candidature\Notifications\CandidatureCreatedNotification;

class ManageCandidatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_member_can_send_a_candidature_to_a_demand()
    {
        $candidature = factory(Candidature::class)->raw();
        $this->actingAs($this->user2)->post(route('demands.apply', $this->demand->id), $candidature);
        $this->assertCount(1, $this->demand->candidatures);
    }

    /** @test */
    public function apply_to_a_demand_return_a_candidature()
    {
        $candidature = factory(Candidature::class)->raw();
        $candidature = $this->user2->apply($this->demand, $candidature);
        $this->assertInstanceOf(Candidature::class, $candidature);
    }

    /** @test */
    public function sending_a_candidature_send_a_notif_to_the_owner()
    {
        //Event::fake();

        $this->debug();
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $this->user2->apply($this->demand, $candidature);
        Notification::assertSentTo(
            $this->user,
            CandidatureCreatedNotification::class,
            function ($notification, $channels) {
                return $notification->candidature->owner_id === $this->user2->id;
            }
        );
    }

    /** @test */
    public function it_throws_an_exception_when_sending_twice_candidature_from_same_user()
    {
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2]);
        $this->expectException(CandidatureAlreadySent::class);
        $this->user2->apply($this->demand, $candidature);
        $this->user2->apply($this->demand->fresh(), $candidature);
        $this->actingAs($this->user2)->post(route('demands.apply', $this->demand->id), $candidature);
    }

    /** @test */
    public function it_throws_an_exception_when_an_owner_send_an_offer_to_himself()
    {
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user]);
        $this->expectException(CandidatureBelongsToOwnerDemand::class);
        $this->user->apply($this->demand, $candidature);
        $this->assertCount(0, $this->demand->candidatures);
    }

    /** @test */
    public function it_throws_an_exception_when_an_offer_is_send_after_the_to_valid_until_date()
    {
        $demand = factory(Demand::class)->create();
        $demand->update(['valid_until' => Carbon::yesterday()]);
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user]);
        $this->expectException(DemandNoLongerAvailable::class);
        $this->user2->apply($demand, $candidature);
        $this->assertCount(0, $this->demand->candidatures);
    }

    /** @test */
    public function it_throws_an_exception_when_an_offer_is_send_on_a_demand_already_contracted()
    {
        $demand = factory(Demand::class)->create(['contracted' => true]);
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user]);
        $this->expectException(DemandAlreadyContracted::class);
        $this->user2->apply($demand, $candidature);
        $this->assertCount(0, $this->demand->candidatures);
    }
}
