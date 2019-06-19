<?php

namespace Tests;

use App\User;
use App\Demand\Demand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->user2 = factory(User::class)->create();
        $this->demand = factory(Demand::class)->create(['owner_id' => $this->user->id]);
        $this->demand2 = factory(Demand::class)->create(['owner_id' => $this->user2->id]);
    }
}
