<?php

namespace App\Http\Controllers;

use App\Demand;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDemand;
use Illuminate\Support\Facades\Auth;

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
        $demand->candidatures()->create($candidature);
    }
}
