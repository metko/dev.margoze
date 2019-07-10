<?php

namespace Tests\Unit;

use App\User\User;
use Tests\TestCase;
use App\Demand\Demand;
use App\Sector\Sector;
use App\Category\Category;
use App\Contract\Contract;
use App\Evaluation\Evaluation;
use Illuminate\Support\Carbon;
use App\Candidature\Candidature;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContractTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_userDemand()
    {
        $contract = factory(Contract::class)->create();
        $this->assertInstanceOf(User::class, $contract->userDemand);
    }

    /** @test */
    public function it_has_userCandidature()
    {
        $contract = factory(Contract::class)->create();
        $this->assertInstanceOf(User::class, $contract->userCandidature);
    }

    /** @test */
    public function it_has_demand()
    {
        $contract = factory(Contract::class)->create();
        $this->assertInstanceOf(Demand::class, $contract->demand);
    }

    /** @test */
    public function it_has_candidature()
    {
        $contract = factory(Contract::class)->create();
        $this->assertInstanceOf(Candidature::class, $contract->candidature);
    }

    /** @test */
    public function it_has_category()
    {
        $contract = factory(Contract::class)->create();
        $this->assertInstanceOf(Category::class, $contract->category);
    }

    /** @test */
    public function it_has_sector()
    {
        $contract = factory(Contract::class)->create();
        $this->assertInstanceOf(Sector::class, $contract->sector);
    }

    /** @test */
    public function it_has_proposeSettings()
    {
        $contract = $this->createContract($this->user, $this->user2);
        $settings = ['be_done_at' => now()->addDays(7)];
        $contract->proposeSettings($settings, $this->user2);
        $this->assertTrue($settings['be_done_at']->eq($contract->fresh()->be_done_at));
    }

    /** @test */
    public function it_has_canProposeSettings()
    {
        $contract = $this->createContract($this->user, $this->user2);
        $this->assertFalse($contract->canProposeSettings($this->user3));
    }

    /** @test */
    public function it_has_validate()
    {
        $contract = $this->createContract($this->user, $this->user2);
        $contract->validate();
        $this->assertTrue(now()->eq($contract->fresh()->validated_at));
        $this->assertFalse($contract->fresh()->wait_for_validate);
    }

    /** @test */
    public function it_has_cancel()
    {
        $contract = $this->createContract($this->user, $this->user2);
        $contract->cancel($this->user);
        $this->assertTrue($contract->fresh()->cancelled_by == $this->user->id);
    }

    /** @test */
    public function it_has_revokeSettings()
    {
        $contract = $this->createContract($this->user, $this->user2);
        $settings = ['be_done_at' => now()->addDays(7)];
        $contract->proposeSettings($settings, $this->user2);
        $contract->revokeSettings($this->user);
        $this->assertNull($contract->fresh()->be_done_at);
        $this->assertFalse($contract->fresh()->wait_for_validate);
        $this->assertNull($contract->fresh()->last_propose_by);
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
    public function it_has_evaluation()
    {
        $contract = $this->createContract($this->user, $this->user2);
        $contract->be_done_at = now();
        $contract->save();
        $contract->validate()->finish();
        $this->user->evaluate($this->user2, $contract->fresh(), ['comment' => 'hello']);
        $this->assertInstanceOf(Evaluation::class, $contract->fresh()->evaluations->first());
    }

    /** @test */
    public function it_has_finish()
    {
        $contract = $this->createContract($this->user, $this->user2);
        $contract->be_done_at = now();
        $contract->save();
        $contract->validate();
        $this->assertTrue(now()->eq($contract->fresh()->validated_at));
        $this->assertFalse($contract->fresh()->wait_for_validate);
        $contract->finish(now()->addMonths(1));

        $this->assertTrue(
            Carbon::parse($contract->fresh()->finished_at)
            ->greaterThan(Carbon::parse($contract->fresh()->be_done_at)));
    }
}
