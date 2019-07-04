<?php

namespace App\Message;

use Illuminate\Http\Request;
use Metko\Galera\Facades\Galera;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function store(Request $request, $conversationId)
    {
        $conversation = Galera::conversation($conversationId);

        $request->user()->write($request->message, $conversation->id);

        return redirect(route('dashboard.conversation.show', $conversation->id));
    }
}
