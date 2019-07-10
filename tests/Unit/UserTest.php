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
        $this->attr = [
            'comment' => 'A good comment',
        ];
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
        $contract = $this->makeContract($this->user, $this->user2);
        $contract->validate()->finish(now()->addMonths(1));
        $this->user->evaluate($this->user2, $contract, $this->attr);
        $this->assertCount(1, $this->user2->evaluations);
    }

    /** @test */
    public function it_has_causer()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $contract->validate()->finish(now()->addMonths(1));
        $this->user->evaluate($this->user2, $contract, $this->attr);
        $this->assertTrue($this->user2->evaluations->first()->causer->is($this->user));
    }

    /** @test */
    public function it_has_hasEvaluate()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $contract->validate()->finish(now()->addMonths(1));
        $this->user->evaluate($this->user2, $contract->fresh(), $this->attr);
        $this->assertTrue($this->user->hasEvaluated($contract->fresh()));
    }
}
