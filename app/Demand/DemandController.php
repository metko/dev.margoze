<?php

namespace App\Demand;

use Illuminate\Http\Request;
use App\Demand\Requests\StoreDemand;
use App\Http\Controllers\Controller;
use App\Notifications\CandidatureSubmit;

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

        $request->user()->apply($demand, $request->all());
        //$demand->owner->notify(new CandidatureSubmit($candidature));
    }

    public function update(Demand $demand, Request $request)
    {
        $this->authorize('manage', $demand);
        $demand->update($request->all());
    }

    public function contracted(Demand $demand)
    {
        $this->authorize('manage', $demand);
        $demand->contracted();
    }

    public function delete(Demand $demand)
    {
        $this->authorize('manage', $demand);
        $demand->delete();
    }

    public function restore($id)
    {
        $demand = Demand::withTrashed()->findOrFail($id);
        $this->authorize('manage', $demand);
        $demand->restore();
    }

    public function destroy(Demand $demand)
    {
        $this->authorize('manage', $demand);
        $demand->forceDelete();
    }
}
