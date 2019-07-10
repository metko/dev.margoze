<?php

namespace Tests\Unit;

use App\User\User;
use Tests\TestCase;
use App\Demand\Demand;
use App\Evaluation\Evaluation;
use App\Candidature\Candidature;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EvaluationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->contract = $this->makeContract($this->user, $this->user2);
        $this->contract->be_done_at = now();
        $this->contract->be_done_at = now()->addDays(7);
        $this->contract->save();
        $this->attr = factory(Evaluation::class)->raw();
    }

    /** @test */
    public function it_has_causer()
    {
        $this->contract->validate()->finish(now()->addMonth(1));
        $this->user->evaluate($this->user2, $this->contract, $this->attr);
        $causer = $this->user2->evaluations->first()->causer;
        $this->assertInstanceOf(User::class, $causer);
        $this->assertTrue($this->user->is($causer));
    }

    /** @test */
    public function it_has_user()
    {
        $this->contract->validate()->finish(now()->addMonth(1));
        $this->user->evaluate($this->user2, $this->contract, $this->attr);
        $user = $this->user2->evaluations->first()->user;
        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($this->user2->is($user));
    }

    /** @test */
    public function it_has_a_comment()
    {
        $this->contract->validate()->finish(now()->addMonth(1));
        $this->user->evaluate($this->user2, $this->contract, $this->attr);
        $this->assertDatabaseHas('evaluations', ['comment' => $this->attr['comment']]);
    }

    /** @test */
    public function it_has_a_note_between_1_and_5()
    {
        $this->contract->validate()->finish(now()->addMonth(1));
        $this->user->evaluate($this->user2, $this->contract, $this->attr);
        $this->assertDatabaseHas('evaluations', ['note' => $this->attr['note']]);
    }

    protected function applyCandidature($user, $demand = null)
    {
        $this->demand = factory(Demand::class)->create(['owner_id' => $this->user->id]);
        $candidature = factory(Candidature::class)->raw(['owner_id' => $user->id]);

        return $user->apply($this->demand, $candidature);
    }

    protected function createContract($user1, $user2)
    {
        $candidature = $this->applyCandidature($user2);
        $contract = $this->demand->fresh()->contractCandidature($candidature);

        return $contract;
    }
}
