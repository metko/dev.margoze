<?php

namespace App\Dashboard;

use App\Demand\Demand;
use App\Contract\Contract;
use Metko\Galera\Facades\Galera;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * index.
     */
    public function index()
    {
        return $this->view('index');
    }

    /**
     * demands.
     */
    public function demands()
    {
        $demands = Demand::with('owner.roles')->where('owner_id', auth()->user()->id)->get();
        $test = '';

        return $this->view('demands.index', compact('demands'));
    }

    /**
     * profile.
     */
    public function profile()
    {
        $contracts = Contract::with(['demand.owner', 'candidature.owner'])
            ->where('demand_owner_id', auth()->user()->id)->orWhere('candidature_owner_id', auth()->user()->id)
            ->get('id');

        return $this->view('demands.index', compact('contracts'));
    }

    /**
     * messages.
     */
    public function messages()
    {
        $user = auth()->user();
        $conversations = $user->getLastConversations();

        return $this->view('messages.index', compact('conversations'));
    }

    /**
     * showConversation.
     *
     * @param mixed $conversationId
     */
    public function showConversation($conversationId)
    {
        $conversation = Galera::conversation($conversationId);
        $messages = Galera::ofConversation($conversation->id)
            ->orderBy('message', 'asc')->get();

        return $this->view('messages.show', compact('conversation', 'messages'));
    }

    protected function view(String $view, array $data = [])
    {
        $user = auth()->user();
        $data['user'] = $user;

        return view('dashboard.'.$view, $data);
    }
}
