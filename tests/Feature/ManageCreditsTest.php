<?php

namespace Tests\Feature;

use App\User\User;
use Tests\TestCase;
use App\Credit\Credit;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageCreditsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_new_subscriber_has_credits()
    {
        $user = factory(User::class)->create();
        $user->subscribe();
        $this->assertInstanceOf(Credit::class, $user->fresh()->credits);
    }

    /** @test */
    public function post_a_demand_without_credit_demand_thrown_exeption()
    {
        $user = factory(User::class)->create();
        $user->subscribe();
        $this->assertInstanceOf(Credit::class, $user->fresh()->credits);
    }
}
