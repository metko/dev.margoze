<?php

namespace Tests\Feature;

use Tests\TestCase;
use Metko\Galera\GlrConversation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageLitigesTest extends TestCase
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
    public function a_litige_can_be_create_by_contract_instance()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $litige = $contract->createLitige($this->attr);
        $this->assertTrue($contract->hasLitiges());
    }

    /** @test */
    public function a_litige_can_close_itself()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $litige = $contract->createLitige($this->attr);
        $this->assertFalse($litige->isClosed());
        $litige->close();
        $this->assertTrue($litige->isClosed());
    }

    /** @test */
    public function a_new_litige_is_caused_by_a_user_about_another_user()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $litige = $contract->createLitige($this->attr);
        $this->assertTrue($litige->causer->is($this->user2));
        $this->assertTrue($litige->user->is($this->user));
    }

    /** @test */
    public function a_new_litige_open_a_conversation_between_causer_and_admin()
    {
        $contract = $this->makeContract($this->user, $this->user2);
        $litige = $contract->createLitige($this->attr);
        $conversation = GlrConversation::all()->last();
        $this->assertTrue($litige->conversation->is($conversation));
    }
}
