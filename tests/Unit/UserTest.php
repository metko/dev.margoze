<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

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
}
