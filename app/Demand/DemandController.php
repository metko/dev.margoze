<?php

namespace App\Demand;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Demand\Requests\StoreDemand;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Demand\Exceptions\DemandNoLongerAvailable;
use App\Candidature\Exceptions\CandidatureAlreadySent;
use App\Candidature\Exceptions\CandidatureBelongsToOwnerDemand;

class DemandController extends Controller
{
    public function store(StoreDemand $request)
    {
        $demand = Demand::create($request->all());
    }

    public function apply(Demand $demand, Request $request)
    {
        $candidature = $request->validate([
            'content' => 'required|min:20',
        ]);
        $candidature['owner_id'] = Auth::user()->id;
        if ($demand->candidatures->contains('owner_id', $candidature['owner_id'])) {
            throw CandidatureAlreadySent::create($demand->id);
        }
        if ($demand->owner->id == $candidature['owner_id']) {
            throw CandidatureBelongsToOwnerDemand::create($demand->id);
        }
        if ($demand->be_done_at < Carbon::now()) {
            throw DemandNoLongerAvailable::create($demand->id);
        }

        $demand->candidatures()->create($candidature);
    }
}
