<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(\App\User::class)->create();
        $this->user2 = factory(\App\User::class)->create();
        $this->demand = factory(\App\Demand::class)->create(['owner_id' => $this->user->id]);
        $this->demand2 = factory(\App\Demand::class)->create(['owner_id' => $this->user2->id]);
    }
}
