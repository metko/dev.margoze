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
use App\Contract\Exceptions\InvalidatedContract;
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
            $this->user,
            ContractCreatedMailNotification::class,
            function ($notification, $channels) {
                $this->assertInstanceOf(Demand::class, $notification->demand);
                $this->assertInstanceOf(Contract::class, $notification->contract);
                $this->assertTrue($notification->contract->candidature_owner_id === $this->user->id);

                return true;
            }
        );
    }

    /** @test */
    public function contract_a_demand_save_a_db_notification_to_userCandidature_and_userDemand()
    {
        $candidature = $this->applyCandidature($this->user, $this->demand2);

        $this->demand2->fresh()->contractCandidature($candidature);
        $user2 = $this->user2;
        Notification::assertSentTo(
            [$this->user2, $this->user],
            ContractCreatedDBNotification::class,
            function ($notification, $channels) use ($user2) {
                $this->assertInstanceOf(User::class, $notification->userCandidature);
                $this->assertInstanceOf(User::class, $notification->userDemand);
                $this->assertInstanceOf(Demand::class, $notification->demand);
                $this->assertInstanceOf(Contract::class, $notification->contract);
                $this->assertTrue($notification->contract->candidature_owner_id == $this->user->id);
                $this->assertTrue($notification->contract->demand_owner_id == $this->user2->id);

                return true;
            }
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
    public function validate_a_contract_with_be_done_at_less_than_now_throw_exception()
    {
        $this->expectException(InvalidatedContract::class);
        $contract = $this->makeContract($this->user, $this->user2);
        $contract->be_done_at = now()->subDays(31);
        $contract->save();
        $contract->validate();
    }

    /** @test */
    public function on_a_new_contract___users_can_propose_be_done_date()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $this->proposeSettings($contract, $this->user2);

        $this->assertTrue($this->settings['be_done_at']->format('Y-m-d H:i:s') == $contract->be_done_at);
        $this->assertTrue($contract->fresh()->wait_for_validate);
        $this->assertTrue($this->user2->id == $contract->fresh()->last_propose_by);
    }

    /** @test */
    public function propose_date_when_already_did_it_throw_exception()
    {
        $this->expectException(SettingsAlreadySubmit::class);
        $contract = $this->makeContract($this->user, $this->user2);
        $this->proposeSettings($contract, $this->user2);
        $this->proposeSettings($contract, $this->user2);
    }

    /** @test */
    public function propose_date_when_not_in_contract_throw_exception()
    {
        $this->expectException(UserDoesntBelongsToContract::class);
        $contract = $this->makeContract($this->user, $this->user2);
        $this->proposeSettings($contract, $this->user3);
    }

    /** @test */
    public function user_can_refused_be_done_date__submited_by_other_user()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $this->proposeSettings($contract, $this->user2);
        $contract->revokeSettings($this->user);
        $this->assertNull($contract->fresh()->be_done_at);
        $this->assertFalse($contract->fresh()->wait_for_validate);
        $this->assertNull($contract->fresh()->last_propose_by);
    }

    /** @test */
    public function accept_settings_set_some_fields_and_fix_the_contract()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $this->proposeSettings($contract, $this->user2);
        $contract->validateSettings($this->user);

        $this->assertTrue($this->settings['be_done_at']->format('Y-m-d H:i:s') == $contract->be_done_at);
        $this->assertFalse($contract->fresh()->wait_for_validate);
        $this->assertTrue($this->user2->id == $contract->fresh()->last_propose_by);
        $this->asserttrue(now()->eq($contract->fresh()->validated_at));
    }

    /** @test */
    public function setDate_on_contract_already_validate_throw_error()
    {
        $this->expectException(ContractAlreadyValidated::class);
        $contract = $this->makeContract($this->user, $this->user2);
        $this->proposeSettings($contract, $this->user2);
        $contract->validateSettings($this->user);
        $this->settings['be_done_at'] = $this->settings['be_done_at']->addDays(3);
        $contract->fresh()->proposeSettings($this->settings, $this->user2);
    }

    /** @test */
    public function contract_can_be_cancelled()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $this->proposeSettings($contract, $this->user2);
        $contract->cancel($this->user);
        $this->assertTrue($this->user->id == $contract->fresh()->cancelled_by);
    }

    /** @test */
    public function valided_settings_contract_fire_an_event()
    {
        Event::Fake([
            ContractValidated::class,
        ]);
        $contract = $this->makeContract($this->user, $this->user2);
        $this->proposeSettings($contract, $this->user2);
        $contract->validateSettings($this->user);
        Event::assertDispatched(ContractValidated::class);
    }

    /** @test */
    public function propose_setting_fire_an_event()
    {
        Event::Fake([
            SettingsContractProposed::class,
        ]);
        $contract = $this->makeContract($this->user, $this->user2);
        $this->proposeSettings($contract, $this->user2);
        Event::assertDispatched(SettingsContractProposed::class, function ($e) use ($contract) {
            $this->assertTrue($e->toUser->id == $this->user->id);
            $this->assertTrue($e->fromUser->id == $this->user2->id);

            return $e->contract->id === $contract->id;
        });
    }

    /** @test */
    public function propose_setting_send_notification_to_other_user()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $this->proposeSettings($contract, $this->user2);

        Notification::assertSentTo(
            $this->user,
            SettingsContractProposedNotification::class,
            function ($notification, $channels) use ($contract) {
                $this->assertTrue($notification->fromUser->id == $this->user2->id);
                $this->assertTrue($notification->toUser->id == $this->user->id);

                return $notification->contract->id == $contract->id;
            }
        );
    }

    /** @test */
    public function revoke_setting_send_notification_to_other_user()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $this->proposeSettings($contract, $this->user2);
        $contract->revokeSettings($this->user);
        Notification::assertSentTo(
            $this->user2,
            SettingsContractRevokedNotification::class,
            function ($notification, $channels) use ($contract) {
                $this->assertTrue($notification->fromUser->id === $this->user->id);
                $this->assertTrue($notification->toUser->id === $this->user2->id);

                return $notification->contract->id == $contract->id;
            }
        );
    }

    /** @test */
    public function revoke_setting_fire_an_event()
    {
        Event::Fake([
            SettingsContractRevoked::class,
        ]);

        $contract = $this->makeContract($this->user, $this->user2);
        $this->proposeSettings($contract, $this->user2);
        $contract->revokeSettings($this->user);
        Event::assertDispatched(SettingsContractRevoked::class, function ($e) use ($contract) {
            $this->assertTrue($e->toUser->id == $this->user2->id);
            $this->assertTrue($e->fromUser->id == $this->user->id);
            $this->assertTrue($e->contract->id === $contract->id);

            return true;
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

    protected function proposeSettings($contract, $user)
    {
        $settings = ['be_done_at' => now()->addDays(7)];
        $contract->proposeSettings($settings, $user);
    }
}
