<?php

namespace Tests\Unit;

use App\User\User;
use Tests\TestCase;
use App\Evaluation\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EvaluationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->contract = $this->makeContract($this->user, $this->user2);
        $this->attr = factory(Evaluation::class)->raw();
    }

    /** @test */
    public function it_has_causer()
    {
        $contract = $this->makeContract($this->user, $this->user2)->validate();
        $this->adjustBeDoneAt($contract);
        $this->user->evaluate($this->user2, $contract, $this->attr);
        $causer = $this->user2->evaluations->first()->causer;
        $this->assertInstanceOf(User::class, $causer);
        $this->assertTrue($this->user->is($causer));
    }

    /** @test */
    public function it_has_user()
    {
        $contract = $this->makeContract($this->user, $this->user2)->validate();
        $this->adjustBeDoneAt($contract);
        $this->user->evaluate($this->user2, $contract, $this->attr);
        $user = $this->user2->evaluations->first()->user;
        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($this->user2->is($user));
    }

    /** @test */
    public function it_has_a_comment()
    {
        $contract = $this->makeContract($this->user, $this->user2)->validate();
        $this->adjustBeDoneAt($contract);
        $this->user->evaluate($this->user2, $contract, $this->attr);
        $this->assertDatabaseHas('evaluations', ['comment' => $this->attr['comment']]);
    }

    /** @test */
    public function it_has_a_note_between_1_and_5()
    {
        $contract = $this->makeContract($this->user, $this->user2)->validate();
        $this->adjustBeDoneAt($contract);
        $this->user->evaluate($this->user2, $contract, $this->attr);
        $this->assertDatabaseHas('evaluations', ['note' => $this->attr['note']]);
    }
}
