<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Demand\Demand;
use App\Candidature\Candidature;
use App\Contract\Exceptions\UnfinishedContract;
use App\Contract\Exceptions\InvalidatedContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Evaluation\Exceptions\UserAlreadyEvaluated;
use App\Contract\Exceptions\UserDoesntBelongsToContract;

class ManageEvaluationsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->contract = $this->createContract($this->user, $this->user2);
    }

    /** @test */
    public function a_user_can_evaluate_a_user_who_he_was_in_contract()
    {
        $attr = [
            'comment' => 'A good comment',
        ];
        $this->contract->be_done_at = now();
        $this->contract->save();
        $this->contract->fresh()->validate()->finish();
        $this->user->evaluate($this->user2, $this->contract->fresh(), $attr);

        $this->assertCount(1, $this->user2->fresh()->evaluations);
    }

    /** @test */
    public function throw_an_exception_if_user_try_to_evaluate_a_second_time_a_user()
    {
        $this->expectException(UserAlreadyEvaluated::class);
        $attr = [
            'comment' => 'A good comment',
        ];

        $this->contract->be_done_at = now();
        $this->contract->save();
        $this->contract->fresh()->validate()->finish();
        $this->user->evaluate($this->user2, $this->contract->fresh(), $attr);
        $this->assertCount(1, $this->user2->fresh()->evaluations);
        $this->user->evaluate($this->user2, $this->contract->fresh(), $attr);
    }

    /** @test */
    public function throw_an_exception_if_user_try_to_evaluate_a_user_who_isnt_in_contract_with()
    {
        $this->expectException(UserDoesntBelongsToContract::class);
        $attr = [
            'comment' => 'A good comment',
        ];
        $this->contract->be_done_at = now();
        $this->contract->save();
        $this->contract->fresh()->validate()->finish();

        $this->user->evaluate($this->user3, $this->contract, $attr);

        $this->assertCount(0, $this->user3->evaluations);
    }

    /** @test */
    public function throw_an_exception_if_user_try_to_evaluate_an_invalid_contract()
    {
        $this->expectException(InvalidatedContract::class);
        $attr = [
            'comment' => 'A good comment',
        ];
        $this->user->evaluate($this->user2, $this->contract, $attr);

        $this->assertCount(0, $this->user3->evaluations);
    }

    /** @test */
    public function throw_an_exception_if_user_try_to_evaluate_an_unfinished_contract()
    {
        $this->expectException(UnfinishedContract::class);
        $attr = [
            'comment' => 'A good comment',
        ];
        $this->contract->be_done_at = now();
        $this->contract->save();
        $this->contract->fresh()->validate();
        $this->user->evaluate($this->user2, $this->contract->fresh(), $attr);

        $this->assertCount(0, $this->user3->evaluations);
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
