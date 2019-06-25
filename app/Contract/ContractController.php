<?php

namespace App\Contract;

use App\Http\Controllers\Controller;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::with(['demand.owner', 'candidature.owner'])
            ->where('demand_owner_id', auth()->user()->id)->orWhere('candidature_owner_id', auth()->user()->id)
            ->get('id');

        return view('contracts.index', compact('contracts'));
    }

    public function show(Contract $contract)
    {
        $this->authorize('show', $contract);

        return view('contracts.show', compact('contract'));
    }
}
