<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Contract\Exceptions\InvalidatedContract;
use App\Contract\Exceptions\ContractUnrealizedYet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Evaluation\Exceptions\UserAlreadyEvaluated;
use App\Contract\Exceptions\UserDoesntBelongsToContract;

class ManageEvaluationsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->attr = [
            'comment' => 'A good comment',
        ];
    }

    /** @test */
    public function a_user_can_evaluate_a_user_who_he_was_in_contract()
    {
        $contract = $this->makeContract($this->user, $this->user2)->validate();
        $this->adjustBeDoneAt($contract);
        $this->user->evaluate($this->user2, $contract->fresh(), $this->attr);
        $this->assertCount(1, $this->user2->fresh()->evaluations);
    }

    /** @test */
    public function evaluate_a_user_save_the_average_note_for_the_user()
    {
        $contract = $this->makeContract($this->user, $this->user2)->validate();
        $this->adjustBeDoneAt($contract);
        $this->user->evaluate($this->user2, $contract->fresh(), $this->attr);
        $this->assertCount(1, $this->user2->fresh()->evaluations);
    }

    /** @test */
    public function throw_an_exception_if_user_try_to_evaluate_a_second_time_a_user()
    {
        $this->expectException(UserAlreadyEvaluated::class);
        $contract = $this->makeContract($this->user, $this->user2)->validate();
        $this->adjustBeDoneAt($contract);
        $this->user2->evaluate($this->user, $contract->fresh(), $this->attr);
        $this->assertCount(1, $this->user->fresh()->evaluations);
        $this->user2->evaluate($this->user, $contract->fresh(), $this->attr);
    }

    /** @test */
    public function throw_an_exception_if_user_try_to_evaluate_a_user_who_isnt_in_contract_with()
    {
        $this->expectException(UserDoesntBelongsToContract::class);
        $contract = $this->makeContract($this->user, $this->user2)->validate();
        $this->adjustBeDoneAt($contract);
        $this->user->evaluate($this->user3, $contract, $this->attr);
        $this->assertCount(0, $this->user3->evaluations);
    }

    /** @test */
    public function throw_an_exception_if_user_try_to_evaluate_an_invalid_contract()
    {
        $this->expectException(InvalidatedContract::class);
        $contract = $this->makeContract($this->user, $this->user2);
        $this->adjustBeDoneAt($contract);
        $this->user->evaluate($this->user2, $contract, $this->attr);
        $this->assertCount(0, $this->user3->evaluations);
    }

    /** @test */
    public function throw_an_exception_if_user_try_to_evaluate_an_unrealized_contract()
    {
        $this->expectException(ContractUnrealizedYet::class);
        $contract = $this->makeContract($this->user, $this->user2)->validate();
        $this->user->evaluate($this->user2, $contract->fresh(), $this->attr);
        $this->assertCount(0, $this->user3->evaluations);
    }
}
