<?php

namespace Tests\Feature;

use App\User\User;
use Tests\TestCase;
use App\Demand\Demand;
use App\Contract\Contract;
use Illuminate\Support\Carbon;
use App\Candidature\Candidature;
use Illuminate\Support\Facades\Event;
use App\Contract\Events\ContractCreated;
use App\Contract\Events\ContractValidated;
use Illuminate\Support\Facades\Notification;
use App\Contract\Exceptions\DateAlreadySubmit;
use App\Demand\Exceptions\DemandAlreadyContracted;
use App\Demand\Exceptions\DemandNoLongerAvailable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Contract\Exceptions\ContractAlreadyValidated;
use App\Contract\Exceptions\UserDoesntBelongsToContract;
use App\Contract\Notifications\ContractCreatedDBNotification;
use App\Contract\Notifications\ContractCreatedMailNotification;
use App\Candidature\Notifications\CandidatureNotAcceptedNotificationMail;

class ManageContractTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_owner_of_a_demand_can_contract_a_candidature()
    {
        $this->debug();
        $this->applyCandidature($this->user2);
        $candidature = $this->demand->fresh()->candidatures->first();
        $this->actingAs($this->user)->post(route('demands.contract.candidature',
            ['demand' => $this->demand->id, 'candidature' => $candidature->id]));
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

        Notification::assertSentTo(
            $this->user2,
            ContractCreatedMailNotification::class,
            function ($notification, $channels) {
                // dd($notification);
                $this->assertInstanceOf(Demand::class, $notification->demand);
                $this->assertInstanceOf(Contract::class, $notification->contract);

                return $notification->contract->candidature_owner_id === $this->user2->id;
            }
        );
    }

    /** @test */
    public function contract_a_demand_save_a_db_notification_to_userCandidature_and_userDemand()
    {
        $candidature = $this->applyCandidature($this->user2);
        $this->demand->fresh()->contractCandidature($candidature);
        $userCandidature = $this->user2;
        Notification::assertSentTo(
            $userCandidature,
            ContractCreatedDBNotification::class,
            function ($notification, $channels) {
                $this->assertInstanceOf(User::class, $notification->userCandidature);
                $this->assertInstanceOf(User::class, $notification->userDemand);
                $this->assertInstanceOf(Demand::class, $notification->demand);
                $this->assertInstanceOf(Contract::class, $notification->contract);

                return $notification->contract->candidature_owner_id === $this->user2->id;
            }
        );
        Notification::assertSentTo(
            [$userCandidature, $this->user], ContractCreatedDBNotification::class
        );
    }

    /** @test */
    public function contract_a_demand_send_mail_notification_to_all_other_candidature()
    {
        $candidature1 = $this->applyCandidature($this->user2);
        $candidature2 = $this->applyCandidature($this->user3);
        $candidature3 = $this->applyCandidature($this->admin);
        $this->demand->fresh()->contractCandidature($candidature1);
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

    protected function createContract($user1, $user2)
    {
        $candidature = $this->applyCandidature($user2);
        $this->demand->fresh()->contractCandidature($candidature);
        $contract = Contract::all()->first();

        return $contract;
    }

    /** @test */
    public function on_a_new_contract___users_can_propose_be_done_date()
    {
        $this->debug();
        $contract = $this->createContract($this->user, $this->user2);
        $date = now()->addDays(7);
        $contract->setDate($date, $this->user2);
        $this->assertSame($date, $contract->be_done_at);
        $this->assertTrue($contract->wait_for_validate);
        $this->assertSame($this->user2->id, $contract->last_propose_by);
    }

    /** @test */
    public function propose_date_when_already_did_it_throw_exception()
    {
        $this->debug();
        $this->expectException(DateAlreadySubmit::class);
        $contract = $this->createContract($this->user, $this->user2);
        $date = now()->addDays(7);
        $contract->setDate($date, $this->user2);
        $contract->setDate($date, $this->user2);
    }

    /** @test */
    public function propose_date_when_not_in_contract_throw_exception()
    {
        $this->debug();
        $this->expectException(UserDoesntBelongsToContract::class);
        $contract = $this->createContract($this->user, $this->user2);
        $date = now()->addDays(7);
        $contract->setDate($date, $this->user3);
    }

    /** @test */
    public function user_can_refused_be_done_date__submited_by_other_user()
    {
        $this->debug();
        $contract = $this->createContract($this->user, $this->user2);
        $date = now()->addDays(7);
        $contract->setDate($date, $this->user2);
        $contract->refuseDate($this->user);
        $this->assertSame(null, $contract->be_done_at);
        $this->assertFalse($contract->wait_for_validate);
        $this->assertSame(null, $contract->last_propose_by);
    }

    /** @test */
    public function accept_be_done_date_set_some_fields_and_fix_the_contract()
    {
        $this->debug();
        $contract = $this->createContract($this->user, $this->user2);
        $date = now()->addDays(7);
        $contract->setDate($date, $this->user2);
        $contract->acceptDate($this->user);
        $this->assertSame($date, $contract->be_done_at);
        $this->assertTrue($contract->wait_for_validate);
        $this->assertSame(2, $contract->last_propose_by);
        $this->assertSame(now()->toString(), $contract->validated_at->toString());
    }

    /** @test */
    public function setDate_on_contract_already_validate_throw_error()
    {
        $this->debug();
        $this->expectException(ContractAlreadyValidated::class);
        $contract = $this->createContract($this->user, $this->user2);
        $date = now()->addDays(7);
        $contract->setDate($date, $this->user2);
        $contract->acceptDate($this->user);
        $contract->setDate($date->addDays(3), $this->user2);
    }

    /** @test */
    public function contract_can_be_cancelled()
    {
        $this->debug();
        $contract = $this->createContract($this->user, $this->user2);
        $date = now()->addDays(7);
        $contract->setDate($date, $this->user2);
        $contract->cancelContract($this->user);
        $this->assertSame(1, $contract->cancelled_by);
    }

    /** @test */
    public function fix_a_contract_fire_an_event()
    {
        Event::Fake([
            ContractValidated::class,
        ]);
        $this->debug();
        $contract = $this->createContract($this->user, $this->user2);
        $date = now()->addDays(7);
        $contract->setDate($date, $this->user2);
        $contract->acceptDate($this->user);
        Event::assertDispatched(ContractValidated::class);
    }

    /** @test */
    public function an_after_be_done_at_send_confirmation_to_see_if_the_work_is_done()
    {
        $this->debug();
        $this->assertTrue(true);
    }

    /** @test */
    public function an_after_done_at_propose_open_a_feedback_evaluable()
    {
        $this->debug();
        $this->assertTrue(true);
    }
}
