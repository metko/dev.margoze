<?php

namespace App\Contract;

use index;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Metko\Galera\Facades\Galera;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class ContractController extends Controller
{
    /**
     * index Return all the contract for the current user.
     */
    public function index(): view
    {
        $contracts = Contract::where('demand_owner_id', auth()->user()->id)->orWhere('candidature_owner_id', auth()->user()->id)
                ->with([
                        'conversation.status',
                        'userDemand' => function ($query) {
                            $query->where('id', '!=', auth()->user()->id);
                        },
                        'userCandidature' => function ($query) {
                            $query->where('id', '!=', auth()->user()->id);
                        }, ]
                )
                ->get();

        $contracts = $contracts->map(function ($contract) {
            $contract->user1 = auth()->user();
            if ($contract->userDemand != null) {
                $contract->user2 = $contract->userDemand;
                unset($contract->userCandidature);
            } elseif ($contract->userCandidature != null) {
                $contract->user2 = $contract->userCandidature;
                unset($contract->userDemand);
            }

            return $contract;
        });

        $user = auth()->user();

        return view('contracts.index', compact('contracts', 'user'));
    }

    /**
     * show. Get the contract with id X.
     *
     * @param int $contract Id of the contract
     */
    public function show(int $contract): View
    {
        $contract = Contract::with(['category', 'sector', 'evaluations'])->find($contract);
        $this->authorize('manage', $contract);
        $user1 = auth()->user();
        $user2 = $contract->getOtherUser($user1);
        $conversation = Galera::conversation($contract->conversation_id, true);
        $messages = $conversation->messages->reverse();
        $evaluations = $contract->evaluations;

        return view('contracts.show', compact('contract', 'user1', 'user2', 'conversation', 'messages', 'evaluations'));
    }

    /**
     * storeMessage Store a message in the conversation of the contract.
     *
     * @param contract $contract
     * @param mixed    $conversationId
     * @param mixed    $request
     */
    public function storeMessage(Contract $contract, int $conversationId, Request $request)
    {
        $this->authorize('manage', $contract);
        if ($contract->conversation_id != $conversationId) {
            abort(500);
        }
        $request->user()->write($request->message, $contract->conversation_id);

        return redirect(route('contracts.show', $contract->id))->with('success', 'message bien envoyé');
    }

    /**
     * storeSettings Store the settings proposed by the current user.
     *
     * @param mixed $contract
     * @param mixed $request
     */
    public function storeSettings(Contract $contract, Request $request)
    {
        $this->authorize('manage', $contract);
        if ($request->user()->proposeSettings($contract, $request->all())) {
            return redirect(route('contracts.show', $contract->id))->with('success', 'settings bien envoyé');
        }
    }

    /**
     * validateContract Validate the current settings and the contract.
     *
     * @param mixed $contract
     * @param mixed $request
     */
    public function validateContract(Contract $contract, Request $request)
    {
        $this->authorize('manage', $contract);
        if ($request->user()->validateSettings($contract)) {
            return redirect(route('contracts.show', $contract->id))->with('success', 'Votre contrat est validé !');
        }
    }

    /**
     * cancel Cancel the current contract.
     *
     * @param mixed $contract
     * @param mixed $request
     */
    public function cancel(Contract $contract, Request $request)
    {
        $this->authorize('manage', $contract);
        if ($request->user()->cancelContract($contract)) {
            return redirect(route('contracts.show', $contract->id))->with('success', 'Vous avez bien annuler le contrat');
        }
    }

    public function evaluate(Contract $contract, Request $request)
    {
        $this->authorize('manage', $contract);
        $attr = $request->validate([
            'note' => 'required',
            'comment' => 'required|min:20',
        ]);
        $user2 = $contract->getOtherUser($request->user());
        $request->user()->evaluate($user2, $contract, $attr);

        return redirect(route('contracts.show', $contract->id))->with('success', 'Vous avez bien evaluer');
    }
}
