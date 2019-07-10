<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Demand\Demand;
use App\Contract\Contract;
use App\Candidature\Candidature;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Event::fake();
        $this->contract = $this->createContract($this->user3, $this->user2);
    }

    /** @test */
    public function it_has_ban()
    {
        Notification::fake();
        $this->user->ban();
        $this->assertTrue($this->user->banned = true);
    }

    /** @test */
    public function it_has_isBanned()
    {
        Notification::fake();
        $this->user->ban();
        $this->assertTrue($this->user->isBanned());
    }

    /** @test */
    public function it_has_suspendAccount()
    {
        Notification::fake();
        $this->user->suspendAccount();
        $this->assertTrue($this->user->suspended = true);
    }

    /** @test */
    public function it_has_isSuspended()
    {
        Notification::fake();
        $this->user->suspendAccount();
        $this->assertTrue($this->user->isSuspended());
    }

    /** @test */
    public function it_has_apply()
    {
        $candidature = factory(Candidature::class)->raw();
        $this->user2->apply($this->demand, $candidature);
        $this->assertCount(1, $this->demand->fresh()->candidatures);
    }

    /** @test */
    public function it_has_hasApply()
    {
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $this->user2->apply($this->demand, $candidature);
        $this->assertTrue($this->user2->hasApply($this->demand->fresh()));
    }

    /** @test */
    public function it_has_canApply()
    {
        $this->assertTrue($this->user2->canApply($this->demand));
        // $this->assertFalse($this->user1->canApply($this->demand));
        $candidature = factory(Candidature::class)->raw();
        $this->user2->apply($this->demand, $candidature);
        //$this->assertTrue($this->user2->canApply($this->demand));
    }

    /** @test */
    public function it_has_IsOwnerDemand()
    {
        $this->assertTrue($this->user->isOwnerDemand($this->demand));
    }

    /** @test */
    public function it_has_isInContract()
    {
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $candidature = $this->user2->apply($this->demand, $candidature);
        $this->demand->fresh()->contractCandidature($candidature);
        $contract = Contract::all()->first();
        $userDemand = $contract->userDemand;
        $this->assertTrue($userDemand->isInContract($contract->id));
        $this->assertTrue($this->user2->isInContract($contract->id));
        $this->assertFalse($this->user3->isInContract($contract->id));
    }

    /** @test */
    public function it_has_evaluate()
    {
        $this->debug();
        $attr = [
            'comment' => 'A good comment',
        ];
        $this->contract->be_done_at = now();
        $this->contract->save();
        $this->contract->validate()->finish();
        $this->user->evaluate($this->user2, $this->contract, $attr);
        $this->assertCount(1, $this->user2->evaluations);
    }

    /** @test */
    public function it_has_causer()
    {
        $attr = [
             'comment' => 'A good comment',
         ];
        $this->contract->be_done_at = now();
        $this->contract->save();
        $this->contract->validate()->finish();
        $this->user->evaluate($this->user2, $this->contract, $attr);
        $this->assertTrue($this->user2->evaluations->first()->causer->is($this->user));
    }

    /** @test */
    public function it_has_hasEvaluate()
    {
        $attr = [
            'comment' => 'A good comment',
        ];
        $this->contract->be_done_at = now();
        $this->contract->save();
        $this->contract->validate()->finish();
        $this->user->evaluate($this->user2, $this->contract->fresh(), $attr);
        $this->assertTrue($this->user->hasEvaluated($this->contract->fresh()));
    }

    protected function applyCandidature($user, $demand = null)
    {
        $this->demand5 = factory(Demand::class)->create(['owner_id' => $this->user->id]);

        $candidature5 = factory(Candidature::class)->raw(['owner_id' => $user->id]);

        return $user->apply($this->demand5, $candidature5);
    }

    protected function createContract($user1, $user2)
    {
        $candidature5 = $this->applyCandidature($user2);
        $contract = $this->demand5->fresh()->contractCandidature($candidature5);

        return $contract;
    }
}
