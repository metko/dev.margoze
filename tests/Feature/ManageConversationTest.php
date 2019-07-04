<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Contract\Contract;
use App\Candidature\Candidature;
use Metko\Galera\Facades\Galera;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Metko\Galera\Exceptions\UnauthorizedConversation;

class ManageConversationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    /** @test */
    public function a_new_contract_reopen_the_conversation_if_exist()
    {
        $this->debug();
        $candidature = factory(Candidature::class)->raw(['owner_id' => $this->user2->id]);
        $candidature = $this->user2->apply($this->demand, $candidature);
        $this->actingAs($this->user)->post(route('demands.contact', [
             'demand' => $this->demand->id,
             'userCandidature' => $this->user2->id,
         ]), ['message' => 'Hello ca va ?']);

        $candidature2 = factory(Candidature::class)->raw(['owner_id' => $this->user->id]);
        $candidature = $this->user->apply($this->demand2, $candidature2);
        $this->actingAs($this->user2)->post(route('demands.contact', [
             'demand' => $this->demand2->id,
             'userCandidature' => $this->user->id,
         ]), ['message' => 'Hello ca va ?']);

        $this->assertCount(1, Galera::allConversations());
        $this->assertCount(2, Galera::allMessages());

        $candidature3 = factory(Candidature::class)->raw(['owner_id' => $this->user3->id]);
        $candidature = $this->user3->apply($this->demand2, $candidature3);
        $this->actingAs($this->user2)->post(route('demands.contact', [
             'demand' => $this->demand2->id,
             'userCandidature' => $this->user3->id,
         ]), ['message' => 'Hello ca va ?']);

        $this->assertCount(2, Galera::allConversations());
        $this->assertCount(3, Galera::allMessages());
    }

    /** @test */
    public function user_who_try_to_contact_someone_without_be_in_conversation_throw_an_exeption()
    {
        $this->debug();
        $this->expectException(UnauthorizedConversation::class);
        $message = 'hello';
        $conversation = Galera::participants(1, 2)->make();
        $this->actingAs($this->user3)->post(route('dashboard.messages.store', $conversation->id), ['message' => 'hello']);
    }

    /** @test */
    public function a_new_contract_open_a_conversation_if_doesnt_exist_already()
    {
        $this->debug();
        $this->applyCandidature($this->user2);
        $candidature = $this->demand->fresh()->candidatures->first();
        $this->actingAs($this->user)->post(route('demands.contract.candidature',
            ['demand' => $this->demand->id, 'candidature' => $candidature->id]));
        $contract = Contract::all()->last();
        $this->assertTrue($this->demand->fresh()->isContracted());
        $conversation = Galera::allConversations()->last();
        $this->assertCount(1, Galera::allConversations());
        $this->assertTrue($contract->conversation->is($conversation));
        //dd($contract);
    }

    protected function applyCandidature($user)
    {
        $candidature = factory(Candidature::class)->raw(['owner_id' => $user->id]);

        return $user->apply($this->demand, $candidature);
    }
}
