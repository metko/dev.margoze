<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Candidature\Candidature;
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

    /** @test */
    public function it_has_apply()
    {
        $candidature = factory(Candidature::class)->raw();
        $this->user2->apply($this->demand, $candidature);
        $this->assertCount(1, $this->demand->fresh()->candidatures);
    }

    /** @test */
    public function it_has_hasApply()
    {
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $this->user2->apply($this->demand, $candidature);
        $this->assertTrue($this->user2->hasApply($this->demand->fresh()));
    }

    /** @test */
    public function it_hasCanApply()
    {
        $this->assertTrue($this->user2->canApply($this->demand));
        // $this->assertFalse($this->user1->canApply($this->demand));
        $candidature = factory(Candidature::class)->raw();
        $this->user2->apply($this->demand, $candidature);
        //$this->assertTrue($this->user2->canApply($this->demand));
    }

    /** @test */
    public function it_hasIsOwnerDemand()
    {
        $this->assertTrue($this->user->isOwnerDemand($this->demand));
    }
}
