<?php

namespace App\Conversation;

use Metko\Galera\Facades\Galera;
use App\Http\Controllers\Controller;

class ConversationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $conversations = $user->getLastConversations();

        return view('conversations.index', compact('user', 'conversations'));
    }

    public function show($conversationId)
    {
        $conversation = Galera::conversation($conversationId);
        $messages = Galera::ofConversation($conversation->id)
            ->orderBy('message', 'asc')->get();

        return view('conversations.show', compact('conversation', 'messages'));
    }
}
