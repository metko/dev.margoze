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

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function a_owner_of_a_demand_can_contract_a_candidature()
    {
        $this->applyCandidature($this->user2);
        $candidature = $this->demand->fresh()->candidatures->first();
        $this->actingAs($this->user)->post(route('demands.contract.candidature',
            ['demand' => $this->demand->id, 'candidature' => $candidature->id]))
            ->assertstatus(200);
        $this->assertTrue($this->demand->fresh()->isContracted());
    }

    /** @test */
    public function a_member_cant_contract_a_candidature_on_demand_not_owned_by_him()
    {
        $this->applyCandidature($this->user2);
        $candidature = $this->demand->fresh()->candidatures->first();
        $this->actingAs($this->user2)->post(route('demands.contract.candidature',
            ['demand' => $this->demand->id, 'candidature' => $candidature->id]))
            ->assertstatus(403);
        $this->assertFalse($this->demand->fresh()->isContracted());
    }

    /** @test */
    public function select_a_candidature_create_a_contract()
    {
        $candidature = $this->applyCandidature($this->user2);
        $this->demand->contractCandidature($candidature);
        $this->assertCount(1, Contract::all());
    }

    /** @test */
    public function contract_a_demand_already_contracted_thrown_an_exception()
    {
        $this->expectException(DemandAlreadyContracted::class);
        $candidature1 = $this->applyCandidature($this->user2);
        $candidature2 = $this->applyCandidature($this->user3);

        $this->demand->contractCandidature($candidature1);
        $this->demand->contractCandidature($candidature2);
    }

    /** @test */
    public function contract_a_demand_passed_thrown_an_exception()
    {
        $this->expectException(DemandNoLongerAvailable::class);
        $candidature = $this->applyCandidature($this->user2);

        $this->demand->fresh()->update(['valid_until' => Carbon::yesterday()]);
        $this->demand->fresh()->contractCandidature($candidature);
    }

    /** @test */
    public function contract_a_demand_fire_an_event()
    {
        Event::fake([
            ContractCreated::class,
        ]);
        $candidature = $this->applyCandidature($this->user2);
        $this->demand->fresh()->contractCandidature($candidature);
        Event::assertDispatched(ContractCreated::class);
    }

    /** @test */
    public function contract_a_demand_send_a_mail_notification_to_userCandidature()
    {
        $candidature = $this->applyCandidature($this->user2);

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
        $candidature = $this->applyCandidature($this->user2);
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
        $candidature1 = $this->applyCandidature($this->user2);
        $candidature2 = $this->applyCandidature($this->user3);
        $candidature3 = $this->applyCandidature($this->admin);
        $this->demand->fresh()->contractCandidature($candidature1);
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
        $candidature = $this->applyCandidature($this->user2);
        $this->demand->fresh()->contractCandidature($candidature);
        $contract = Contract::all()->first();
        $this->assertInstanceOf(User::class, $contract->userDemand);
    }

    protected function applyCandidature($user)
    {
        $candidature = factory(Candidature::class)->raw(['owner_id' => $user->id]);

        return $user->apply($this->demand, $candidature);
    }
}
