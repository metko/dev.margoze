<?php

namespace Tests\Unit;

use App\User\User;
use Tests\TestCase;
use App\Contract\Contract;
use Metko\Galera\GlrConversation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LitigeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->attr = [
            'user_id' => $this->user->id,
            'causer_id' => $this->user2->id,
        ];
    }

    /** @test */
    public function it_has_contract()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $litige = $contract->createLitige($this->attr);
        $this->assertInstanceOf(Contract::class, $litige->contract);
    }

    /** @test */
    public function it_has_conversation()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $litige = $contract->createLitige($this->attr);
        $this->assertInstanceOf(GlrConversation::class, $litige->conversation);
    }

    /** @test */
    public function it_has_user_and_causer()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $litige = $contract->createLitige($this->attr);

        $this->assertInstanceOf(User::class, $litige->user);
        $this->assertInstanceOf(User::class, $litige->causer);
    }

    /** @test */
    public function it_has_isClosed()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $litige = $contract->createLitige($this->attr)->close();
        $this->assertTrue($contract->litiges->first()->isClosed());
    }

    /** @test */
    public function it_as_close()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $litige = $contract->createLitige($this->attr)->close();
        $this->assertTrue($contract->litiges->first->closed == true);
    }

    /** @test */
    public function it_as_uuid()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $litige = $contract->createLitige($this->attr);
        $this->assertTrue(!empty($litige->uuid));
    }

    /** @test */
    public function it_as_hasNewMessages()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $litige = $contract->createLitige($this->attr);
        $this->assertTrue(!empty($litige->uuid));
    }
}
