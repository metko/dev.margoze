<?php

namespace Tests\Feature;

use App\User\User;
use Tests\TestCase;
use App\Contract\Contract;
use Illuminate\Support\Carbon;
use App\Candidature\Candidature;
use Illuminate\Support\Facades\Event;
use App\Contract\Events\ContractCreated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;
use App\Demand\Exceptions\DemandAlreadyContracted;
use App\Demand\Exceptions\DemandNoLongerAvailable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Contract\Notifications\ContractCreatedNotification;
use App\Candidature\Notifications\CandidatureNotAcceptedNotificationMail;

class ManageContractTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_owner_of_a_demand_can_contract_a_candidature()
    {
        Notification::fake();
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $this->user2->apply($this->demand, $candidature);
        $candidature = $this->demand->fresh()->candidatures->first();
        $this->actingAs($this->user)->post(route('demands.contract.candidature',
            ['demand' => $this->demand->id, 'candidature' => $candidature->id]))
            ->assertstatus(200);
        $this->assertTrue($this->demand->fresh()->isContracted());
    }

    /** @test */
    public function a_member_cant_contract_a_candidature_on_demand_not_owned_by_him()
    {
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $this->user2->apply($this->demand, $candidature);
        $candidature = $this->demand->fresh()->candidatures->first();
        $this->actingAs($this->user2)->post(route('demands.contract.candidature',
            ['demand' => $this->demand->id, 'candidature' => $candidature->id]))
            ->assertstatus(403);
        $this->assertFalse($this->demand->fresh()->isContracted());
    }

    /** @test */
    public function select_a_candidature_create_a_contract()
    {
        Notification::fake();
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $candidature = $this->user2->apply($this->demand, $candidature);
        $this->demand->contractCandidature($candidature);
        $this->assertCount(1, Contract::all());
    }

    /** @test */
    public function contract_a_demand_already_contracted_thrown_an_exception()
    {
        Notification::fake();
        $this->expectException(DemandAlreadyContracted::class);
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $candidature2 = factory(Candidature::class)->raw(['owner_id' => $this->user3->id]);
        $candidature = $this->user2->apply($this->demand, $candidature);
        $candidature2 = $this->user3->apply($this->demand, $candidature2);
        $this->demand->contractCandidature($candidature);
        $this->demand->contractCandidature($candidature2);
    }

    /** @test */
    public function contract_a_demand_passed_thrown_an_exception()
    {
        Notification::fake();
        $this->expectException(DemandNoLongerAvailable::class);
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $candidature = $this->user2->apply($this->demand, $candidature);
        $this->demand->fresh()->update(['valid_until' => Carbon::yesterday()]);
        $this->demand->fresh()->contractCandidature($candidature);
    }

    /** @test */
    public function contract_a_demand_fire_an_event()
    {
        Notification::fake();
        Event::fake([
            ContractCreated::class,
        ]);
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $candidature = $this->user2->apply($this->demand, $candidature);
        $this->demand->fresh()->contractCandidature($candidature);
        Event::assertDispatched(ContractCreated::class);
    }

    /** @test */
    public function contract_a_demand_send_a_mail_notification_to_userCandidature()
    {
        Notification::fake();
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $candidature = $this->user2->apply($this->demand, $candidature);
        $this->demand->fresh()->contractCandidature($candidature);
        $user = $this->user2;
        Notification::assertSentTo(
            new AnonymousNotifiable(), ContractCreatedNotification::class,
            function ($notification, $channels) use ($user) {
                return $notification->userCandidature->id === $user->id;
            }
        );
    }

    /** @test */
    public function contract_a_demand_save_a_db_notification_to_userCandidature_and_userDemand()
    {
        Notification::fake();
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $candidature = $this->user2->apply($this->demand, $candidature);
        $this->demand->fresh()->contractCandidature($candidature);
        $user = $this->user2;
        Notification::assertSentTo(
            new AnonymousNotifiable(), ContractCreatedNotification::class,
            function ($notification, $channels) use ($user) {
                return $notification->userCandidature->id === $user->id;
            }
        );
    }

    /** @test */
    public function contract_a_demand_send_mail_notification_to_all_other_candidature()
    {
        Notification::fake();
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $candidature2 = factory(Candidature::class)->raw(['owner_id' => $this->user3->id]);
        $candidature3 = factory(Candidature::class)->raw(['owner_id' => $this->admin->id]);
        $candidature = $this->user2->apply($this->demand, $candidature);
        $candidature2 = $this->user3->apply($this->demand, $candidature2);
        $candidature3 = $this->admin->apply($this->demand, $candidature3);
        $this->demand->fresh()->contractCandidature($candidature);
        $user3 = $this->user3;
        $admin = $this->admin;
        // Assert a notification was sent to the given users...
        Notification::assertSentTo(
            [$this->user3, $this->admin], CandidatureNotAcceptedNotificationMail::class
        );
    }

    /** @test */
    public function a_new_contract_belongs_to_a_user_of_candidature_and_user_of_demand()
    {
        Notification::fake();
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $candidature = $this->user2->apply($this->demand, $candidature);
        $this->demand->fresh()->contractCandidature($candidature);
        $contract = Contract::all()->first();
        $this->assertInstanceOf(User::class, $contract->userDemand);
    }
}
