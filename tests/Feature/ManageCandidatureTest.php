<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Demand\Demand;
use Illuminate\Support\Carbon;
use App\Candidature\Candidature;
use Illuminate\Support\Facades\DB;
use App\Notifications\CandidatureSubmit;
use Illuminate\Support\Facades\Notification;
use App\Demand\Exceptions\DemandAlreadyContracted;
use App\Demand\Exceptions\DemandNoLongerAvailable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Candidature\Exceptions\CandidatureAlreadySent;
use App\Candidature\Exceptions\CandidatureBelongsToOwnerDemand;

class ManageCandidatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_member_can_send_a_candidature_to_a_demand()
    {
        $this->withoutExceptionHandling();
        $candidature = factory(Candidature::class)->raw();
        $this->actingAs($this->user2)->post(route('demands.apply', $this->demand->id), $candidature);
        $this->assertCount(1, $this->demand->candidatures);
    }

    // /** @test */
    // public function submit_candidature_fire_a_notification_mail()
    // {
    //     $this->debug();
    //     Notification::fake();
    //     $candidature = factory(Candidature::class)->raw();
    //     $this->actingAs($this->user2)->post(route('demands.apply', $this->demand->id), $candidature);
    //     $candidature = $this->demand->candidatures->last();

    //     Notification::assertSentTo(
    //         $this->user,
    //         CandidatureSubmit::class,
    //         function ($notification, $channels) use ($candidature) {
    //             return $notification->candidature->id === $candidature->id;
    //         }
    //     );

    //     // Assert a notification was sent to the given users...
    //     Notification::assertSentTo(
    //         [$this->user], CandidatureSubmit::class
    //     );

    //     dd(DB::table('notifications')->get());
    // }

    /** @test */
    public function a_user_can_send_a_candidature_to_a_demand_twice()
    {
        $candidature = factory(Candidature::class)->raw();
        $candidature2 = factory(Candidature::class)->raw();
        $this->actingAs($this->user2)->post(route('demands.apply', $this->demand->id), $candidature);
        $this->assertCount(1, $this->demand->candidatures);
        $this->actingAs($this->user2)->post(route('demands.apply', $this->demand->id), $candidature2);
        $this->demand->load('candidatures');
        $this->assertCount(1, $this->demand->candidatures);
    }

    /** @test */
    public function it_throws_an_exception_when_sending_twice_candidature_from_same_user()
    {
        $this->withoutExceptionHandling();
        $candidature = factory(Candidature::class)->raw();
        $this->expectException(CandidatureAlreadySent::class);
        $this->actingAs($this->user2)->post(route('demands.apply', $this->demand->id), $candidature)
                ->assertStatus(200);
        $this->actingAs($this->user2)->post(route('demands.apply', $this->demand->id), $candidature)
                ->assertStatus(500);
    }

    /** @test */
    public function it_throws_an_exception_when_an_owner_send_an_offer_to_himself()
    {
        $this->withoutExceptionHandling();
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user]);
        $this->expectException(CandidatureBelongsToOwnerDemand::class);
        $this->actingAs($this->user)->post(route('demands.apply', $this->demand->id), $candidature)
                ->assertStatus(500);
        $this->assertCount(0, $this->demand->candidatures);
    }

    /** @test */
    public function it_throws_an_exception_when_an_offer_is_send_after_the_to_be_done_date()
    {
        $this->withoutExceptionHandling();
        $demand = factory(Demand::class)->create(['be_done_at' => Carbon::yesterday()]);
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user]);
        $this->expectException(DemandNoLongerAvailable::class);
        $this->actingAs($this->user)->post(route('demands.apply', $demand->id), $candidature)
                ->assertStatus(500);
        $this->assertCount(0, $this->demand->candidatures);
    }

    /** @test */
    public function it_throws_an_exception_when_an_offer_is_send_on_a_demand_already_contracted()
    {
        $this->withoutExceptionHandling();
        $demand = factory(Demand::class)->create(['contracted' => true]);
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user]);
        $this->expectException(DemandAlreadyContracted::class);
        $this->actingAs($this->user)->post(route('demands.apply', $demand->id), $candidature)
                ->assertStatus(500);
        $this->assertCount(0, $this->demand->candidatures);
    }
}
