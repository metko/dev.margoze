<?php

namespace App\Dashboard;

use App\Demand\Demand;
use App\Contract\Contract;
use App\Candidature\Candidature;
use Metko\Galera\Facades\Galera;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    /**
     * index.
     */
    public function index()
    {
        $demandsCount = Demand::all()
                ->where('valid_until', '>', now())
                ->where('contracted', true)
                ->where('owner_id', auth()->user()->id)->count();
        $candidaturesCount = Candidature::whereHas('demand', function (Builder $query) {
            $query->where('contracted', 0);
        })
        ->where('owner_id', auth()->user()->id)->count();

        $contractsCount = Contract::where('demand_owner_id', auth()->user()->id)->orWhere('candidature_owner_id', auth()->user()->id)
                ->where('finished_at', null)->count();

        return $this->view('index', compact('demandsCount', 'candidaturesCount', 'contractsCount'));
    }

    /**
     * demands.
     */
    public function demands()
    {
        $demands = Demand::with(['candidatures'])->where('owner_id', auth()->user()->id)->get();

        return $this->view('demands.index', compact('demands'));
    }

    /**
     * profile.
     */
    public function profile()
    {
        $user = auth()->user()->load('commune', 'district');
        $demandsCount = Demand::all()
                ->where('owner_id', auth()->user()->id)->count();
        $candidaturesCount = Candidature::where('owner_id', auth()->user()->id)->count();
        $contractsCount = Contract::where('demand_owner_id', auth()->user()->id)->orWhere('candidature_owner_id', auth()->user()->id)->count();

        return $this->view('users.index', compact('user', 'demandsCount', 'candidaturesCount', 'contractsCount'));
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
