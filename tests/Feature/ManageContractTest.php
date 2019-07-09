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
use App\Contract\Events\SettingsContractRevoked;
use App\Contract\Events\SettingsContractProposed;
use App\Contract\Exceptions\SettingsAlreadySubmit;
use App\Demand\Exceptions\DemandAlreadyContracted;
use App\Demand\Exceptions\DemandNoLongerAvailable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Contract\Exceptions\ContractAlreadyValidated;
use App\Contract\Exceptions\UserDoesntBelongsToContract;
use App\Contract\Notifications\ContractCreatedDBNotification;
use App\Contract\Notifications\ContractCreatedMailNotification;
use App\Contract\Notifications\SettingsContractRevokedNotification;
use App\Contract\Notifications\SettingsContractProposedNotification;
use App\Candidature\Notifications\CandidatureNotAcceptedNotificationMail;

class ManageContractTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->settings['be_done_at'] = now()->addDays(7);
        $this->contract = $this->createContract($this->user, $this->user2);
    }

    /** @test */
    public function a_owner_of_a_demand_can_contract_a_candidature()
    {
        $this->applyCandidature($this->user2);
        $candidature = $this->demand->fresh()->candidatures->first();
        $this->actingAs($this->user)->post(route('demands.contract.candidature',
            ['demand' => $this->demand->id, 'candidature' => $candidature->id]));
        $this->assertTrue($this->demand->fresh()->isContracted());
    }

    /** @test */
    public function a_member_cant_contract_a_candidature_on_demand_not_owned_by_him()
    {
        $this->applyCandidature($this->user, $this->demand2);
        $candidature = $this->demand2->fresh()->candidatures->first();
        $this->actingAs($this->user)->post(route('demands.contract.candidature',
            ['demand' => $this->demand2->id, 'candidature' => $candidature->id]))
            ->assertstatus(403);
        $this->assertFalse($this->demand2->fresh()->isContracted());
    }

    /** @test */
    public function select_a_candidature_create_a_contract()
    {
        $candidature = $this->applyCandidature($this->user, $this->demand2);
        $this->demand2->contractCandidature($candidature);
        $this->assertCount(2, Contract::all());
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
        $candidature = $this->applyCandidature($this->user, $this->demand2);

        $this->demand2->fresh()->update(['valid_until' => Carbon::yesterday()]);
        $this->demand2->fresh()->contractCandidature($candidature);
    }

    /** @test */
    public function contract_a_demand_fire_an_event()
    {
        Event::fake([
            ContractCreated::class,
        ]);
        $candidature = $this->applyCandidature($this->user, $this->demand2);
        $this->demand2->fresh()->contractCandidature($candidature);
        Event::assertDispatched(ContractCreated::class);
    }

    /** @test */
    public function contract_a_demand_send_a_mail_notification_to_userCandidature()
    {
        $candidature = $this->applyCandidature($this->user, $this->demand2);
        $this->demand2->fresh()->contractCandidature($candidature);

        Notification::assertSentTo(
            $this->user2,
            ContractCreatedMailNotification::class,
            function ($notification, $channels) {
                $this->assertInstanceOf(Demand::class, $notification->demand);
                $this->assertInstanceOf(Contract::class, $notification->contract);

                return $notification->contract->candidature_owner_id === $this->user2->id;
            }
        );
    }

    /** @test */
    public function contract_a_demand_save_a_db_notification_to_userCandidature_and_userDemand()
    {
        $candidature = $this->applyCandidature($this->user, $this->demand2);
        $this->demand2->fresh()->contractCandidature($candidature);
        Notification::assertSentTo(
            $this->user2,
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
            [$this->user2, $this->user], ContractCreatedDBNotification::class
        );
    }

    /** @test */
    public function contract_a_demand_send_mail_notification_to_all_other_candidature()
    {
        $candidature1 = $this->applyCandidature($this->user, $this->demand2);
        $candidature2 = $this->applyCandidature($this->user3, $this->demand2);
        $candidature3 = $this->applyCandidature($this->admin, $this->demand2);
        $this->demand2->fresh()->contractCandidature($candidature1);
        Notification::assertSentTo(
            [$this->user3, $this->admin], CandidatureNotAcceptedNotificationMail::class
        );
    }

    /** @test */
    public function a_new_contract_belongs_to_a_user_of_candidature_and_user_of_demand()
    {
        $candidature = $this->applyCandidature($this->user, $this->demand2);
        $this->demand2->fresh()->contractCandidature($candidature);
        $contract = Contract::all()->first();
        $this->assertInstanceOf(User::class, $contract->userDemand);
    }

    /** @test */
    public function on_a_new_contract___users_can_propose_be_done_date()
    {
        $this->proposeSettings($this->contract, $this->user2);
        $this->assertTrue($this->settings['be_done_at']->eq($this->contract->fresh()->be_done_at));
        $this->assertTrue($this->contract->fresh()->wait_for_validate);
        $this->asserttrue($this->user2->id == $this->contract->fresh()->last_propose_by);
    }

    /** @test */
    public function propose_date_when_already_did_it_throw_exception()
    {
        $this->expectException(SettingsAlreadySubmit::class);
        $this->proposeSettings($this->contract, $this->user2);
        $this->proposeSettings($this->contract, $this->user2);
    }

    /** @test */
    public function propose_date_when_not_in_contract_throw_exception()
    {
        $this->expectException(UserDoesntBelongsToContract::class);
        $this->proposeSettings($this->contract, $this->user3);
    }

    /** @test */
    public function user_can_refused_be_done_date__submited_by_other_user()
    {
        $this->proposeSettings($this->contract, $this->user2);
        $this->contract->revokeSettings($this->user);
        $this->assertNull($this->contract->fresh()->be_done_at);
        $this->assertFalse($this->contract->fresh()->wait_for_validate);
        $this->assertNull($this->contract->fresh()->last_propose_by);
    }

    /** @test */
    public function accept_settings_set_some_fields_and_fix_the_contract()
    {
        $this->proposeSettings($this->contract, $this->user2);
        $this->contract->validateSettings($this->user);

        $this->assertTrue($this->settings['be_done_at']->eq($this->contract->fresh()->be_done_at));
        $this->assertFalse($this->contract->fresh()->wait_for_validate);
        $this->assertTrue($this->user2->id == $this->contract->fresh()->last_propose_by);
        $this->asserttrue(now()->eq($this->contract->fresh()->validated_at));
    }

    /** @test */
    public function setDate_on_contract_already_validate_throw_error()
    {
        $this->expectException(ContractAlreadyValidated::class);
        $this->proposeSettings($this->contract, $this->user2);
        $this->contract->validateSettings($this->user);
        $this->settings['be_done_at'] = $this->settings['be_done_at']->addDays(3);
        $this->contract->fresh()->proposeSettings($this->settings, $this->user2);
    }

    /** @test */
    public function contract_can_be_cancelled()
    {
        $this->proposeSettings($this->contract, $this->user2);
        $this->contract->cancel($this->user);
        $this->assertTrue($this->user->id == $this->contract->fresh()->cancelled_by);
    }

    /** @test */
    public function valided_settings_contract_fire_an_event()
    {
        Event::Fake([
            ContractValidated::class,
        ]);
        $this->proposeSettings($this->contract, $this->user2);
        $this->contract->validateSettings($this->user);
        Event::assertDispatched(ContractValidated::class);
    }

    /** @test */
    public function propose_setting_fire_an_event()
    {
        Event::Fake([
            SettingsContractProposed::class,
        ]);
        $this->proposeSettings($this->contract, $this->user2);
        Event::assertDispatched(SettingsContractProposed::class, function ($e) {
            $this->assertTrue($e->toUser->id == $this->user->id);
            $this->assertTrue($e->fromUser->id == $this->user2->id);

            return $e->contract->id === $this->contract->id;
        });
    }

    /** @test */
    public function propose_setting_send_notification_to_other_user()
    {
        $this->proposeSettings($this->contract, $this->user2);

        Notification::assertSentTo(
            $this->user,
            SettingsContractProposedNotification::class,
            function ($notification, $channels) {
                $this->assertTrue($notification->fromUser->id == $this->user2->id);
                $this->assertTrue($notification->toUser->id == $this->user->id);

                return $notification->contract->id == $this->contract->id;
            }
        );
    }

    /** @test */
    public function revoke_setting_send_notification_to_other_user()
    {
        $this->proposeSettings($this->contract, $this->user2);
        $this->contract->revokeSettings($this->user);
        Notification::assertSentTo(
            $this->user2,
            SettingsContractRevokedNotification::class,
            function ($notification, $channels) {
                $this->assertTrue($notification->fromUser->id === $this->user->id);
                $this->assertTrue($notification->toUser->id === $this->user2->id);

                return $notification->contract->id == $this->contract->id;
            }
        );
    }

    /** @test */
    public function revoke_setting_fire_an_event()
    {
        $this->debug();
        Event::Fake([
            SettingsContractRevoked::class,
        ]);

        $this->proposeSettings($this->contract, $this->user2);
        $this->contract->revokeSettings($this->user);
        Event::assertDispatched(SettingsContractRevoked::class, function ($e) {
            $this->assertTrue($e->toUser->id == $this->user2->id);
            $this->assertTrue($e->fromUser->id == $this->user->id);

            return $e->contract->id === $this->contract->id;
        });
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

    protected function applyCandidature($user, $demand = null)
    {
        $demand = $demand ?? $this->demand;

        $candidature = factory(Candidature::class)->raw(['owner_id' => $user->id]);

        return $user->apply($demand, $candidature);
    }

    protected function createContract($user1, $user2)
    {
        $candidature = $this->applyCandidature($user2);
        $this->demand->fresh()->contractCandidature($candidature);
        $contract = Contract::all()->first();

        return $contract;
    }

    protected function proposeSettings($contract, $user)
    {
        $settings = ['be_done_at' => now()->addDays(7)];
        $contract->proposeSettings($settings, $user);
    }
}
