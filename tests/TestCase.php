<?php

namespace Tests;

use App\User\User;
use App\Demand\Demand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        error_log('setup testcase');
        $this->user = factory(User::class)->create(['username' => 'user1']);
        $this->user2 = factory(User::class)->create(['username' => 'user2']);
        $this->demand = factory(Demand::class)->create(['owner_id' => $this->user->id]);
        $this->demand2 = factory(Demand::class)->create(['owner_id' => $this->user2->id]);
    }
}
